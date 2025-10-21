@extends('layouts.app')

@section('content')
<h1>Nueva Asignación</h1>

<form action="{{ route('assignments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <label>Activo:</label>
    <select name="asset_id" required>
        <option value="">Seleccionar</option>
        @foreach($assets as $asset)
            <option value="{{ $asset->id }}">
                {{ $asset->code }} - {{ $asset->category->name }} - {{ $asset->brand }} {{ $asset->model }}
            </option>
        @endforeach
    </select>

    <label>Empleado:</label>
    <select name="employee_id" required>
        <option value="">Seleccionar</option>
        @foreach($employees as $employee)
            <option value="{{ $employee->id }}">
                {{ $employee->dni }} - {{ $employee->full_name }} ({{ $employee->department }})
            </option>
        @endforeach
    </select>

    <label>Fecha de Asignación:</label>
    <input type="date" name="assigned_date" value="{{ date('Y-m-d') }}" required>

    <label>Condición al Entregar:</label>
    <select name="condition_on_assignment" required>
        <option value="new">Nuevo</option>
        <option value="good" selected>Bueno</option>
        <option value="fair">Regular</option>
        <option value="poor">Malo</option>
    </select>

    <label>Observaciones de Entrega:</label>
    <textarea name="assignment_observations" rows="3"></textarea>

    <h3>Documento de Responsabilidad</h3>

    <label>Número de Documento:</label>
    <input type="text" name="document_number" required>

    <label>Fecha de Firma:</label>
    <input type="date" name="signed_date" value="{{ date('Y-m-d') }}" required>

    <label>Archivo PDF del Documento:</label>
    <input type="file" name="document_file" accept=".pdf" required>

    <label>Notas del Documento:</label>
    <textarea name="document_notes" rows="2"></textarea>

    <button type="submit" class="btn">Guardar Asignación</button>
    <a href="{{ route('assignments.index') }}">Cancelar</a>
</form>
@endsection