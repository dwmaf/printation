<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printfile;

class PrintController extends Controller
{
    public function uploadPage()
    {
        return view('user-upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file'   => 'required|array|min:1',
            'file.*' => 'file|mimes:pdf,jpg,png,docx|max:10240',
        ]);

        foreach ($request->file('file') as $uploadedFile) {
            // Simpan file ke storage/app/public/uploads
            $path = $uploadedFile->store('uploads', 'public');

            // Simpan ke database (cuma 2 kolom)
            Printfile::create([
                'filename'      => $path, // simpan full path relatif: uploads/xxxx.pdf
                'original_name' => $uploadedFile->getClientOriginalName(),
            ]);
        }

        return back()->with('success', 'Semua file berhasil dikirim!');
    }
}
