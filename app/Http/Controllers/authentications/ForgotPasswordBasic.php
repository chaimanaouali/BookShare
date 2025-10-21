<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordBasic extends Controller
{
    /**
     * Show the forgot password form
     */
    public function index()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to user's email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Generate token
        $token = Str::random(64);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Send email with reset link
        try {
            Mail::send('emails.password-reset', ['token' => $token, 'email' => $request->email], function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password - BookVerse');
            });

            return back()->with('success', 'We have sent you a password reset link to your email!');
        } catch (\Exception $e) {
            // If email fails, still show success for security (don't reveal if email exists)
            // But log the error
            \Log::error('Password reset email failed: ' . $e->getMessage());
            
            // For development, you can show the reset link directly
            $resetLink = url('/password/reset/' . $token . '?email=' . urlencode($request->email));
            return back()->with('success', 'Password reset link: ' . $resetLink);
        }
    }

    /**
     * Show the password reset form
     */
    public function showResetForm($token)
    {
        $email = request('email');
        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Reset the user's password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required'
        ]);

        // Check if token exists and is valid (not older than 60 minutes)
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Check if token matches
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Reset token has expired. Please request a new one.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('auth')->with('success', 'Your password has been reset successfully! You can now login.');
    }
}
