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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();

            $table->integer('company_id')->default(0);
            $table->integer('mobile_service_order_code')->default(0);
            $table->integer('reseller_id')->default(0);
            $table->integer('customer_id')->default(0);

            $table->string('ticket_code',15)->nullable();
            $table->string('description',60)->nullable();
            $table->string('fault_category',255)->nullable();
            $table->string('status',20)->nullable();
            $table->string('email',100)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();

            $table->boolean('api_triggered')->nullable()->default(false);

            $table->date('date');
            $table->dateTime('api_triggered_datetime');
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
        Schema::dropIfExists('support_tickets');
    }
};
