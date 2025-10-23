@extends('layouts.app')

@section('content')
<h1>Activos</h1>
<a href="{{ route('assets.create') }}" class="btn btn-success">Nuevo Activo</a>
<a href="{{ route('reports.assets.export') }}" class="btn btn-warning">Exportar a Excel</a>

<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Categoría</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Detalles</th>
            <th>Estado</th>
            <th>Asignado a</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($assets as $asset)
        <tr>
            <td>{{ $asset->code }}</td>
            <td>{{ $asset->category->name }}</td>
            <td>{{ $asset->brand }}</td>
            <td>{{ $asset->model }}</td>
            <td>
                @php
                    $categoryName = strtolower($asset->category->name);
                @endphp
                
                @if($categoryName === 'celular')
                    IMEI: {{ $asset->imei }}<br>
                    Tel: {{ $asset->phone }}
                @elseif($categoryName === 'pc' || $categoryName === 'laptop')
                    {{ $asset->processor }}<br>
                    RAM: {{ $asset->ram }} | {{ $asset->storage }}
                @elseif($categoryName === 'monitor')
                    {{ $asset->screen_size_monitor }}<br>
                    {{ $asset->resolution }}
                @elseif($categoryName === 'mouse' || $categoryName === 'teclado')
                    {{ $asset->connection_type }}
                    @if($asset->is_wireless)
                        (Inalámbrico)
                    @endif
                @elseif($categoryName === 'audífonos')
                    {{ $asset->audio_type }}<br>
                    {{ $asset->connection_type }}
                @else
                    {{ $asset->serial_number ?? '-' }}
                @endif
            </td>
            <td>
                @if($asset->status === 'available')
                    <span style="color: green;">✓ Disponible</span>
                @elseif($asset->status === 'assigned')
                    <span style="color: blue;">● Asignado</span>
                @elseif($asset->status === 'maintenance')
                    <span style="color: orange;">⚠ Mantenimiento</span>
                @elseif($asset->status === 'damaged')
                    <span style="color: red;">✗ Dañado</span>
                @else
                    <span style="color: gray;">⊗ Retirado</span>
                @endif
            </td>
            <td>
                @if($asset->currentAssignment)
                    {{ $asset->currentAssignment->employee->full_name }}<br>
                    <small>Desde: {{ $asset->currentAssignment->assigned_date->format('d/m/Y') }}</small>
                @else
                    -
                @endif
            </td>
            <td>
                <a href="{{ route('assets.show', $asset) }}">Ver</a> |
                <a href="{{ route('assets.edit', $asset) }}">Editar</a>
                @if(!$asset->currentAssignment)
                    | <form action="{{ route('assets.destroy', $asset) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Eliminar este activo?')" style="background:none;border:none;color:red;cursor:pointer;text-decoration:underline;">Eliminar</button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center;">No hay activos registrados</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $assets->links() }}

<style>
    table { font-size: 14px; }
    td small { color: #666; font-size: 12px; }
</style>
@endsection