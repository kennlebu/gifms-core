<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankProjectBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_project_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bank_account_id');
            $table->integer('project_id');
            $table->double('balance',22,5)->nullable();
            $table->dateTime('balance_date');
            $table->integer('balance_status');
            $table->dateTime('balance_end_date')->nullable();
            $table->double('balance_usd',22,5);
            $table->integer('invoice')->nullable();
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
        Schema::dropIfExists('bank_project_balances');
    }
}
