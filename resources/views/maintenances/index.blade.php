@extends('layouts.app')

@section('title', 'Mantenimientos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mantenimientos</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Mantenimientos</h1>
        <p class="page-subtitle">Gestión de mantenimientos preventivos y correctivos</p>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="programado" {{ request('status') == 'programado' ? 'selected' : '' }}>Programado
                            </option>
                            <option value="en_proceso" {{ request('status') == 'en_proceso' ? 'selected' : '' }}>En Proceso
                            </option>
                            <option value="completado" {{ request('status') == 'completado' ? 'selected' : '' }}>Completado
                            </option>
                            <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" class="form-select">
                            <option value="">Todos</option>
                            <option value="preventivo" {{ request('type') == 'preventivo' ? 'selected' : '' }}>Preventivo
                            </option>
                            <option value="correctivo" {{ request('type') == 'correctivo' ? 'selected' : '' }}>Correctivo
                            </option>
                            <option value="predictivo" {{ request('type') == 'predictivo' ? 'selected' : '' }}>Predictivo
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Desde</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $stats['programado'] }}</div>
                <div class="stat-label">Programados</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-value">{{ $stats['en_proceso'] }}</div>
                <div class="stat-label">En Proceso</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-value">{{ $stats['completados_mes'] }}</div>
                <div class="stat-label">Completados (Mes)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value">{{ $stats['vencidos'] }}</div>
                <div class="stat-label">Vencidos</div>
            </div>
        </div>
    </div>

    <!-- Maintenances Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Lista de Mantenimientos</h5>
            @can('canPerformMaintenance', auth()->user())
                <a href="{{ route('maintenances.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Nuevo Mantenimiento
                </a>
            @endcan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="border-0">Código Activo</th>
                            <th class="border-0">Activo</th>
                            <th class="border-0">Tipo</th>
                            <th class="border-0">Fecha Programada</th>
                            <th class="border-0">Técnico</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenances as $maintenance)
                            <tr class="{{ $maintenance->isOverdue() ? 'table-warning' : '' }}">
                                <td class="fw-bold">{{ $maintenance->asset->code }}</td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $maintenance->asset->brand }}
                                            {{ $maintenance->asset->model }}</div>
                                        <small class="text-muted">{{ $maintenance->asset->category->name }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if ($maintenance->type == 'preventivo')
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">Preventivo</span>
                                    @elseif($maintenance->type == 'correctivo')
                                        <span class="badge" style="background: #fee2e2; color: #991b1b;">Correctivo</span>
                                    @else
                                        <span class="badge" style="background: #fce7f3; color: #9f1239;">Predictivo</span>
                                    @endif
                                </td>
                                <td>{{ $maintenance->scheduled_date->format('d/m/Y') }}</td>
                                <td>{{ $maintenance->technician->name }}</td>
                                <td>
                                    @if ($maintenance->status == 'programado')
                                        <span class="badge" style="background: #fef3c7; color: #92400e;">
                                            <i class="fas fa-clock me-1"></i>Programado
                                        </span>
                                    @elseif($maintenance->status == 'en_proceso')
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                            <i class="fas fa-spinner me-1"></i>En Proceso
                                        </span>
                                    @elseif($maintenance->status == 'completado')
                                        <span class="badge" style="background: #d1fae5; color: #065f46;">
                                            <i class="fas fa-check me-1"></i>Completado
                                        </span>
                                    @else
                                        <span class="badge" style="background: #f3f4f6; color: #374151;">
                                            <i class="fas fa-times me-1"></i>Cancelado
                                        </span>
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
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No hay mantenimientos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($maintenances->hasPages())
            <div class="card-footer">
                {{ $maintenances->links() }}
            </div>
        @endif
    </div>
@endsection
