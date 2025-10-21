<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = auth()->user();
        $user->loadCount(['bibliotheques', 'livres', 'avis', 'emprunts']);
        
        // Route based on user role
        if ($user->role === 'admin' || $user->role === 'contributor') {
            // Admin and contributors use the dashboard profile page
            return view('profile.show', compact('user'));
        } else {
            // Regular users use the front-end profile page
            return view('profile.user-show', compact('user'));
        }
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = auth()->user();
        
        // Route based on user role
        if ($user->role === 'admin' || $user->role === 'contributor') {
            // Admin and contributors use the dashboard edit page
            return view('profile.edit', compact('user'));
        } else {
            // Regular users use the front-end edit page
            return view('profile.user-edit', compact('user'));
        }
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
