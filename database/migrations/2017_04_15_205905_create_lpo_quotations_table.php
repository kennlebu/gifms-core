<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLpoQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lpo_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lpo_id')->nullable();
            $table->string('quotation_doc');
            $table->integer('Supplier');
            $table->double('amount',22,8)->nullable();
            $table->text('quote_description')->nullable();
            $table->date('quote_date')->nullable();
            $table->integer('Uploaded_by')->nullable();
            $table->integer('quote_option')->nullable();
            $table->integer('lpo_migration_id')->nullable();
            $table->integer('migration_id')->unique();
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
        Schema::dropIfExists('lpo_quotations');
    }
}
