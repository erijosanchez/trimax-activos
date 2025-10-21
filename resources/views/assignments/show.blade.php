@extends('layouts.app')

@section('content')
    <h1>Detalle de Asignación</h1>

    <h2>Información del Activo</h2>
    <table>
        <tr>
            <th>Código:</th>
            <td>{{ $assignment->asset->code }}</td>
        </tr>
        <tr>
            <th>Categoría:</th>
            <td>{{ $assignment->asset->category->name }}</td>
        </tr>
        <tr>
            <th>Activo:</th>
            <td>{{ $assignment->asset->brand }} {{ $assignment->asset->model }}</td>
        </tr>
        <tr>
            <th>Serie:</th>
            <td>{{ $assignment->asset->serial_number }}</td>
        </tr>
    </table>

    <h2>Información del Empleado</h2>
    <table>
        <tr>
            <th>DNI:</th>
            <td>{{ $assignment->employee->dni }}</td>
        </tr>
        <tr>
            <th>Nombre:</th>
            <td>{{ $assignment->employee->full_name }}</td>
        </tr>
        <tr>
            <th>Departamento:</th>
            <td>{{ $assignment->employee->department }}</td>
        </tr>
        <tr>
            <th>Cargo:</th>
            <td>{{ $assignment->employee->position }}</td>
        </tr>
    </table>

    <h2>Detalles de la Asignación</h2>
    <table>
        <tr>
            <th>Fecha de Entrega:</th>
            <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Fecha de Devolución:</th>
            <td>{{ $assignment->returned_date?->format('d/m/Y') ?? 'En uso' }}</td>
        </tr>
        <tr>
            <th>Días de Uso:</th>
            <td>{{ $assignment->usage_days }} días</td>
        </tr>
        <tr>
            <th>Condición al Entregar:</th>
            <td>{{ $assignment->condition_on_assignment }}</td>
        </tr>
        <tr>
            <th>Condición al Devolver:</th>
            <td>{{ $assignment->condition_on_return ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Estado:</th>
            <td>{{ $assignment->is_active ? 'Activo' : 'Finalizado' }}</td>
        </tr>
        <tr>
            <th>Observaciones Entrega:</th>
            <td>{{ $assignment->assignment_observations }}</td>
        </tr>
        <tr>
            <th>Observaciones Devolución:</th>
            <td>{{ $assignment->return_observations }}</td>
        </tr>
    </table>

    @if ($assignment->responsibilityDocument)
        <h2>Documento de Responsabilidad</h2>
        <table>
            <tr>
                <th>Número:</th>
                <td>{{ $assignment->responsibilityDocument->document_number }}</td>
            </tr>
            <tr>
                <th>Fecha Firma:</th>
                <td>{{ $assignment->responsibilityDocument->signed_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Notas:</th>
                <td>{{ $assignment->responsibilityDocument->notes }}</td>
            </tr>
            <tr>
                <th>Documento:</th>
                <td><a href="{{ Storage::url($assignment->responsibilityDocument->document_path) }}" target="_blank"
                        class="btn">Ver PDF</a></td>
            </tr>
        </table>
    @endif

    @if ($assignment->is_active)
        <h2>Registrar Devolución</h2>
        <form action="{{ route('assignments.return', $assignment) }}" method="POST">
            @csrf

            <label>Fecha de Devolución:</label>
            <input type="date" name="returned_date" value="{{ date('Y-m-d') }}" required>

            <label>Condición al Devolver:</label>
            <select name="condition_on_return" required>
                <option value="good">Bueno</option>
                <option value="fair">Regular</option>
                <option value="poor">Malo</option>
                <option value="damaged">Dañado</option>
            </select>

            <label>Observaciones de Devolución:</label>
            <textarea name="return_observations" rows="3"></textarea>

            <button type="submit" class="btn btn-warning">Registrar Devolución</button>
        </form>
    @endif

    <br>
    <a href="{{ route('assignments.index') }}" class="btn">Volver</a>
@endsection
