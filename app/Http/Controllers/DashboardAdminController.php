<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Printfile;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return view('/admin/dashboard');
    }

    public function indexOutlet()
    {
        // Ambil data outlet beserta info ownernya
        $outlets = Outlet::with(['owner'])->latest()->get(); 
        // Catatan: Pastikan di Model Outlet ada relasi: public function owner() { return $this->hasOne(User::class); }
        
        return view('admin.outlets.index', compact('outlets'));
    }

    public function storeOutlet(Request $request)
    {
        $request->validate([
            'outlet_name' => 'required',
            'owner_email' => 'required|email|unique:users,email',
            'owner_name'  => 'required',
            'owner_password' => 'required|min:6',
            'max_stations'=> 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat Data Outlet
            $outlet = Outlet::create([
                'name' => $request->outlet_name,
                'address' => $request->address,
                'max_stations' => $request->max_stations, // Jumlah station yg diizinkan admin
            ]);

            // 2. Buat Akun Owner Otomatis
            $owner = User::create([
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => Hash::make($request->owner_password), // Default password, nanti owner bisa ganti
                'outlet_id' => $outlet->id,
            ]);

            // 3. Assign Role Spatie
            $owner->assignRole('outlet-owner');
        });

        return back()->with('success', 'Outlet & Akun Owner berhasil dibuat! (Pass Owner: password)');
    }

}
