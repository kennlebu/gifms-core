<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_types', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('approval')->nullable();
            $table->string('status_table')->nullable();
            $table->string('approval_table')->nullable();
            $table->string('status_field')->nullable();
            $table->integer('display_order')->nullable();
            $table->string('title_field')->nullable();
            $table->string('payee_field')->nullable();
            $table->string('amounts_field')->nullable();
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
        Schema::dropIfExists('approval_types');
    }
}
