@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Empleados</h2>
            </div>
            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Empleados</h5>
                        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">
                            <i class="fas fa-plus me-2"></i> Nuevo Empleado
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>DNI</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Departamento</th>
                                    <th>Cargo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->dni }}</td>
                                        <td>{{ $employee->full_name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->department }}</td>
                                        <td>{{ $employee->position }}</td>
                                        <td>{{ $employee->active ? 'Activo' : 'Inactivo' }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary btn-action me-1"
                                                href="{{ route('employees.show', $employee) }}"><i
                                                    class="fas fa-eye"></i></a> |
                                            <a href="{{ route('employees.edit', $employee) }}"
                                                class="btn btn-sm btn-outline-warning btn-action"><i
                                                    class="fas fa-edit"></i></a>
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
    {{ $employees->links() }}
@endsection
