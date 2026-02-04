<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Assignment;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

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

        $recent_assignments = Assignment::with(['asset.category', 'employee'])
            ->where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        // Estadísticas por categoría para el gráfico
        $category_stats = Asset::select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category->name,
                    'count' => $item->count
                ];
            });

        return view('dashboard', compact('stats', 'recent_assignments', 'category_stats'));
    }
}

