<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Assignment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_assets' => Asset::count(),
            'available_assets' => Asset::where('status', 'available')->count(),
            'assigned_assets' => Asset::where('status', 'assigned')->count(),
            'total_employees' => Employee::where('active', true)->count(),
            'active_assignments' => Assignment::where('is_active', true)->count(),
        ];

        $recent_assignments = Assignment::with(['asset', 'employee'])
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_assignments'));
    }
}
