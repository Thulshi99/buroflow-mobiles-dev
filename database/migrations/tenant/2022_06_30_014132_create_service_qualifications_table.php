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
        Schema::create('service_qualifications', function (Blueprint $table) {
            $table->id();
            $table->string('loc_id')->nullable();
            $table->string('tenant_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('service_qualifications');
    }
};
