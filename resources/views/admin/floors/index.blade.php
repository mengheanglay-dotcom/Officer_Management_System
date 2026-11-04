@extends('layouts.app')
@section('title', 'Floors')
@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <header class="flex flex-col gap-4 rounded-2xl bg-[#102a4c] p-6 text-white shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10"><i data-lucide="layers-3" class="h-6 w-6 text-sky-200"></i></div>
                <div><p class="text-sm font-medium text-sky-200">Administration</p><h1 class="mt-1 text-2xl font-semibold">Floors</h1><p class="mt-1 text-sm text-slate-300">Manage the floors available for department assignments.</p></div>
            </div>
            <a href="{{ route('admin.buildings') }}" class="inline-flex items-center gap-2 rounded-lg border border-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/10"><i data-lucide="building-2" class="h-4 w-4"></i> Manage buildings</a>
        </header>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-4"><h2 class="font-semibold text-slate-900">Add floor</h2><p class="mt-1 text-sm text-slate-500">Create a floor option for department forms.</p></div>
            <form method="POST" action="{{ route('admin.floors.store') }}" class="flex flex-col gap-3 sm:flex-row">
                @csrf
                <input name="name" value="{{ old('name') }}" placeholder="e.g. Floor 1" required class="flex-1 rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"><i data-lucide="plus" class="h-4 w-4"></i> Add floor</button>
            </form>
            @error('name') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
        </section>
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 sm:px-6"><div><h2 class="font-semibold text-slate-900">Floor directory</h2><p class="mt-1 text-sm text-slate-500">{{ $floors->count() }} floor options</p></div><i data-lucide="list" class="h-5 w-5 text-slate-400"></i></div>
            <div class="divide-y divide-slate-100">
                @forelse ($floors as $floor)
                    <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                        <div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-50 text-sky-600"><i data-lucide="layers-3" class="h-5 w-5"></i></div><div><p class="font-medium text-slate-900">{{ $floor->name }}</p><p class="text-xs text-slate-500">{{ $floor->departments_count }} {{ Str::plural('department', $floor->departments_count) }}</p></div></div>
                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('admin.floors.update', $floor) }}" class="flex gap-2">@csrf @method('PUT')<input name="name" value="{{ $floor->name }}" required class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"><button class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Save</button></form>
                            <form method="POST" action="{{ route('admin.floors.destroy', $floor) }}" data-confirm-delete data-delete-message="{{ 'Delete ' . $floor->name . '? This action cannot be undone.' }}">@csrf @method('DELETE')<button class="rounded-lg p-2 text-rose-600 hover:bg-rose-50" title="Delete"><i data-lucide="trash-2" class="h-4 w-4"></i></button></form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-slate-500">No floors configured yet.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
