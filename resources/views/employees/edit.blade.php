@extends('layouts.app')

@section('content')
<h1>Editar Empleado</h1>

<form action="{{ route('employees.update', $employee) }}" method="POST">
    @csrf
    @method('PUT')
    
    <label>DNI:</label>
    <input type="text" name="dni" value="{{ $employee->dni }}" required>

    <label>Nombre:</label>
    <input type="text" name="first_name" value="{{ $employee->first_name }}" required>

    <label>Apellido:</label>
    <input type="text" name="last_name" value="{{ $employee->last_name }}" required>

    <label>Email:</label>
    <input type="email" name="email" value="{{ $employee->email }}" required>

    <label>Teléfono:</label>
    <input type="text" name="phone" value="{{ $employee->phone }}">

    <label>Departamento:</label>
    <input type="text" name="department" value="{{ $employee->department }}">

    <label>Cargo:</label>
    <input type="text" name="position" value="{{ $employee->position }}">

    <label>Estado:</label>
    <select name="active">
        <option value="1" {{ $employee->active ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ !$employee->active ? 'selected' : '' }}>Inactivo</option>
    </select>

    <button type="submit" class="btn">Actualizar</button>
    <a href="{{ route('employees.show', $employee) }}">Cancelar</a>
</form>
@endsection