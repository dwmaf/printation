<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printfile;
use Illuminate\Support\Facades\Log;

class PrinterController extends Controller
{
    public function print(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id' => 'required',
            'copies' => 'required|integer|min:1',
            'color_mode' => 'required|in:bw,color',
            'paper_size' => 'nullable|string', // A4, Legal
            'page_range' => 'nullable|string', // Contoh: "1-5"
        ]);

        // 2. Ambil File
        $file = Printfile::findOrFail($request->id);
        $pdfPath = storage_path('app/public/' . $file->filename);
        $exePath = base_path('tools/SumatraPDF.exe');

        if (!file_exists($exePath)) {
            return response()->json(['status' => 'error', 'message' => 'Driver Printer (SumatraPDF) tidak ditemukan.'], 500);
        }

        // 3. Konfigurasi Settingan SumatraPDF
        $settings = [];

        // A. Copies
        $settings[] = $request->copies . "x";

        // B. Color Mode
        if ($request->color_mode == 'bw') {
            $settings[] = "monochrome";
        } else {
            $settings[] = "color";
        }

        // C. Paper Size (Ukuran Kertas)
        // Format Sumatra: "paper=A4"
        if (!empty($request->paper_size)) {
            $settings[] = "paper=" . $request->paper_size;
        }

        // D. Page Range (Halaman)
        // Format Sumatra: "1-5,7"
        if (!empty($request->page_range)) {
            $settings[] = $request->page_range;
        }

        // Gabungkan setting: "2x,monochrome,paper=A4,1-5"
        $settingsString = implode(',', $settings);

        // 4. Eksekusi Perintah
        $command = "\"{$exePath}\" -print-to-default -print-settings \"{$settingsString}\" -silent \"{$pdfPath}\"";

        try {
            shell_exec($command);
            
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
}