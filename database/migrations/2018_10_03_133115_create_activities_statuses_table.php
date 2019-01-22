<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->text('status');
            $table->integer('next_status_id')->nullable();
            $table->string('short_name', 50)->nullable();
            $table->string('display_color', 20)->nullable();
            $table->integer('approvable')->nullable();
            $table->integer('status_id')->nullable();
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
        Schema::dropIfExists('activities_statuses');
    }
}
