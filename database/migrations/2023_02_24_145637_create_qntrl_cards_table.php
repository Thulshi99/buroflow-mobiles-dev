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
        Schema::create('qntrl_cards', function (Blueprint $table) {
            $table->id();
            $table->string('status_updated')->nullable();
            $table->string('qntrl_id')->nullable();
            $table->string('buroflow_reference')->nullable();
            $table->json('raw')->nullable();
            $table->dateTime('scheduled_time')->nullable();
            // $table->foreignId('user_id')->nullable();
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
        Schema::dropIfExists('qntrl_cards');
    }
};
