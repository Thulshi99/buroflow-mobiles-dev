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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->integer('mobile_service_order_id')->default(0);
            $table->integer('company_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->integer('vendor_product_id')->default(0);
            $table->integer('reseller_id')->default(0);
            $table->integer('wholesale_package_id')->default(0);
            $table->integer('wholesale_package_option_id')->default(0);
            $table->integer('customer_id')->default(0);
            $table->integer('retail_package_id')->default(0);
            $table->integer('retail_package_option_id')->default(0);

            $table->string('order_id',8)->nullable();
            $table->string('order_status',50)->nullable();
            $table->string('mobile_number',12)->nullable();
            $table->string('vendor_account_id',20)->nullable();
            $table->string('retails_discount_mrc_dollar',20)->nullable();
            $table->string('retails_discount_mrc_precentage',20)->nullable();
            $table->string('cost_centre',20)->nullable();
            $table->string('customer_reference',100)->nullable();
            $table->string('customer_notes',100)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();

            $table->boolean('wholesale_or_retail')->nullable()->default(false);
            $table->boolean('outgoing_call')->nullable()->default(false);
            $table->boolean('voice_mail')->nullable()->default(false);
            $table->boolean('roaming')->nullable()->default(false);
            $table->boolean('api_call_trigger')->nullable()->default(false);

            $table->dateTime('api_trigger_date_time');
            $table->dateTime('last_modified_date_time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
