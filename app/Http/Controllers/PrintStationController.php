<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Printfile;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintStationController extends Controller
{
    public function index()
    {
        return view('/print-station', [
            'files' => Printfile::latest()->get(),
            'qrCode' => QrCode::size(250)->generate(url('/upload'))
        ]);
    }
}
