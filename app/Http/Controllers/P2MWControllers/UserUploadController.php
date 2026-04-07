<?php

namespace App\Http\Controllers\P2MWControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Printfile;
use App\Models\User;
use App\Events\FileUploaded;
use Inertia\Inertia;

class UserUploadController extends Controller
{
    public function index($id)
    {
        $station = User::findOrFail($id);
        return Inertia::render('P2MW/UserUpload/UserUpload', [
            'station' => [
                'id' => $station->id,
                'name' => $station->name
            ]
        ]);
    }

    public function store(Request $request, $id)
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

        return redirect()->back()->with('success', 'Thanks for uploading your files!');
    }
}
