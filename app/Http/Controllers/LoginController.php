<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginform(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        $userpass = $user->password ?? null;
        if (! $user || ! Hash::check($request->password, $userpass)) {
            return redirect()->route('login')->with('error', 'invalid credentials');
        }
        Auth::login($user);
        if (Auth::user() && Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'admin login successfully');
        } elseif (Auth::user() && Auth::user()->role == 'student') {
            return redirect()->route('student.dashboard')->with('success', 'student login successfully');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logs out the current user

        $request->session()->invalidate(); // Invalidates the current session

        $request->session()->regenerateToken(); //

        return redirect()->route('login');
    }
}
