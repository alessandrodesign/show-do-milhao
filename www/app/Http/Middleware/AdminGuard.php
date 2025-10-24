<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminGuard
{
    public function handle(Request $r, Closure $next)
    {
        $u = $r->user();
        if (!$u || !$u->is_admin) {
            return response()->json(['message' => 'Acesso restrito a administradores.'], 403);
        }

        return $next($r);
    }
}
