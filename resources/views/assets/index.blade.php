@extends('layouts.app')

@section('content')
<h1>Activos</h1>
<a href="{{ route('assets.create') }}" class="btn">Nuevo Activo</a>

<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Categoría</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Serie</th>
            <th>Estado</th>
            <th>Asignado a</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
        <tr>
            <td>{{ $asset->code }}</td>
            <td>{{ $asset->category->name }}</td>
            <td>{{ $asset->brand }}</td>
            <td>{{ $asset->model }}</td>
            <td>{{ $asset->serial_number }}</td>
            <td>{{ $asset->status }}</td>
            <td>{{ $asset->currentAssignment?->employee->full_name ?? '-' }}</td>
            <td>
                <a href="{{ route('assets.show', $asset) }}">Ver</a> |
                <a href="{{ route('assets.edit', $asset) }}">Editar</a> |
                <form action="{{ route('assets.destroy', $asset) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $assets->links() }}
@endsection