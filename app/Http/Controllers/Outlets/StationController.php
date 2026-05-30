<?php

namespace App\Http\Controllers\Outlets;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class StationController extends Controller
{
    public function index()
    {
        $outletId = Auth::user()->outlet_id;
        
        $stations = User::role('station')
            ->where('outlet_id', $outletId)
            ->get();

        return Inertia::render('Outlets/IndexStation', [
            'stations' => $stations
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $outletId = Auth::user()->outlet_id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'outlet_id' => $outletId,
        ]);

        $user->assignRole('station');

        return redirect()->back()->with('success', 'Akun Station berhasil dibuat');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = User::where('outlet_id', Auth::user()->outlet_id)->findOrFail($id);
        
        $data = ['name' => $request->name];
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Akun Station berhasil diupdate');
    }

    public function destroy(int $id)
    {
        $user = User::where('outlet_id', Auth::user()->outlet_id)->findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Akun Station berhasil dihapus');
    }
}