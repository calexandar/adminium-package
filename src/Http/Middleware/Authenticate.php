<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class Authenticate
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guardName = adminium_guard();

        if (Auth::guard($guardName)->check()) {
            Auth::shouldUse($guardName);

            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('admin.sign-in.view'));
    }
}
