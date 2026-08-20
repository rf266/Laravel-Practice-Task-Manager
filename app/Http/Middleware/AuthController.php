<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthController
{
    /**
     * Handle an incoming request.
     */

    public function handle(Request $request, Closure $next)
    {
        if (!session('user_id')) { //if there isnt a session now with the particular user id, redirect to login page
            return redirect()->route('/login');
        }
         //if logged in then return thiss
        return $next($request);
    }
}
