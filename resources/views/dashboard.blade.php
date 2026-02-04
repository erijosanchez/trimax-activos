@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Bienvenido, {{ auth()->user()->name }} - Resumen general del sistema</p>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-value">{{ $stats['total_assets'] }}</div>
            <div class="stat-label">Total Activos</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value" style="color: #10b981;">{{ $stats['available_assets'] }}</div>
            <div class="stat-label">Activos Disponibles</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="stat-value" style="color: #f59e0b;">{{ $stats['assigned_assets'] }}</div>
            <div class="stat-label">Activos Asignados</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value" style="color: #2563eb;">{{ $stats['active_assignments'] }}</div>
            <div class="stat-label">Asignaciones Activas</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribución de Activos por Categoría</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estado de Activos</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Assignments Table -->
@if(auth()->user()->canManageInventory())
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Últimas Asignaciones</h5>
        <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-2"></i>Nueva Asignación
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: #f8fafc;">
                    <tr>
                        <th class="border-0">Código</th>
                        <th class="border-0">Activo</th>
                        <th class="border-0">Empleado</th>
                        <th class="border-0">Fecha Asignación</th>
                        <th class="border-0">Días de Uso</th>
                        <th class="border-0 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_assignments as $assignment)
                    <tr>
                        <td class="fw-bold">{{ $assignment->asset->code }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-medium">{{ $assignment->asset->brand }} {{ $assignment->asset->model }}</div>
                                    <small class="text-muted">{{ $assignment->asset->category->name }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $assignment->employee->full_name }}</td>
                        <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge" style="background: #d1fae5; color: #065f46;">
                                {{ $assignment->usage_days }} días
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('assignments.show', $assignment) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">No hay asignaciones recientes</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// Datos de categorías desde el controlador
const categoryData = @json($category_stats);

// Chart de Categorías
const ctxCategory = document.getElementById('categoryChart').getContext('2d');
new Chart(ctxCategory, {
    type: 'bar',
    data: {
        labels: categoryData.map(item => item.name),
        datasets: [{
            label: 'Cantidad de Activos',
            data: categoryData.map(item => item.count),
            backgroundColor: [
                'rgba(37, 99, 235, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(6, 182, 212, 0.8)',
                'rgba(100, 116, 139, 0.8)',
            ],
            borderColor: [
                'rgb(37, 99, 235)',
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
                'rgb(239, 68, 68)',
                'rgb(139, 92, 246)',
                'rgb(236, 72, 153)',
                'rgb(6, 182, 212)',
                'rgb(100, 116, 139)',
            ],
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Chart de Estado
const ctxStatus = document.getElementById('statusChart').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: ['Disponibles', 'Asignados'],
        datasets: [{
            data: [{{ $stats['available_assets'] }}, {{ $stats['assigned_assets'] }}],
            backgroundColor: [
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
            ],
            borderColor: [
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>
@endpush
@endsection
