<?php

namespace App\Http\Controllers\InertiaControllers;

use App\Http\Controllers\Controller;
use App\Models\Filetoprint;
use App\Models\PrintRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InertiaPrintStationController extends Controller
{
    public function index(Request $request)
    {
        $station_id = Auth::id();
        $uploadUrl = $request->getSchemeAndHttpHost() . '/upa/upload/' . $station_id;

        // Fetch files with latest verification request
        $filetoprints = Filetoprint::with(['latestPrintRequest'])
            ->latest()
            ->where('station_id', $station_id) // Filter by station
            ->get();

        // Generate QR Code as SVG string
        $qrCode = QrCode::size(300)->margin(2)->generate($uploadUrl);

        return Inertia::render('PrintStation', [
            'filetoprints' => $filetoprints,
            'stationId' => $station_id,
            'qrCode' => (string) $qrCode,
        ]);
    }

    public function submitRequest(Request $request)
    {
        // dd($request);
        $request->validate([
            'file_id' => 'required|exists:filetoprints,id',
            'station_id' => 'required',
            'print_config' => 'required|array',
        ]);

        $filetoprint = Filetoprint::find($request->file_id);

        $config = $request->print_config;
        $copies = $config['copies'] ?? 1;
        $isColor = ($config['color'] ?? 'bw') === 'color';
        $detectedPages = $config['detected_pages'] ?? 1;
        
        $actualPages = $detectedPages;
        if (isset($config['pages']) && $config['pages'] !== 'all') {
            // Count custom pages from string like "1,3,5-10"
            $actualPages = 0;
            $parts = explode(',', str_replace(' ', '', $config['pages']));
            foreach ($parts as $part) {
                if (strpos($part, '-') !== false) {
                    $range = explode('-', $part);
                    if (count($range) === 2) {
                        $start = (int)$range[0];
                        $end = (int)$range[1];
                        if ($start > 0 && $end >= $start) {
                            $actualPages += ($end - $start + 1);
                        }
                    }
                } elseif (is_numeric($part)) {
                    $actualPages++;
                }
            }
            // Fallback if failed to parse or 0
            if ($actualPages === 0) $actualPages = $detectedPages;
        }

        // Create Verification Request
        $verification = PrintRequest::create([
            'request_id' => 'REQ-' . strtoupper(uniqid()),
            'filetoprint_id' => $filetoprint->id,
            'station_id' => $request->station_id,
            'original_name' => $filetoprint->original_name,
            'status' => 'pending',
            'copies' => $copies,
            'color_mode' => $isColor ? 'color' : 'bw',
            'paper_size' => $config['paper'] ?? 'A4',
            'page_range' => $config['pages'] ?? 'all',
            'detected_pages' => $detectedPages,
            'calculated_pages' => $actualPages,
        ]);

        return redirect()->back();
    }

    public function print(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'request_id' => 'required|exists:print_verification_requests,id',
        ]);

        $verification = PrintRequest::findOrFail($request->request_id);
        $filetoprint = Filetoprint::findOrFail($verification->filetoprint_id);
        
        $pdfPath = storage_path('app/public/' . $filetoprint->filename);
        $exePath = base_path('tools/SumatraPDF.exe');

        if (!file_exists($exePath)) {
            return response()->json(['status' => 'error', 'message' => 'Driver Printer (SumatraPDF) tidak ditemukan.'], 500);
        }

        // 3. Konfigurasi Settingan SumatraPDF
        $settings = [];

        // A. Copies
        $settings[] = $verification->copies . "x";

        // B. Color Mode
        if ($verification->color_mode == 'bw') {
            $settings[] = "monochrome";
        } else {
            $settings[] = "color";
        }

        // C. Paper Size (Ukuran Kertas)
        if (!empty($verification->paper_size)) {
            $settings[] = "paper=" . $verification->paper_size;
        }

        // D. Page Range (Halaman)
        if (!empty($verification->page_range) && $verification->page_range !== 'all') {
            $settings[] = $verification->page_range;
        }

        // Gabungkan setting: "2x,monochrome,paper=A4,1-5"
        $settingsString = implode(',', $settings);

        // 4. Eksekusi Perintah
        $command = "\"{$exePath}\" -print-to-default -print-settings \"{$settingsString}\" -silent \"{$pdfPath}\"";

        try {
            shell_exec($command);
            // status transaksinya dijadiin completed agar kalau print lagi ya harus bayar lagi
            $trx = PrintRequest::find($request->request_id);
            if ($trx) {
                $trx->update(['status' => 'completed']);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Perintah cetak terkirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal print: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Filetoprint $filetoprint)
    {
        if ($filetoprint->filename) {
            Storage::disk('public')->delete($filetoprint->filename);
        }

        $filetoprint->delete();

        return redirect()->back();
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:filetoprints,id'
        ]);

        $filetoprints = Filetoprint::whereIn('id', $request->file_ids)->get();
        $count = $filetoprints->count();

        foreach ($filetoprints as $filetoprint) {
            if ($filetoprint->filename) {
                Storage::disk('public')->delete($filetoprint->filename);
            }
            $filetoprint->delete();
        }

        return redirect()->back()->with('success', $count . ' file berhasil dihapus.');
    }
}
