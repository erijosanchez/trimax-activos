@extends('layouts.app')

@section('content')
<h1>Detalle del Empleado</h1>

<table>
    <tr><th>DNI:</th><td>{{ $employee->dni }}</td></tr>
    <tr><th>Nombre:</th><td>{{ $employee->full_name }}</td></tr>
    <tr><th>Email:</th><td>{{ $employee->email }}</td></tr>
    <tr><th>Teléfono:</th><td>{{ $employee->phone }}</td></tr>
    <tr><th>Departamento:</th><td>{{ $employee->department }}</td></tr>
    <tr><th>Cargo:</th><td>{{ $employee->position }}</td></tr>
    <tr><th>Estado:</th><td>{{ $employee->active ? 'Activo' : 'Inactivo' }}</td></tr>
</table>

<a href="{{ route('employees.edit', $employee) }}" class="btn">Editar</a>

<h2>Activos Asignados Actualmente</h2>
<table>
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
        @foreach($employee->activeAssignments as $assignment)
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

<h2>Historial de Asignaciones</h2>
<table>
    <thead>
        <tr>
            <th>Activo</th>
            <th>Fecha Entrega</th>
            <th>Fecha Devolución</th>
            <th>Días Uso</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employee->assignments()->where('is_active', false)->get() as $assignment)
        <tr>
            <td>{{ $assignment->asset->code }} - {{ $assignment->asset->brand }} {{ $assignment->asset->model }}</td>
            <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
            <td>{{ $assignment->returned_date?->format('d/m/Y') }}</td>
            <td>{{ $assignment->usage_days }} días</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection