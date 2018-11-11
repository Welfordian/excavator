<?php

namespace App\Http\Middleware;

use App;
use Closure;

class BeHorribleToJosh
{
    public function handle($request, Closure $next)
    {
        if (\Auth::check()) {
    if (\Auth::user()->id === 1) {
        return abort(403);
    }
}

return $next($request);
    }
}