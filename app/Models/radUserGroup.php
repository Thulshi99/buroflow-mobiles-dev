<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class radUserGroup extends Model
{
    use HasFactory;

    protected $connection = 'radiusTest';
    protected $table = 'radusergroup';

    const CREATED_AT = null;
    const UPDATED_AT = 'updated';

}
