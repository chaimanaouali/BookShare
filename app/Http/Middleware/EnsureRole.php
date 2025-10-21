<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $userRole = strtolower((string) $user->role);
        $requiredRole = strtolower((string) $role);

        // Allow exact role match
        if ($userRole === $requiredRole) {
            return $next($request);
        }

        // Allow admins to access contributor pages
        if ($requiredRole === 'contributor' && $userRole === 'admin') {
            return $next($request);
        }

        // Gracefully redirect mismatched roles to their home areas
        if ($userRole === 'admin') {
            return redirect('/admin');
        }
        if ($userRole === 'contributor') {
            return redirect('/contributor');
        }
        return redirect('/explore');
    }
}
