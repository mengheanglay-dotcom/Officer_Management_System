@extends('layouts.app')
@section('title', 'Departments')
@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold">Departments</h1>
        <p class="text-sm text-slate-500">Manage departments and their managers</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('departments.create') }}" class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            <i data-lucide="plus" class="h-4 w-4"></i> Add department
        </a>
    @endif
</div>

<form method="GET" class="mb-4 flex gap-2 rounded-xl border border-slate-200 bg-white p-4">
    <div class="relative flex-1">
        <i data-lucide="search" class="absolute left-3 top-2.5 h-4 w-4 text-slate-400"></i>
        <input name="search" value="{{ request('search') }}" placeholder="Search by department name"
               class="w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
    </div>
    <button class="flex items-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900"><i data-lucide="search" class="h-4 w-4"></i> Search</button>
    <a href="{{ route('departments.index') }}" class="flex items-center rounded-lg border border-slate-300 px-3 py-2 text-slate-600 hover:bg-slate-100" title="Reset"><i data-lucide="rotate-ccw" class="h-4 w-4"></i></a>
</form>

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
    <table class="w-full text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
            <tr>
                <th class="px-4 py-3 font-medium">ID</th>
                <th class="px-4 py-3 font-medium">Department</th>
                <th class="px-4 py-3 font-medium">Location</th>
                <th class="px-4 py-3 font-medium">Manager</th>
                <th class="px-4 py-3 font-medium">Officers</th>
                @if (auth()->user()->isAdmin())
                    <th class="px-4 py-3 text-right font-medium">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        @forelse ($departments as $d)
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 text-slate-500">{{ $d->dept_id }}</td>
                <td class="px-4 py-3 font-medium">{{ $d->dept_name }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $d->location ?: 'N/A' }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $d->manager?->officer_name ?? 'Not assigned' }}</td>
                <td class="px-4 py-3"><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-600">{{ $d->officers_count }}</span></td>
                @if (auth()->user()->isAdmin())
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-1">
                            <a href="{{ route('departments.edit', $d) }}" class="rounded-lg p-2 text-indigo-600 hover:bg-indigo-50" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                            <form method="POST" action="{{ route('departments.destroy', $d) }}" onsubmit="return confirm('Delete {{ $d->dept_name }}?')">
                                @csrf @method('DELETE')
                                <button class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800" title="Delete"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                            </form>
                        </div>
                    </td>
                @endif
            </tr>
        @empty
            <tr><td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="px-4 py-10 text-center text-slate-500">No departments found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $departments->links() }}</div>
@endsection
