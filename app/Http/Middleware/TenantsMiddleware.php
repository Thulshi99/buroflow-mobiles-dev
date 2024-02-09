<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Services\TenantService;

class TenantsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $get_http_host = request()->getHost();
        $tnt = strstr($get_http_host, '.', true);

        config(['database.connections.tenant.database' => 'tenant'.$tnt]);
        \DB::purge('tenant');    
        \DB::reconnect('tenant');

        $tenant = Tenant::where('id', $tnt)->first();
        
        TenantService::switchToTenant($tenant);
        // dd($request);
        return $next($request);
    }
}