<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

class SaveRadiusData
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
        if ($request->isMethod('post') && $request->input('serverMemo.data.data.layout_id') == '1399000000082001') {
            $data = [
                'rad_user' => $request->input('serverMemo.data.data.rad_user'),
                'rad_pass' => $request->input('serverMemo.data.data.rad_pass'),
                'rad_ip' => $request->input('serverMemo.data.data.rad_ip'),
                'radiusIP_id' => $request->input('serverMemo.data.radiusIP_id'),
                'buroflow_reference' => $request->input('serverMemo.data.data.customfield_shorttext41')
            ];

            // dd($data);
            app()->make('App\Services\RadiusService')->saveData($data);
        }
        
        return $next($request);
    }
}
