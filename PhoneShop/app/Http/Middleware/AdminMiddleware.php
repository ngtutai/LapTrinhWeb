<?php

// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

class AdminMiddleware
{
    public function handle($request, \Closure $next)
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Only admin.');
        }

        return $next($request);
    }
}
