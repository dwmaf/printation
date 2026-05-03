<?php

namespace App\Http\Controllers\P2MWControllers;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class OutletAdminController extends Controller
{
    public function index(Request $request)
    {
        $outlets = Outlet::with(['owner'])->latest()->paginate(10);

        return Inertia::render('P2MW/SuperAdmin/Outlet', [
            'outlets' => $outlets,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'outlet_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'max_stations' => 'required|integer|min:1',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|string|min:8',
        ]);

        try {
            DB::beginTransaction();

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
                'email_verified_at' => now(),
            ]);

            $owner->assignRole('outlet-owner');
            DB::commit();
            return redirect()->back()->with('success', 'Outlet and Owner registered successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to register outlet: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'max_stations' => 'required|integer|min:1',
        ]);

        $outlet = Outlet::findOrFail($id);
        $outlet->update([
            'name' => $request->name,
            'max_stations' => $request->max_stations,
        ]);

        return redirect()->back()->with('success', 'Outlet updated successfully.');
    }
}
