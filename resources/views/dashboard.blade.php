@extends('layouts.app')

@section('content')
<h1>Dashboard</h1>

<h2>Estadísticas</h2>
<table>
    <tr>
        <th>Total Activos</th>
        <td>{{ $stats['total_assets'] }}</td>
    </tr>
    <tr>
        <th>Activos Disponibles</th>
        <td>{{ $stats['available_assets'] }}</td>
    </tr>
    <tr>
        <th>Activos Asignados</th>
        <td>{{ $stats['assigned_assets'] }}</td>
    </tr>
    <tr>
        <th>Total Empleados</th>
        <td>{{ $stats['total_employees'] }}</td>
    </tr>
    <tr>
        <th>Asignaciones Activas</th>
        <td>{{ $stats['active_assignments'] }}</td>
    </tr>
</table>

<h2>Últimas Asignaciones</h2>
<table>
    <thead>
        <tr>
            <th>Activo</th>
            <th>Empleado</th>
            <th>Fecha</th>
            <th>Días de Uso</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recent_assignments as $assignment)
        <tr>
            <td>{{ $assignment->asset->code }} - {{ $assignment->asset->brand }} {{ $assignment->asset->model }}</td>
            <td>{{ $assignment->employee->full_name }}</td>
            <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
            <td>{{ $assignment->usage_days }} días</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection