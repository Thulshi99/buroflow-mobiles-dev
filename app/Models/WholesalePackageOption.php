<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalePackageOption extends Model
{
    use HasFactory;

    public function wholesalepackage()
    {
        return $this->belongsTo(WholesalePackage::class,'wholesale_pakage_id','wholesale_pakage_id');
    }
}
