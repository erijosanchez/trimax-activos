@extends('layouts.app')

@section('content')
<h1>Nuevo Empleado</h1>

<form action="{{ route('employees.store') }}" method="POST">
    @csrf
    
    <label>DNI:</label>
    <input type="text" name="dni" required>

    <label>Nombre:</label>
    <input type="text" name="first_name" required>

    <label>Apellido:</label>
    <input type="text" name="last_name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Teléfono:</label>
    <input type="text" name="phone">

    <label>Departamento:</label>
    <input type="text" name="department">

    <label>Cargo:</label>
    <input type="text" name="position">

    <label>Estado:</label>
    <select name="active">
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
    </select>

    <button type="submit" class="btn">Guardar</button>
    <a href="{{ route('employees.index') }}">Cancelar</a>
</form>
@endsection