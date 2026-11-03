<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AccountStatusNotification;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountApprovalController extends Controller
{
    public function index()
    {
        return view('admin.accounts', [
            'accounts' => User::where('role', 'officer')
                ->with('officer')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function approve(User $user)
    {
        abort_unless($user->role === 'officer', 404);

        DB::transaction(function () use ($user): void {
            $user->forceFill([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'rejection_reason' => null,
                'is_active' => true,
            ])->save();
            ActivityLogger::record('Officer approval', $user->officer ?: $user);
        });
        $user->notify(new AccountStatusNotification(true));

        return back()->with('success', 'Officer account approved.');
    }

    public function reject(Request $request, User $user)
    {
        abort_unless($user->role === 'officer', 404);

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($user, $data): void {
            $user->forceFill([
                'approval_status' => 'rejected',
                'rejection_reason' => $data['rejection_reason'],
                'is_active' => false,
            ])->save();
            ActivityLogger::record('Officer rejection', $user->officer ?: $user);
        });
        $user->notify(new AccountStatusNotification(false));

        return back()->with('success', 'Officer registration rejected.');
    }

    public function toggle(User $user)
    {
        abort_unless($user->role === 'officer' && $user->approval_status === 'approved', 404);

        $user->forceFill(['is_active' => ! $user->is_active])->save();
        ActivityLogger::record($user->is_active ? 'Account activation' : 'Account deactivation', $user->officer ?: $user);

        return back()->with('success', $user->is_active ? 'Account activated.' : 'Account deactivated.');
    }
}
