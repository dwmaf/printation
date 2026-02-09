<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('printfile')
            ->latest()
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function approve(Transaction $transaction)
    {
        $transaction->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Transaksi berhasil dikonfirmasi (PAID).');
    }


    public function reject(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Transaksi ditolak (REJECTED).');
    }
}
