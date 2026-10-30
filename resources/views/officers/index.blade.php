@extends('layouts.app')
@section('title', 'Officers')
@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold">Officers</h1>
        <p class="text-sm text-slate-500">{{ $total }} officers across {{ $deptCount }} departments</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('officers.create') }}" class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            <i data-lucide="plus" class="h-4 w-4"></i> Add officer
        </a>
    @endif
</div>

{{-- Search + filters --}}
<form method="GET" class="mb-4 grid gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-[1fr_auto_auto_auto_auto]">
    <div class="relative">
        <i data-lucide="search" class="absolute left-3 top-2.5 h-4 w-4 text-slate-400"></i>
        <input name="search" value="{{ request('search') }}" placeholder="Search by officer name"
               class="w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
    </div>
    <select name="dept_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
        <option value="">All departments</option>
        @foreach ($departments as $d)
            <option value="{{ $d->dept_id }}" @selected(request('dept_id') == $d->dept_id)>{{ $d->dept_name }}</option>
        @endforeach
    </select>
    <select name="gender" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
        <option value="">All genders</option>
        <option @selected(request('gender') === 'Male')>Male</option>
        <option @selected(request('gender') === 'Female')>Female</option>
    </select>
    <select name="sort" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
        <option value="newest">Newest first</option>
        <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
    </select>
    <div class="flex gap-2">
        <button class="flex items-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900"><i data-lucide="filter" class="h-4 w-4"></i> Filter</button>
        <a href="{{ route('officers.index') }}" class="flex items-center rounded-lg border border-slate-300 px-3 py-2 text-slate-600 hover:bg-slate-100" title="Reset"><i data-lucide="rotate-ccw" class="h-4 w-4"></i></a>
    </div>
</form>

{{-- Table --}}
<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
    <table class="w-full text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
            <tr>
                <th class="px-4 py-3 font-medium">ID</th>
                <th class="px-4 py-3 font-medium">Officer</th>
                <th class="px-4 py-3 font-medium">Contact</th>
                <th class="px-4 py-3 font-medium">Hired</th>
                <th class="px-4 py-3 font-medium">Department</th>
                @if (auth()->user()->isAdmin())
                    <th class="px-4 py-3 text-right font-medium">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        @forelse ($officers as $o)
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 text-slate-500">{{ $o->officer_id }}</td>
                <td class="px-4 py-3">
                    <div class="font-medium">{{ $o->officer_name }}</div>
                    <div class="text-xs text-slate-500">{{ $o->position ?: 'Position not assigned' }} · {{ $o->gender ?: 'Gender not assigned' }}</div>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1.5"><i data-lucide="mail" class="h-3.5 w-3.5 text-slate-400"></i>{{ $o->email }}</div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-500"><i data-lucide="phone" class="h-3.5 w-3.5 text-slate-400"></i>{{ $o->phone ?: 'N/A' }}</div>
                </td>
                <td class="px-4 py-3">
                    <div>{{ $o->hire_date?->format('M d, Y') ?: 'Hire date not assigned' }}</div>
                    <div class="text-xs text-slate-500">{{ $o->service_years }}</div>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-600">
                        <i data-lucide="building-2" class="h-3 w-3"></i>{{ $o->department?->dept_name ?: 'Department not assigned' }}
                    </span>
                </td>
                @if (auth()->user()->isAdmin())
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-1">
                            <a href="{{ route('officers.edit', $o) }}" class="rounded-lg p-2 text-indigo-600 hover:bg-indigo-50" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                            <form method="POST" action="{{ route('officers.destroy', $o) }}" onsubmit="return confirm('Delete {{ $o->officer_name }}?')">
                                @csrf @method('DELETE')
                                <button class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800" title="Delete"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                            </form>
                        </div>
                    </td>
                @endif
            </tr>
        @empty
            <tr><td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="px-4 py-10 text-center text-slate-500">No officers found. Try a different search.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $officers->links() }}</div>
@endsection
