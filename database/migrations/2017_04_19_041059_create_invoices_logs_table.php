<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('invoice_date')->nullable();
            $table->double('invoice_amount',30,5)->nullable();
            $table->integer('log_status')->nullable();
            $table->string('invoice_no')->nullable();
            $table->integer('logged_by')->nullable();
            $table->integer('staff_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('migration_logged_by')->nullable();
            $table->integer('migration_staff_id')->nullable();
            $table->integer('migration_supplier_id')->nullable();
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
        Schema::dropIfExists('invoices_logs');
    }
}
