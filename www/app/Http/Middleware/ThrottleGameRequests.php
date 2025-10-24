<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Limita chamadas repetidas aos endpoints do jogo
 * evitando flood ou bots (por IP e jogador).
 */
class ThrottleGameRequests
{
    private RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $r, Closure $next, int $max = 30, int $minutes = 1): Response
    {
        $key = sprintf('game:%s:%s', $r->ip(), $r->input('player_name') ?? 'anon');
        if ($this->limiter->tooManyAttempts($key, $max)) {
            return response()->json(['message' => 'Muitas requisições. Aguarde alguns segundos.'], 429);
        }
        $this->limiter->hit($key, now()->addMinutes($minutes));

        return $next($r);
    }
}
