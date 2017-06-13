<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobilePaymentPayeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_payment_payees', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->nullable();
            $table->text('registered_name')->nullable();
            $table->string('id_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->double('amount',30,5)->nullable();
            $table->string('email')->nullable();
            $table->double('withdrawal_charges',10,2)->nullable();
            $table->double('total',30,5)->nullable();
            $table->string('designation')->nullable();
            $table->string('sub_county')->nullable();
            $table->string('county_id')->nullable();
            $table->string('region_id')->nullable();
            $table->integer('paid')->nullable();
            $table->string('payment_reference')->nullable();
            $table->integer('mobile_payment_id')->nullable();
            $table->integer('migration_mobile_payment_id')->nullable();
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
        Schema::dropIfExists('mobile_payment_payees');
    }
}
