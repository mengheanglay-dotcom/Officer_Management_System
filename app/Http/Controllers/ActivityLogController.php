<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('admin.activity-logs', [
            'logs' => ActivityLog::with('user')->latest('created_at')->paginate(25),
        ]);
    }
}
