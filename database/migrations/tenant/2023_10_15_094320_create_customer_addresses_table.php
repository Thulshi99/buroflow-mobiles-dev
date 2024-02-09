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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            $table->integer('company_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('customer_id')->default(0);
            $table->string('billing_address_code',12)->nullable();
            $table->string('address_type',12)->nullable();
            $table->string('line_one',50)->nullable();
            $table->string('line_two',50)->nullable();
            $table->string('line_three',50)->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',50)->nullable();
            $table->string('country',50)->nullable();
            $table->string('postal_code',20)->nullable();
            $table->string('tax_location_code',30)->nullable();
            $table->string('tax_municipal_code',30)->nullable();
            $table->string('tax_school_code',30)->nullable();
            $table->string('node_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();

            $table->boolean('is_validated')->nullable()->default(false);
            $table->boolean('is_billing')->nullable()->default(false);
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
        Schema::dropIfExists('customer_addresses');
    }
};
