<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AssetsExport;
use App\Exports\AssignmentsExport;
use App\Exports\EmployeesExport;
use App\Exports\AssignmentHistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function exportAssets()
    {
        return Excel::download(new AssetsExport, 'activos_' . date('Y-m-d') . '.xlsx');
    }

    public function exportAssignments()
    {
        return Excel::download(new AssignmentsExport, 'asignaciones_' . date('Y-m-d') . '.xlsx');
    }

    public function exportEmployees()
    {
        return Excel::download(new EmployeesExport, 'empleados_' . date('Y-m-d') . '.xlsx');
    }

    public function exportAssetHistory($assetId)
    {
        return Excel::download(
            new AssignmentHistoryExport($assetId), 
            'historial_activo_' . $assetId . '_' . date('Y-m-d') . '.xlsx'
        );
    }
}
