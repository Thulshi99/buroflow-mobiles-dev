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
        Schema::create('sim_batch_orders', function (Blueprint $table) {
            $table->id();

            $table->integer('company_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->integer('ship_via')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('first_sim_card_id')->default(0);
            $table->integer('last_sim_card_id')->default(0);
            $table->integer('reseller_id')->default(0);

            $table->string('batch_number',6)->nullable();
            $table->string('ordered_by',50)->nullable();
            $table->string('delivery_address_line_one',2500)->nullable();

            $table->date('date_ordered');
            $table->date('date_received');
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
        Schema::dropIfExists('sim_batch_orders');
    }
};
