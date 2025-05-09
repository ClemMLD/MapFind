<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->is_active) {
            return $next($request);
        }

        if ($request->wantsJson()) {
            return response()->json(['error' => 'Your account is not active.'], 403);
        }

        auth('web')->logout();
        return redirect()->route('login');
    }
}
