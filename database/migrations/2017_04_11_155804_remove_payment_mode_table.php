<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePaymentModeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('payment_mode');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::create('payment_mode', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_mode_description');
            $table->string('abrv');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
