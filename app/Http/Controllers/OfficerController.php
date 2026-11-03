<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Officer;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $data = $this->validated($request);
        $officer = DB::transaction(function () use ($data, $request): Officer {
            if ($request->hasFile('avatar')) {
                $data['avatar_path'] = $request->file('avatar')->store('officer-avatars', 'public');
            }

            $officer = Officer::create($data);
            ActivityLogger::record('Officer creation', $officer);

            return $officer;
        });

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
        $data = $this->validated($request, $officer);
        DB::transaction(function () use ($data, $request, $officer): void {
            if ($request->hasFile('avatar')) {
                if ($officer->avatar_path) {
                    Storage::disk('public')->delete($officer->avatar_path);
                }
                $data['avatar_path'] = $request->file('avatar')->store('officer-avatars', 'public');
            }

            $officer->update($data);
            ActivityLogger::record('Officer updates', $officer);
        });

        return redirect()->route('officers.index')->with('success', 'Officer updated.');
    }

    public function destroy(Officer $officer)
    {
        DB::transaction(function () use ($officer): void {
            if ($officer->avatar_path) {
                Storage::disk('public')->delete($officer->avatar_path);
            }
            $officer->delete();
            ActivityLogger::record('Officer deletion', $officer);
        });

        return back()->with('success', 'Officer deleted.');
    }

    private function validated(Request $request, ?Officer $officer = null): array
    {
        $data = $request->validate([
            'officer_name' => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('officers', 'email')->ignore($officer?->officer_id, 'officer_id')],
            'gender'       => 'required|in:Male,Female',
            'dob'          => 'nullable|date|before:today',
            'phone'        => 'nullable|string|max:30',
            'position'     => 'required|string|max:255',
            'hire_date'    => 'required|date',
            'dept_id'      => 'required|exists:departments,dept_id',
            'description'  => 'nullable|string',
            'avatar'       => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=2000,max_height=2000',
        ]);

        unset($data['avatar']);

        return $data;
    }
}
