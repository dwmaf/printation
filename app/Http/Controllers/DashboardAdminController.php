<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardAdminController extends Controller
{

    public function indexDashboard()
    {
        return view('admin.dashboard-admin');
    }
}
