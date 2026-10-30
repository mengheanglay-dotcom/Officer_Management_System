@extends('layouts.app')
@section('title', 'Log in')
@section('content')
    <div class="mx-auto mt-10 max-w-md rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4 text-center">
            <i data-lucide="shield-check" class="mx-auto mb-1 h-8 w-8 text-indigo-600"></i>
            <h1 class="text-xl font-semibold">Log in</h1>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-4 p-6">
            @csrf
            @if (session('success'))
                <p class="rounded-lg bg-indigo-50 px-3 py-2 text-sm text-indigo-700">{{ session('success') }}</p>
            @endif
            <div>
                <label class="mb-1 block text-sm font-medium">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                @error('email')
                <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>
            <button
                class="flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 py-2.5 font-medium text-white hover:bg-indigo-700">
                <i data-lucide="log-in" class="h-4 w-4"></i> Log in
            </button>
            <p class="text-center text-sm text-slate-500">No account? <a href="{{ route('register') }}"
                    class="font-medium text-indigo-600 hover:underline">Register</a></p>
        </form>
    </div>
@endsection