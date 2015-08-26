<?php

namespace TeamReport\Console\Commands;

use Illuminate\Console\Command;
use Bus;
use Teamwork;
use Storage;
use TeamReport\Jobs\GenerateReports;

class Reports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:generate
        {amount? : The amount of projects you want to retrieve (for testing purposes).}
        {--production}
        {--queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate reports based on tasklist titles fetched from TeamworkPM.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $job = (new GenerateReports());

        if ($this->option('queued')) {
            $this->comment('GenerateReports added to queue.');
            Bus::dispatch($job);
        } else {
            $this->comment('Downloading projects from Teamwork:');
            Bus::dispatchNow($job);
        }
    }

    /**
     * Fetch tasklists from TeamworkPM API and write to local files.
     */
    protected function generate()
    {
        $tasklistsArray = [];

        $projects = Teamwork::project()->all()['projects'];
        $this->output->progressStart(count($projects));

        $i = 0;

        foreach ($projects as $project) {
            if (!$this->argument('amount') || $i < $this->argument('amount')) {
                $projectId = (int)$project['id'];
                $tasklistsArray[$projectId] = [];
                $tasklistsArray[$projectId]['id'] = $projectId;
                $tasklistsArray[$projectId]['name'] = $project['name'];
                $tasklistsArray[$projectId]['company'] = $project['company']['name'];

                $tasklists = Teamwork::project($projectId)->tasklists()['tasklists'];
                foreach ($tasklists as $tasklist) {
                    $tasklistId = (int)$tasklist['id'];
                    $tasklistName = $this->getTasklistName($tasklist['name']);

                    $tasklistsArray[$projectId]['tasklists'][] = [
                        'id' => $tasklistId,
                        'name' => $tasklistName,
                        'budget' => $this->getTasklistbudget($tasklist['name']),
                        'used' => (float)Teamwork::tasklist((int)$tasklist['id'])->timeTotal()['projects'][0]['tasklist']['time-totals']['total-hours-sum']
                    ];
                }

                $this->output->progressAdvance();
            }
            $i++;
        }

        $this->saveReport(array_values($tasklistsArray));

        $this->output->progressFinish();
    }
}
                     