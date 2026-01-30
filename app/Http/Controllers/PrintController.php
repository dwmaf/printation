<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printing;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class PrintController extends Controller
{
    // Halaman Dashboard Laptop (Menampilkan QR)
    public function index()
{
    // Hanya tampilkan yang statusnya BELUM 'printed'
    $printings = \App\Models\Printing::where('status', '!=', 'printed')
                                     ->orderBy('created_at', 'desc')
                                     ->get();

    $url = config('app.url') . '/upload';
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);

    return view('desktop', compact('printings', 'qrCodeUrl'));
}

    // Halaman Upload di HP
    public function uploadPage() {
        return view('upload');
    }

    // Proses Simpan File dari HP
    public function store(Request $request) {
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,png,docx|max:10240', // Anti-curang: filter tipe & ukuran
        ]);

        // Cek jika IP ini sudah upload terlalu banyak (Anti-spam)
        $uploadCount = Printing::where('ip_address', $request->ip())
                                ->where('created_at', '>', now()->subMinutes(5))
                                ->count();
        if($uploadCount > 3) return back()->with('error', 'Terlalu banyak upload. Tunggu sebentar.');

        $path = $request->file('file')->store('uploads', 'public');

        Printing::create([
            'queue_number' => 'A-' . rand(100, 999),
            'file_path' => $path,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('SUCCES', 'File berhasil dikirim! Silahkan bayar di kasir.');
    }

}
