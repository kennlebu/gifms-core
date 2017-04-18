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
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('post')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('mpesa_no')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('cheque_addressee')->nullable();
            $table->string('payment_mode')->nullable();
            $table->integer('bank_id')->nullable();
            $table->integer('bank_branch_id')->nullable();
            $table->string('station')->nullable();
            $table->string('swift_code')->nullable();
            $table->integer('active')->nullable();
            $table->string('signature')->nullable();
            $table->string('bank_signatory')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('migration_user_id')->nullable();
            $table->integer('migration_department_id')->nullable();
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
