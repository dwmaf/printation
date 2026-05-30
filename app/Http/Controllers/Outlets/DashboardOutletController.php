<?php

namespace App\Http\Controllers\Outlets;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardOutletController extends Controller
{

    public function index()
    {
        return Inertia::render('Outlets/DashboardOutlet', [
            'stats' => [
                'chartData' => [
                    ['month' => 'Jan', 'total' => 50],
                    ['month' => 'Feb', 'total' => 120],
                    ['month' => 'Mar', 'total' => 80],
                ],
                'sheetsThisMonth' => 120,
                'trendPercentage' => '15.5',
                'sheetsAllTime' => 850,
            ]
        ]);
    }
}
