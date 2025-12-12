@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Detalle del empleado</h2>
            </div>

            <div class="row g-3">
                {{-- INFORMACIÓN GENERAL --}}
                <div class="col-12 col-md-8 col-lg-12">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Informacion General</h5>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tr>
                                        <th>DNI:</th>
                                        <td>{{ $employee->dni }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nombre:</th>
                                        <td>{{ $employee->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $employee->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Teléfono:</th>
                                        <td>{{ $employee->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Area:</th>
                                        <td>{{ $employee->department }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cargo:</th>
                                        <td>{{ $employee->position }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estado:</th>
                                        <td>{{ $employee->active ? 'Activo' : 'Inactivo' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary mt-4 p-2 col-12">Editar</a>
                        </div>
                        
                    </div>
                </div>

                {{-- ACTIVOS ASIGNADOS E HISTORIAL --}}
                <div class="col-12 col-md-8 col-lg-12">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Activos Asignados Actualmente</h5>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Activo</th>
                                            <th>Fecha Asignación</th>
                                            <th>Días de Uso</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employee->activeAssignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->asset->code }}</td>
                                                <td>{{ $assignment->asset->brand }} {{ $assignment->asset->model }}</td>
                                                <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                                <td>{{ $assignment->usage_days }} días</td>
                                                <td><a href="{{ route('assignments.show', $assignment) }}">Ver</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-8 col-lg-12">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Historial de Asignaciones</h5>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Activo</th>
                                            <th>Fecha Entrega</th>
                                            <th>Fecha Devolución</th>
                                            <th>Días Uso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employee->assignments()->where('is_active', false)->get() as $assignment)
                                            <tr>
                                                <td>{{ $assignment->asset->code }} - {{ $assignment->asset->brand }}
                                                    {{ $assignment->asset->model }}</td>
                                                <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                                <td>{{ $assignment->returned_date?->format('d/m/Y') }}</td>
                                                <td>{{ $assignment->usage_days }} días</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
@endsection
