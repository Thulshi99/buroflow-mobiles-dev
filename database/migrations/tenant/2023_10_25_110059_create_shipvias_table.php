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
        Schema::create('shipvias', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->integer('freight_rate_id')->default(0);
            $table->integer('freight_sales_sub_id')->default(0);
            $table->integer('freight_expense_acct_id')->default(0);
            $table->integer('freight_expense_sub_id')->default(0);

            $table->string('shipvia_code',15)->nullable();
            $table->string('shipvia_agent_name',100)->nullable();
            $table->string('calc_method',10)->nullable();
            $table->string('description',100)->nullable();
            $table->string('tax_category_id',15)->nullable();
            $table->string('carrier_plugin_id',15)->nullable();
            $table->string('plugin_method',100)->nullable();
            $table->string('shipping_application_type',3)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();

            $table->decimal('base_rate', $precision = 8, $scale = 2);

            $table->boolean('is_external')->nullable()->default(false);
            $table->boolean('confirmation_required')->nullable()->default(false);
            $table->boolean('package_required')->nullable()->default(false);
            $table->boolean('is_common_carrier')->nullable()->default(true);
            $table->boolean('return_label')->nullable()->default(false);
            $table->boolean('is_external_shipping_application')->nullable()->default(false);
            $table->boolean('validate_packed_qty')->nullable()->default(false);
            $table->boolean('calc_freight_on_return')->nullable()->default(false);


            $table->dateTime('date');
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
        Schema::dropIfExists('shipvias');
    }
};
