<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TeamReport\Models\Project;
use Mail;

class EmailTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
     public function testEmailTest()
     {

      //  $details = [];
      //  $details['projectname'] = 'test';
      //  $details['budget'] = '100';
      //  $details['spend'] = '120';
      //  var_dump($details);
      //  try{
      //       Mail::send('emails.overbudget', ['details'=> $details],function($m) use ($details){
      //      $m->from('teamreport@teamreport.nublueagency.uk', 'Teamreport Automated Sender');
      //      $m->to('harry.messenger@nublue.co.uk')->subject('Project: '.$details['projectname'].' is over budget');
      //    });
       //
      //    var_dump(Mail::failures());
      //  }
      //  catch (\Exception $e)
      //  {
      //    var_dump('test');
      //      dd($e->getMessage());
      //  }
       $this->assertTrue(1);
     }
}
