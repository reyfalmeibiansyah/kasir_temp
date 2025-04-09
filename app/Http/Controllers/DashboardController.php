<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $salesData = [
            'dates' => ['08 February 2025', '09 February 2025', '10 February 2025', '11 February 2025', '12 February 2025', '13 February 2025', '14 February 2025'],
            'sales' => [0, 10, 30, 70, 20, 10, 60]
        ];

        $productData = [
            'labels' => ['TV', 'mesin rumput', 'HP', 'gizi seimbang', 'tws', 'LMS - Jagoscript', 'Botol Minum', 'Buku', 'niki', 'Lockheed Skunk F-22 Raptor'],
            'percentages' => [10, 15, 5, 10, 8, 12, 5, 10, 15, 10]
        ];

        return view('admin.dashboard', compact('salesData', 'productData'));
    }

    public function dashboardpetugas()
    {
        $salesData = [
            'dates' => ['08 February 2025', '09 February 2025', '10 February 2025', '11 February 2025', '12 February 2025', '13 February 2025', '14 February 2025'],
            'sales' => [0, 10, 30, 70, 20, 10, 60]
        ];

        return view('petugas.dashboardpetugas', compact('salesData'));
    }
}