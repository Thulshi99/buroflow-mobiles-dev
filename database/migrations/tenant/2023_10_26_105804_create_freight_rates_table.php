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
        Schema::create('freight_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->integer('line_number')->default(0);

            $table->decimal('weight', $precision = 15, $scale = 6);
            $table->decimal('volume', $precision = 15, $scale = 6);
            $table->decimal('rate', $precision = 15, $scale = 6);

            $table->string('ship_via_id',15)->nullable();
            $table->string('zone_id',15)->nullable();
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
        Schema::dropIfExists('freight_rates');
    }
};
