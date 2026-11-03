<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApprovedAccountMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isApproved()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = $user?->isRejected()
                ? 'Your registration was rejected. Please contact an administrator.'
                : 'Your account is awaiting administrator approval.';

            return redirect()->route('login')->with('error', $message);
        }

        return $next($request);
    }
}
