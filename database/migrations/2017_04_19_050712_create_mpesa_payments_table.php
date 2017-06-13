<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpesaPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesa_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meeting')->nullable();
            $table->date('requested_date')->nullable();
            $table->integer('requested_by')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('mpesa_payment_type')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->string('title')->nullable();
            $table->text('mpesa_payment_desc')->nullable();
            $table->string('payment_document')->nullable();
            $table->integer('status')->nullable();
            $table->integer('project_manager')->nullable();
            $table->integer('brevity')->nullable();
            $table->integer('region')->nullable();
            $table->integer('county')->nullable();
            $table->string('attentendance_sheet')->nullable();
            $table->text('reject_reason')->nullable();
            $table->integer('migration_project_id')->nullable();
            $table->integer('migration_account_id')->nullable();
            $table->integer('migration_invoice_id')->nullable();
            $table->integer('migration_project_manager')->nullable();
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
        Schema::dropIfExists('mpesa_payments');
    }
}
