@extends('layouts.app')

@section('content')
<h1>Empleados</h1>
<a href="{{ route('employees.create') }}" class="btn">Nuevo Empleado</a>

<table>
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
        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->dni }}</td>
            <td>{{ $employee->full_name }}</td>
            <td>{{ $employee->email }}</td>
            <td>{{ $employee->department }}</td>
            <td>{{ $employee->position }}</td>
            <td>{{ $employee->active ? 'Activo' : 'Inactivo' }}</td>
            <td>
                <a href="{{ route('employees.show', $employee) }}">Ver</a> |
                <a href="{{ route('employees.edit', $employee) }}">Editar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $employees->links() }}
@endsection