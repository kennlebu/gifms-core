<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectCashNeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_cash_needs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('activity')->nullable();
            $table->double('amount',26,5);
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('requested_by')->nullable();
            $table->integer('cash_request_purpose')->nullable();
            $table->integer('migration_project_id')->nullable();
            $table->integer('migration_requested_by')->nullable();
            $table->integer('migration_id')->unique();
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
        Schema::dropIfExists('project_cash_needs');
    }
}
