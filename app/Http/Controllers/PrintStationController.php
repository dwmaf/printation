<?php

namespace App\Http\Controllers;

use App\Models\Printfile;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class PrintStationController extends Controller
{
    public function index(Request $request)
    {
        $stationId = Auth::id(); // Ambil ID user station yg sedang login
        // Ini bikin QR selalu pakai domain yang sedang dibuka (ngrok), bukan APP_URL
        $uploadUrl = $request->getSchemeAndHttpHost() . '/upload/' . $stationId;

        // Ambil files + transaksi terbaru (pastikan relasi transactions ada)
        $files = Printfile::with(['transactions' => function ($q) {
            $q->latest();
        }])->where('station_id', $stationId)->latest()->get();

        $outlet = Auth::user()->outlet;
        $outletQr = ($outlet && $outlet->qris_path) ? asset('storage/' . $outlet->qris_path) : asset('images/dana_qr.jpeg');

        return view('print-station', [
            'files'  => $files,
            'qrCode' => QrCode::size(300)->margin(2)->generate($uploadUrl),
            'outletQr' => $outletQr,
        ]);
    }

    public function show(Request $request, Printfile $printfile)
    {
        return view('print-afile', [
            'file' => $printfile,
        ]);
    }

    public function destroy(Request $request, Printfile $printfile)
    {
        $printfile->delete();
        return redirect()->back()->with('success', 'File berhasil dihapus.');
    }
}
