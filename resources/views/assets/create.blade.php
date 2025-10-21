@extends('layouts.app')

@section('content')
<h1>Nuevo Activo</h1>

<form action="{{ route('assets.store') }}" method="POST">
    @csrf
    
    <label>Categoría:</label>
    <select name="category_id" required>
        <option value="">Seleccionar</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <label>Código:</label>
    <input type="text" name="code" required>

    <label>Marca:</label>
    <input type="text" name="brand" required>

    <label>Modelo:</label>
    <input type="text" name="model" required>

    <label>Número de Serie:</label>
    <input type="text" name="serial_number">

    <label>Especificaciones:</label>
    <textarea name="specifications" rows="3"></textarea>

    <label>Fecha de Compra:</label>
    <input type="date" name="purchase_date">

    <label>Precio de Compra:</label>
    <input type="number" step="0.01" name="purchase_price">

    <label>Estado:</label>
    <select name="status" required>
        <option value="available">Disponible</option>
        <option value="assigned">Asignado</option>
        <option value="maintenance">Mantenimiento</option>
        <option value="damaged">Dañado</option>
        <option value="retired">Retirado</option>
    </select>

    <label>Observaciones:</label>
    <textarea name="observations" rows="3"></textarea>

    <button type="submit" class="btn">Guardar</button>
    <a href="{{ route('assets.index') }}">Cancelar</a>
</form>
@endsection