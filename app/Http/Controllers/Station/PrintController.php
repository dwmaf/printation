<?php

namespace App\Http\Controllers\Station;

use App\Http\Controllers\Controller;
use App\Models\Printfile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PrintController extends Controller
{
    public function index()
    {
        $stationId = Auth::id();
        $files = Printfile::where('station_id', $stationId)->orderBy('created_at','desc')->paginate(20);
        $config = Auth::user()->print_config ?? null;
        $qr = null;
        return Inertia::render('Station/PrintStation/index', [
            'filetoprints' => $files,
            'printConfig' => $config,
            'qrCode' => $qr,
        ]);
    }

    public function destroy($id)
    {
        $file = Printfile::where('id', $id)->where('station_id', Auth::id())->firstOrFail();
        $file->delete();
        return redirect()->back()->with('success', 'Deleted');
    }

    public function updateConfig(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->print_config = $request->only(['copies','duplex']);
        $user->save();
        return redirect()->back()->with('success', 'Saved');
    }
}
