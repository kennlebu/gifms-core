<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref')->nullable();
            $table->date('payment_date')->nullable();
            $table->date('upload_date')->nullable();
            $table->integer('upload_status')->nullable();
            $table->integer('processed_by')->nullable();
            $table->integer('uploaded_by')->nullable();
            $table->integer('migration_processed_by')->nullable();
            $table->integer('migration_uploaded_by')->nullable();
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
        Schema::dropIfExists('payment_batches');
    }
}
