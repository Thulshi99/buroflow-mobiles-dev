<?php

namespace App\Services;

use Exception;
use App\Models\Tenant;
use Illuminate\Validation\ValidationException;
use Symfony\Component\CssSelector\Exception\ParseException;

class TenantService

{

    private static $tenant;
    private static$domain;
    private static $database;

    public static function switchToTenant(Tenant $tenant)
    {
        if(!$tenant instanceof Tenant){
            // throw error or tenant class 
            throw ValidationException::withMessages(['field_name' => 'This value is incorrect']);
        }
        \DB::purge('landlord');
        \DB::purge('tenant');
        \Config::set('database.connections.tenant.database' , $tenant->tenancy_db_name);

        Self::$tenant = $tenant;
        Self::$domain = $tenant->domain;
        Self::$database = $tenant->tenancy_db_name;
        $databaseName = $tenant->tenancy_db_name;
        \DB::connection('tenant')->reconnect();
        \DB::setDefaultConnection('tenant');
    }

    public static function switchToDefault()
    {
        \DB::purge('landlord');
        \DB::purge('tenant');
        \DB::connection('landlord')->reconnect();
        \DB::setDefaultConnection('landlord');
    }


    public static function getTenant(){
        return Self::$tenant;
    }

}