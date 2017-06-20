<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->increments('id');
            $table->double('total',30,4)->nullable();
            $table->string('expense_desc')->nullable();
            $table->text('expense_purpose')->nullable();
            $table->date('requested_date')->nullable();
            $table->string('claim_document')->nullable();
            $table->date('allocated_at')->nullable();
            $table->integer('allocated_by_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('payment_mode_id')->nullable();
            $table->integer('requested_by_id')->nullable();
            $table->integer('request_action_by_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('project_manager_id')->nullable();
            $table->integer('migration_requested_by_id')->nullable();
            $table->integer('migration_project_id')->nullable();
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
        Schema::dropIfExists('claims');
    }
}
