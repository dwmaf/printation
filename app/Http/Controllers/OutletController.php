<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PrintFile;
use Illuminate\Support\Facades\Storage;
use App\Events\TransactionUpdated;

class OutletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $outlet = $user->outlet; // Pastikan relasi di User model ada method 'outlet()'

        if (!$outlet) return redirect('/')->with('error', 'Anda tidak memiliki outlet.');
        $pendingCount = Transaction::whereHas('station', function($q) use ($outlet) {
            $q->where('outlet_id', $outlet->id);
        })->where('status', 'pending')->count();
        $totalRevenue = Transaction::whereHas('station', function($q) use ($outlet) {
            $q->where('outlet_id', $outlet->id);
        })->where('status', 'paid')->sum('amount');

        return view('outlet-owner.dashboard', compact('outlet', 'pendingCount', 'totalRevenue', 'user'));
    }

    public function payments()
    {
        $user = Auth::user();
        $outlet = $user->outlet;

        $transactions = Transaction::whereHas('station', function($q) use ($outlet) {
            $q->where('outlet_id', $outlet->id);
        })->latest()->get();

        return view('outlet-owner.payments', compact('outlet', 'transactions'));
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

    public function indexFiles()
    {
        $outlet = Auth::user()->outlet;
        
        // Ambil station milik outlet ini beserta file-filenya
        $stations = User::role('station')
            ->where('outlet_id', $outlet->id)
            ->with(['printFiles' => function($q) {
                $q->latest();
            }])
            ->get();

        return view('outlet-owner.files', compact('outlet', 'stations'));
    }

    public function destroyFile($id)
    {
        $file = PrintFile::findOrFail($id);
        // Proteksi: pastikan file milik station di outlet ini
        if (!$file->station || $file->station->outlet_id != Auth::user()->outlet_id) abort(403);

        // Sesuai migrasi, kolomnya adalah 'filename'
        if ($file->filename) {
            Storage::disk('public')->delete($file->filename);
        }
        $file->delete();

        return back()->with('success', 'File berhasil dihapus.');
    }

    public function bulkDeleteByStation($stationId)
    {
        $station = User::findOrFail($stationId);
        if ($station->outlet_id != Auth::user()->outlet_id) abort(403);

        $files = PrintFile::where('station_id', $stationId)->get();
        foreach ($files as $file) {
            if ($file->filename) {
                Storage::disk('public')->delete($file->filename);
            }
            $file->delete();
        }

        return back()->with('success', 'Semua file di Station ' . $station->name . ' telah dibersihkan.');
    }

    public function clearAllFiles()
    {
        $outletId = Auth::user()->outlet_id;
        $files = PrintFile::whereHas('station', function($q) use ($outletId) {
            $q->where('outlet_id', $outletId);
        })->get();

        foreach ($files as $file) {
            if ($file->filename) {
                Storage::disk('public')->delete($file->filename);
            }
            $file->delete();
        }

        return back()->with('success', 'Semua file di seluruh station telah dibersihkan.');
    }

    // POIN 3: AKSI VERIFIKASI
    public function verify($id)
    {
        $trx = Transaction::findOrFail($id);
        // Validasi kepemilikan
        if($trx->station && $trx->station->outlet_id != Auth::user()->outlet_id) abort(403);

        $trx->update(['status' => 'paid']); // Trigger ke printer station
        event(new TransactionUpdated($trx->station_id));
        return back()->with('success', 'Pembayaran diverifikasi.');
    }

    public function reject($id)
    {
        $trx = Transaction::findOrFail($id);

        // Cek kepemilikan
        if($trx->station && $trx->station->outlet_id != Auth::user()->outlet_id) abort(403);

        $trx->update(['status' => 'rejected']);
        event(new TransactionUpdated($trx->station_id));
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
    public function editQRIS()
    {
        $outlet = Auth::user()->outlet;
        return view('outlet-owner.qris', compact('outlet'));
    }
    public function updateQRIS(Request $request)
    {
        $request->validate([
            'qris_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $outlet = Auth::user()->outlet;
        if (!$outlet) abort(403);

        if ($request->hasFile('qris_file')) {
            $path = $request->file('qris_file')->store('qris', 'public');
            $outlet->update(['qris_path' => $path]);
        }

        return back()->with('success', 'QRIS Outlet berhasil diperbarui.');
    }}