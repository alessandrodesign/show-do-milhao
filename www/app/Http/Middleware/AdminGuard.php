<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminGuard
{
    public function handle(Request $request, Closure $next)
    {
        // placeholder: implement minimal guard (cookie/token check)
        if (!$request->user() || !$request->user()->is_admin) {
            return redirect('/'); // or abort(403)
        }

        return $next($request);
    }
}
