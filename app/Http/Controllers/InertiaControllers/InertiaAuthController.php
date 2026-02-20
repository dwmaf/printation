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

            if ($user->hasRole('super-admin')) {
                return redirect()->route('admin.upa.dashboard');
            } elseif ($user->hasRole('station-upa-pkk')) {
                return redirect()->route('upa.station.index');
            }

            // Default fallback
            return redirect()->route('welcome');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function dashboard()
    {
        return Inertia::render('DashboardAdmin');
    }
}
