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
        Schema::create('mobile_plans', function (Blueprint $table) {
            $table->id();

            $table->integer('company_id')->default(0);
            $table->integer('data_plan_id')->default(0);
            $table->integer('carrier_plan_id')->default(0);

            $table->bigInteger('data_allowance');
            $table->bigInteger('50_percent');
            $table->bigInteger('80_percent');

            $table->string('data_plan_code',12)->nullable();
            $table->string('plan_name',64)->nullable();
            $table->string('carrier_name',100)->nullable();
            $table->string('carrier_plan_name',64)->nullable();
            $table->string('status',64)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();
            $table->string('usage_type',5)->nullable();

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
        Schema::dropIfExists('mobile_plans');
    }
};
