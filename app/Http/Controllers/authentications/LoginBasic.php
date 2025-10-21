<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
    // Handle registration POST
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'user',
        ]);
        Auth::login($user);
        // Redirect based on role to avoid 403 on /contributor for normal users
        if ($user->role === 'admin') {
            return redirect('/admin');
        }
        if ($user->role === 'contributor') {
            return redirect('/contributor');
        }
        if ($user->role === 'user') {
            return redirect('/');
        }
        return redirect('/');
    }

    // Show the login form
    public function index()
    {
        return view('auth.register');
    }

    // Handle login POST
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin');
            }
            if ($user->role === 'contributor') {
                return redirect('/contributor');
            }
            if ($user->role === 'user') {
                return redirect('/');
            }
            return redirect('/');
        }
        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
