<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckActiveMembership
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->hasActiveMembership()) {
            return redirect()->route('membership.expired');
        }

        return $next($request);
    }
}
