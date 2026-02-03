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
        'file.*' => 'file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
    ]);

    foreach ($request->file('file') as $uploadedFile) {
        $path = $uploadedFile->store('uploads', 'public');

        Printfile::create([
            'filename'      => $path,
            'original_name' => $uploadedFile->getClientOriginalName(),
        ]);
    }

    return back()->with('success', 'Thanks for uploading your files!');
}



}
