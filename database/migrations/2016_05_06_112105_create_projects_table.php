<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('projects', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('project_id');
          $table->integer('budget');
          $table->integer('budget_used');
          $table->date('over_budget_at')->default(NULL)->nullable();
          $table->unsignedInteger('created_at');
          $table->unsignedInteger('updated_at');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projects');
    }
}
