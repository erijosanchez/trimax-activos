@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Activos</h2>
            </div>

            <div class="mb-3 d-flex gap-2 align-items-center">
                <a href="{{ route('reports.assets.export') }}" class="btn btn-warning animated-entry">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </a>
                <button type="button" id="download-barcodes-btn" class="btn btn-success animated-entry"
                    style="display: none;">
                    <i class="fas fa-barcode"></i> Descargar Códigos de Barras Seleccionados
                </button>
                <button type="button" id="select-all-btn" class="btn btn-outline-secondary animated-entry">
                    <i class="fas fa-check-square"></i> Seleccionar Todos
                </button>
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
                                    <th width="50">
                                        <input type="checkbox" id="select-all-checkbox" class="form-check-input">
                                    </th>
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
                                        <td>
                                            <input type="checkbox" class="form-check-input asset-checkbox"
                                                value="{{ $asset->id }}" data-code="{{ $asset->code }}">
                                        </td>
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
                                                href="{{ route('asset.show', $asset) }}" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a class="btn btn-sm btn-outline-success btn-action me-1"
                                                href="{{ route('asset.barcode.download', $asset) }}"
                                                title="Descargar código de barras">
                                                <i class="fas fa-barcode"></i>
                                            </a>
                                            <a class="btn btn-sm btn-outline-warning btn-action"
                                                href="{{ route('asset.edit', $asset) }}" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if (!$asset->currentAssignment)
                                                <form action="{{ route('asset.destroy', $asset) }}" method="POST"
                                                    style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('¿Eliminar este activo?')"
                                                        class="btn btn-sm btn-outline-danger btn-action" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="text-align: center;">No hay activos registrados</td>
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

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const selectAllCheckbox = document.getElementById('select-all-checkbox');
                    const selectAllBtn = document.getElementById('select-all-btn');
                    const downloadBtn = document.getElementById('download-barcodes-btn');
                    const assetCheckboxes = document.querySelectorAll('.asset-checkbox');

                    // Función para actualizar el estado del botón de descarga
                    function updateDownloadButton() {
                        const checkedBoxes = document.querySelectorAll('.asset-checkbox:checked');
                        if (checkedBoxes.length > 0) {
                            downloadBtn.style.display = 'inline-block';
                        } else {
                            downloadBtn.style.display = 'none';
                        }
                    }

                    // Checkbox de seleccionar todos
                    selectAllCheckbox.addEventListener('change', function() {
                        assetCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateDownloadButton();
                    });

                    // Botón de seleccionar todos
                    selectAllBtn.addEventListener('click', function() {
                        const allChecked = Array.from(assetCheckboxes).every(cb => cb.checked);
                        assetCheckboxes.forEach(checkbox => {
                            checkbox.checked = !allChecked;
                        });
                        selectAllCheckbox.checked = !allChecked;
                        updateDownloadButton();
                    });

                    // Checkboxes individuales
                    assetCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            const allChecked = Array.from(assetCheckboxes).every(cb => cb.checked);
                            selectAllCheckbox.checked = allChecked;
                            updateDownloadButton();
                        });
                    });

                    // Descargar códigos de barras seleccionados
                    downloadBtn.addEventListener('click', function() {
                        const checkedBoxes = document.querySelectorAll('.asset-checkbox:checked');
                        const assetIds = Array.from(checkedBoxes).map(cb => cb.value);

                        if (assetIds.length === 0) {
                            alert('Seleccione al menos un activo');
                            return;
                        }

                        // Crear formulario temporal para enviar la solicitud
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('asset.barcode.download-multiple') }}';
                        form.style.display = 'none';

                        // Token CSRF
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // IDs de activos
                        assetIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'asset_ids[]';
                            input.value = id;
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                        document.body.removeChild(form);
                    });
                });
            </script>
        </div>
    </main>
@endsection
