<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Stancl\Tenancy\Database\Models\Domain;

class TeamSubdomainMiddleware
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
        $domain = $request->getHost();
        $teamDomain = $this->teamDomain();

        if ($domain != $teamDomain) {
            // dd($domain);
            return Redirect::route('dashboard')->domain($teamDomain);
        }

        return $next($request);
    }

    private function teamDomain() 
    {
        $team = auth()->user()->currentTeam ?? false;
        
        if (!$team) {
            return false;
        }

        return Domain::where('tenant_id', $team->tenant_id)->first()?->domain;
    }
}
