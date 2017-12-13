<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrantAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grant_allocations', function (Blueprint $table) {
            $table->increments('id');;
            $table->integer('grant_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->decimal('amount_allocated',30,5)->nullable();
            $table->double('percentage_allocated',26,5)->nullable();
            $table->integer('allocated_by_id')->nullable();
            $table->integer('allocate_action_by_id')->nullable();
            $table->string('allocation_type')->default("DEFAULT");
            $table->integer('migration_grant_id')->nullable();
            $table->integer('migration_project_id')->nullable();
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
        Schema::dropIfExists('grant_allocations');
    }
}
