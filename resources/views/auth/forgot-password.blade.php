@extends('layouts.app')
@section('title', 'Forgot password')
@section('content')
    <div class="mx-auto mt-10 max-w-md rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h1 class="text-xl font-semibold">Reset your password</h1>
            <p class="mt-1 text-sm text-slate-500">Enter your email and we will send a reset link if the account exists.</p>
        </div>
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4 p-6">
            @csrf
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full rounded-lg border border-slate-300 px-3 py-2">
            @error('email') <p class="text-sm text-slate-600">{{ $message }}</p> @enderror
            <button class="w-full rounded-lg bg-indigo-600 py-2.5 font-medium text-white">Send reset link</button>
            <a href="{{ route('login') }}" class="block text-center text-sm text-indigo-600">Back to login</a>
        </form>
    </div>
@endsection
