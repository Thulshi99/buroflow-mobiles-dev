<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPPoolIMS extends Model
{
    use HasFactory;

    protected $connection = 'imsTest';
    protected $database = 'ims';
    protected $table = 'ip_pool';
    
}
