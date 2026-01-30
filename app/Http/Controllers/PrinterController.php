<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printfile; // Pastikan Model ini benar (sesuai database temanmu)
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
        ]);

        // 2. Ambil File dari Database
        // Pastikan model 'Printfile' benar. Jika temanmu pakai nama lain (misal 'File' atau 'Document'), sesuaikan.
        $file = Printfile::findOrFail($request->id);
        
        // 3. Tentukan Lokasi File & Aplikasi
        $pdfPath = storage_path('app/public/' . $file->filename);
        $exePath = base_path('tools/SumatraPDF.exe'); // Mengarah ke folder tools yang kamu buat

        // Cek apakah aplikasi ada (untuk debugging)
        if (!file_exists($exePath)) {
            return response()->json(['status' => 'error', 'message' => 'Aplikasi SumatraPDF tidak ditemukan di server.'], 500);
        }

        // 4. Racik Settingan
        $settings = [];
        $settings[] = $request->copies . "x";
        
        if ($request->color_mode == 'bw') {
            $settings[] = "monochrome";
        } else {
            $settings[] = "color";
        }

        $settingsString = implode(',', $settings);

        // 5. Perintah CMD
        // -print-to-default : Menggunakan printer default Windows
        $command = "\"{$exePath}\" -print-to-default -print-settings \"{$settingsString}\" -silent \"{$pdfPath}\"";

        // 6. Eksekusi
        try {
            shell_exec($command);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Perintah cetak terkirim ke printer.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal print: ' . $e->getMessage()
            ], 500);
        }
    }
}
