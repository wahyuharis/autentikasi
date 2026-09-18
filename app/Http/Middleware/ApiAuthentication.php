<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ApiAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Email dan Password wajib diisi'
            ], 401);
        }

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Email dan Password tidak dikenali'
            ], 401);
        }

        // Simpan user yang sudah terautentikasi
        $request->merge([
            'api_user' => $user
        ]);

        return $next($request);
    }
}