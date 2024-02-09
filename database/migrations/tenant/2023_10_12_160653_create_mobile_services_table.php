<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_services', function (Blueprint $table) {
            $table->id();
            $table->integer('mobile_service_id')->notNullable();
            $table->integer('company_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('vendor_account_id')->nullable();
            $table->integer('vendor_product_id')->nullable();
            $table->integer('reseller_id')->notNullable();
            $table->integer('wholesale_package_id')->nullable();
            $table->integer('wholesale_package_option_id')->nullable();
            $table->integer('retail_account_id')->nullable();
            $table->integer('retail_package_id')->nullable();
            $table->integer('retail_package_option_id')->nullable();
            $table->integer('datapool_id')->nullable();
            $table->integer('lineSeqNo')->nullable()->default(null);

            $table->string('retail_account_name', 50)->nullable();
            $table->string('mobile_number', 12)->nullable();
            $table->string('retail_discount_MRC_dollar', 45)->nullable();
            $table->string('retail_discount_MRC_percentage', 45)->nullable();
            $table->string('cost_centre', 45)->nullable();
            $table->string('end_user_name', 100)->nullable();
            $table->string('end_user_email', 100)->nullable();
            $table->string('service_address_line_1', 2500)->nullable();
            $table->string('service_address_line_2', 2500)->nullable();
            $table->string('city', 250)->nullable();
            $table->string('country', 250)->nullable();
            $table->string('state', 250)->nullable();
            $table->string('postal_code', 250)->nullable();
            $table->string('service_status', 150)->nullable();
            $table->string('customer_reference', 100)->nullable();
            $table->string('customer_notes', 100)->nullable();
            $table->string('batch_id', 10);
            $table->string('order_id', 10)->nullable()->default(null);
            $table->string('notes', 30)->nullable()->default(null);

            $table->bigInteger('data_limit')->nullable()->default(null);
            $table->bigInteger('data_quota')->nullable()->default(null);
            $table->bigInteger('data_used')->nullable()->default(null);

            $table->dateTime('api_trigger_datetime')->nullable();
            $table->date('user_date_of_birth')->nullable();

            $table->char('note_id', 36)->notNullable();

            $table->boolean('wholesale_or_retail')->nullable()->default(false);
            $table->boolean('outgoing_call')->nullable()->default(false);
            $table->boolean('voice_mail')->nullable()->default(false);
            $table->boolean('roaming')->nullable()->default(false);
            $table->boolean('api_call_trigger')->nullable()->default(false);
            $table->boolean('is_a_batch')->default(false);

            $table->timestamp('created_at', 6)->nullable();
            $table->timestamp('updated_at', 6)->nullable();
            $table->timestamp('tstamp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_services');
    }
};
