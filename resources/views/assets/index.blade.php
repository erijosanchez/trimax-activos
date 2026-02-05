@extends('layouts.app')

@section('title', 'Activos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Activos</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Activos</h1>
        <p class="page-subtitle">Gestión y control del inventario de activos</p>
    </div>

    <!-- Barra de acciones -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <!-- Buscador con soporte para escaneo -->
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" id="search-input"
                            placeholder="Buscar por código (escribe o escanea)..." autofocus>
                        <button class="btn btn-outline-secondary" type="button" id="clear-search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="d-flex gap-2 flex-wrap justify-content-lg-end">
                        <a href="{{ route('reports.assets.export') }}" class="btn btn-warning">
                            <i class="fas fa-file-excel me-2"></i>Exportar a Excel
                        </a>
                        <button type="button" id="download-barcodes-btn" class="btn btn-success" style="display: none;">
                            <i class="fas fa-barcode me-2"></i>Descargar Códigos Seleccionados
                        </button>
                        <button type="button" id="select-all-btn" class="btn btn-outline-secondary">
                            <i class="fas fa-check-square me-2"></i>Seleccionar Todos
                        </button>
                        <a href="{{ route('asset.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Agregar Activo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje de búsqueda -->
    <div id="search-info" class="alert alert-info mb-3" style="display: none;">
        <i class="fas fa-info-circle me-2"></i>
        <span id="search-info-text"></span>
        <button type="button" class="btn-close float-end"
            onclick="document.getElementById('search-info').style.display='none'"></button>
    </div>

    <!-- Tabla de activos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Activos</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="border-0" width="50">
                                <input type="checkbox" id="select-all-checkbox" class="form-check-input">
                            </th>
                            <th class="border-0">Código</th>
                            <th class="border-0">Categoría</th>
                            <th class="border-0">Marca</th>
                            <th class="border-0">Modelo</th>
                            <th class="border-0">Detalles</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0">Asignado a</th>
                            <th class="border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input asset-checkbox"
                                        value="{{ $asset->id }}" data-code="{{ $asset->code }}">
                                </td>
                                <td class="fw-bold">{{ $asset->code }}</td>
                                <td>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                        {{ $asset->category->name }}
                                    </span>
                                </td>
                                <td>{{ $asset->brand }}</td>
                                <td>{{ $asset->model }}</td>
                                <td>
                                    @php
                                        $categoryName = strtolower($asset->category->name);
                                    @endphp

                                    <small class="text-muted">
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
                                    </small>
                                </td>
                                <td>
                                    @if ($asset->status === 'available')
                                        <span class="badge" style="background: #d1fae5; color: #065f46;">
                                            <i class="fas fa-check-circle me-1"></i>Disponible
                                        </span>
                                    @elseif($asset->status === 'assigned')
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                            <i class="fas fa-user-check me-1"></i>Asignado
                                        </span>
                                    @elseif($asset->status === 'maintenance')
                                        <span class="badge" style="background: #fef3c7; color: #92400e;">
                                            <i class="fas fa-tools me-1"></i>Mantenimiento
                                        </span>
                                    @elseif($asset->status === 'damaged')
                                        <span class="badge" style="background: #fee2e2; color: #991b1b;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Dañado
                                        </span>
                                    @else
                                        <span class="badge" style="background: #f3f4f6; color: #374151;">
                                            <i class="fas fa-times-circle me-1"></i>Retirado
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($asset->currentAssignment)
                                        <div class="fw-medium">{{ $asset->currentAssignment->employee->full_name }}</div>
                                        <small class="text-muted">
                                            Desde: {{ $asset->currentAssignment->assigned_date->format('d/m/Y') }}
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('asset.show', $asset) }}"
                                            title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a class="btn btn-sm btn-outline-success"
                                            href="{{ route('asset.barcode.download', $asset) }}"
                                            title="Descargar código de barras">
                                            <i class="fas fa-barcode"></i>
                                        </a>
                                        <a class="btn btn-sm btn-outline-warning"
                                            href="{{ route('asset.edit', $asset) }}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if (!$asset->currentAssignment)
                                            <form action="{{ route('asset.destroy', $asset) }}" method="POST"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar este activo?')"
                                                    class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    <p class="mb-0">No hay activos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($assets->hasPages())
            <div class="card-footer bg-white">
                {{ $assets->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
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
                const SCAN_SPEED_THRESHOLD = 50;
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

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('asset.barcode.download-multiple') }}';
                    form.style.display = 'none';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

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

                    if (searchTerm !== '') {
                        if (foundAsset) {
                            searchInfo.className = 'alert alert-success mb-3';
                            searchInfoText.textContent = `✓ Activo encontrado: ${searchTerm.toUpperCase()}`;
                            searchInfo.style.display = 'block';

                            foundAsset.style.backgroundColor = '#d4edda';
                            setTimeout(() => {
                                foundAsset.style.backgroundColor = '';
                            }, 3000);

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

                    selectAllCheckbox.checked = false;
                    updateDownloadButton();
                }

                // Detectar entrada rápida (escaneo de código de barras)
                searchInput.addEventListener('keypress', function(e) {
                    const currentTime = Date.now();
                    const timeDiff = currentTime - lastKeyTime;
                    lastKeyTime = currentTime;

                    if (timeDiff < SCAN_SPEED_THRESHOLD) {
                        scanBuffer += e.key;
                    } else {
                        scanBuffer = e.key;
                    }

                    clearTimeout(scanTimeout);
                    scanTimeout = setTimeout(() => {
                        scanBuffer = '';
                    }, 200);

                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const scannedCode = this.value.trim();
                        if (scannedCode) {
                            performSearch(scannedCode);
                        }
                    }
                });

                // Búsqueda en tiempo real
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
    @endpush
@endsection
