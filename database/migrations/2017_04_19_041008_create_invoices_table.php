<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref')->nullable();
            $table->text('expense_desc')->nullable();
            $table->text('expense_purpose')->nullable();
            $table->string('external_ref')->nullable();
            $table->timestamp('invoice_date')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->integer('received_by_id')->nullable();
            $table->timestamp('raised_at')->nullable();
            $table->integer('raised_by_id')->nullable();
            $table->integer('raise_action_by_id')->nullable();
            $table->double('total',30,5)->nullable();
            $table->string('invoice_document')->nullable();
            $table->integer('project_manager_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->dateTime('accountant_approval_date')->nullable();
            $table->dateTime('management_approval_date')->nullable();
            $table->integer('management_approval_id')->nullable();
            $table->dateTime('pm_approval_date')->nullable();
            $table->integer('allocated')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->integer('payment_mode_id')->nullable();
            $table->integer('staff_advance')->nullable();
            $table->date('reconcilliation_date')->nullable();
            $table->text('comments')->nullable();
            $table->integer('invoice_type_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('rejected_by_id')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->integer('withholding_tax')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('voucher_no')->nullable();
            // $table->integer('uploaded_by')->nullable();
            $table->integer('approver_id')->nullable();
            $table->integer('claim_id')->nullable();
            $table->integer('lpo_id')->nullable();
            $table->integer('advance_id')->nullable();
            $table->integer('mpesa_id')->nullable();
            $table->string('bank_ref_no')->nullable();
            $table->integer('shared_cost')->nullable();
            $table->datetime('csv_generated')->nullable();
            $table->string('recurring_period')->nullable();
            $table->date('recurr_end_date')->nullable();
            $table->integer('vat')->nullable();
            $table->integer('excise_duty')->nullable();
            $table->integer('catering_levy')->nullable();
            $table->double('zero_rated',30,5)->nullable();
            $table->double('exempt_supplies',30,5)->nullable();
            $table->double('other_levies',30,5)->nullable();
            $table->double('other_amounts',30,5)->nullable();
            $table->integer('migration_supplier_id')->nullable();
            $table->integer('migration_project_manager_id')->nullable();
            $table->integer('migration_management_approval_id')->nullable();
            $table->integer('migration_raised_by_id')->nullable();
            $table->integer('migration_approver_id')->nullable();
            $table->integer('migration_claim_id')->nullable();
            $table->integer('migration_lpo_id')->nullable();
            $table->integer('migration_advance_id')->nullable();
            $table->integer('migration_mpesa_id')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
