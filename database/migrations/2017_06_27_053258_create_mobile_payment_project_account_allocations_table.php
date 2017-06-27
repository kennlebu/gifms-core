<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobilePaymentProjectAccountAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_payment_project_account_allocations', function (Blueprint $table) {
            $table->increments('id');
            $table->text('allocation_purpose')->nullable();
            $table->double('amount_allocated',30,4)->nullable();
            $table->double('percentage_allocated',7,4)->nullable();
            $table->integer('allocated_by_id')->nullable();
            $table->integer('mobile_payment_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_payment_project_account_allocations');
    }
}
