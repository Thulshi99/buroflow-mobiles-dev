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
        Schema::create('qntrl_orders', function (Blueprint $table) {
            $table->id();
            $table->string('card_id')->nullable('true');
            $table->timestamp('creation_date')->nullable('true');
            $table->string('buroflow_reference');
            $table->string('location_id');
            $table->string('retail_account')->nullable('true');
            $table->string('customer_name')->nullable('true');
            $table->string('customer_reference')->nullable('true');
            $table->string('prior_service')->nullable('true');
            $table->string('radius_user')->nullable('true');
            $table->string('aapt_service')->nullable('true');
            $table->json('raw');
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
        Schema::dropIfExists('qntrl_orders');
    }
};
