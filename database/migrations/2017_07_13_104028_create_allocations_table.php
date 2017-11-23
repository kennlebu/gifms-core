<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('allocatable_id')->nullable();
            $table->text('allocatable_type')->nullable();
            $table->double('amount_allocated',30,5)->nullable();
            $table->double('amount_allocated_base_currency',30,5)->nullable();
            $table->integer('allocation_month')->nullable();
            $table->integer('allocation_year')->nullable();
            $table->text('allocation_purpose')->nullable();
            $table->double('percentage_allocated',26,5)->nullable();
            $table->integer('allocated_by_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('account_2013_id')->nullable();
            $table->integer('account_2016_id')->nullable();
            $table->integer('migration_allocated_by_id')->nullable();
            $table->integer('migration_allocatable_id')->nullable();
            $table->integer('migration_project_id')->nullable();
            $table->integer('migration_account_2013_code')->nullable();
            $table->integer('migration_account_2016_code')->nullable();
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
        Schema::dropIfExists('allocations');
    }
}
