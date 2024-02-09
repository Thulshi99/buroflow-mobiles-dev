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
        Schema::create('data_pools', function (Blueprint $table) {
            $table->id();
            $table->integer('datapool_id')->notNullable()->default(0);
            $table->string('datapool_code', 30)->nullable()->default(null);
            $table->string('data_allowance(gb)', 45)->nullable()->default(null);
            $table->integer('company_id');
            $table->integer('reseller_id')->notNullable();
            $table->integer('vendor_id');
            $table->integer('vendor_datapool_product_id');
            $table->integer('service_no')->notNullable();
            $table->integer('lineseq_no')->nullable()->default(null);
            $table->string('customer_name', 150)->notNullable();
            $table->string('department', 100)->notNullable();
            $table->string('carrier', 100)->notNullable();
            $table->string('email_address_1', 100)->nullable()->default(null);
            $table->string('email_address_2', 100)->nullable()->default(null);
            $table->string('email_address_3', 100)->nullable()->default(null);
            $table->tinyInteger('is_compatible_plan')->nullable()->default(null);
            $table->string('pool_type', 45)->nullable()->default(null);
            $table->string('description', 60)->nullable()->default(null);
            $table->integer('data_plan_id')->nullable()->default(null);
            $table->string('pricing', 45)->nullable()->default(null);
            $table->bigInteger('data_limit')->nullable()->default(null);
            $table->bigInteger('data_used')->nullable()->default(null);
            $table->integer('order_id')->nullable()->default(null);
            $table->integer('status')->nullable()->default(0);
            $table->string('notes', 30)->nullable()->default(null);
            $table->char('note_id', 36)->notNullable();
            $table->char('createdby_id', 36)->nullable()->default(null);
            $table->timestamp('created_at')->nullable();
            $table->char('last_modifiedby_id', 36)->nullable()->default(null);
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('tstamp')->nullable();

            //$table->foreign('note_id')->references('note_id')->on('notes')->onUpdate('no action')->onDelete('no action');
            //$table->foreign('data_plan_id')->references('dataplan_id')->on('dataplans')->onUpdate('no action')->onDelete('no action');
            //$table->foreign('reseller_id')->references('reseller_id')->on('resellers')->onUpdate('no action')->onDelete('no action');
            //$table->foreign('note_id')->references('note_id')->on('notes')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_pools');
    }
};
