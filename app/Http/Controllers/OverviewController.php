<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Officer;

class OverviewController extends Controller
{
    public function index()
    {
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
