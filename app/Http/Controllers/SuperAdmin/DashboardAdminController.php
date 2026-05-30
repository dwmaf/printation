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
        // TODO: Ganti dengan data asli dari database (Transaction / Printfile dll)
        return Inertia::render('SuperAdmin/DashboardAdmin', [
            'stats' => [
                'chartData' => [
                    ['month' => 'Jan', 'total' => 150],
                    ['month' => 'Feb', 'total' => 220],
                    ['month' => 'Mar', 'total' => 180],
                    ['month' => 'Apr', 'total' => 250],
                    ['month' => 'May', 'total' => 300],
                    ['month' => 'Jun', 'total' => 280],
                ],
                'sheetsThisMonth' => 280,
                'trendPercentage' => '12.5',
                'sheetsAllTime' => 1380,
            ]
        ]);
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
            'address' => 'required|string',
            'owner_name' => 'required|string',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|min:8',
        ]);

        DB::transaction(function () use ($request) {
            $outlet = Outlet::create([
                'name' => $request->name,
                'address' => $request->address,
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

    public function updateOutlet(Request $request, int $id)
    {
        $outlet = Outlet::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        $outlet->update($request->only('name', 'address'));

        return redirect()->back()->with('success', 'Outlet berhasil diupdate');
    }
    public function destroyOutlet(int $id)
    {
        $outlet = Outlet::findOrFail($id);
        // User terkait biasanya ikut terhapus jika di set cascade di DB, 
        // atau hapus manual di sini.
        $outlet->delete();

        return redirect()->back()->with('success', 'Outlet berhasil dihapus');
    }
}
