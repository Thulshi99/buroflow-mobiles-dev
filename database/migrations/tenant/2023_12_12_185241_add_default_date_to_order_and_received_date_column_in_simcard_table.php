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
        Schema::table('sim_batch_orders', function (Blueprint $table) {
            $table->date('date_ordered')->default(date("Y-m-d"))->change();
            $table->date('date_received')->default(date("Y-m-d"))->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sim_batch_orders', function (Blueprint $table) {
            //
        });
    }
};
