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

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // REDIRECT SESUAI ROLE SPATIE
            if ($user->hasRole('super-admin')) {
                return redirect()->route('admin.outlets');
            } elseif ($user->hasRole('outlet-owner')) {
                return redirect('/outlet-owner/dashboard');
            } elseif ($user->hasRole('station')) {
                return redirect('/station');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}