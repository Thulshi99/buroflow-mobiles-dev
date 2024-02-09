<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'reseller_id',
        'reseller_name',
        'reseller_email_address',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function simcards()
    {
        return $this->hasMany(SimCard::class);
    }
}
