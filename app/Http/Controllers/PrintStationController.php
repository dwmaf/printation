<?php

namespace App\Http\Controllers;

use App\Models\Printfile;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintStationController extends Controller
{
    public function index(Request $request)
    {
        // Ini bikin QR selalu pakai domain yang sedang dibuka (ngrok), bukan APP_URL
        $uploadUrl = $request->getSchemeAndHttpHost() . '/upload';

        // Ambil files + transaksi terbaru (pastikan relasi transactions ada)
        $files = Printfile::with(['transactions' => function ($q) {
            $q->latest();
        }])->latest()->get();

        return view('print-station', [
            'files'  => $files,
            'qrCode' => QrCode::size(300)->margin(2)->generate($uploadUrl),
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
