<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class radReply extends Model
{
    use HasFactory;

    protected $connection = 'radiusTest';
    protected $table = 'radreply';

    const CREATED_AT = null;
    const UPDATED_AT = 'updated';
}
