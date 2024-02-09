<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    use HasFactory;

    public function wholesalepackages()
    {
        return $this->hasMany(WholesalePackage::class,'vendor_inventory_id','vendor_inventory_id');
    }


    public function retailpackages()
    {
        return $this->hasMany(RetailPackage::class,'vendor_inventory_id','vendor_inventory_id');
    }
}
