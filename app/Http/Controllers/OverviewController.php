<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Officer;

class OverviewController extends Controller
{
    public function index()
    {
        if (! auth()->user()->isAdmin()) {
            return view('officer.dashboard', [
                'officer' => auth()->user()->officer()->with('department')->first(),
            ]);
        }

        return view('overview', [
            'officerCount' => Officer::count(),
            'departmentCount' => Department::count(),
            'recentOfficers' => Officer::with('department')
                ->orderByDesc('officer_id')
                ->limit(5)
                ->get(),
        ]);
    }
}
