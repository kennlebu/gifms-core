<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentBatchStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_batch_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_batch_status');
            $table->integer('next_status_id')->nullable();
            $table->integer('order_priority')->nullable();
            $table->string('display_color')->nullable();
            $table->integer('default_status')->nullable();
            $table->integer('approval_level_id')->nullable();
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
        Schema::dropIfExists('payment_batch_statuses');
    }
}
