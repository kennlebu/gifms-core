<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceApportionmentRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_apportionment_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_split')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('migration_account_id')->nullable();
            $table->integer('migration_project_id')->nullable();
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
        Schema::dropIfExists('invoice_apportionment_rates');
    }
}
