@php
    $f = function ($k) use ($officer) {
        $v = $officer->$k ?? null;
        return old($k, $v instanceof \Carbon\Carbon ? $v->format('Y-m-d') : $v);
    };
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6 rounded-xl border border-slate-200 bg-white p-6">
    @csrf
    @if ($officer->exists) @method('PUT') @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium">Officer name</label>
            <input name="officer_name" value="{{ $f('officer_name') }}" class="{{ $input }}" required>
            @error('officer_name') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Email address</label>
            <input type="email" name="email" value="{{ $f('email') }}" class="{{ $input }}" required>
            @error('email') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Gender</label>
            <select name="gender" class="{{ $input }}" required>
                <option value="">Select gender</option>
                @foreach (['Male', 'Female'] as $g) <option @selected($f('gender') === $g)>{{ $g }}</option> @endforeach
            </select>
            @error('gender') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Date of birth</label>
            <input type="date" name="dob" value="{{ $f('dob') }}" class="{{ $input }}">
            @error('dob') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Phone</label>
            <input name="phone" value="{{ $f('phone') }}" class="{{ $input }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Position</label>
            <input name="position" value="{{ $f('position') }}" class="{{ $input }}" required>
            @error('position') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Hire date</label>
            <input type="date" name="hire_date" value="{{ $f('hire_date') }}" class="{{ $input }}" required>
            @error('hire_date') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Department</label>
            <select name="dept_id" class="{{ $input }}" required>
                <option value="">Select department</option>
                @foreach ($departments as $d)
                    <option value="{{ $d->dept_id }}" @selected($f('dept_id') == $d->dept_id)>{{ $d->dept_name }}</option>
                @endforeach
            </select>
            @error('dept_id') <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium">Notes</label>
        <textarea name="description" rows="3" class="{{ $input }}" placeholder="Anything else worth recording about this officer">{{ $f('description') }}</textarea>
    </div>

    <div class="flex gap-3 border-t border-slate-200 pt-4">
        <button class="flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            <i data-lucide="save" class="h-4 w-4"></i> Save officer
        </button>
        <a href="{{ route('officers.index') }}" class="flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2 text-sm text-slate-600 hover:bg-slate-100">
            <i data-lucide="x" class="h-4 w-4"></i> Cancel
        </a>
    </div>
</form>
