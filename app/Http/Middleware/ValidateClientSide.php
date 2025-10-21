<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateClientSide
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Add validation headers for client-side validation
        $response = $next($request);
        
        if ($request->ajax() || $request->wantsJson()) {
            $response->headers->set('X-Validation-Required', 'true');
        }
        
        return $response;
    }
}