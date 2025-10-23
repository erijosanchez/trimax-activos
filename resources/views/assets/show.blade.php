@extends('layouts.app')

@section('content')
<h1>Detalle del Activo</h1>

<h2>Información General</h2>
<table>
    <tr><th>Código:</th><td>{{ $asset->code }}</td></tr>
    <tr><th>Categoría:</th><td>{{ $asset->category->name }}</td></tr>
    <tr><th>Marca:</th><td>{{ $asset->brand }}</td></tr>
    <tr><th>Modelo:</th><td>{{ $asset->model }}</td></tr>
    <tr><th>Número de Serie:</th><td>{{ $asset->serial_number ?? '-' }}</td></tr>
</table>

@php
    $categoryName = strtolower($asset->category->name);
@endphp

<!-- DETALLES PARA CELULAR -->
@if($categoryName === 'celular')
<h2>Información del Celular</h2>
<table>
    <tr><th>IMEI 1:</th><td>{{ $asset->imei }}</td></tr>
    @if($asset->imei_2)
        <tr><th>IMEI 2:</th><td>{{ $asset->imei_2 }}</td></tr>
    @endif
    <tr><th>Número Telefónico:</th><td>{{ $asset->phone }}</td></tr>
    <tr><th>Operador:</th><td>{{ $asset->operator_name }}</td></tr>
</table>
@endif

<!-- DETALLES PARA PC O LAPTOP -->
@if($categoryName === 'pc' || $categoryName === 'laptop')
<h2>Especificaciones Técnicas</h2>
<table>
    <tr><th>Procesador:</th><td>{{ $asset->processor }}</td></tr>
    <tr><th>Memoria RAM:</th><td>{{ $asset->ram }}</td></tr>
    <tr><th>Almacenamiento:</th><td>{{ $asset->storage }} ({{ $asset->storage_type }})</td></tr>
    @if($asset->graphics_card)
        <tr><th>Tarjeta Gráfica:</th><td>{{ $asset->graphics_card }}</td></tr>
    @endif
    <tr><th>Sistema Operativo:</th><td>{{ $asset->operating_system }}</td></tr>
    @if($categoryName === 'laptop' && $asset->screen_size)
        <tr><th>Tamaño de Pantalla:</th><td>{{ $asset->screen_size }}</td></tr>
    @endif
</table>
@endif

<!-- DETALLES PARA MONITOR -->
@if($categoryName === 'monitor')
<h2>Especificaciones del Monitor</h2>
<table>
    <tr><th>Tamaño:</th><td>{{ $asset->screen_size_monitor }}</td></tr>
    <tr><th>Resolución:</th><td>{{ $asset->resolution }}</td></tr>
    @if($asset->panel_type)
        <tr><th>Tipo de Panel:</th><td>{{ $asset->panel_type }}</td></tr>
    @endif
    @if($asset->refresh_rate)
        <tr><th>Tasa de Refresco:</th><td>{{ $asset->refresh_rate }}</td></tr>
    @endif
</table>
@endif

<!-- DETALLES PARA MOUSE O TECLADO -->
@if($categoryName === 'mouse' || $categoryName === 'teclado')
<h2>Especificaciones</h2>
<table>
    <tr><th>Tipo de Conexión:</th><td>{{ $asset->connection_type }}</td></tr>
    <tr><th>Inalámbrico:</th><td>{{ $asset->is_wireless ? 'Sí' : 'No' }}</td></tr>
</table>
@endif

<!-- DETALLES PARA AUDÍFONOS -->
@if($categoryName === 'audífonos')
<h2>Especificaciones de Audífonos</h2>
<table>
    <tr><th>Tipo de Conexión:</th><td>{{ $asset->connection_type }}</td></tr>
    <tr><th>Inalámbrico:</th><td>{{ $asset->is_wireless ? 'Sí' : 'No' }}</td></tr>
    <tr><th>Tipo:</th><td>{{ $asset->audio_type }}</td></tr>
    <tr><th>Tiene Micrófono:</th><td>{{ $asset->has_microphone ? 'Sí' : 'No' }}</td></tr>
</table>
@endif

<h2>Información Adicional</h2>
<table>
    <tr><th>Fecha de Compra:</th><td>{{ $asset->purchase_date?->format('d/m/Y') ?? '-' }}</td></tr>
    <tr><th>Precio de Compra:</th><td>{{ $asset->purchase_price ? 'S/. ' . number_format($asset->purchase_price, 2) : '-' }}</td></tr>
    <tr><th>Estado:</th><td>{{ $asset->status }}</td></tr>
    <tr><th>Observaciones:</th><td>{{ $asset->observations ?? '-' }}</td></tr>
    <tr><th>Días Total de Uso:</th><td>{{ $asset->total_usage_days }} días</td></tr>
</table>

<a href="{{ route('assets.edit', $asset) }}" class="btn">Editar</a>
<a href="{{ route('reports.asset.history.export', $asset) }}" class="btn btn-success">Descargar Historial Excel</a>

<h2>Historial de Asignaciones</h2>
@if($asset->assignmentHistory->count() > 0)
<table>
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Fecha Entrega</th>
            <th>Fecha Devolución</th>
            <th>Días Uso</th>
            <th>Estado Entrega</th>
            <th>Estado Devolución</th>
            <th>Documento</th>
        </tr>
    </thead>
    <tbody>
        @foreach($asset->assignmentHistory as $assignment)
        <tr>
            <td>{{ $assignment->employee->full_name }}</td>
            <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
            <td>{{ $assignment->returned_date?->format('d/m/Y') ?? 'En uso' }}</td>
            <td>{{ $assignment->usage_days }} días</td>
            <td>{{ $assignment->condition_on_assignment }}</td>
            <td>{{ $assignment->condition_on_return ?? 'N/A' }}</td>
            <td>
                @if($assignment->responsibilityDocument)
                    <a href="{{ Storage::url($assignment->responsibilityDocument->document_path) }}" target="_blank">Ver PDF</a>
                @else
                    -
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No hay historial de asignaciones para este activo.</p>
@endif

<br>
<a href="{{ route('assets.index') }}" class="btn">Volver al listado</a>
@endsection