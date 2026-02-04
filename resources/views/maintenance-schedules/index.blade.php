@extends('layouts.app')

@section('title', 'Programación de Mantenimientos')

@section('breadcrumb')
    <li class="breadcrumb-item">Mantenimiento</li>
    <li class="breadcrumb-item active">Programación</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Programación de Mantenimientos</h1>
        <p class="page-subtitle">Configura la frecuencia de mantenimiento para cada activo</p>
    </div>

    <!-- Schedules Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Programaciones Activas</h5>
            <a href="{{ route('maintenance-schedules.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-2"></i>Nueva Programación
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="border-0">Código</th>
                            <th class="border-0">Activo</th>
                            <th class="border-0">Frecuencia</th>
                            <th class="border-0">Último Mantenimiento</th>
                            <th class="border-0">Próximo Mantenimiento</th>
                            <th class="border-0">Días Restantes</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr>
                                <td class="fw-bold">{{ $schedule->asset->code }}</td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $schedule->asset->brand }} {{ $schedule->asset->model }}
                                        </div>
                                        <small class="text-muted">{{ $schedule->asset->category->name }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                        {{ $schedule->frequency_name }}
                                    </span>
                                </td>
                                <td>
                                    @if ($schedule->last_maintenance_date)
                                        {{ $schedule->last_maintenance_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Sin registro</span>
                                    @endif
                                </td>
                                <td>{{ $schedule->next_maintenance_date->format('d/m/Y') }}</td>
                                <td>
                                    @if ($schedule->days_until_maintenance < 0)
                                        <span class="badge bg-danger">
                                            Vencido ({{ abs($schedule->days_until_maintenance) }} días)
                                        </span>
                                    @elseif($schedule->days_until_maintenance <= 7)
                                        <span class="badge bg-warning">
                                            {{ $schedule->days_until_maintenance }} días
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ $schedule->days_until_maintenance }} días
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($schedule->is_active)
                                        <span class="badge" style="background: #d1fae5; color: #065f46;">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge" style="background: #f3f4f6; color: #374151;">
                                            <i class="fas fa-times-circle me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('maintenance-schedules.show', $schedule) }}"
                                        class="btn btn-sm btn-outline-primary" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('maintenance-schedules.edit', $schedule) }}"
                                        class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No hay programaciones de mantenimiento</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($schedules->hasPages())
            <div class="card-footer">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
@endsection
