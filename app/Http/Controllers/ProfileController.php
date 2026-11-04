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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('officers', 'email')->ignore($officer?->officer_id, 'officer_id'),
            ],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'avatar' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=2000,max_height=2000',
        ];

        if ($officer) {
            $rules['gender'] = ['required', Rule::in(['Male', 'Female'])];
            $rules['dob'] = ['nullable', 'date', 'before:today'];
            $rules['phone'] = ['nullable', 'string', 'max:30'];
        }

        $data = $request->validate($rules);
        $avatar = $data['avatar'] ?? null;
        unset($data['avatar'], $data['current_password']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $officerData = array_intersect_key($data, array_flip(['gender', 'dob', 'phone']));
        $userData = array_diff_key($data, $officerData);

        DB::transaction(function () use ($user, $officer, $userData, $officerData, $avatar): void {
            if ($avatar) {
                if ($officer) {
                    if ($officer->avatar_path) {
                        Storage::disk('public')->delete($officer->avatar_path);
                    }
                    $officerData['avatar_path'] = $avatar->store('officer-avatars', 'public');
                } else {
                    if ($user->avatar_path) {
                        Storage::disk('public')->delete($user->avatar_path);
                    }
                    $userData['avatar_path'] = $avatar->store('avatars', 'public');
                }
            }

            $user->update($userData);
            $officer?->update(array_merge($officerData, [
                'officer_name' => $user->name,
                'email' => $user->email,
            ]));
        });

        return redirect()->route('profile.edit')->with('success', 'Profile updated.');
    }
}
