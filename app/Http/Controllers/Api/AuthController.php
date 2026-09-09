<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Cek ketersediaan user dan kecocokan password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email atau password salah'
            ], 401);
        }

        // 4. Buat token autentikasi
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Kembalikan respon JSON beserta data role user
        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'data'    => [
                'user'  => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role, // Menampilkan role user
                ],
                'token' => $token
            ]
        ], 200);
    }
}
