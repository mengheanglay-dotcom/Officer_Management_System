@extends('layouts.app')

@section('title', 'My workspace')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="overflow-hidden rounded-2xl bg-[#102a4c] text-white shadow-sm">
            <div class="grid gap-8 px-6 py-8 sm:px-8 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-sky-100">
                        <i data-lucide="shield-check" class="h-3.5 w-3.5"></i>
                        Officer workspace
                    </div>
                    <h1 class="text-3xl font-semibold tracking-tight">Welcome, {{ auth()->user()->name }}</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-200">
                        View your assigned information and keep your contact details up to date.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-[#102a4c] hover:bg-sky-50">
                            <i data-lucide="user-round-pen" class="h-4 w-4"></i> Update my profile
                        </a>
                        <a href="{{ route('profile.edit') }}#change-password"
                            class="inline-flex items-center gap-2 rounded-lg border border-white/25 px-4 py-2.5 text-sm font-medium text-white hover:bg-white/10">
                            <i data-lucide="key-round" class="h-4 w-4"></i> Reset password
                        </a>
                    </div>
                </div>
                <div class="hidden h-36 w-36 items-center justify-center rounded-full border border-white/15 bg-white/10 lg:flex">
                    <i data-lucide="user-round" class="h-16 w-16 text-sky-200"></i>
                </div>
            </div>
        </section>

        @if ($officer)
            <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-indigo-600">My information</p>
                            <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $officer->officer_name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">Officer record #{{ $officer->officer_id }}</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-sky-50 font-semibold text-[#102a4c]">
                            @if ($officer->avatar_path)
                                <img src="{{ asset('storage/' . $officer->avatar_path) }}" alt="{{ $officer->officer_name }}"
                                    class="h-full w-full rounded-full object-cover">
                            @else
                                {{ strtoupper(substr($officer->officer_name, 0, 1)) }}
                            @endif
                        </div>
                    </div>
                    <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Email address</dt>
                            <dd class="mt-1 break-all text-sm font-medium text-slate-800">{{ $officer->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Phone number</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-800">{{ $officer->phone ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Gender</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-800">{{ $officer->gender ?: 'Not assigned' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Date of birth</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-800">{{ $officer->dob?->format('M d, Y') ?: 'Not provided' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                            <i data-lucide="briefcase-business" class="h-5 w-5"></i>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-indigo-600">Current assignment</p>
                            <h2 class="mt-1 text-xl font-semibold text-slate-900">Your placement</h2>
                        </div>
                    </div>
                    <dl class="mt-6 space-y-5">
                        <div class="flex items-start gap-3">
                            <i data-lucide="building-2" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Department</dt>
                                <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $officer->department?->dept_name ?: 'Not assigned' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="badge-check" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Position</dt>
                                <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $officer->position ?: 'Not assigned' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="calendar-days" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Hire date</dt>
                                <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $officer->hire_date?->format('M d, Y') ?: 'Not assigned' }}</dd>
                            </div>
                        </div>
                    </dl>
                </section>
            </div>
        @else
            <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-800">
                <div class="flex items-start gap-3">
                    <i data-lucide="info" class="mt-0.5 h-5 w-5 shrink-0"></i>
                    <div>
                        <h2 class="font-semibold">Officer record not assigned yet</h2>
                        <p class="mt-1 text-sm">Your administrator has not linked an officer record to this account.</p>
                    </div>
                </div>
            </section>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900">Account security</h2>
                    <p class="mt-1 text-sm text-slate-500">Manage your profile details and password securely.</p>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                    <i data-lucide="settings-2" class="h-4 w-4"></i> Manage account
                </a>
            </div>
        </section>
    </div>
@endsection
