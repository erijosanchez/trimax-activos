@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Asignaciones</h2>
            </div>
            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de asignaciones</h5>
                        <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">
                            <i class="fas fa-plus me-2"></i>Nueva Asignación
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Activo</th>
                                    <th>Empleado</th>
                                    <th>Fecha Asignación</th>
                                    <th>Fecha Devolución</th>
                                    <th>Días Uso</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->asset->code }} - {{ $assignment->asset->brand }}
                                            {{ $assignment->asset->model }}
                                        </td>
                                        <td>{{ $assignment->employee->full_name }}</td>
                                        <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                        <td>{{ $assignment->returned_date?->format('d/m/Y') ?? 'En uso' }}</td>
                                        <td>{{ $assignment->usage_days }} días</td>
                                        <td>{{ $assignment->is_active ? 'Activo' : 'Finalizado' }}</td>
                                        <td>
                                            <a href="{{ route('assignments.show', $assignment) }}"
                                                class="btn btn-sm btn-outline-primary btn-action me-1"><i
                                                    class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    {{ $assignments->links() }}
@endsection
