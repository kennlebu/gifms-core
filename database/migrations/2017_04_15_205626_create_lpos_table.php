<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lpos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref')->nullable();
            $table->string('lpo_date')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('addressee')->nullable();
            $table->text('expense_desc')->nullable();
            $table->text('expense_purpose')->nullable();
            $table->integer('requested_by_id')->nullable();
            $table->integer('request_action_by_id')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('preffered_quotation_id')->nullable();
            $table->integer('supply_category')->nullable();
            $table->string('delivery_made')->nullable();
            $table->string('delivery_document')->nullable();
            $table->text('delivery_Comment')->nullable();
            $table->timestamp('date_delivered')->nullable();
            $table->integer('received_by_id')->nullable();
            $table->integer('meeting')->nullable();
            $table->text('comments')->nullable();
            $table->string('preffered_supplier')->nullable();
            $table->integer('preffered_supplier_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->string('attention')->nullable();
            $table->string('lpo_email')->nullable();
            $table->integer('project_manager_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('rejected_by_id')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->integer('cancelled_by_id')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('quote_exempt')->nullable();
            $table->text('quote_exempt_explanation')->nullable();
            $table->integer('migration_preffered_quotation_id')->nullable();
            $table->integer('migration_account_id')->nullable();
            $table->integer('migration_requested_by_id')->nullable();
            $table->integer('migration_project_id')->nullable();
            $table->integer('migration_received_by_id')->nullable();
            $table->integer('migration_supplier_id')->nullable();
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
        Schema::dropIfExists('lpos');
    }
}
