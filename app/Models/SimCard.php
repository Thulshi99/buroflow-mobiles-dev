<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class SimCard extends Model
{
    // use HasFactory, BelongsToTenant;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['sim_card_code','batch_number','mobile_number','status','reseller_id','company_id','shipvia_id'];

    const STATUS_ALLOCATED = 1;
    const STATUS_TERMINATED = 2;
    const STATUS_RESERVED = 3;
    const STATUS_LOCKED = 4;
    const STATUS_LOST_STOLEN = 5;
    const STATUS_PENDING = 6;
    const STATUS_AVAILABLE = 7;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'simcards';

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function reseller()
    {
        return $this->belongsTo(Reseller::class,'reseller_id','reseller_id');
    }

}
