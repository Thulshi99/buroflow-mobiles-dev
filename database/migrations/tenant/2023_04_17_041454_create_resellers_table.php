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
        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->integer('reseller_id')->length(11);
            $table->integer('parent_reseller_id')->length(11);
            $table->integer('reseller_porta_id')->length(11)->nullable();
            $table->string('reseller_porta_other_info',200)->nullable();
            $table->string('reseller_name',100);
            $table->string('reseller_billing_account_no',45);
            $table->string('reseller_utb_account_no',11)->nullable();
            $table->enum('reseller_billing_from',['Selcomm(BW)','Utility Bill','Porta'])->nullable();
            $table->string('reseller_website_url',65)->nullable();
            $table->string('reseller_email_address',300)->nullable();
            $table->string('reseller_tel_no',15)->nullable();
            $table->string('reseller_mobile',12)->nullable();
            $table->string('reseller_address',150)->nullable();
            $table->string('reseller_logo',150)->nullable();
            $table->string('reseller_dir_name',65);
            $table->enum('reseller_status',['active','banned','deactivated'])->default('active');
            $table->enum('brand_type',['Reseller','Retail','Main Tenant','Main Wholesale','Main Retail'])->nullable();
            $table->string('associated_realm',300);
            $table->string('associated_users',200)->nullable();
            $table->enum('email_notifications',['Y','N'])->default('N');
            $table->enum('sms_notifications',['Y','N'])->default('N');
            $table->enum('retail_billing',['yes','no'])->default('no');
            $table->string('business_unit_code',100)->nullable();
            $table->string('business_unit_narative',65)->nullable();
            $table->enum('tiab_billing',['yes','no'])->default('no');
            $table->string('tiab_brands',150)->nullable();
            $table->string('bw_acc_nos',200)->nullable();
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
        Schema::dropIfExists('resellers');
    }
};
