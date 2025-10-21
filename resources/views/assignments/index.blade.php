@extends('layouts.app')

@section('content')
    <h1>Asignaciones</h1>
    <a href="{{ route('assignments.create') }}" class="btn">Nueva Asignación</a>

    <table>
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
                    <td>{{ $assignment->asset->code }} - {{ $assignment->asset->brand }} {{ $assignment->asset->model }}
                    </td>
                    <td>{{ $assignment->employee->full_name }}</td>
                    <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                    <td>{{ $assignment->returned_date?->format('d/m/Y') ?? 'En uso' }}</td>
                    <td>{{ $assignment->usage_days }} días</td>
                    <td>{{ $assignment->is_active ? 'Activo' : 'Finalizado' }}</td>
                    <td>
                        <a href="{{ route('assignments.show', $assignment) }}">Ver</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $assignments->links() }}
@endsection
