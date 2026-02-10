<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardAdminController extends Controller
{

    public function indexDashboard()
    {
        return view('admin.dashboard-admin');
    }
    public function indexOutlet()
    {
        $outlets = Outlet::with(['owner'])->latest()->get();
        return view('admin.outlets.index', compact('outlets'));
    }

    public function storeOutlet(Request $request)
    {
        $request->validate([
            'outlet_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'max_stations' => 'required|integer|min:1',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $outlet = Outlet::create([
                'name' => $request->outlet_name,
                'address' => $request->address,
                'max_stations' => $request->max_stations,
                'qris_path' => null,
            ]);

            $owner = User::create([
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => Hash::make($request->owner_password),
                'outlet_id' => $outlet->id,
            ]);

            $owner->assignRole('outlet-owner');
        });

        return back()->with('success', 'Outlet & Akun Owner berhasil dibuat!');
    }
}
