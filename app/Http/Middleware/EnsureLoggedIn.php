<?php

namespace App\Http\Middleware;

use App;
use Closure;

class EnsureLoggedIn
{
    public function handle($request, Closure $next)
    {
        if (! \Auth::check()) {
    	return redirect()->to('/login');
}

return $next($request);
    }
}