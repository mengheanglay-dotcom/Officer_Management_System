@extends('layouts.app')
@section('title', 'Activity log')
@section('content')
    <div class="mb-6"><h1 class="text-2xl font-semibold">Activity log</h1><p class="text-sm text-slate-500">Security and administrative activity. Sensitive data is never recorded.</p></div>
    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50"><tr><th class="px-4 py-3">Date and time</th><th class="px-4 py-3">User</th><th class="px-4 py-3">Action</th><th class="px-4 py-3">Record ID</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $log)
                    <tr><td class="px-4 py-3 text-slate-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td><td class="px-4 py-3">{{ $log->user?->name ?: 'System' }}</td><td class="px-4 py-3">{{ $log->action }}</td><td class="px-4 py-3 text-slate-500">{{ $log->subject_id ?: '—' }}</td></tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">No activity has been recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
@endsection
