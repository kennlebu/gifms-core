<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrantAccountRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grant_account_restrictions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('grant_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('restricted_by_id')->nullable();
            $table->integer('migration_grant_id')->nullable();
            $table->integer('migration_account_id')->nullable();
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
        Schema::dropIfExists('grant_account_restrictions');
    }
}
