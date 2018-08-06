<?php

namespace App\Http\Middleware;

use App\Ad;
use App\Event;
use Auth;
use Closure;

class RedirectIfAuctionClosed
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
        $user = Auth::user();
        if (!$user || !$user->is_admin()) {

            $auction = null;
            if ($request->id && $request->slug) {
                $auction = Ad::findOrFail($request->id);
            }
            // if auction is closed
            if (isset($auction) && $auction->events()->first()->status == '3') {
                return redirect('/');
            }

            // if event is closed
            if ($request->event) {
                if ($request->event->status == '3') {                    
                    return redirect('/');
                }
            }
        }
        return $next($request);
    }
}
