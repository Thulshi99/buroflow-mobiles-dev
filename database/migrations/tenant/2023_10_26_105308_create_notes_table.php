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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);

            $table->string('old_note_id',36)->nullable();
            $table->string('external_key',255)->nullable();
            $table->longText('note_text')->nullable();
            $table->longText('note_popup_text')->nullable();
            $table->string('graph_type',255)->nullable();
            $table->string('entity_type',255)->nullable();
            $table->string('company_mask',32)->nullable();
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
        Schema::dropIfExists('notes');
    }
};
