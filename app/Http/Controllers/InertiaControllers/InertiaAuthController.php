<?php

namespace App\Http\Controllers\InertiaControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InertiaAuthController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan Role
            if ($user->hasRole('super-admin')) {
                return redirect()->route('admin.upa.dashboard');
            } elseif ($user->hasRole('station-upa-pkk')) {
                return redirect()->route('upa.station.index');
            } elseif ($user->hasRole('outlet-owner')) {
                return redirect()->route('outlet.dashboard');
            } elseif ($user->hasRole('station')) {
                return redirect('/station');
            }

            // Default fallback jika tidak ada role yang cocok
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard()
    {
        return Inertia::render('DashboardAdmin');
    }
}
