@extends('layouts.app')

@section('title', 'Dashboard de Mantenimientos')

@section('breadcrumb')
    <li class="breadcrumb-item">Mantenimiento</li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard de Mantenimientos</h1>
        <p class="page-subtitle">Resumen y control de mantenimientos programados</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $stats['programados'] }}</div>
                <div class="stat-label">Programados</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-value">{{ $stats['en_proceso'] }}</div>
                <div class="stat-label">En Proceso</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $stats['completados_mes'] }}</div>
                <div class="stat-label">Completados (Mes)</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value">{{ $stats['vencidos'] }}</div>
                <div class="stat-label">Vencidos</div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if ($overdueMaintenances->count() > 0)
        <div class="alert alert-danger mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">¡Atención! Tienes {{ $overdueMaintenances->count() }} mantenimientos vencidos</h5>
                    <p class="mb-0">Revisa y programa estos mantenimientos lo antes posible.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Mantenimientos Próximos -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Próximos Mantenimientos
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8fafc;">
                                <tr>
                                    <th class="border-0">Activo</th>
                                    <th class="border-0">Fecha</th>
                                    <th class="border-0">Técnico</th>
                                    <th class="border-0 text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingMaintenances as $maintenance)
                                    <tr>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $maintenance->asset->code }}</div>
                                                <small class="text-muted">{{ $maintenance->asset->brand }}
                                                    {{ $maintenance->asset->model }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background: #fef3c7; color: #92400e;">
                                                {{ $maintenance->scheduled_date->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>{{ $maintenance->technician->name }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('maintenances.show', $maintenance) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No hay mantenimientos próximos</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mantenimientos Vencidos -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Mantenimientos Vencidos
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8fafc;">
                                <tr>
                                    <th class="border-0">Activo</th>
                                    <th class="border-0">Fecha</th>
                                    <th class="border-0">Días Atraso</th>
                                    <th class="border-0 text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overdueMaintenances as $maintenance)
                                    <tr class="table-danger">
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $maintenance->asset->code }}</div>
                                                <small class="text-muted">{{ $maintenance->asset->brand }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $maintenance->scheduled_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-danger">
                                                {{ now()->diffInDays($maintenance->scheduled_date) }} días
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('maintenances.show', $maintenance) }}"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-tools"></i> Atender
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <p class="text-muted mb-0">¡Excelente! No hay mantenimientos vencidos</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activos que Necesitan Programación -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Activos que Necesitan Programación de Mantenimiento
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8fafc;">
                                <tr>
                                    <th class="border-0">Código</th>
                                    <th class="border-0">Activo</th>
                                    <th class="border-0">Categoría</th>
                                    <th class="border-0">Frecuencia</th>
                                    <th class="border-0">Próximo Mantenimiento</th>
                                    <th class="border-0">Estado</th>
                                    <th class="border-0 text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assetsNeedingMaintenance as $schedule)
                                    <tr>
                                        <td class="fw-bold">{{ $schedule->asset->code }}</td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $schedule->asset->brand }}
                                                    {{ $schedule->asset->model }}</div>
                                                <small class="text-muted">S/N:
                                                    {{ $schedule->asset->serial_number ?: 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                                {{ $schedule->asset->category->name }}
                                            </span>
                                        </td>
                                        <td>{{ $schedule->frequency_name }}</td>
                                        <td>{{ $schedule->next_maintenance_date->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($schedule->isOverdue())
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Vencido
                                                </span>
                                            @elseif($schedule->isUpcoming())
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Próximo
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Programado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @can('canPerformMaintenance', auth()->user())
                                                <a href="{{ route('maintenances.create') }}?asset_id={{ $schedule->asset_id }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i>Programar
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                            <p class="text-muted mb-0">Todos los activos tienen su mantenimiento programado
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
