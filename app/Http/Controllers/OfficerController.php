<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficerController extends Controller
{
    public function index(Request $request)
    {
        $officers = Officer::with('department')
            ->when($request->search, fn ($q, $s) => $q->where('officer_name', 'like', "%{$s}%"))
            ->when($request->dept_id, fn ($q, $d) => $q->where('dept_id', $d))
            ->when($request->gender, fn ($q, $g) => $q->where('gender', $g))
            ->orderBy('officer_id', $request->sort === 'oldest' ? 'asc' : 'desc')
            ->paginate(8)
            ->withQueryString();

        return view('officers.index', [
            'officers'    => $officers,
            'departments' => Department::orderBy('dept_name')->get(),
            'total'       => Officer::count(),
            'deptCount'   => Department::count(),
        ]);
    }

    public function create()
    {
        return view('officers.create', ['departments' => Department::orderBy('dept_name')->get()]);
    }

    public function store(Request $request)
    {
        Officer::create($this->validated($request));
        return redirect()->route('officers.index')->with('success', 'Officer added.');
    }

    public function edit(Officer $officer)
    {
        return view('officers.edit', [
            'officer'     => $officer,
            'departments' => Department::orderBy('dept_name')->get(),
        ]);
    }

    public function update(Request $request, Officer $officer)
    {
        $officer->update($this->validated($request, $officer));
        return redirect()->route('officers.index')->with('success', 'Officer updated.');
    }

    public function destroy(Officer $officer)
    {
        $officer->delete();
        return back()->with('success', 'Officer deleted.');
    }

    private function validated(Request $request, ?Officer $officer = null): array
    {
        return $request->validate([
            'officer_name' => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('officers', 'email')->ignore($officer?->officer_id, 'officer_id')],
            'gender'       => 'required|in:Male,Female',
            'dob'          => 'nullable|date|before:today',
            'phone'        => 'nullable|string|max:30',
            'position'     => 'required|string|max:255',
            'hire_date'    => 'required|date',
            'dept_id'      => 'required|exists:departments,dept_id',
            'description'  => 'nullable|string',
        ]);
    }
}
