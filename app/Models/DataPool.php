<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPool extends Model
{
    use HasFactory;

    protected $fillable = [
        'datapool_id',
        'customer_name',
        'department',
        'carrier',
        'lineseq_no',
        'data_limit',
        'datapool_code',
        'reseller_id',
        'email_address_1',
        'email_address_2',
        'email_address_3',
        'is_compatible_plan',
        'pool_type',
        'description',
        'mobile_service_ids',
        'data_plan_id',
        'pricing',
        'order_id',
        'status',
        'notes'
    ];


}
