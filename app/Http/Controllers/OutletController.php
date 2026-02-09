<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OutletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $outlet = $user->outlet; // Pastikan relasi di User model ada method 'outlet()'

        if (!$outlet) return redirect('/')->with('error', 'Anda tidak memiliki outlet.');

        // 1. DATA VERIFIKASI PEMBAYARAN (POIN 3)
        // Ambil transaksi milik station2 yg ada di outlet ini
        $transactions = Transaction::whereHas('station', function($q) use ($outlet) {
            $q->where('outlet_id', $outlet->id);
        })
        ->latest()->get();

        // 2. DATA MANAJEMEN STATION (POIN 2)
        $stations = User::role('station')
                    ->where('outlet_id', $outlet->id)
                    ->get();

        return view('outlet-owner.dashboard', compact('outlet', 'transactions', 'stations'));
    }

    public function indexStation()
    {
        $user = Auth::user();
        $outlet = $user->outlet;

        $stations = User::role('station')
                    ->where('outlet_id', $outlet->id)
                    ->get();

        return view('outlet-owner.stations', compact('outlet', 'stations'));
    }

    // POIN 3: AKSI VERIFIKASI
    public function verify($id)
    {
        $trx = Transaction::findOrFail($id);
        // Validasi kepemilikan
        if($trx->station && $trx->station->outlet_id != Auth::user()->outlet_id) abort(403);

        $trx->update(['status' => 'paid']); // Trigger ke printer station
        return back()->with('success', 'Pembayaran diverifikasi.');
    }

    public function reject($id)
    {
        $trx = Transaction::findOrFail($id);

        // Cek kepemilikan
        if($trx->station && $trx->station->outlet_id != Auth::user()->outlet_id) abort(403);

        $trx->update(['status' => 'rejected']);

        return back()->with('error', 'Transaksi ditolak (Rejected).');
    }

    // POIN 2: TAMBAH STATION
    public function storeStation(Request $request)
    {
        $outlet = Auth::user()->outlet;
        
        // Cek Batas Kuota
        $count = User::role('station')->where('outlet_id', $outlet->id)->count();
        if ($count >= $outlet->max_stations) {
            return back()->with('error', 'Kuota Station Penuh!');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4'
        ]);

        $u = User::create([
            'name' => $request->name, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'outlet_id' => $outlet->id
        ]);
        $u->assignRole('station');

        return back()->with('success', 'Station baru dibuat.');
    }

    public function destroyStation($id) {
        $u = User::findOrFail($id);
        if($u->outlet_id != Auth::user()->outlet_id) abort(403);
        $u->delete();
        return back()->with('success', 'Dihapus.');
    }
}