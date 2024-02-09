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
        Schema::create('customer_contact_infos', function (Blueprint $table) {
            $table->id();

            $table->integer('company_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('present_user_id')->default(0);
            $table->integer('customer_id')->default(0);
            $table->string('customer_billing_contact_code',12)->nullable();
            $table->string('contact_type',255)->nullable();
            $table->string('display_name',255)->nullable();
            $table->string('salutation',255)->nullable();
            $table->string('attention',255)->nullable();
            $table->string('first_name',50)->nullable();
            $table->string('mid_name',50)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('website',255)->nullable();
            $table->string('fax',50)->nullable();
            $table->string('fax_type',3)->nullable();
            $table->string('phone_one',50)->nullable();
            $table->string('phone_one_type',3)->nullable();
            $table->string('phone_two',50)->nullable();
            $table->string('phone_two_type',3)->nullable();
            $table->string('phone_three',50)->nullable();
            $table->string('phone_three_type',3)->nullable();
            $table->string('gender',8)->nullable();
            $table->string('spouse',255)->nullable();
            $table->string('status',10)->nullable();
            $table->string('class_id',10)->nullable();
            $table->string('ext_ref_number',36)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();

            $table->boolean('deleted_db_record')->nullable()->default(false);
            $table->boolean('no_fax')->nullable()->default(false);
            $table->boolean('no_mail')->nullable()->default(false);
            $table->boolean('no_marketing')->nullable()->default(false);
            $table->boolean('no_call')->nullable()->default(false);
            $table->boolean('no_email')->nullable()->default(false);
            $table->boolean('no_mass_mail')->nullable()->default(false);
            $table->boolean('marital_status')->nullable()->default(false);
            $table->boolean('is_convertable')->nullable()->default(false);
    

            $table->dateTime('date_of_birth');
            $table->dateTime('anniversary');
            $table->dateTime('assign_date');
            $table->dateTime('created_date_time');
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
        Schema::dropIfExists('customer_contact_infos');
    }
};
