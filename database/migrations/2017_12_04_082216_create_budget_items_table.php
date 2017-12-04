<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('budget_item_purpose')->nullable();
            $table->integer('budget_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->decimal('amount',26,5)->nullable();
            $table->integer('created_by_id')->nullable();
            $table->integer('create_action_by_id')->nullable();
            $table->integer('migration_project_id')->nullable();
            $table->integer('migration_account_id')->nullable();
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
        Schema::dropIfExists('budget_items');
    }
}
