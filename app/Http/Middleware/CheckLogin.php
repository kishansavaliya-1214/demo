<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() && Auth::user()->role == 'admin') {

            if ($request->routeIs('login')) {
                return redirect()->route('admin.dashboard');
            }

            return $next($request);
        }
        if (Auth::user() && Auth::user()->role == 'student') {
            if ($request->routeIs('login')) {
                return redirect()->route('student.dashboard');
            }

            return $next($request);
        }

        if (! Auth::user()) {
            if ($request->routeIs('login')) {
                return $next($request);
            }
        }

        return $next($request);
    }
}
