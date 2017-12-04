<?php

use Illuminate\Support\Facades\Schema;
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
            $table->increments('id');
            $table->integer('project_manager_id')->nullable();
            $table->integer('program_id')->nullable();
            $table->integer('grant_id')->nullable();
            $table->string('project_code');
            $table->text('project_name');
            $table->text('project_desc')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamp('closed_on')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('cluster')->nullable();
            $table->integer('client')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('budget_id')->nullable();
            $table->integer('qb')->nullable();
            $table->integer('migration_project_manager_id')->nullable();
            $table->integer('migration_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
