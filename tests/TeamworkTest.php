<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TeamReport\Models\Project;
use Mail;
use Teamwork;

class TeamworkTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
     public function testTeamworkTest()
     {

       $projects = Teamwork::project()->all()['projects'];
       foreach($projects as $project)
       {
         $project_api = Teamwork::project(intval($project['id']))->find()['project'];
         var_dump($project_api);
         $this->assertTrue(1);
       }
     }
}
