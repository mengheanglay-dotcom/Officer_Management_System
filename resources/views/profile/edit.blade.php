@extends('layouts.app')
@section('title', 'My profile')
@section('content')
    @php
        $input = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200';
        $officer = auth()->user()->officer;
    @endphp

    <div class="mx-auto max-w-5xl">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-indigo-600">Account settings</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">My profile</h1>
                <p class="mt-1 text-sm text-slate-500">Update your personal details and account security.</p>
            </div>
            <div class="hidden h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 sm:flex">
                <i data-lucide="user-round-cog" class="h-5 w-5"></i>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @csrf
            @method('PUT')
            <div class="grid lg:grid-cols-[1.15fr_0.85fr]">
                <section class="space-y-5 p-5 sm:p-7">
                    <div>
                        <h2 class="font-semibold text-slate-900">Personal information</h2>
                        <p class="mt-1 text-sm text-slate-500">Keep your contact details current.</p>
                    </div>

                    <div class="flex items-center gap-4 rounded-xl bg-slate-50 p-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#102a4c] text-lg font-semibold text-white">
                            @if ($officer?->avatar_path || auth()->user()->avatar_path)
                                <img src="{{ asset('storage/' . ($officer?->avatar_path ?: auth()->user()->avatar_path)) }}"
                                    alt="{{ auth()->user()->name }}" class="h-full w-full rounded-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ ucfirst(auth()->user()->role) }} account</p>
                            <label class="mt-2 inline-flex cursor-pointer items-center gap-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-700">
                                <i data-lucide="camera" class="h-3.5 w-3.5"></i> Change photo
                                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only">
                            </label>
                        </div>
                    </div>
                    @error('avatar') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Full name</label>
                            <input name="name" value="{{ old('name', auth()->user()->name) }}" required class="{{ $input }}">
                            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Email address</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="{{ $input }}">
                            @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        @if ($officer)
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Gender</label>
                                <select name="gender" required class="{{ $input }}">
                                    <option value="">Select gender</option>
                                    @foreach (['Male', 'Female'] as $gender)
                                        <option value="{{ $gender }}" @selected(old('gender', $officer->gender) === $gender)>{{ $gender }}</option>
                                    @endforeach
                                </select>
                                @error('gender') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Date of birth</label>
                                <input type="date" name="dob"
                                    value="{{ old('dob', optional($officer->dob)->format('Y-m-d')) }}"
                                    max="{{ now()->subDay()->format('Y-m-d') }}" class="{{ $input }}">
                                @error('dob') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Phone number</label>
                                <input type="tel" name="phone" value="{{ old('phone', $officer->phone) }}" maxlength="30" class="{{ $input }}">
                                @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    </div>
                </section>

                <section id="change-password" class="border-t border-slate-200 bg-slate-50/70 p-5 sm:p-7 lg:border-l lg:border-t-0">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                            <i data-lucide="key-round" class="h-5 w-5"></i>
                        </div>
                        <h2 class="mt-4 font-semibold text-slate-900">Change password</h2>
                        <p class="mt-1 text-sm leading-5 text-slate-500">Leave blank to keep your current password.</p>
                    </div>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Current password</label>
                            <input type="password" name="current_password" autocomplete="current-password"
                                placeholder="Enter your current password" class="{{ $input }}">
                            @error('current_password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">New password</label>
                            <input type="password" name="password" autocomplete="new-password"
                                placeholder="Enter a new password" class="{{ $input }}">
                            @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Confirm new password</label>
                            <input type="password" name="password_confirmation" autocomplete="new-password"
                                placeholder="Confirm your new password" class="{{ $input }}">
                        </div>
                        <p class="text-xs leading-5 text-slate-500">To change your password, enter your current password first.</p>
                    </div>
                </section>
            </div>

            <div class="flex justify-end border-t border-slate-200 bg-white px-5 py-4 sm:px-7">
                <button class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <i data-lucide="save" class="h-4 w-4"></i> Save changes
                </button>
            </div>
        </form>
    </div>
@endsection