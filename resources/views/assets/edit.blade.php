@extends('layouts.app')

@section('content')
<h1>Editar Activo</h1>

<form action="{{ route('assets.update', $asset) }}" method="POST">
    @csrf
    @method('PUT')
    
    <label>Categoría:</label>
    <select name="category_id" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $asset->category_id == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <label>Código:</label>
    <input type="text" name="code" value="{{ $asset->code }}" required>

    <label>Marca:</label>
    <input type="text" name="brand" value="{{ $asset->brand }}" required>

    <label>Modelo:</label>
    <input type="text" name="model" value="{{ $asset->model }}" required>

    <label>Número de Serie:</label>
    <input type="text" name="serial_number" value="{{ $asset->serial_number }}">

    <label>Especificaciones:</label>
    <textarea name="specifications" rows="3">{{ $asset->specifications }}</textarea>

    <label>Fecha de Compra:</label>
    <input type="date" name="purchase_date" value="{{ $asset->purchase_date?->format('Y-m-d') }}">

    <label>Precio de Compra:</label>
    <input type="number" step="0.01" name="purchase_price" value="{{ $asset->purchase_price }}">

    <label>Estado:</label>
    <select name="status" required>
        <option value="available" {{ $asset->status == 'available' ? 'selected' : '' }}>Disponible</option>
        <option value="assigned" {{ $asset->status == 'assigned' ? 'selected' : '' }}>Asignado</option>
        <option value="maintenance" {{ $asset->status == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
        <option value="damaged" {{ $asset->status == 'damaged' ? 'selected' : '' }}>Dañado</option>
        <option value="retired" {{ $asset->status == 'retired' ? 'selected' : '' }}>Retirado</option>
    </select>

    <label>Observaciones:</label>
    <textarea name="observations" rows="3">{{ $asset->observations }}</textarea>

    <button type="submit" class="btn">Actualizar</button>
    <a href="{{ route('assets.show', $asset) }}">Cancelar</a>
</form>
@endsection