<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_ref');
            $table->string('chai_ref');
            $table->string('inputter')->nullable();
            $table->string('approver')->nullable();
            $table->string('file_type')->nullable();
            $table->decimal('amount',18,2);
            $table->string('file_name');
            $table->date('txn_date');
            $table->string('txn_time');
            $table->date('processing_date');
            $table->string('narrative');
            $table->integer('bank_csv');
            $table->integer('allocated');
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
        Schema::dropIfExists('bank_transactions');
    }
}
