<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLpoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lpo_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lpo_id')->nullable();
            $table->text('item_description')->nullable();
            $table->double('unit_price',22,5)->nullable();
            $table->integer('vat_inclusive')->nullable();
            $table->integer('qty');
            $table->string('qty_description')->nullable();
            $table->string('quotation')->nullable();
            $table->string('item')->nullable();
            $table->integer('vat_charge');
            $table->integer('lpo_migration_id');
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
        Schema::dropIfExists('lpo_items');
    }
}
