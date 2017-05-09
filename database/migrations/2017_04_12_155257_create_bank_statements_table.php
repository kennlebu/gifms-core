<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->date('post_date');
            $table->longText('reference');
            $table->longText('narrative');
            $table->date('value_date');
            $table->decimal('debit',18,2);
            $table->decimal('credit',18,2);
            $table->decimal('closingBalance',18,2);
            $table->integer('statement_month');
            $table->integer('statement_year');
            $table->integer('bank_account');
            $table->integer('posted');
            $table->integer('payment');
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
        Schema::dropIfExists('bank_statements');
    }
}
