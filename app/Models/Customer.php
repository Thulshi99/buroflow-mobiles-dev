<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    // /**
    //  * The primary key associated with the table.
    //  *
    //  * @var string
    //  */
    // protected $primaryKey = 'customer_id';

        /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'company_id','job_title','email', 'customer_code', 'primary_contact_name', 'current_phone_number', 'allow_override_rate','payments_allowed',
        'auto_apply_payments','print_statements','print_statements','send_statement_by_email',
        'shared_credit_policy','consolidate_statements','fin_change_apply','pay_to_parent',
        'def_so_address_id','def_bill_address_id','def_bill_contact_id','def_payment_method_id','base_bill_contact_id','def_pm_instance_id',
        'rate_type_id','disable_account','deleted_database_record','mail_dunning_letters','print_dunning_letters','mail_invoices','print_invoices','credit_limit',
        'small_balance_limit','credit_rule','statement_cycle_id','statement_type','local_name','note_id','created_by_id','create_by_screen_id',
        'last_modified_by_id','last_modified_by_screen_id','credit_days_past_due','statement_customer_id','shared_credit_customer_id','reseller_id','statement_last_date','created_date_time','last_modified_date_time'
    ];

    public function customercontactinfos()
    {
        return $this->hasOne(CustomerContactInfo::class,'customer_id');
    }

    public function addresses()
    {
        return $this->hasOne(CustomerAddress::class,'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
