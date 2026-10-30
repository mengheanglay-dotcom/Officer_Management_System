@extends('layouts.app')
@section('title', 'My profile')
@section('content')
<div class="mx-auto max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">My profile</h1>
        <p class="text-sm text-slate-500">Update your account details and password.</p>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 rounded-xl border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            @error('name') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Email address</label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            @error('email') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>

        <div class="border-t border-slate-200 pt-4">
            <h2 class="font-medium">Change password</h2>
            <p class="mb-3 text-sm text-slate-500">Leave these fields blank to keep your current password.</p>
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium">New password</label>
                    <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    @error('password') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
            </div>
        </div>

        <button class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
            <i data-lucide="save" class="h-4 w-4"></i> Save changes
        </button>
    </form>
</div>
@endsection
