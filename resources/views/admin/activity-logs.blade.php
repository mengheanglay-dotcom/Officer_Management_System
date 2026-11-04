@extends('layouts.app')

@section('title', 'Activity log')

@section('content')
    @php
        $actionStyles = [
            'New Officer registration' => ['icon' => 'user-plus', 'classes' => 'bg-sky-50 text-sky-700 ring-sky-200'],
            'Officer approval' => ['icon' => 'user-check', 'classes' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
            'Officer rejection' => ['icon' => 'user-x', 'classes' => 'bg-rose-50 text-rose-700 ring-rose-200'],
            'Account activation' => ['icon' => 'play-circle', 'classes' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
            'Account deactivation' => ['icon' => 'pause-circle', 'classes' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        ];
    @endphp

    <div class="mx-auto max-w-6xl space-y-6">
        <header class="flex flex-col gap-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#102a4c] text-white">
                    <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-indigo-600">Administration</p>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Activity log</h1>
                    <p class="mt-1 text-sm text-slate-500">Review security and administrative activity.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 border-t border-slate-100 pt-4 sm:border-0 sm:pt-0">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                    <i data-lucide="history" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="text-2xl font-semibold leading-none text-slate-900">{{ $logs->total() }}</p>
                    <p class="mt-1 text-xs text-slate-500">record{{ $logs->total() === 1 ? '' : 's' }}</p>
                </div>
            </div>
        </header>

        <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-800">
            <div class="flex items-start gap-2.5">
                <i data-lucide="shield-check" class="mt-0.5 h-4 w-4 shrink-0"></i>
                <p>Sensitive data is never recorded in the activity log.</p>
            </div>
        </div>

        @if ($logs->count())
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-label="Activity records">
                <div class="hidden border-b border-slate-200 bg-slate-50 px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 md:grid md:grid-cols-[1.4fr_1.2fr_2fr_0.8fr] md:gap-6">
                    <span>Date and time</span>
                    <span>User</span>
                    <span>Action</span>
                    <span>Record</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($logs as $log)
                        @php
                            $action = $actionStyles[$log->action] ?? [
                                'icon' => 'activity',
                                'classes' => 'bg-slate-100 text-slate-700 ring-slate-200',
                            ];
                        @endphp
                        <article class="grid gap-4 px-5 py-5 sm:px-6 md:grid-cols-[1.4fr_1.2fr_2fr_0.8fr] md:items-center md:gap-6">
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $log->created_at->format('M d, Y') }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $log->created_at->format('h:i A') }}</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-600">
                                    {{ strtoupper(substr($log->user?->name ?: 'S', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-slate-800">{{ $log->user?->name ?: 'System' }}</p>
                                    @if ($log->user?->role)
                                        <p class="mt-0.5 text-xs capitalize text-slate-500">{{ $log->user->role }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg ring-1 ring-inset {{ $action['classes'] }}">
                                    <i data-lucide="{{ $action['icon'] }}" class="h-4 w-4"></i>
                                </span>
                                <span class="text-sm font-medium text-slate-800">{{ $log->action }}</span>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400 md:hidden">Record ID</p>
                                <p class="mt-1 font-mono text-sm text-slate-600 md:mt-0">{{ $log->subject_id ?: '—' }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @else
            <section class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <i data-lucide="clipboard-x" class="h-6 w-6"></i>
                </div>
                <h2 class="mt-4 font-medium text-slate-900">No activity recorded</h2>
                <p class="mt-1 text-sm text-slate-500">Administrative activity will appear here when it occurs.</p>
            </section>
        @endif

        @if ($logs->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
