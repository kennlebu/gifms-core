<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bank_id')->nullable();
            $table->integer('Bank_branch_id')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('mobile_payment_number')->nullable();
            $table->string('chaque_address')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('usd_account')->nullable();
            $table->string('alternative_email')->nullable();
            $table->string('currency')->nullable();
            $table->string('mobile_payment_name')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('qb')->nullable();
            $table->integer('status')->nullable();
            $table->integer('staff')->nullable();
            $table->string('password')->nullable();
            $table->integer('quick_books')->nullable();
            $table->string('tax_pin')->nullable();
            $table->integer('migration_bank_id')->nullable();
            $table->integer('migration_Bank_branch_id')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
