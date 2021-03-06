<?php

namespace App\Http\Middleware;

use App\Cts;
use Closure;
use Auth;

class Admin
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
        //check if user is logged in and admin
        if(Auth::check())
        {
            $user = Auth::user();

            if($user->isAdmin())
            {
                return $next($request);
            }
        }

        if (! $request->expectsJson())
        {
            return route('login');
        }
        else
        {
            return response('Unauthorized',Cts::HTTP_STATUS_UNAUTHORIZED);
        }

    }
}
