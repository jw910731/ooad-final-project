<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->system_admin) {
            return $next($request);
        }
        return redirect()->back();
    }
}
