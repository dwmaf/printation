<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Printfile; // Sesuaikan dengan nama model file kamu

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'file_id' => 'required',
            'amount' => 'required|integer',
            'copies' => 'required|integer',
            
        ]);

        // 2. ORDER ID 
        $orderId = 'TRX-' . rand(1000, 9999);

        // 3. Simpan Transaksi
        $transaction = Transaction::create([
            'order_id' => $orderId,
            'file_id' => $request->file_id,
            'amount' => $request->amount,
            'status' => 'pending', 
            'print_config' => [
                'copies' => $request->copies,
                'color_mode' => $request->color_mode,
                'paper_size' => $request->paper_size,
                'page_range' => $request->page_range,
                'page_count_detected' => $request->page_count 
            ]
        ]);

        // 4. Send Respond to Frontend
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibuat',
            'order_id' => $orderId,
            'amount' => $request->amount
        ]);
    }
}