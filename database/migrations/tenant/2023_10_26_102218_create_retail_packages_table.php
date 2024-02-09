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
        Schema::create('retail_packages', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->integer('reseller_id')->default(0);
            $table->integer('reseller_wholesale_package_id')->default(0);
            $table->integer('vendor_inventory_id')->default(0);

            $table->string('retail_pakage_code',12)->nullable();
            $table->string('retail_pakage_name',50)->nullable();
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
        Schema::dropIfExists('retail_packages');
    }
};
