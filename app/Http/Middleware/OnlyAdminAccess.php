<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OnlyAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! Auth::check()){
            return redirect()->guest(route('login'))->with('error', trans('app.unauthorized_access'));
        }        

        $user = Auth::user();

        if ( ! $user->is_admin())            
            // ->with('error', trans('app.access_restricted'))
            return redirect(route('active_bidding_auctions'));

        return $next($request);
    }
}
