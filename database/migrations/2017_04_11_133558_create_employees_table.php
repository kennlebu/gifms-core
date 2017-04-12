<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('f_name');
            $table->string('l_name');
            $table->integer('department_id');
            $table->string('post');
            $table->string('mobile_no');
            $table->string('mpesa_no')->nullable();
            $table->string('bank_account');
            $table->string('cheque_addressee');
            $table->string('payment_mode');
            $table->integer('bank');
            $table->integer('bank_branch');
            $table->string('station');
            $table->string('swift_code');
            $table->string('signature')->nullable();
            $table->string('bank_signatory');
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
        Schema::dropIfExists('employees');
    }
}
