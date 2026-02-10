<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printfile;
use App\Events\FileUploaded;

class PrintController extends Controller
{
    public function uploadPage($station_id)
    {
        $station = \App\Models\User::find($station_id);
        if (!$station) abort(404, 'Station tidak ditemukan');
        return view('user-upload', ['station' => $station]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:users,id',
            'file'   => 'required|array|min:1',
            'file.*' => 'file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
        ], [
            'file.*.max' => 'Ukuran file melebihi 10MB',
            'file.*.mimes' => 'Format file tidak didukung. Gunakan PDF, PNG, JPG, atau JPEG',
            'file.required' => 'Silakan pilih file untuk diunggah',
        ]);

        foreach ($request->file('file') as $uploadedFile) {
            $path = $uploadedFile->store('uploads', 'public');

            Printfile::create([
                'filename'      => $path,
                'original_name' => $uploadedFile->getClientOriginalName(),
                'station_id' => $request->station_id
            ]);
        }

        event(new FileUploaded($request->station_id));

        return back()->with('success', 'Thanks for uploading your files!');
    }
}
