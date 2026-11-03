<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Officer;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function showForgotPassword() { return view('auth.forgot-password'); }
    public function showResetPassword(string $token) { return view('auth.reset-password', compact('token')); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (! $request->user()->isApproved()) {
                $status = $request->user()->approval_status;
                Auth::logout();

                return back()->with('error', $status === 'rejected'
                    ? 'Your registration was rejected. Please contact an administrator.'
                    : 'Your account is awaiting administrator approval.')->onlyInput('email');
            }

            $request->session()->regenerate();
            ActivityLogger::record($request->user()->isAdmin() ? 'Admin login' : 'Officer login');

            return redirect()->intended(route('officers.index'));
        }

        return back()->withErrors(['email' => 'Email or password is incorrect.'])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            $user->forceFill([
                'role' => 'officer',
                'approval_status' => 'pending',
            ])->save();

            $officer = Officer::firstOrCreate(['email' => $user->email], [
                'officer_name' => $user->name,
            ]);

            ActivityLogger::record('New Officer registration', $officer);
        });

        return redirect()->route('login')->with('success', 'Registration successful. Please wait for an administrator to approve your account.');
    }

    public function sendResetLink(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $status = Password::sendResetLink($data);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'If an account exists for that email, a password reset link has been sent.')
            : back()->withErrors(['email' => 'Unable to send a password reset link right now.']);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $data,
            function (User $user, string $password): void {
                $user->forceFill(['password' => $password])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successfully. You can now log in.')
            : back()->withErrors(['email' => 'This password reset link is invalid or expired.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
