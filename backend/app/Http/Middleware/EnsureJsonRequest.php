<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureJsonRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Content-Type') !== 'application/json') {
            return response()->json([
                'status' => false,
                'message' => 'Only application/json requests are accepted.',
            ], 415);
        }

        return $next($request);
    }
}
