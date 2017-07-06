<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref')->nullable();
            $table->integer('payable_id')->nullable();
            $table->text('payable_type')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->integer('advance_id')->nullable();
            $table->integer('debit_bank_account_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->text('paid_to_mobile_phone_no')->nullable();
            $table->text('paid_to_bank_account_no')->nullable();
            $table->integer('paid_to_bank_id')->nullable();
            $table->integer('payment_mode_id')->nullable();
            $table->double('amount',30,5)->nullable();
            $table->integer('payment_batch_id')->nullable();
            $table->double('bank_charges',26,2)->nullable();
            $table->string('migration_payment_batch_id')->nullable();
            $table->integer('migration_invoice_id')->nullable();
            $table->integer('migration_advance_id')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
