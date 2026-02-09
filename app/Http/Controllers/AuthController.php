<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect sesuai role
        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.outlets');
        }

        if ($user->hasRole('outlet-owner')) {
            return redirect()->route('outlet.dashboard');
        }

        if ($user->hasRole('station')) {
            return redirect('/station');
        }

        // Kalau login sukses tapi role belum diset
        Auth::logout();
        return back()->withErrors(['email' => 'Akun kamu belum punya role. Hubungi admin.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
