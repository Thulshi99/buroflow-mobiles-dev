<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QntrlCard extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $dateFormat = 'Y-m-d H:i:s';
    // protected $table = 'qntrl_cards';

}
