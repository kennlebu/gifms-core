<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asset_name', 255);
            $table->string('serial_number', 255)->nullable();
            $table->timestamp('purchase_date', 30)->nullable();
            $table->double('cost')->nullable();
            $table->double('donated_value')->nullable();
            $table->timestamp('date_donated', 30)->nullable();
            $table->integer('obsolete_value')->nullable();
            $table->timestamp('obsolescence_date', 30)->nullable();
            $table->double('scrapped_value')->nullable();
            $table->timestamp('date_scrapped', 30)->nullable();
            $table->double('insured_value')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('asset_location_id')->nullable();
            $table->integer('asset_category_id')->nullable();
            $table->integer('assigned_to_id')->nullable();
            $table->integer('added_by_id')->nullable();
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
        Schema::dropIfExists('fixed_assets');
    }
}
