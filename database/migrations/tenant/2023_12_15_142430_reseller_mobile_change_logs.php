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
        Schema::create('reseller_mobile_change_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('mobile_service_id')->notNullable();
            $table->string('sim_card_code',30)->nullable();
            $table->string('what_change',100)->nullable();
            $table->string('old_value',150)->notNullable();
            $table->string('new_value',150)->nullable();
            $table->dateTime('changed_at')->nullable();
            $table->integer('changed_by')->nullable();
            $table->dateTime('notification_sent_at')->nullable();  
            $table->string('notification_sent_to',150)->nullable();
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
        Schema::dropIfExists('reseller_mobile_change_logs');
    }
};



   
