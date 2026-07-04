<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Login sayfasını göster
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($role === 'staff') {
                return redirect('/staff/dashboard');
            } elseif ($role === 'student') {
                return redirect('/student/dashboard');
            } elseif ($role === 'parent') {
                return redirect('/parent/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email veya şifreniz hatalıdır.',
        ]);
    }
    //Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
