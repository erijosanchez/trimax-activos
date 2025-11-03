@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Editar Empleado</h2>
            </div>

            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <form action="{{ route('employees.update', $employee) }}" class="needs-validation form-control p-4"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">DNI:</label>
                            <input class="form-control" type="text" name="dni" value="{{ $employee->dni }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre:</label>
                            <input class="form-control" type="text" name="first_name" value="{{ $employee->first_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido:</label>
                            <input class="form-control" type="text" name="last_name" value="{{ $employee->last_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" >Email:</label>
                            <input class="form-control" type="email" name="email" value="{{ $employee->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono:</label>
                            <input class="form-control" type="text" name="phone" value="{{ $employee->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Departamento:</label>
                            <input class="form-control" type="text" name="department" value="{{ $employee->department }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cargo:</label>
                            <input class="form-control" type="text" name="position" value="{{ $employee->position }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado:</label>
                            <select class="form-select" name="active">
                                <option value="1" {{ $employee->active ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !$employee->active ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Actualizar</button>
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
