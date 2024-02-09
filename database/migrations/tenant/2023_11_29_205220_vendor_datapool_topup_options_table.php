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
        Schema::create('vendor_datapool_topup_options', function (Blueprint $table) {
            $table->id();
            $table->integer('option_id');
            $table->integer('vendor_datapool_product_id');
            $table->decimal('topup_amount_gb', 8, 3);


            //$table->foreign('vendor_datapool_product_id')->references('id')->on('vendor_datapool_products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
