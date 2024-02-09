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
        Schema::create('simcards', function (Blueprint $table) {
            $table->id();

            $table->integer('puk_code')->default(0);
            $table->integer('reseller_id')->default(0);
            $table->integer('company_id')->default(0);
            $table->integer('shipvia_id')->default(0);

            $table->string('sim_card_code',30)->nullable();
            $table->string('batch_number',6)->nullable();
            $table->string('mobile_number',12)->nullable();
            $table->string('status',50)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();

            $table->boolean('port')->nullable()->default(false);
            $table->boolean('api_call_trigger')->nullable()->default(false);
            $table->dateTime('api_trigger_date_time');


            $table->dateTime('activation_date_time');
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
        Schema::dropIfExists('simcards');
    }
};
