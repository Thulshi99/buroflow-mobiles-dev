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
        Schema::create('hardware', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->integer('model_id')->default(0);
            $table->integer('reseller_id')->default(0);
            $table->integer('reseller_account_id')->default(0);

            $table->string('model_code',12)->nullable();
            $table->string('description',60)->nullable();
            $table->string('brand',30)->nullable();
            $table->string('model',30)->nullable();
            $table->string('serial_number',50)->nullable();
            $table->string('imei',50)->nullable();
            $table->string('supplier',50)->nullable();
            $table->string('end_user',100)->nullable();
            $table->string('customer_reference',60)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();


            $table->dateTime('last_modified_date_time');
            $table->date('warranty_ends');
            $table->date('purchase_date');
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
        Schema::dropIfExists('hardware');
    }
};
