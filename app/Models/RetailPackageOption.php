<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailPackageOption extends Model
{
    use HasFactory;

    public function retailpackage()
    {
        return $this->belongsTo(RetailPackage::class,'retail_package_id','retail_package_id');
    }
}
