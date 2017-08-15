<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobilePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('mobile_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref')->nullable();
            $table->integer('meeting')->nullable();
            $table->date('requested_date')->nullable();
            $table->integer('requested_by_id')->nullable();
            $table->integer('request_action_by_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('mobile_payment_type_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->text('expense_desc')->nullable();
            $table->text('expense_purpose')->nullable();
            $table->string('payment_document')->nullable();
            $table->integer('payees_upload_mode_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('currency_id')->nullable()->default(1);
            $table->integer('project_manager_id')->nullable();
            $table->integer('brevity')->nullable();
            $table->integer('region_id')->nullable();
            $table->integer('county_id')->nullable();
            $table->string('attendance_sheet')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('rejected_by_id')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->integer('migration_requested_by_id')->nullable();
            $table->integer('migration_project_id')->nullable();
            $table->integer('migration_account_id')->nullable();
            $table->integer('migration_invoice_id')->nullable();
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
        Schema::dropIfExists('mobile_payments');
    }
}
