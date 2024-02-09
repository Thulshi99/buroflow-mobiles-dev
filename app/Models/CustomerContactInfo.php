<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContactInfo extends Model
{
    use HasFactory;
    protected $table = 'customer_contact_infos';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    } 

}
