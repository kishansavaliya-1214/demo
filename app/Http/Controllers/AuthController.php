<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        $password = $user->password ?? null;
        if (! $user || ! Hash::check($request->password, $password)) {
            return response()->json(['message' => 'Invalid Credential'], 401);
        }
        $token = $user->createToken('userLogin')->plainTextToken;

        return response()->json(['message' => 'login successfully', 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Successfully logged out',
            ], 200);
        }

        return response()->json([
            'message' => 'Unauthenticated',
        ], 401);
    }
}
