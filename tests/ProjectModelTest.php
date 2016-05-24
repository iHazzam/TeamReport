<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TeamReport\Models\Project;
use Mail;
use Illuminate\Console\Command;
use Bus;
use Teamwork;
use Storage;
use DateTime;


class ProjectModelTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
     public function testPMTest()
     {
       // 
      //  $projects = Teamwork::project()->all()['projects'];
      //  $i = 0;
      //  foreach ($projects as $project) {
      //         var_dump('project:'.$i);
      //          $projectId = (int) $project['id'];
      //          //Check if project model exists
      //          $project_model = Project::where('project_id', '=', $projectId)->first();
      //          if ($project_model === NULL)//if it doesn't exist, create it
      //          {
      //              $project_model = new Project;
      //              $project_model->project_id = $projectId;
      //          }
       //
       //
      //          $tasklists = Teamwork::project($projectId)->tasklists()['tasklists'];
      //          $budget_sum = 0;
      //          $budget_used_sum = 0;
      //          $j = 0;
      //          foreach ($tasklists as $tasklist) {
      //            var_dump('tasklist:'.$j);
      //              $tasklistId = (int) $tasklist['id'];
      //              $tasklistName = $this->getTasklistName($tasklist['name']);
      //              $local_budget = $this->getTasklistbudget($tasklist['name']);
      //              $local_used = (float) Teamwork::tasklist((int) $tasklist['id'])->timeTotal()['projects'][0]['tasklist']['time-totals']['total-hours-sum'];
       //
      //              $budget_sum = $local_budget + $budget_sum; //keep the running total of budget
      //              $budget_used_sum =  $local_used + $budget_used_sum; //keep running total of used
      //          }
       //
      //          if($project_model->over_budget_at === null) //if budget has not yet gone over
      //          {
      //            $project_model->budget = $budget_sum;
      //            $project_model->budget_used = $budget_used_sum;
      //            if($budget_used_sum > $budget_sum)
      //            {
      //                date_default_timezone_set('Europe/London');
      //                $project_model->over_budget_at = date('dmy'); //set that today the budget went over
      //            }
      //          }
       //
      //          $project_model->save();
       //
      //      $i++;
      //  }
       $this->assertTrue(1);
     }
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
}
