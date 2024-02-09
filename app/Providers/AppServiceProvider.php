<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use App\Models\TenantConfiguration;
use App\Models\Tenant;
use App\Services\TenantService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->runningInConsole()){
            return;
        }

        $get_http_host = request()->getHost();
        $tenant_from_url = strstr($get_http_host, '.', true);


        config(['database.connections.tenant.database' => 'tenant'.$tenant_from_url]);
        DB::purge('tenant');    
        DB::reconnect('tenant');
        // TenantService::switchToDomain($tenant_from_url);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // foreach(Tenant::all() as $tnt){
        //     $tenant_db = $tnt->tenancy_db_name;

        //     config(['database.connections.tenant.database' => $tenant_db]);
        //     DB::purge('tenant');    
        //     DB::reconnect('tenant');

        //     if($tenant_config = TenantConfiguration::on('tenant')->find(1)){
        //         config(['zoho-oauth.client_id' => $tenant_config->client_id]);
        //         config(['zoho-oauth.client_secret' => $tenant_config->client_secret]);
        //         config(['zoho-oauth.code' => $tenant_config->code]);
        //     }
        // }

        // if($tenant_config = TenantConfiguration::on('tenant')->find(1)){
        //     config(['zoho-oauth.client_id' => $tenant_config->client_id]);
        //     config(['zoho-oauth.client_secret' => $tenant_config->client_secret]);
        //     config(['zoho-oauth.code' => $tenant_config->code]);
        // }
       
    }
}
