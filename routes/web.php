<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Http\Middleware\CheckRole;

// Redireccionar a login si no está autenticado
Route::redirect('/', '/login');

// Rutas protegidas con autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - Todos los usuarios autenticados
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestión de Usuarios - Solo Admins
    Route::middleware(CheckRole::class . ':admin')->group(function () {
        Route::resource('users', UserManagementController::class);
        Route::patch('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
            ->name('users.toggle-status');
        Route::patch('users/{user}/update-password', [UserManagementController::class, 'updatePassword'])
            ->name('users.update-password');
    });

    // Activos - Admin, TI, Servicios Generales, Marketing
    Route::middleware(CheckRole::class . ':admin,ti,servicios_generales,marketing')->group(function () {
        Route::resource('asset', AssetController::class);
        Route::get('asset/{asset}/barcode', [AssetController::class, 'generateBarcode'])->name('asset.barcode');
        Route::get('asset/{asset}/barcode/download', [AssetController::class, 'downloadBarcode'])->name('asset.barcode.download');
        Route::post('asset/barcode/download-multiple', [AssetController::class, 'downloadBarcodesPDF'])->name('asset.barcode.download-multiple');
        Route::get('asset-next-code', [AssetController::class, 'getNextCode'])->name('asset.next-code');
    });

    // Empleados - Admin, TI, Servicios Generales, Marketing
    Route::middleware(CheckRole::class . ':admin,ti,servicios_generales,marketing')->group(function () {
        Route::resource('employees', EmployeeController::class);
    });

    // Asignaciones - Admin, TI, Servicios Generales, Marketing
    Route::middleware(CheckRole::class . ':admin,ti,servicios_generales,marketing')->group(function () {
        Route::resource('assignments', AssignmentController::class);
        Route::post('assignments/{assignment}/return', [AssignmentController::class, 'returnAsset'])
            ->name('assignments.return');
        Route::get('assignments/{assignment}/generate-delivery-document', [AssignmentController::class, 'generateDeliveryDocument'])
            ->name('assignments.generate-delivery-document');
        Route::get('assignments/{assignment}/generate-return-document', [AssignmentController::class, 'generateReturnDocument'])
            ->name('assignments.generate-return-document');
        Route::post('assignments/{assignment}/upload-document', [AssignmentController::class, 'uploadDocument'])
            ->name('assignments.upload-document');
    });

    // Reportes - Todos pueden ver, solo admin/ti pueden exportar
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::middleware(CheckRole::class . ':admin,ti')->group(function () {
        Route::get('/reports/assets/export', [ReportController::class, 'exportAssets'])->name('reports.assets.export');
        Route::get('/reports/assignments/export', [ReportController::class, 'exportAssignments'])->name('reports.assignments.export');
        Route::get('/reports/employees/export', [ReportController::class, 'exportEmployees'])->name('reports.employees.export');
        Route::get('/reports/asset/{asset}/history/export', [ReportController::class, 'exportAssetHistory'])->name('reports.asset.history.export');
    });

    // Mantenimiento - Admin y TI pueden gestionar, otros solo ver
    Route::prefix('maintenance')->name('maintenances.')->group(function () {
        // Dashboard de mantenimiento - todos pueden ver
        Route::get('/dashboard', [MaintenanceController::class, 'dashboard'])->name('dashboard');
        
        // Ver mantenimientos - todos pueden ver
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('/{maintenance}', [MaintenanceController::class, 'show'])->name('show');
        
        // Gestionar mantenimientos - solo Admin y TI
        Route::middleware(CheckRole::class . ':admin,ti')->group(function () {
            Route::get('/create/new', [MaintenanceController::class, 'createnew'])->name('create.new');
            Route::post('/', [MaintenanceController::class, 'store'])->name('store');
            Route::get('/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('edit');
            Route::put('/{maintenance}', [MaintenanceController::class, 'update'])->name('update');
            Route::delete('/{maintenance}', [MaintenanceController::class, 'destroy'])->name('destroy');
            Route::post('/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('complete');
        });
        
        // Firmas - técnico o usuario correspondiente
        Route::post('/{maintenance}/sign-technician', [MaintenanceController::class, 'signAsTechnician'])->name('sign-technician');
        Route::post('/{maintenance}/sign-user', [MaintenanceController::class, 'signAsUser'])->name('sign-user');
        
        // Generar documento - todos pueden generar si el mantenimiento está completado
        Route::get('/{maintenance}/document', [MaintenanceController::class, 'generateDocument'])->name('document');
    });

    // Programación de Mantenimiento - Solo Admin y TI
    Route::middleware(CheckRole::class . ':admin,ti')->group(function () {
        Route::resource('maintenance-schedules', MaintenanceScheduleController::class);
        Route::post('maintenance-schedules/{maintenanceSchedule}/reactivate', [MaintenanceScheduleController::class, 'reactivate'])
            ->name('maintenance-schedules.reactivate');
    });
});

require __DIR__ . '/auth.php';
