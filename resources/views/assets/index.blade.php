@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Activos</h2>
            </div>

            <div class="mb-3 d-flex gap-2 align-items-center flex-wrap">
                <!-- Buscador con soporte para escaneo -->
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="search-input"
                        placeholder="Buscar por código (escribe o escanea)..." autofocus>
                    <button class="btn btn-outline-secondary" type="button" id="clear-search">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

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

            <!-- Mensaje de búsqueda -->
            <div id="search-info" class="alert alert-info mb-3" style="display: none;">
                <i class="fas fa-info-circle"></i>
                <span id="search-info-text"></span>
                <button type="button" class="btn-close float-end"
                    onclick="document.getElementById('search-info').style.display='none'"></button>
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
                    const searchInput = document.getElementById('search-input');
                    const clearSearchBtn = document.getElementById('clear-search');
                    const searchInfo = document.getElementById('search-info');
                    const searchInfoText = document.getElementById('search-info-text');

                    // Variables para detección de escaneo
                    let scanBuffer = '';
                    let scanTimeout;
                    const SCAN_SPEED_THRESHOLD = 50; // ms entre caracteres para detectar escaneo
                    let lastKeyTime = Date.now();

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
                        const visibleCheckboxes = Array.from(assetCheckboxes).filter(cb => {
                            return cb.closest('tr').style.display !== 'none';
                        });
                        visibleCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateDownloadButton();
                    });

                    // Botón de seleccionar todos
                    selectAllBtn.addEventListener('click', function() {
                        const visibleCheckboxes = Array.from(assetCheckboxes).filter(cb => {
                            return cb.closest('tr').style.display !== 'none';
                        });
                        const allChecked = visibleCheckboxes.every(cb => cb.checked);
                        visibleCheckboxes.forEach(checkbox => {
                            checkbox.checked = !allChecked;
                        });
                        selectAllCheckbox.checked = !allChecked;
                        updateDownloadButton();
                    });

                    // Checkboxes individuales
                    assetCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            const visibleCheckboxes = Array.from(assetCheckboxes).filter(cb => {
                                return cb.closest('tr').style.display !== 'none';
                            });
                            const allChecked = visibleCheckboxes.every(cb => cb.checked);
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

                    // ========== BUSCADOR CON SOPORTE PARA ESCANEO ==========

                    // Función de búsqueda
                    function performSearch(searchTerm) {
                        searchTerm = searchTerm.toLowerCase().trim();
                        let visibleCount = 0;
                        let foundAsset = null;

                        assetCheckboxes.forEach(checkbox => {
                            const row = checkbox.closest('tr');
                            const code = checkbox.dataset.code.toLowerCase();
                            const rowText = row.textContent.toLowerCase();

                            if (searchTerm === '' || code.includes(searchTerm) || rowText.includes(searchTerm)) {
                                row.style.display = '';
                                visibleCount++;
                                if (code === searchTerm) {
                                    foundAsset = row;
                                }
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        // Mostrar información de búsqueda
                        if (searchTerm !== '') {
                            if (foundAsset) {
                                searchInfo.className = 'alert alert-success mb-3';
                                searchInfoText.textContent = `✓ Activo encontrado: ${searchTerm.toUpperCase()}`;
                                searchInfo.style.display = 'block';

                                // Resaltar el activo encontrado
                                foundAsset.style.backgroundColor = '#d4edda';
                                setTimeout(() => {
                                    foundAsset.style.backgroundColor = '';
                                }, 3000);

                                // Scroll al activo
                                foundAsset.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            } else if (visibleCount > 0) {
                                searchInfo.className = 'alert alert-info mb-3';
                                searchInfoText.textContent =
                                    `Se encontraron ${visibleCount} resultado(s) para: "${searchTerm}"`;
                                searchInfo.style.display = 'block';
                            } else {
                                searchInfo.className = 'alert alert-warning mb-3';
                                searchInfoText.textContent = `No se encontraron resultados para: "${searchTerm}"`;
                                searchInfo.style.display = 'block';
                            }
                        } else {
                            searchInfo.style.display = 'none';
                        }

                        // Actualizar checkbox de seleccionar todos
                        selectAllCheckbox.checked = false;
                        updateDownloadButton();
                    }

                    // Detectar entrada rápida (escaneo de código de barras)
                    searchInput.addEventListener('keypress', function(e) {
                        const currentTime = Date.now();
                        const timeDiff = currentTime - lastKeyTime;
                        lastKeyTime = currentTime;

                        // Si los caracteres llegan muy rápido, es probablemente un escaneo
                        if (timeDiff < SCAN_SPEED_THRESHOLD) {
                            scanBuffer += e.key;
                        } else {
                            scanBuffer = e.key;
                        }

                        // Limpiar el buffer después de un tiempo
                        clearTimeout(scanTimeout);
                        scanTimeout = setTimeout(() => {
                            scanBuffer = '';
                        }, 200);

                        // Si se presiona Enter después de un escaneo rápido
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const scannedCode = this.value.trim();
                            if (scannedCode) {
                                performSearch(scannedCode);
                            }
                        }
                    });

                    // Búsqueda en tiempo real (para escritura manual)
                    let searchTimeout2;
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout2);
                        searchTimeout2 = setTimeout(() => {
                            performSearch(this.value);
                        }, 300);
                    });

                    // Botón limpiar búsqueda
                    clearSearchBtn.addEventListener('click', function() {
                        searchInput.value = '';
                        performSearch('');
                        searchInput.focus();
                    });

                    // Limpiar con ESC
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            this.value = '';
                            performSearch('');
                        }
                    });
                });
            </script>
        </div>
    </main>
@endsection
