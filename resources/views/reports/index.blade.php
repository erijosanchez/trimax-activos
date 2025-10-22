@extends('layouts.app')

@section('content')
    <h1>Reportes</h1>

    <h2>Descargar Reportes en Excel</h2>

    <table>
        <tr>
            <th>Reporte</th>
            <th>Descripción</th>
            <th>Acción</th>
        </tr>
        <tr>
            <td>Todos los Activos</td>
            <td>Listado completo de todos los activos del inventario</td>
            <td><a href="{{ route('reports.assets.export') }}" class="btn btn-success">Descargar Excel</a></td>
        </tr>
        <tr>
            <td>Todas las Asignaciones</td>
            <td>Historial completo de todas las asignaciones realizadas</td>
            <td><a href="{{ route('reports.assignments.export') }}" class="btn btn-success">Descargar Excel</a></td>
        </tr>
        <tr>
            <td>Todos los Empleados</td>
            <td>Listado de empleados con cantidad de activos asignados</td>
            <td><a href="{{ route('reports.employees.export') }}" class="btn btn-success">Descargar Excel</a></td>
        </tr>
    </table>

    <h2>Reportes Individuales</h2>
    <p>Para descargar el historial de un activo específico, ve a la página de detalle del activo y haz clic en "Descargar
        Historial Excel"</p>
@endsection
