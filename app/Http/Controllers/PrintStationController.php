<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Printfile;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class PrintStationController extends Controller
{
    public function pdf(Printfile $printfile)
    {
        // pastikan file ada
        if (!Storage::disk('public')->exists($printfile->filename)) {
            abort(404, 'File not found');
        }

        // stream pdf dari storage public supaya pdf.js bisa fetch tanpa CORS
        $fullPath = Storage::disk('public')->path($printfile->filename);

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($printfile->filename) . '"'
        ]);
    }

    public function preview(Printfile $printfile)
    {
        // pakai route pdf (same-origin)
        $fileUrl = route('station.pdf', $printfile->id);

        return view('pdf-preview-swipe', compact('fileUrl'));
    }

    public function index()
    {
        return view('/print-station', [
            'files' => Printfile::latest()->get(),
            'qrCode' => QrCode::size(250)->generate(url('/upload'))
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

    public function getFileInfo(Printfile $printfile)
    {
        // path file di db
        $path = storage_path('app/public/' . $printfile->filename);

        $pageCount = 0;

        // Cek tipe file (hanya hitung jika PDF)
        if (str_contains(strtolower($printfile->filename), '.pdf') && file_exists($path)) {
            try {
                // Pastikan kelas Parser ada (jika dependensi belum terpasang akan memicu Error)
                if (!class_exists(Parser::class)) {
                    throw new \RuntimeException('PDF parser class not found. Run composer install.');
                }

                $parser = new Parser();
                $pdf = $parser->parseFile($path);
                $pageCount = count($pdf->getPages());
            } catch (\Throwable $e) {
                // Log error agar mudah diagnosa, tapi jangan hentikan eksekusi
                \Log::error('PDF parsing failed for file ' . $path . ': ' . $e->getMessage());
                $pageCount = 1; // Default jika gagal baca
            }
        } else {
            $pageCount = 1; // Gambar/Doc dianggap 1 halaman dulu
        }
        // dia ngasih json id filenya, url filenya, jumlah halaman, dan tipe file
        return response()->json([
            'id' => $printfile->id,
            'url' => asset('storage/' . $printfile->filename),
            'pages' => $pageCount,
            'type' => $printfile->type
        ]);
    }
}
