<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Printfile;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return view('/admin/dashboard');
    }
}
