<?php

namespace TeamReport\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use TeamReport\Models\Project;
use Teamwork;
use Storage;
use DateTime;

class SendEmailOverBudget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overbudget:sendemail {address?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will check database for any projects that have just gone over budget, and send email to specified address';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $this->comment('Checking Database:');
        $this->SendEmailOverBudget();
    }

    public function SendEmailOverBudget()
    {
       $this->info('Analysing projects...');
       $address = $this->argument('address');
       //for each in Projects table of DB
       $projects = Project::all();
       date_default_timezone_set('Europe/London');
       $this->info('Sending emails (if any)...');

       $i = 0;//counter
       foreach($projects as $project)
       {
         //if date went over budget column == today
          if($project->over_budget_at == date('dmy')) //same format
          {
            //send email appropriate email address
            $project_api = Teamwork::project(intval($project['project_id']))->find()['project'];
            $details = [];
            $details['projectname'] = $project_api['name'];
            $details['budget'] = $project->budget;
            $details['spend'] = $project->budget_used;
            Mail::send('emails.overbudget', ['details'=> $details],function($m) use ($details){
           $m->from('teamreport@teamreport.nublueagency.uk', 'Teamreport Automated Sender');
           $m->to('pm@nublue.co.uk')->cc('harry@harrymessenger.co.uk')->subject('Project: '.$details['projectname'].' is over budget');
            });
            $this->info('Email #'.$i.' Sent');
            $i++;
          }
       }
       $this->info('Complete!');
    }
}
