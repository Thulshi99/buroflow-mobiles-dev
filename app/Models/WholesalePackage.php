<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalePackage extends Model
{
    use HasFactory;

    public function vendorproducts()
    {
        return $this->belongsTo(VendorProduct::class,'vendor_inventory_id','vendor_inventory_id');
    }

    public function wholesalepackageoption()
    {
        return $this->hasOne(WholesalePackageOption::class);
    }

}
