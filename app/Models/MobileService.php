<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileService extends Model
{
    use HasFactory;

    protected $table = 'mobile_services';
    protected $fillable = [
        'datapool_id',
        'status_in_datapool',
        'data_used',
        'data_limit',
        'data_quota',
        'order_id',
        'notes'
    ];

    public function simCard()
    {
        return $this->hasOne(SimCard::class, 'mobile_number', 'mobile_number');
    }

    public function customers()
    {
        return $this->hasOne(Customer::class, 'mobile_service_id', 'mobile_service_id');
    }
    public function orders()
    {
        return $this->hasOne(Order::class, 'order_id', 'order_id');
    }
    public function reseller()
    {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'reseller_id');
    }

    public function reseller_mobile_change_logs	()
    {
        return $this->hasOne(ResellerMobileChangeLogs::class, 'mobile_service_id', 'mobile_service_id');
    }

    public function wholesale_packages()
    {
        return $this->hasOne(WholesalePackage::class, 'id', 'wholesale_package_id');
    }
    public function retail_packages()
    {
        return $this->hasOne(RetailPackage::class, 'id', 'retail_package_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('service_status', 'COMPLETED');
    }

    public function scopeUpdatedWithinLast24Hours($query)
    {
        return $query->where('updated_at', '>=', now()->subHours(24));
    }

}
