<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_status');
            $table->integer('next_status_id')->nullable();
            $table->integer('status_security_level')->nullable();
            $table->integer('order_priority')->nullable();
            $table->string('display_color')->nullable();
            $table->integer('default_status')->nullable();
            $table->integer('default_log_status')->nullable();
            $table->integer('approval_level_id')->nullable();
            $table->integer('approvable')->nullable();
            $table->integer('migration_status_security_level')->nullable();
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
        Schema::dropIfExists('invoice_statuses');
    }
}
