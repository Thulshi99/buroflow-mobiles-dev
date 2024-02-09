<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMSRealms extends Model
{
    use HasFactory;

    protected $connection = "imsTest";
    protected $table = "realms";
    protected $primaryKey = 'realm_id';
    protected $fillable = ['realm_name'];
}
