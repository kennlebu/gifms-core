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
            $table->string('chai_ref')->nullable();
            $table->string('lpo_date')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('addressee')->nullable();
            $table->text('expense_desc')->nullable();
            $table->text('purpose')->nullable();
            $table->integer('requested_by_id')->nullable();
            $table->integer('requested_action_by_id')->nullable();
            $table->date('request_date')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('currency')->nullable();
            $table->integer('quotation')->nullable();
            $table->integer('supply_category')->nullable();
            $table->string('delivery_document')->nullable();
            $table->date('date_delivered')->nullable();
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
            $table->text('reject_reason')->nullable();
            $table->integer('quote_exempt')->nullable();
            $table->text('quote_exempt_explanation')->nullable();
            $table->integer('migration_account_id')->nullable();
            $table->integer('migration_requested_by_id')->nullable();
            $table->integer('migration_project_id');
            $table->integer('migration_received_by_id')->nullable();
            $table->integer('migration_supplier_id')->nullable();
            $table->integer('migration_project_manager_id')->nullable();
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
        Schema::dropIfExists('lpos');
    }
}
