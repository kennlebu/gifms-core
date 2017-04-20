<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('station_name')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->string('country')->nullable();
            $table->integer('budget_default')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('primary_contact')->nullable();
            $table->string('email')->nullable();
            $table->string('town')->nullable();
            $table->string('currency')->nullable();
            $table->string('expiry_date')->nullable();
            $table->integer('licence_level')->nullable();
            $table->integer('licence_status')->nullable();
            $table->string('accountant_email')->nullable();
            $table->string('finance_email')->nullable();
            $table->string('manager_email')->nullable();
            $table->string('installation_path')->nullable();
            $table->string('alert_email_address')->nullable();
            $table->string('alert_email_password')->nullable();
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
        Schema::dropIfExists('stations');
    }
}
