<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{

    public function index()
    {
        return Inertia::render('SuperAdmin/DashboardAdmin');
    }

    // buat ke halaman daftar outlet
    public function indexOutlet()
    {
        return Inertia::render('SuperAdmin/IndexOutlet', [
            'outlets' => Outlet::with('owner')->get()
        ]);
    }

    public function storeOutlet(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'owner_name' => 'required|string',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|min:8',
        ]);

        DB::transaction(function () use ($request) {
            $outlet = Outlet::create([
                'name' => $request->name,
                'location' => $request->location,
            ]);

            $user = User::create([
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => Hash::make($request->owner_password),
                'outlet_id' => $outlet->id,
            ]);

            $user->assignRole('outlet-owner');
        });

        return redirect()->back()->with('success', 'Outlet dan Owner berhasil dibuat');
    }

    public function updateOutlet(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
        ]);

        $outlet->update($request->only('name', 'location'));

        return redirect()->back()->with('success', 'Outlet berhasil diupdate');
    }
    public function destroyOutlet($id)
    {
        $outlet = Outlet::findOrFail($id);
        // User terkait biasanya ikut terhapus jika di set cascade di DB, 
        // atau hapus manual di sini.
        $outlet->delete();

        return redirect()->back()->with('success', 'Outlet berhasil dihapus');
    }
}
