<?php

namespace App\Http\Controllers\Outlets;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class VerifyPrintController extends Controller
{
    public function index(Request $request)
    {
        $outletId = Auth::user()->outlet_id;
        
        $query = Transaction::where('outlet_id', $outletId)->orderBy('created_at', 'desc');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('order_id', 'like', "%{$search}%");
        }

        $transactions = $query->paginate(10)->withQueryString();

        return Inertia::render('Outlets/VerifyPrint', [
            'transactions' => $transactions,
            'filters' => $request->only('search')
        ]);
    }

    public function verify(int $id)
    {
        $transaction = Transaction::where('outlet_id', Auth::user()->outlet_id)->findOrFail($id);
        $transaction->update(['status' => 'completed']);
        
        return redirect()->back()->with('success', 'Berhasil memverifikasi.');
    }

    public function reject(int $id)
    {
        $transaction = Transaction::where('outlet_id', Auth::user()->outlet_id)->findOrFail($id);
        $transaction->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Pesanan ditolak.');
    }
}