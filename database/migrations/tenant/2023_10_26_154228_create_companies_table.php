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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('parent_company_id')->default(0);
            $table->integer('sequence')->default(0);
            $table->bigInteger('size');

            $table->boolean('is_readonly')->nullable()->default(false);
            $table->boolean('is_template')->nullable()->default(false);

            $table->string('company_cd',128)->nullable();
            $table->string('country_id',5)->nullable();
            $table->string('phone_mask',5)->nullable();
            $table->string('company_type',128)->nullable();
            $table->string('company_key',128)->nullable();
            $table->string('theme',255)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();


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
        Schema::dropIfExists('companies');
    }
};
