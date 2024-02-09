<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Prunable;

class ServiceQualification extends Model
{
    use HasFactory;

    protected $casts = [
        'raw' => 'array'
    ];

    protected $fillable = [
        'loc_id',
        'tenant_id',
        'raw',
    ];

    public function getdetailsAttribute()
    {
        return json_decode(json_encode($this->raw));
    }

    public function gettypeAttribute()
    {
        return data_get($this->raw, 'sqType');
    }

    public function getlocIdAttribute()
    {
        return data_get($this->raw, 'locationId');
    }

    public function getsourceTypeAttribute()
    {
        return data_get($this->raw, 'sourceType');
    }

    public function getaddressAttribute()
    {
        return data_get($this->raw, 'address');
    }

    public function getstatusAttribute()
    {
        return data_get($this->raw, 'status');
    }

    public function getregionAttribute()
    {
        return data_get($this->raw, 'region');
    }

    public function getaltTechAttribute()
    {
        return data_get($this->raw, 'alternativeTechnology');
    }

    public function getPOIAttribute()
    {
        return (object) [
            'id' => data_get($this->raw, 'poiId'),
            'name' => data_get($this->raw, 'poiName'),
        ];
    }

    public function getaddressDetailsAttribute()
    {
        return (object) data_get($this->raw, 'addressDetails');
    }

    public function gettechnologyTypeAttribute()
    {
        return data_get($this->raw, 'technologyType');
    }

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }

}
