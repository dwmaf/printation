<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Printfile;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintStationController extends Controller
{
    public function index()
{
    $uploadUrl = rtrim(config('app.url'), '/') . '/upload';

    return view('/print-station', [
        'files'  => Printfile::latest()->get(),
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

    public function getFileInfo(Printfile $printfile)
    {
        // path file di db
        $path = storage_path('app/public/' . $printfile->filename);

        $pageCount = 0;

        // Cek tipe file (hanya hitung jika PDF)
        if (str_contains(strtolower($printfile->filename), '.pdf') && file_exists($path)) {
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($path);
                $pageCount = count($pdf->getPages());
            } catch (\Exception $e) {
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
