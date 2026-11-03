<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Officer;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::with('manager')->withCount('officers')
            ->when($request->search, fn ($q, $s) => $q->where('dept_name', 'like', "%{$s}%"))
            ->orderBy('dept_name')
            ->paginate(8)
            ->withQueryString();

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create', ['managers' => collect()]);
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request): void {
            $department = Department::create($this->validated($request));
            ActivityLogger::record('Department creation', $department);
        });

        return redirect()->route('departments.index')->with('success', 'Department added.');
    }

    public function edit(Department $department)
    {
        // Only officers that belong to this department can manage it
        return view('departments.edit', [
            'department' => $department,
            'managers'   => Officer::where('dept_id', $department->dept_id)->orderBy('officer_name')->get(),
        ]);
    }

    public function update(Request $request, Department $department)
    {
        DB::transaction(function () use ($request, $department): void {
            $department->update($this->validated($request, $department));
            ActivityLogger::record('Department updates', $department);
        });

        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        if ($department->officers()->exists()) {
            return back()->with('error', 'Move or delete the officers in this department first.');
        }
        DB::transaction(function () use ($department): void {
            $department->delete();
            ActivityLogger::record('Department deletion', $department);
        });
        return back()->with('success', 'Department deleted.');
    }

    private function validated(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'dept_name'  => ['required', 'string', 'max:255', Rule::unique('departments', 'dept_name')->ignore($department?->dept_id, 'dept_id')],
            'location'   => 'nullable|string|max:255',
            'manager_id' => [
                'nullable',
                Rule::exists('officers', 'officer_id')->where(
                    fn ($query) => $query->where('dept_id', $department?->dept_id ?? $request->input('dept_id'))
                ),
            ],
        ]);
    }
}
