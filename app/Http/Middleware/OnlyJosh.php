<?php

namespace App\Http\Middleware;

use App;
use Closure;

class OnlyJosh
{
    public function handle($request, Closure $next)
    {
        if (\Auth::check()) {
    if (\Auth::user()->id === 1) {
        return $next($request);
    }
}

return abort(403);
    }
}