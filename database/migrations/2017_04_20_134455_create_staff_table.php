<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('security_group_id')->nullable();
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('o_names')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('post')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('mpesa_no')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('cheque_addressee')->nullable();
            $table->string('payment_mode_id')->nullable();
            $table->integer('bank_id')->nullable();
            $table->integer('bank_branch_id')->nullable();
            $table->string('station')->nullable();
            $table->string('swift_code')->nullable();
            $table->integer('active')->nullable();
            $table->string('signature')->nullable();
            $table->string('bank_signatory')->nullable();
            $table->integer('receive_global_email_bcc')->nullable();
            $table->integer('migration_bank_id')->nullable();
            $table->integer('migration_bank_branch_id')->nullable();
            $table->integer('migration_department_id')->nullable();
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
        Schema::dropIfExists('staff');
    }
}
