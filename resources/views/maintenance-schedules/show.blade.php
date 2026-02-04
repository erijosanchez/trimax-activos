@extends('layouts.app')

@section('title', 'Detalle de Programación')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('maintenance-schedules.index') }}">Programación</a></li>
    <li class="breadcrumb-item active">Detalle</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Detalle de Programación</h1>
        <p class="page-subtitle">Información completa del calendario de mantenimiento</p>
    </div>

    <div class="row g-4">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información de la Programación</h5>
                    <div>
                        @if ($maintenanceSchedule->is_active)
                            <span class="badge" style="background: #d1fae5; color: #065f46; font-size: 1rem;">
                                <i class="fas fa-check-circle me-1"></i>Activo
                            </span>
                        @else
                            <span class="badge" style="background: #f3f4f6; color: #374151; font-size: 1rem;">
                                <i class="fas fa-times-circle me-1"></i>Inactivo
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Activo Info -->
                    <div class="mb-4">
                        <label class="text-muted small">Activo</label>
                        <div class="p-3" style="background: #f8fafc; border-radius: 8px;">
                            <div class="fw-bold fs-5">{{ $maintenanceSchedule->asset->code }}</div>
                            <div class="fw-medium">{{ $maintenanceSchedule->asset->brand }}
                                {{ $maintenanceSchedule->asset->model }}</div>
                            <div class="d-flex gap-2 mt-2">
                                <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                    {{ $maintenanceSchedule->asset->category->name }}
                                </span>
                                @if ($maintenanceSchedule->asset->serial_number)
                                    <span class="badge" style="background: #f3f4f6; color: #374151;">
                                        S/N: {{ $maintenanceSchedule->asset->serial_number }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Frecuencia y Fechas -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Frecuencia de Mantenimiento</label>
                            <div class="fs-4 fw-bold" style="color: #2563eb;">
                                <i class="fas fa-sync me-2"></i>{{ $maintenanceSchedule->frequency_name }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Estado Actual</label>
                            <div class="fs-4">
                                @if ($maintenanceSchedule->isOverdue())
                                    <span class="badge bg-danger" style="font-size: 1.2rem;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Vencido
                                    </span>
                                @elseif($maintenanceSchedule->isUpcoming())
                                    <span class="badge bg-warning" style="font-size: 1.2rem;">
                                        <i class="fas fa-clock me-1"></i>Próximo
                                    </span>
                                @else
                                    <span class="badge bg-success" style="font-size: 1.2rem;">
                                        <i class="fas fa-check me-1"></i>Al día
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Timeline -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3" style="background: #f8fafc; border-radius: 8px;">
                                <label class="text-muted small">Último Mantenimiento Realizado</label>
                                @if ($maintenanceSchedule->last_maintenance_date)
                                    <div class="fw-bold fs-5">
                                        {{ $maintenanceSchedule->last_maintenance_date->format('d/m/Y') }}</div>
                                    <small
                                        class="text-muted">{{ $maintenanceSchedule->last_maintenance_date->diffForHumans() }}</small>
                                @else
                                    <div class="text-muted">Sin registro de mantenimiento</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3" style="background: #fef3c7; border-radius: 8px;">
                                <label class="text-muted small">Próximo Mantenimiento Programado</label>
                                <div class="fw-bold fs-5">{{ $maintenanceSchedule->next_maintenance_date->format('d/m/Y') }}
                                </div>
                                @if ($maintenanceSchedule->days_until_maintenance < 0)
                                    <small class="text-danger fw-bold">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Vencido hace {{ abs($maintenanceSchedule->days_until_maintenance) }} días
                                    </small>
                                @else
                                    <small class="text-muted">En {{ $maintenanceSchedule->days_until_maintenance }}
                                        días</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notas -->
                    @if ($maintenanceSchedule->notes)
                        <hr>
                        <div>
                            <label class="text-muted small">Notas Adicionales</label>
                            <div class="p-3" style="background: #f8fafc; border-radius: 8px;">
                                {{ $maintenanceSchedule->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historial de Mantenimientos -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Mantenimientos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8fafc;">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Tipo</th>
                                    <th class="border-0">Fecha</th>
                                    <th class="border-0">Técnico</th>
                                    <th class="border-0">Estado</th>
                                    <th class="border-0 text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenanceSchedule->maintenances as $maintenance)
                                    <tr>
                                        <td>#{{ $maintenance->id }}</td>
                                        <td>
                                            <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                                {{ $maintenance->type_name }}
                                            </span>
                                        </td>
                                        <td>{{ $maintenance->scheduled_date->format('d/m/Y') }}</td>
                                        <td>{{ $maintenance->technician->name }}</td>
                                        <td>
                                            @if ($maintenance->status == 'completado')
                                                <span class="badge bg-success">Completado</span>
                                            @else
                                                <span class="badge bg-warning">{{ $maintenance->status_name }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('maintenances.show', $maintenance) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No hay mantenimientos registrados</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('canPerformMaintenance', auth()->user())
                            <a href="{{ route('maintenances.create') }}?asset_id={{ $maintenanceSchedule->asset_id }}"
                                class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Programar Mantenimiento
                            </a>
                            <a href="{{ route('maintenance-schedules.edit', $maintenanceSchedule) }}"
                                class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Editar Programación
                            </a>
                            @if ($maintenanceSchedule->is_active)
                                <form action="{{ route('maintenance-schedules.destroy', $maintenanceSchedule) }}"
                                    method="POST" onsubmit="return confirm('¿Desactivar esta programación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-ban me-2"></i>Desactivar
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('maintenance-schedules.reactivate', $maintenanceSchedule) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success w-100">
                                        <i class="fas fa-check me-2"></i>Reactivar
                                    </button>
                                </form>
                            @endif
                        @endcan
                        <a href="{{ route('maintenance-schedules.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Total Mantenimientos</span>
                            <span class="fw-bold">{{ $maintenanceSchedule->maintenances->count() }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Completados</span>
                            <span
                                class="fw-bold text-success">{{ $maintenanceSchedule->maintenances->where('status', 'completado')->count() }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ $maintenanceSchedule->maintenances->count() > 0 ? ($maintenanceSchedule->maintenances->where('status', 'completado')->count() / $maintenanceSchedule->maintenances->count()) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Pendientes</span>
                            <span
                                class="fw-bold text-warning">{{ $maintenanceSchedule->maintenances->where('status', 'programado')->count() }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-warning"
                                style="width: {{ $maintenanceSchedule->maintenances->count() > 0 ? ($maintenanceSchedule->maintenances->where('status', 'programado')->count() / $maintenanceSchedule->maintenances->count()) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
