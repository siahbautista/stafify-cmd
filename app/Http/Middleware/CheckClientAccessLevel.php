<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckClientAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has access_level 2 (Client)
        if (Auth::check() && Auth::user()->access_level == 2) {
            return $next($request);
        }

        // If not, redirect them.
        // You can change this to a specific /access-denied route
        return redirect('/');
    }
}