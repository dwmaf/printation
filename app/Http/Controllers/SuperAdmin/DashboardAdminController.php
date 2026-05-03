<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardAdminController extends Controller
{

    public function index()
    {
        return view('admin.dashboard-admin');
    }

    // buat ke halaman daftar outlet
    public function indexOutlet()
    {}

    public function createOutlet(){}
    public function storeOutlet(){}
    public function editOutlet(){}
    public function updateOutlet(){}
    public function destroyOutlet(){}
}
