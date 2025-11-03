@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Nuevo Asignación</h2>
            </div>
            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <form action="{{ route('assignments.store') }}" method="POST" class="needs-validation form-control p-4"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Activo:</label>
                            <select class="form-select" name="asset_id" required>
                                <option value="">Seleccionar</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}">
                                        {{ $asset->code }} - {{ $asset->category->name }} - {{ $asset->brand }}
                                        {{ $asset->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Empleado:</label>
                            <select class="form-select" name="employee_id" required>
                                <option value="">Seleccionar</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->dni }} - {{ $employee->full_name }} ({{ $employee->department }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Asignación:</label>
                            <input class="form-control" type="date" name="assigned_date" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condición al Entregar:</label>
                            <select class="form-select" name="condition_on_assignment" required>
                                <option value="new">Nuevo</option>
                                <option value="good" selected>Bueno</option>
                                <option value="fair">Regular</option>
                                <option value="poor">Malo</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Observaciones de Entrega:</label>
                            <textarea class="form-control" name="assignment_observations" rows="3"></textarea>
                        </div>

                        <h5 class="text-primary fw-bold mb-3"> Especificaciones del Audifonos</h5>

                        <div class="col-md-6">
                            <label class="form-label">Número de Documento:</label>
                            <input class="form-control" type="text" name="document_number" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Firma:</label>
                            <input class="form-control" type="date" name="signed_date" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Archivo PDF del Documento:</label>
                            <input class="form-control" type="file" name="document_file" accept=".pdf" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Notas del Documento:</label>
                            <textarea class="form-control" name="document_notes" rows="2"></textarea>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Guardar</button>
                            <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
