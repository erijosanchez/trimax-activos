@extends('layouts.app')

@section('title', 'Detalle del Empleado')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Empleados</a></li>
    <li class="breadcrumb-item active">{{ $employee->full_name }}</li>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title">Detalle del Empleado</h1>
                <p class="page-subtitle">Información completa de {{ $employee->full_name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- INFORMACIÓN GENERAL --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th width="40%">DNI:</th>
                                <td><span class="fw-bold">{{ $employee->dni }}</span></td>
                            </tr>
                            <tr>
                                <th>Nombre Completo:</th>
                                <td class="fw-medium">{{ $employee->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>
                                    <a href="mailto:{{ $employee->email }}" class="text-primary">
                                        {{ $employee->email }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Teléfono:</th>
                                <td>
                                    @if ($employee->phone)
                                        <a href="tel:{{ $employee->phone }}" class="text-primary">
                                            {{ $employee->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">No registrado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Departamento:</th>
                                <td>
                                    @if ($employee->department)
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                            {{ $employee->department }}
                                        </span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Cargo:</th>
                                <td>
                                    @if ($employee->position)
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                            {{ $employee->position }}
                                        </span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    @if ($employee->active)
                                        <span class="badge" style="background: #d1fae5; color: #065f46;">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge" style="background: #fee2e2; color: #991b1b;">
                                            <i class="fas fa-times-circle me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Activos Asignados:</th>
                                <td>
                                    <span class="badge" style="background: #fef3c7; color: #92400e;">
                                        <i class="fas fa-boxes me-1"></i>{{ $employee->activeAssignments->count() }}
                                        activos
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ESTADÍSTICAS --}}
        <div class="col-12 col-lg-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="stat-icon mx-auto mb-3"
                                style="background: #dbeafe; color: #2563eb; width: 56px; height: 56px;">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <h3 class="mb-1">{{ $employee->activeAssignments->count() }}</h3>
                            <p class="text-muted mb-0">Activos Actuales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="stat-icon mx-auto mb-3"
                                style="background: #d1fae5; color: #10b981; width: 56px; height: 56px;">
                                <i class="fas fa-history"></i>
                            </div>
                            <h3 class="mb-1">{{ $employee->assignments->count() }}</h3>
                            <p class="text-muted mb-0">Total Asignaciones</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="stat-icon mx-auto mb-3"
                                style="background: #fef3c7; color: #f59e0b; width: 56px; height: 56px;">
                                <i class="fas fa-undo"></i>
                            </div>
                            <h3 class="mb-1">{{ $employee->assignments()->where('is_active', false)->count() }}</h3>
                            <p class="text-muted mb-0">Devoluciones</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información adicional en card --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Resumen</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>{{ $employee->full_name }}</strong> es un empleado
                        <span class="badge {{ $employee->active ? 'bg-success' : 'bg-danger' }}">
                            {{ $employee->active ? 'activo' : 'inactivo' }}
                        </span>
                        @if ($employee->department)
                            del departamento de <strong>{{ $employee->department }}</strong>
                        @endif
                        @if ($employee->position)
                            con cargo de <strong>{{ $employee->position }}</strong>
                        @endif.
                    </p>
                    <p class="mb-0 text-muted">
                        Actualmente tiene <strong>{{ $employee->activeAssignments->count() }}</strong>
                        {{ $employee->activeAssignments->count() == 1 ? 'activo asignado' : 'activos asignados' }}
                        bajo su responsabilidad.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIVOS ASIGNADOS ACTUALMENTE --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-box-open me-2"></i>Activos Asignados Actualmente</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                @if ($employee->activeAssignments->count() > 0)
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th class="border-0">Código</th>
                                <th class="border-0">Activo</th>
                                <th class="border-0">Categoría</th>
                                <th class="border-0">Fecha Asignación</th>
                                <th class="border-0">Días de Uso</th>
                                <th class="border-0 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->activeAssignments as $assignment)
                                <tr>
                                    <td class="fw-bold">{{ $assignment->asset->code }}</td>
                                    <td>
                                        <div>
                                            <div class="fw-medium">{{ $assignment->asset->brand }}
                                                {{ $assignment->asset->model }}</div>
                                            <small
                                                class="text-muted">{{ $assignment->asset->serial_number ?? 'S/N no disponible' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                            {{ $assignment->asset->category->name }}
                                        </span>
                                    </td>
                                    <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge" style="background: #d1fae5; color: #065f46;">
                                            {{ $assignment->usage_days }} días
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('assignments.show', $assignment) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <p class="mb-0">Este empleado no tiene activos asignados actualmente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- HISTORIAL DE ASIGNACIONES --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Asignaciones</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                @php
                    $inactiveAssignments = $employee
                        ->assignments()
                        ->where('is_active', false)
                        ->orderBy('returned_date', 'desc')
                        ->get();
                @endphp
                @if ($inactiveAssignments->count() > 0)
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th class="border-0">Código</th>
                                <th class="border-0">Activo</th>
                                <th class="border-0">Fecha Entrega</th>
                                <th class="border-0">Fecha Devolución</th>
                                <th class="border-0">Días de Uso</th>
                                <th class="border-0">Condición Devolución</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inactiveAssignments as $assignment)
                                <tr>
                                    <td class="fw-bold">{{ $assignment->asset->code }}</td>
                                    <td>
                                        <div>
                                            <div class="fw-medium">{{ $assignment->asset->brand }}
                                                {{ $assignment->asset->model }}</div>
                                            <small class="text-muted">{{ $assignment->asset->category->name }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($assignment->returned_date)
                                            {{ $assignment->returned_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #fef3c7; color: #92400e;">
                                            {{ $assignment->usage_days }} días
                                        </span>
                                    </td>
                                    <td>
                                        @if ($assignment->condition_on_return)
                                            <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                                {{ $assignment->condition_on_return }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <p class="mb-0">No hay historial de asignaciones devueltas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Botón volver -->
    <div class="mb-4">
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver al listado
        </a>
    </div>
@endsection
