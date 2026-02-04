@extends('layouts.app')

@section('title', 'Empleados')

@section('breadcrumb')
    <li class="breadcrumb-item active">Empleados</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Empleados</h1>
        <p class="page-subtitle">Gestión de empleados de la empresa</p>
    </div>

    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employees.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-search me-2"></i>Buscar Empleado
                        </label>
                        <input type="text" name="search" class="form-control" placeholder="Nombre, DNI, email..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="active" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-3 mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Lista de Empleados</h5>
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-2"></i>Nuevo Empleado
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="border-0">Empleado</th>
                            <th class="border-0">DNI</th>
                            <th class="border-0">Email</th>
                            <th class="border-0">Teléfono</th>
                            <th class="border-0">Activos Asignados</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $employee->full_name }}</div>
                                        <small class="text-muted">{{ $employee->position ?: 'Sin cargo' }}</small>
                                    </div>
                                </td>
                                <td>{{ $employee->dni }}</td>
                                <td>{{ $employee->email ?: 'N/A' }}</td>
                                <td>{{ $employee->phone ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                        {{ $employee->assignments()->where('is_active', true)->count() }} activos
                                    </span>
                                </td>
                                <td>
                                    @if ($employee->active)
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
                                    <a href="{{ route('employees.show', $employee) }}"
                                        class="btn btn-sm btn-outline-primary" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}"
                                        class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No hay empleados registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($employees->hasPages())
            <div class="card-footer">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
@endsection
