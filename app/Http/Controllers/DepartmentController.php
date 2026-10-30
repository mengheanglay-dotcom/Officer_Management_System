<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Officer;
use Illuminate\Http\Request;
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
        Department::create($this->validated($request));
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
        $department->update($this->validated($request, $department));
        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        if ($department->officers()->exists()) {
            return back()->with('error', 'Move or delete the officers in this department first.');
        }
        $department->delete();
        return back()->with('success', 'Department deleted.');
    }

    private function validated(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'dept_name'  => ['required', 'string', 'max:255', Rule::unique('departments', 'dept_name')->ignore($department?->dept_id, 'dept_id')],
            'location'   => 'nullable|string|max:255',
            'manager_id' => 'nullable|exists:officers,officer_id',
        ]);
    }
}
