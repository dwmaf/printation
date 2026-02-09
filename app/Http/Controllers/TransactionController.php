<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Printfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // USER: dipanggil dari tombol "SAYA SUDAH TRANSFER"
    public function store(Request $request)
    {
        $data = $request->validate([
            'file_id'     => 'required|integer|exists:printfiles,id',
            'copies'      => 'required|integer|min:1',
            'color_mode'  => 'required|in:bw,color',
            'paper_size'  => 'nullable|string',
            'page_range'  => 'nullable|string',
            'page_count'  => 'required|integer|min:1',
            'amount'      => 'required|integer|min:0',
        ]);

        // pastikan file ada
        $file = Printfile::findOrFail($data['file_id']);

        // bikin order id unik
        $orderId = strtoupper(Str::random(6));

        // simpan config ke kolom print_config (JSON string)
        $printConfig = json_encode([
            'copies'     => (int) $data['copies'],
            'color_mode' => $data['color_mode'],
            'paper_size' => $data['paper_size'] ?? 'A4',
            'page_range' => $data['page_range'],
            'page_count' => (int) $data['page_count'],
        ]);
        $filenameSnapshot = $file->original_name;
        $tx = Transaction::create([
            // KUNCI: tabel kamu butuh ini semua
            'order_id'     => $orderId,
            'file_id'      => $file->id,          // WAJIB (NOT NULL)
            'station_id'   => $file->station_id, 
            'amount'       => (int) $data['amount'],
            'status'       => 'pending',
            'print_config' => $printConfig,       // WAJIB (NOT NULL)
            'filename_snapshot' => $filenameSnapshot 
        ]);

        return response()->json([
            'status'   => 'success',
            'order_id' => $tx->order_id,
        ]);
    }

    // ADMIN: list transaksi
    public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('admin.transactions', compact('transactions'));
    }

    // ADMIN: confirm pembayaran
    public function confirm(Transaction $transaction)
    {
        $transaction->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Transaksi dikonfirmasi (PAID).');
    }

    public function status($orderId)
    {
        $tx = \App\Models\Transaction::where('order_id', $orderId)->latest()->first();

        if (!$tx) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status' => $tx->status,         // pending / paid / rejected
            'order_id' => $tx->order_id,
            'print_config' => $tx->print_config, // JSON string
            'amount' => $tx->amount,
        ]);
    }



    // ADMIN: reject
    public function reject(Transaction $transaction)
    {
        $transaction->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Transaksi ditolak (REJECTED).');
    }
}
