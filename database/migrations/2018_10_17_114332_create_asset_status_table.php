<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_asset_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->integer('next_status_id')->nullable();
            $table->integer('order_priority')->nullable();
            $table->string('display_color', 10)->nullable();
            $table->integer('default_status')->nullable();
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
        Schema::dropIfExists('fixed_asset_statuses');
    }
}
