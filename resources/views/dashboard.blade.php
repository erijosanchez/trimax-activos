@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <!-- Encabezado -->
            <div class="mb-4">
                <h2 class="mb-1">Dashboard</h2>
                <p class="text-muted">Resumen general del inventario</p>
            </div>

            <!-- Dashboard Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-card animated-entry" style="animation-delay: 0.1s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2 small">Total Activos</p>
                                    <h3 class="mb-0 fw-bold">{{ $stats['total_assets'] }}</h3>
                                </div>
                                <div class="icon-wrapper icon-primary">
                                    <i class="fas fa-box-open"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-card animated-entry" style="animation-delay: 0.2s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2 small">Activos Disponibles</p>
                                    <h3 class="mb-0 fw-bold text-success">{{ $stats['available_assets'] }}</h3>
                                </div>
                                <div class="icon-wrapper icon-success">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-card animated-entry" style="animation-delay: 0.3s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2 small">Activos Asignados</p>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $stats['assigned_assets'] }}</h3>
                                </div>
                                <div class="icon-wrapper icon-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-card animated-entry" style="animation-delay: 0.4s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2 small">Asignaciones Activas</p>
                                    <h3 class="mb-0 fw-bold text-info">{{ $stats['active_assignments'] }}</h3>
                                </div>
                                <div class="icon-wrapper icon-info">
                                    <i class="fas fa-tags"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Inventario -->
            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Últimas Asignaciones</h5>
                        <button class="btn btn-primary btn-sm mt-2 mt-md-0">
                            <i class="fas fa-plus me-2"></i>Agregar Producto
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Activo</th>
                                    <th>Empleado</th>
                                    <th class="hide-mobile">Fecha</th>
                                    <th>Días de uso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recent_assignments as $assignment)
                                    <tr>
                                        <td><strong>{{ $assignment->asset->code }} - {{ $assignment->asset->brand }}
                                                {{ $assignment->asset->model }}</strong></td>
                                        <td>{{ $assignment->employee->full_name }}</td>
                                        <td class="hide-mobile">{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                        <td><span class="badge bg-success">{{ $assignment->usage_days }} días</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-action me-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning btn-action">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="dashboard-wrapper">
                            <div class="iframe-container" id="dashboardContainer">
                                <div class="loading" id="loading">
                                    <div class="spinner"></div>
                                    <p>Cargando dashboard...</p>
                                </div>
                                <!-- 🔥 REEMPLAZA ESTE LINK CON TU LINK DE POWER BI -->
                                <iframe id="powerbiFrame"
                                    src="https://app.powerbi.com/view?r=eyJrIjoiMWQ1NzdmN2UtODI4NC00YmI2LTgyYjgtMTJiNTU5MWY2NTY1IiwidCI6ImQ0NDVlNWFiLWUxMmUtNDI0OC05ZWY3LTAzMjFjMWQ5MDM0MiJ9"
                                    allowFullScreen="true" onload="hideLoading()">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function closeSidebarMobile() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        }

        // Cerrar sidebar al cambiar tamaño de ventana
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        // Activar link en navegación
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
@endsection
