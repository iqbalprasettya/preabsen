<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Kredensial yang diberikan tidak sesuai.'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        
        // Hapus token lama
        // $user->tokens()->delete();
        
        // Buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('departement', 'officeLocation', 'workSchedule')
        ]);
    }

    public function logout(Request $request)
    {
        // Mengatur token saat ini menjadi expired
        $request->user()->tokens()->update([
            'expires_at' => now()
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user()->load('departement', 'officeLocation', 'workSchedule')
        ]);
    }
}
