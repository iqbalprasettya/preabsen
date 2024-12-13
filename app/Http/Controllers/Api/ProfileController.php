<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('departement', 'officeLocation', 'workSchedule');
        $user->photo_url = $user->photo ? url('storage/' . $user->photo) : null;

        return response()->json([
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $fileName = 'profile_' . uniqid();
            $extension = $request->file('photo')->getClientOriginalExtension();
            $photo = $request->file('photo')->storeAs('profile-photos', $fileName . '.' . $extension, 'public');
            $user->photo = $photo;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->photo_url = $user->photo ? url('storage/' . $user->photo) : null;

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user->load('departement', 'officeLocation', 'workSchedule')
        ]);
    }
}
