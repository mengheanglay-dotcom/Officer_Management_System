@extends('layouts.app')

@section('title', 'Overview')

@section('content')
    <div class="overflow-hidden rounded-2xl bg-[#102a4c] text-white shadow-sm">
        <div class="grid gap-8 px-6 py-8 sm:px-8 lg:grid-cols-[1.4fr_0.6fr] lg:items-center">
            <div>
                <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-sky-100">
                    <i data-lucide="shield-check" class="h-3.5 w-3.5"></i>
                    Officer Management System
                </div>
                <h1 class="max-w-2xl text-3xl font-semibold tracking-tight sm:text-4xl">Manage your officers with confidence.</h1>
                <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-200 sm:text-base">
                    A centralized workspace for organizing officer records, departments, and account details.
                    Keep information accurate, easy to find, and ready for your team.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('officers.index') }}" class="flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-[#102a4c] hover:bg-sky-50">
                        <i data-lucide="users" class="h-4 w-4"></i> Browse officers
                    </a>
                    <a href="{{ route('departments.index') }}" class="flex items-center gap-2 rounded-lg border border-white/25 px-4 py-2.5 text-sm font-medium text-white hover:bg-white/10">
                        <i data-lucide="building-2" class="h-4 w-4"></i> View departments
                    </a>
                </div>
            </div>
            <div class="hidden justify-center lg:flex">
                <div class="flex h-44 w-44 items-center justify-center rounded-full border border-white/15 bg-white/10">
                    <i data-lucide="network" class="h-20 w-20 text-sky-200"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">Registered officers</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $officerCount }}</p>
                </div>
                <span class="rounded-lg bg-indigo-50 p-2.5 text-indigo-600"><i data-lucide="users" class="h-5 w-5"></i></span>
            </div>
            <a href="{{ route('officers.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700">
                Manage officer records <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
            </a>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">Active departments</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $departmentCount }}</p>
                </div>
                <span class="rounded-lg bg-sky-50 p-2.5 text-sky-600"><i data-lucide="building-2" class="h-5 w-5"></i></span>
            </div>
            <a href="{{ route('departments.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-sky-600 hover:text-sky-700">
                Organize departments <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-slate-900">Recently added officers</h2>
                    <p class="mt-1 text-sm text-slate-500">The latest records in your directory.</p>
                </div>
                <a href="{{ route('officers.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentOfficers as $officer)
                    <div class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-slate-800">{{ $officer->officer_name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $officer->position }} · {{ $officer->department?->dept_name ?: 'No department' }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-500">ID #{{ $officer->officer_id }}</span>
                    </div>
                @empty
                    <p class="py-4 text-sm text-slate-500">No officer records have been added yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-slate-900">What you can do</h2>
            <div class="mt-4 space-y-4">
                <div class="flex gap-3">
                    <span class="rounded-lg bg-indigo-50 p-2 text-indigo-600"><i data-lucide="clipboard-list" class="h-4 w-4"></i></span>
                    <div><p class="text-sm font-medium text-slate-800">Maintain complete records</p><p class="mt-1 text-xs leading-5 text-slate-500">Store contact, position, hiring, and department details in one place.</p></div>
                </div>
                <div class="flex gap-3">
                    <span class="rounded-lg bg-sky-50 p-2 text-sky-600"><i data-lucide="search" class="h-4 w-4"></i></span>
                    <div><p class="text-sm font-medium text-slate-800">Find information quickly</p><p class="mt-1 text-xs leading-5 text-slate-500">Search and filter the directory by name, department, or gender.</p></div>
                </div>
                <div class="flex gap-3">
                    <span class="rounded-lg bg-emerald-50 p-2 text-emerald-600"><i data-lucide="lock-keyhole" class="h-4 w-4"></i></span>
                    <div><p class="text-sm font-medium text-slate-800">Work with role-based access</p><p class="mt-1 text-xs leading-5 text-slate-500">Administrators manage records while staff can safely view information.</p></div>
                </div>
            </div>
        </section>
    </div>
@endsection
