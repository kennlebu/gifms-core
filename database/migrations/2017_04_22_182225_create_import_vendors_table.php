<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_vendors', function (Blueprint $table) {          
            $table->increments('id');
            $table->string('date_time')->nullable();
            $table->string('status')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('is_active')->nullable();
            $table->string('company_name')->nullable();
            $table->string('salutation')->nullable();
            $table->string('first_name')->nullable();
            $table->string('m_i')->nullable();
            $table->string('last_name')->nullable();
            $table->string('contact')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('alt_phone')->nullable();
            $table->string('alt_contact')->nullable();
            $table->string('email')->nullable();
            $table->string('print_on_check')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('address_line_4')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_country')->nullable();
            $table->string('vendor_type')->nullable();
            $table->string('terms')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('eligible_for_1099')->nullable();
            $table->string('account_number')->nullable();
            $table->string('credit_limit')->nullable();
            $table->string('notes')->nullable();
            $table->string('prefill_account')->nullable();
            $table->string('billing_rate')->nullable();
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
        Schema::dropIfExists('import_vendors');
    }
}
