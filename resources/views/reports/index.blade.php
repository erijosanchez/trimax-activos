@extends('layouts.app')

@section('title', 'Reportes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reportes</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Reportes y Exportaciones</h1>
        <p class="page-subtitle">Descarga reportes en formato Excel del sistema</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
                    <i class="fas fa-file-excel"></i>
                </div>
                <div class="stat-value">3</div>
                <div class="stat-label">Reportes Globales</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-value">{{ \App\Models\Asset::count() }}</div>
                <div class="stat-label">Total Activos</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-value">{{ \App\Models\Assignment::count() }}</div>
                <div class="stat-label">Total Asignaciones</div>
            </div>
        </div>
    </div>

    <!-- Reportes Globales -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Reportes Globales</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="border-0" width="25%">Reporte</th>
                            <th class="border-0">Descripción</th>
                            <th class="border-0 text-end" width="20%">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-medium">
                                <i class="fas fa-boxes me-2 text-primary"></i>
                                Todos los Activos
                            </td>
                            <td class="text-muted">
                                Listado completo de todos los activos del inventario con detalles técnicos
                            </td>
                            <td class="text-end">
                                <a href="{{ route('reports.assets.export') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel me-2"></i>Descargar Excel
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">
                                <i class="fas fa-handshake me-2 text-warning"></i>
                                Todas las Asignaciones
                            </td>
                            <td class="text-muted">
                                Historial completo de todas las asignaciones realizadas con fechas y estados
                            </td>
                            <td class="text-end">
                                <a href="{{ route('reports.assignments.export') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel me-2"></i>Descargar Excel
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">
                                <i class="fas fa-users me-2 text-info"></i>
                                Todos los Empleados
                            </td>
                            <td class="text-muted">
                                Listado de empleados con información de contacto y cantidad de activos asignados
                            </td>
                            <td class="text-end">
                                <a href="{{ route('reports.employees.export') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel me-2"></i>Descargar Excel
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Reportes Individuales -->
    <div class="card mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
            <h5 class="mb-0" style="color: #1e40af;">
                <i class="fas fa-file-alt me-2"></i>Reportes Individuales
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Reporte de Activo Individual -->
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="stat-icon" style="background: #e0e7ff; color: #3730a3; width: 48px; height: 48px;">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold mb-2">Historial de Activo</h6>
                            <p class="text-muted mb-2">
                                Para descargar el historial completo de un activo específico con todas sus asignaciones:
                            </p>
                            <ol class="small text-muted mb-0">
                                <li>Ve a la página de <a href="{{ route('asset.index') }}" class="text-primary">Lista de
                                        Activos</a></li>
                                <li>Selecciona el activo que deseas consultar</li>
                                <li>Haz clic en el botón "Descargar Historial Excel"</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="stat-icon" style="background: #d1fae5; color: #065f46; width: 48px; height: 48px;">
                                <i class="fas fa-info-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold mb-2">Formato de los Reportes</h6>
                            <p class="text-muted mb-2">
                                Todos los reportes se descargan en formato Excel (.xlsx) e incluyen:
                            </p>
                            <ul class="small text-muted mb-0">
                                <li>Datos completos y actualizados</li>
                                <li>Formato profesional con colores</li>
                                <li>Cabeceras descriptivas</li>
                                <li>Fácil de filtrar y analizar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="alert alert-info">
        <div class="d-flex align-items-start">
            <i class="fas fa-lightbulb fa-2x me-3"></i>
            <div>
                <h6 class="alert-heading mb-2">Consejos para usar los reportes</h6>
                <ul class="mb-0 small">
                    <li>Los reportes se generan con la información actualizada al momento de la descarga</li>
                    <li>Puedes abrir los archivos con Microsoft Excel, Google Sheets o LibreOffice</li>
                    <li>Usa las funciones de filtro para analizar datos específicos</li>
                    <li>Los reportes son útiles para auditorías y control de inventario</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Botón volver -->
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
        </a>
    </div>
@endsection
