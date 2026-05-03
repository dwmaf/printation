<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionStatusController extends Controller
{
    public function show($orderId)
    {
        $tx = Transaction::where('order_id', $orderId)->latest()->first();

        if (!$tx) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status' => $tx->status, // pending|paid|rejected
        ]);
    }
}
