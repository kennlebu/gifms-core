<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceProjectAccountAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_project_account_allocations', function (Blueprint $table) {
            $table->increments('id');
            $table->double('amount_allocated',30,5)->nullable();
            $table->integer('invoice_allocation_month')->nullable();
            $table->integer('invoice_allocation_year')->nullable();
            $table->text('allocation_purpose')->nullable();
            $table->double('percentage_allocated',26,5)->nullable();
            $table->integer('brevity')->nullable();
            $table->integer('allocated_by')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('project_account')->nullable();
            $table->integer('project_account_2016')->nullable();
            $table->integer('migration_allocated_by')->nullable();
            $table->integer('migration_invoice_id')->nullable();
            $table->integer('migration_project_id')->nullable();
            $table->integer('migration_project_account')->nullable();
            $table->integer('migration_project_account_2016')->nullable();
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
        Schema::dropIfExists('invoice_project_account_allocations');
    }
}
