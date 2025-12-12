@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Nuevo Asignación</h2>
            </div>
            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <form action="{{ route('assignments.store') }}" method="POST" class="needs-validation form-control p-4"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Activo: *</label>
                            <div class="mb-2">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                    <input type="text" class="form-control" id="asset-search-input"
                                        placeholder="Buscar por código (escribe o escanea)..." autofocus>
                                    <button class="btn btn-outline-secondary" type="button" id="clear-asset-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Puedes escanear el código de barras del activo
                                </small>
                            </div>
                            <select class="form-select" name="asset_id" id="asset-select" required>
                                <option value="">Seleccionar activo disponible</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}" data-code="{{ $asset->code }}">
                                        {{ $asset->code }} - {{ $asset->category->name }} - {{ $asset->brand }}
                                        {{ $asset->model }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="asset-not-found" class="alert alert-warning mt-2" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span id="asset-not-found-text"></span>
                            </div>
                            <div id="asset-found" class="alert alert-success mt-2" style="display: none;">
                                <i class="fas fa-check-circle"></i>
                                <span id="asset-found-text"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Empleado:</label>
                            <select class="form-select" name="employee_id" required>
                                <option value="">Seleccionar</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->dni }} - {{ $employee->full_name }} ({{ $employee->department }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Asignación:</label>
                            <input class="form-control" type="date" name="assigned_date" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condición al Entregar:</label>
                            <select class="form-select" name="condition_on_assignment" required>
                                <option value="new">Nuevo</option>
                                <option value="good" selected>Bueno</option>
                                <option value="fair">Regular</option>
                                <option value="poor">Malo</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Observaciones de Entrega:</label>
                            <textarea class="form-control" name="assignment_observations" rows="3"></textarea>
                        </div>

                        <h5 class="text-primary fw-bold mb-3">📄 Documento de Responsabilidad (Opcional)</h5>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nuevo flujo:</strong> Puedes crear la asignación ahora y subir el documento después de
                            firmarlo.
                            <br>El sistema generará automáticamente el acta con todos los datos.
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Número de Documento:</label>
                            <input class="form-control" type="text" name="document_number"
                                placeholder="Ej: ACTA-2024-001">
                            <small class="text-muted">Puedes dejarlo vacío y subirlo después</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Firma:</label>
                            <input class="form-control" type="date" name="signed_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Archivo PDF del Documento:</label>
                            <input class="form-control" type="file" name="document_file" accept=".pdf">
                            <small class="text-muted">Opcional - Sube el documento firmado cuando lo tengas</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Notas del Documento:</label>
                            <textarea class="form-control" name="document_notes" rows="2"></textarea>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i>
                                Guardar</button>
                            <a href="{{ route('assignments.index') }}"
                                class="btn btn-outline-secondary px-4">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const assetSearchInput = document.getElementById('asset-search-input');
            const clearAssetSearchBtn = document.getElementById('clear-asset-search');
            const assetSelect = document.getElementById('asset-select');
            const assetNotFound = document.getElementById('asset-not-found');
            const assetNotFoundText = document.getElementById('asset-not-found-text');
            const assetFound = document.getElementById('asset-found');
            const assetFoundText = document.getElementById('asset-found-text');

            // Variables para detección de escaneo
            let scanBuffer = '';
            let scanTimeout;
            const SCAN_SPEED_THRESHOLD = 50; // ms entre caracteres
            let lastKeyTime = Date.now();

            // Función para buscar activo por código
            function searchAssetByCode(code) {
                code = code.toUpperCase().trim();

                // Ocultar mensajes previos
                assetNotFound.style.display = 'none';
                assetFound.style.display = 'none';

                if (code === '') {
                    assetSelect.value = '';
                    return;
                }

                // Buscar en las opciones del select
                let found = false;
                const options = assetSelect.querySelectorAll('option');

                for (let option of options) {
                    const optionCode = option.dataset.code;
                    if (optionCode && optionCode.toUpperCase() === code) {
                        assetSelect.value = option.value;
                        found = true;

                        // Mostrar mensaje de éxito
                        assetFoundText.textContent = `Activo encontrado: ${option.textContent.trim()}`;
                        assetFound.style.display = 'block';

                        // Resaltar el select
                        assetSelect.style.borderColor = '#28a745';
                        assetSelect.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
                        setTimeout(() => {
                            assetSelect.style.borderColor = '';
                            assetSelect.style.boxShadow = '';
                        }, 2000);

                        break;
                    }
                }

                if (!found) {
                    assetSelect.value = '';
                    assetNotFoundText.textContent =
                        `No se encontró el activo con código: ${code}. Verifica que esté disponible.`;
                    assetNotFound.style.display = 'block';

                    // Limpiar mensaje después de 5 segundos
                    setTimeout(() => {
                        assetNotFound.style.display = 'none';
                    }, 5000);
                }
            }

            // Detectar escaneo rápido
            assetSearchInput.addEventListener('keypress', function(e) {
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
                    const code = this.value.trim();
                    if (code) {
                        searchAssetByCode(code);
                    }
                }
            });

            // Búsqueda en tiempo real
            let searchTimeout2;
            assetSearchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout2);
                searchTimeout2 = setTimeout(() => {
                    searchAssetByCode(this.value);
                }, 300);
            });

            // Botón limpiar
            clearAssetSearchBtn.addEventListener('click', function() {
                assetSearchInput.value = '';
                assetSelect.value = '';
                assetNotFound.style.display = 'none';
                assetFound.style.display = 'none';
                assetSearchInput.focus();
            });

            // Limpiar con ESC
            assetSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    assetSelect.value = '';
                    assetNotFound.style.display = 'none';
                    assetFound.style.display = 'none';
                }
            });

            // Sincronizar cuando se selecciona del select manualmente
            assetSelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    const code = selectedOption.dataset.code;
                    if (code) {
                        assetSearchInput.value = code;
                        assetNotFound.style.display = 'none';
                        assetFound.style.display = 'none';
                    }
                }
            });
        });
    </script>
@endsection
