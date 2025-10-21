@extends('layouts.app')

@section('content')
<h1>Detalle del Activo</h1>

<table>
    <tr><th>Código:</th><td>{{ $asset->code }}</td></tr>
    <tr><th>Categoría:</th><td>{{ $asset->category->name }}</td></tr>
    <tr><th>Marca:</th><td>{{ $asset->brand }}</td></tr>
    <tr><th>Modelo:</th><td>{{ $asset->model }}</td></tr>
    <tr><th>Serie:</th><td>{{ $asset->serial_number }}</td></tr>
    <tr><th>Especificaciones:</th><td>{{ $asset->specifications }}</td></tr>
    <tr><th>Fecha Compra:</th><td>{{ $asset->purchase_date?->format('d/m/Y') }}</td></tr>
    <tr><th>Precio:</th><td>{{ $asset->purchase_price }}</td></tr>
    <tr><th>Estado:</th><td>{{ $asset->status }}</td></tr>
    <tr><th>Observaciones:</th><td>{{ $asset->observations }}</td></tr>
    <tr><th>Días Total Uso:</th><td>{{ $asset->total_usage_days }} días</td></tr>
</table>

<a href="{{ route('assets.edit', $asset) }}" class="btn">Editar</a>
<a href="{{ route('reports.asset.history.export', $asset) }}" class="btn btn-success">Descargar Historial Excel</a>

<h2>Historial de Asignaciones</h2>
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
@endsection