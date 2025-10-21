<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ReportController;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Activos
Route::resource('assets', AssetController::class);

// Empleados
Route::resource('employees', EmployeeController::class);

// Asignaciones
Route::resource('assignments', AssignmentController::class);
Route::post('assignments/{assignment}/return', [AssignmentController::class, 'returnAsset'])
    ->name('assignments.return');

// Reportes
// Reportes
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/assets/export', [ReportController::class, 'exportAssets'])->name('reports.assets.export');
Route::get('/reports/assignments/export', [ReportController::class, 'exportAssignments'])->name('reports.assignments.export');
Route::get('/reports/employees/export', [ReportController::class, 'exportEmployees'])->name('reports.employees.export');
Route::get('/reports/asset/{asset}/history/export', [ReportController::class, 'exportAssetHistory'])->name('reports.asset.history.export');

require __DIR__.'/auth.php';
