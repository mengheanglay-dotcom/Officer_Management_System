<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BuildingController extends Controller
{
    public function index()
    {
        return view('admin.buildings.index', ['buildings' => Building::withCount('departments')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $building = Building::create($this->validated($request));
        ActivityLogger::record('Building creation', $building);

        return back()->with('success', 'Building added.');
    }

    public function update(Request $request, Building $building)
    {
        $building->update($this->validated($request, $building));
        ActivityLogger::record('Building updates', $building);

        return back()->with('success', 'Building updated.');
    }

    public function destroy(Building $building)
    {
        if ($building->departments()->exists()) {
            return back()->with('error', 'Remove this building from its departments first.');
        }

        $building->delete();
        ActivityLogger::record('Building deletion', $building);

        return back()->with('success', 'Building deleted.');
    }

    private function validated(Request $request, ?Building $building = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('buildings', 'name')->ignore($building)],
        ]);
    }
}
