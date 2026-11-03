<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $officer = $user->officer;
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('officers', 'email')->ignore($officer?->officer_id, 'officer_id'),
            ],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'avatar' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=2000,max_height=2000',
        ]);

        $avatar = $data['avatar'] ?? null;
        unset($data['avatar']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        DB::transaction(function () use ($user, $officer, $data, $avatar): void {
            if ($avatar) {
                if ($user->avatar_path) {
                    Storage::disk('public')->delete($user->avatar_path);
                }
                $data['avatar_path'] = $avatar->store('avatars', 'public');
            }

            $user->update($data);
            $officer?->update(['officer_name' => $user->name, 'email' => $user->email]);
        });

        return redirect()->route('profile.edit')->with('success', 'Profile updated.');
    }
}
