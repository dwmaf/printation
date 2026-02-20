<?php

namespace App\Http\Controllers\InertiaControllers;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use App\Events\TransactionUpdated;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaVerifyPrintController extends Controller
{
    public function index()
    {
        $printrequests = PrintRequest::with(['user', 'filetoprint'])
            ->latest()
            ->get();

        return Inertia::render('VerifyPrint', [
            'printrequests' => $printrequests // Pass as 'requests'
        ]);
    }

    public function verify($id)
    {
        $request = PrintRequest::findOrFail($id);
        $request->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        event(new TransactionUpdated($request->station_id));
        return redirect()->back();
    }

    public function reject($id)
    {
        $request = PrintRequest::findOrFail($id);
        $request->update([
            'status' => 'rejected',
        ]);

        event(new TransactionUpdated($request->station_id));
        return redirect()->back();
    }
}