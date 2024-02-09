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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            // $table->integer('def_so_address_id')->default(0);
            // $table->integer('def_bill_address_id')->default(0);
            // $table->integer('def_bill_contact_id')->default(0);
            // $table->integer('base_bill_contact_id')->default(0);
            $table->string('customer_code',15)->nullable();
            $table->string('primary_contact_name',12)->nullable();
            $table->string('job_title',50)->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->string('current_phone_number',12)->nullable();
            $table->string('def_payment_method_id',10)->nullable();
            $table->string('def_pm_instance_id',10)->nullable();
            $table->string('rate_type_id',6)->nullable();

            $table->boolean('allow_override_rate')->default(0);
            $table->boolean('payments_allowed')->default(0);
            $table->boolean('auto_apply_payments')->default(0);
            $table->boolean('print_statements')->default(0);
            $table->boolean('send_statement_by_email')->default(0);
            $table->boolean('shared_credit_policy')->default(0);
            $table->boolean('consolidate_statements')->default(0);
            $table->boolean('small_balance_allow')->default(0);
            $table->boolean('fin_change_apply')->default(0);
            $table->boolean('pay_to_parent')->default(0);
            $table->boolean('print_invoices')->default(0);
            $table->boolean('mail_invoices')->default(0);
            $table->boolean('print_dunning_letters')->default(0);
            $table->boolean('mail_dunning_letters')->default(0);
            $table->boolean('deleted_database_record')->default(0);
            $table->boolean('disable_account')->default(0);

            $table->double('credit_limit', 19, 4)->default('0.00');
            $table->double('small_balance_limit', 19, 4)->default('0.00');

            $table->string('credit_rule',5)->nullable();
            $table->string('statement_cycle_id',10)->nullable();
            $table->string('statement_type',5)->nullable();
            $table->string('local_name',10)->nullable();
            $table->string('note_id',36)->nullable();
            $table->string('created_by_id',36)->nullable();
            $table->string('create_by_screen_id',8)->nullable();
            $table->string('last_modified_by_id',36)->nullable();
            $table->string('last_modified_by_screen_id',8)->nullable();

            $table->integer('credit_days_past_due')->default(0);
            $table->integer('statement_customer_id')->default(0);
            $table->integer('shared_credit_customer_id')->default(0);
            $table->integer('reseller_id')->default(0);

            $table->dateTime('statement_last_date');
            $table->dateTime('created_date_time');
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
        Schema::dropIfExists('customers');
    }
};
