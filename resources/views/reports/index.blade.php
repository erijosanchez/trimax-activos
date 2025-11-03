@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Descargar reportes en excel</h2>
            </div>

            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-file-excel me-2"></i> Lista de activos</h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tr>
                                <th>Reporte</th>
                                <th>Descripción</th>
                                <th>Acción</th>
                            </tr>
                            <tr>
                                <td>Todos los Activos</td>
                                <td>Listado completo de todos los activos del inventario</td>
                                <td><a href="{{ route('reports.assets.export') }}" class="btn btn-success"><i
                                            class="fas fa-file-excel me-2"></i> Descargar
                                        Excel</a></td>
                            </tr>
                            <tr>
                                <td>Todas las Asignaciones</td>
                                <td>Historial completo de todas las asignaciones realizadas</td>
                                <td><a href="{{ route('reports.assignments.export') }}" class="btn btn-success"><i
                                            class="fas fa-file-excel me-2"></i> Descargar
                                        Excel</a></td>
                            </tr>
                            <tr>
                                <td>Todos los Empleados</td>
                                <td>Listado de empleados con cantidad de activos asignados</td>
                                <td><a href="{{ route('reports.employees.export') }}" class="btn btn-success"><i
                                            class="fas fa-file-excel me-2"></i> Descargar
                                        Excel</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-file-excel me-2"></i>Reportes individuales</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <p class="p-3">Para descargar el historial de un activo específico, ve a la página de detalle del
                            activo y haz
                            clic en
                            "Descargar
                            Historial Excel"</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
