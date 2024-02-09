<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

class CentralTenantAccessMiddleware
{

    private User $user;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) 
    {
        $this->user = auth()->user();

        if ($this->isAdminTeamMember()) {
            return $next($request);
        }
        
        return redirect('dashboard')->domain($this->redirectDomain());

    }

    private function isAdminTeamMember()
    {

        $teams = $this->user->teams;
        // dd($teams);
        $adminTeams = $teams->map( function($team) {
            return $team->central_access;
        });

        return $adminTeams->contains(true);

    }

    /**
     * return a domain for redirection
     *
     * @return void
     */
    private function redirectDomain()
    {

        $teams = $this->user->teams();

        if ( $teams->count() > 0 ) {
            $tenant_id = $teams->first()->tenant_id;
            return Domain::whereTenantId($tenant_id)->first()->domain;
        }

    }
}
