@extends('layouts.app')
@section('title', 'Reset password')
@section('content')
    <div class="mx-auto mt-10 max-w-md rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4"><h1 class="text-xl font-semibold">Choose a new password</h1></div>
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4 p-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2">
            <input type="password" name="password" placeholder="New password" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2">
            <input type="password" name="password_confirmation" placeholder="Confirm new password" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2">
            @foreach ($errors->all() as $error) <p class="text-sm text-slate-600">{{ $error }}</p> @endforeach
            <button class="w-full rounded-lg bg-indigo-600 py-2.5 font-medium text-white">Reset password</button>
        </form>
    </div>
@endsection
