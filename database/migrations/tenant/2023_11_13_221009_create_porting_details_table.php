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
        Schema::create('porting_details', function (Blueprint $table) {
            $table->id();

            $table->integer('company_id')->default(0);
            $table->integer('mobile_number')->default(0);
            $table->integer('order_id')->default(0);


            $table->string('loosing_carrier',64)->nullable();
            $table->string('loosing_carrier_account',64)->nullable();
            $table->string('loosing_carrier_address',150)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();

            $table->dateTime('port_submitted_date_time');
            $table->dateTime('port_confirmed_date_time');
            $table->dateTime('last_modified_date_time');
            $table->dateTime('date_of_birth');
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
        Schema::dropIfExists('porting_details');
    }
};
