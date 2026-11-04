<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FloorController extends Controller
{
    public function index()
    {
        return view('admin.floors.index', ['floors' => Floor::withCount('departments')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $floor = Floor::create($this->validated($request));
        ActivityLogger::record('Floor creation', $floor);

        return back()->with('success', 'Floor added.');
    }

    public function update(Request $request, Floor $floor)
    {
        $floor->update($this->validated($request, $floor));
        ActivityLogger::record('Floor updates', $floor);

        return back()->with('success', 'Floor updated.');
    }

    public function destroy(Floor $floor)
    {
        if ($floor->departments()->exists()) {
            return back()->with('error', 'Remove this floor from its departments first.');
        }

        $floor->delete();
        ActivityLogger::record('Floor deletion', $floor);

        return back()->with('success', 'Floor deleted.');
    }

    private function validated(Request $request, ?Floor $floor = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('floors', 'name')->ignore($floor)],
        ]);
    }
}
