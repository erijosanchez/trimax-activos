@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Activos</h2>
            </div>

            <div class="mb-3 d-flex gap-2 ">
                <a href="{{ route('reports.assets.export') }}" class="btn btn-warning animated-entry">Exportar a Excel <i
                        class="fas fa-file-excel"></i> </a>
            </div>
            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de activos</h5>
                        <a href="{{ route('asset.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">
                            <i class="fas fa-plus me-2"></i>Agregar Activo
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
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

                                            @if ($categoryName === 'celular')
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
                                                @if ($asset->is_wireless)
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
                                            @if ($asset->status === 'available')
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
                                            @if ($asset->currentAssignment)
                                                {{ $asset->currentAssignment->employee->full_name }}<br>
                                                <small>Desde:
                                                    {{ $asset->currentAssignment->assigned_date->format('d/m/Y') }}</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary btn-action me-1"
                                                href="{{ route('asset.show', $asset) }}"><i class="fas fa-eye"></i></a> |
                                            <a class="btn btn-sm btn-outline-warning btn-action"
                                                href="{{ route('asset.edit', $asset) }}"><i class="fas fa-edit"></i></a>
                                            @if (!$asset->currentAssignment)
                                                | <form action="{{ route('asset.destroy', $asset) }}" method="POST"
                                                    style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('¿Eliminar este activo?')"
                                                        class="btn btn-sm btn-outline-danger btn-action"><i class="fas fa-trash"></i></button>
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
                    </div>
                </div>
            </div>

            {{ $assets->links() }}

            <style>
                table {
                    font-size: 14px;
                }

                td small {
                    color: #666;
                    font-size: 12px;
                }
            </style>
        </div>
    </main>
@endsection
