@php
    $f = fn ($k) => old($k, $department->$k ?? null);
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    @csrf
    @if ($department->exists) @method('PUT') @endif

    <div>
        <label class="mb-1 block text-sm font-medium">Department name</label>
        <input name="dept_name" value="{{ $f('dept_name') }}" class="{{ $input }}" required>
        @error('dept_name') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Location</label>
        <input name="location" value="{{ $f('location') }}" class="{{ $input }}">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Manager</label>
        <select name="manager_id" class="{{ $input }}">
            <option value="">No manager</option>
            @foreach ($managers as $m)
                <option value="{{ $m->officer_id }}" @selected($f('manager_id') == $m->officer_id)>{{ $m->officer_name }}</option>
            @endforeach
        </select>
        @if ($managers->isEmpty())
            <p class="mt-1 text-xs text-slate-500">Add officers to this department first, then pick a manager here.</p>
        @endif
    </div>

    <div class="flex gap-3 border-t border-slate-200 pt-4">
        <button class="flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700"><i data-lucide="save" class="h-4 w-4"></i> Save department</button>
        <a href="{{ route('departments.index') }}" class="flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2 text-sm text-slate-600 hover:bg-slate-100"><i data-lucide="x" class="h-4 w-4"></i> Cancel</a>
    </div>
</form>
