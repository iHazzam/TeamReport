<?php

namespace TeamReport\Console\Commands;

use Illuminate\Console\Command;
use Bus;
use Teamwork;
use Storage;
use TeamReport\Models\Project;
use DateTime;

class GenerateReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:generate
        {amount? : The amount of projects you want to retrieve (for testing purposes).}
        {--queue : Whether the job should be queued}';

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
        if ($this->option('queue')) {
            $this->comment('Report generation added to queue.');
            Bus::dispatch(new \TeamReport\Jobs\GenerateReports());
        } else {
            $this->comment('Downloading projects from Teamwork:');
            $this->generateReport();
        }
    }

    /**
     * Fetch tasklists from TeamworkPM API and write to local files.
     */
    protected function generateReport()
    {
        $tasklistsArray = [];

        $projects = Teamwork::project()->all()['projects'];
        $this->output->progressStart(count($projects));

        $i = 0;

        foreach ($projects as $project) {
            if (! $this->argument('amount') || $i < $this->argument('amount')) {
                $projectId = (int) $project['id'];
                //Check if project model exists
                $project_model = Project::where('project_id', '=', $projectId)->first();
                if ($project_model === NULL)//if it doesn't exist, create it
                {
                    $project_model = new Project;
                    $project_model->project_id = $projectId;
                }

                $tasklistsArray[$projectId] = [];
                $tasklistsArray[$projectId]['id'] = $projectId;
                $tasklistsArray[$projectId]['name'] = $project['name'];
                $tasklistsArray[$projectId]['company'] = $project['company']['name'];

                $tasklists = Teamwork::project($projectId)->tasklists()['tasklists'];
                $budget_sum = 0;
                $budget_used_sum = 0;
                foreach ($tasklists as $tasklist) {
                    $tasklistId = (int) $tasklist['id'];
                    $tasklistName = $this->getTasklistName($tasklist['name']);
                    $local_budget = $this->getTasklistbudget($tasklist['name']);
                    $local_used = (float) Teamwork::tasklist((int) $tasklist['id'])->timeTotal()['projects'][0]['tasklist']['time-totals']['total-hours-sum'];
                    $tasklistsArray[$projectId]['tasklists'][] = [
                        'id' => $tasklistId,
                        'name' => $tasklistName,
                        'budget' => $local_budget,
                        'used' => $local_used
                    ];
                    $budget_sum = $local_budget + $budget_sum; //keep the running total of budget
                    $budget_used_sum =  $local_used + $budget_used_sum; //keep running total of used
                }
                if($project_model->over_budget_at === null) //if budget has not yet gone over
                {
                  $project_model->budget = $budget_sum;
                  $project_model->budget_used = $budget_used_sum;
                  if($budget_used_sum > $budget_sum)
                  {
                      date_default_timezone_set('Europe/London');
                      $project_model->over_budget_at = date('dmy'); //set that today the budget went over
                  }
                }

                $this->output->progressAdvance();
                $project_model->save();
            }
            $i++;
        }

        $this->saveReport(array_values($tasklistsArray));

        $this->output->progressFinish();
    }

    /**
     * Separate the name from the tasklist.
     *
     * @return string $name
     */
    protected function getTasklistName($tasklist)
    {
        $name = $tasklist;
        $parenthesisPosition = strpos($name, '(');
        if ($parenthesisPosition) {
            $name = substr($tasklist, 0, $parenthesisPosition);
        }
        $name = trim($name);

        return $name;
    }

    /**
     * Separate the budget from the tasklist.
     *
     * @return int|false
     */
    protected function getTasklistBudget($tasklist)
    {
        strtok($tasklist, '(');
        $budget = strtok(')');

        return $this->formatBudget($budget);
    }

    /**
     * Read the budget budget and convert it into a single format.
     *
     * @param $budget
     * @return float $budget
     */
    protected function formatBudget($budget)
    {
        $budget = strtolower($budget);
        $budget = preg_replace('/(hours|hour|hrs|hr|h)/', '', $budget);
        $budget = trim($budget);

        return (float) $budget;
    }

    /**
     * Write report to JSON files.
     *
     * @param $report
     */
    protected function saveReport($report)
    {
        $time = time();

        if (Storage::exists('report.json')) {
            Storage::delete('report.json');
        }

        Storage::append('report-' . $time . '.json', json_encode($report, JSON_UNESCAPED_SLASHES));

        if (env('APP_DEBUG')) {
            Storage::append('report.json', json_encode($report, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        } else {
            Storage::append('report.json', json_encode($report, JSON_UNESCAPED_SLASHES));
        }
    }
}
