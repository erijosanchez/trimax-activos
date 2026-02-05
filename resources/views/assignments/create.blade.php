@extends('layouts.app')

@section('title', 'Nueva Asignación')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}">Asignaciones</a></li>
    <li class="breadcrumb-item active">Nueva Asignación</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Nueva Asignación</h1>
        <p class="page-subtitle">Registra la asignación de un activo a un empleado</p>
    </div>

    {{-- MENSAJES DE ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>¡Error!</strong> Por favor corrige los siguientes errores:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('assignments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Selección de Activo y Empleado -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-handshake me-2"></i>Información de la Asignación</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Búsqueda de Activo -->
                    <div class="col-md-6">
                        <label class="form-label">Activo <span class="text-danger">*</span></label>

                        <!-- Buscador con escaneo -->
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-barcode text-muted"></i>
                            </span>
                            <input type="text" class="form-control" id="asset-search-input"
                                placeholder="Buscar por código (escribe o escanea)..." autofocus>
                            <button class="btn btn-outline-secondary" type="button" id="clear-asset-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mb-2">
                            <i class="fas fa-info-circle me-1"></i>Puedes escanear el código de barras del activo
                        </small>

                        <!-- Select de activos -->
                        <select class="form-select" name="asset_id" id="asset-select" required>
                            <option value="">Seleccionar activo disponible</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" data-code="{{ $asset->code }}"
                                    {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                    {{ $asset->code }} - {{ $asset->category->name }} - {{ $asset->brand }}
                                    {{ $asset->model }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Mensajes de búsqueda -->
                        <div id="asset-not-found" class="alert alert-warning mt-2" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="asset-not-found-text"></span>
                        </div>
                        <div id="asset-found" class="alert alert-success mt-2" style="display: none;">
                            <i class="fas fa-check-circle me-2"></i>
                            <span id="asset-found-text"></span>
                        </div>
                    </div>

                    <!-- Selección de Empleado -->
                    <div class="col-md-6">
                        <label class="form-label">Empleado <span class="text-danger">*</span></label>
                        <select class="form-select" name="employee_id" required>
                            <option value="">Seleccionar empleado</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->dni }} - {{ $employee->full_name }}
                                    @if ($employee->department)
                                        ({{ $employee->department }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha de Asignación -->
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Asignación <span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="assigned_date"
                            value="{{ old('assigned_date', date('Y-m-d')) }}" required>
                    </div>

                    <!-- Condición al Entregar -->
                    <div class="col-md-6">
                        <label class="form-label">Condición al Entregar <span class="text-danger">*</span></label>
                        <select class="form-select" name="condition_on_assignment" required>
                            <option value="new" {{ old('condition_on_assignment') == 'new' ? 'selected' : '' }}>Nuevo
                            </option>
                            <option value="good"
                                {{ old('condition_on_assignment', 'good') == 'good' ? 'selected' : '' }}>Bueno</option>
                            <option value="fair" {{ old('condition_on_assignment') == 'fair' ? 'selected' : '' }}>Regular
                            </option>
                            <option value="poor" {{ old('condition_on_assignment') == 'poor' ? 'selected' : '' }}>Malo
                            </option>
                        </select>
                        <small class="text-muted">Estado físico del activo al momento de la entrega</small>
                    </div>

                    <!-- Observaciones -->
                    <div class="col-md-12">
                        <label class="form-label">Observaciones de Entrega</label>
                        <textarea class="form-control" name="assignment_observations" rows="3"
                            placeholder="Ingrese observaciones adicionales sobre el activo...">{{ old('assignment_observations') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documento de Responsabilidad -->
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                <h5 class="mb-0" style="color: #1e40af;">
                    <i class="fas fa-file-contract me-2"></i>Documento de Responsabilidad (Opcional)
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nuevo flujo:</strong> Puedes crear la asignación ahora y subir el documento después de firmarlo.
                    El sistema generará automáticamente el acta con todos los datos.
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Número de Documento</label>
                        <input class="form-control" type="text" name="document_number"
                            value="{{ old('document_number') }}" placeholder="Ej: ACTA-2025-001">
                        <small class="text-muted">Puedes dejarlo vacío y asignarlo después</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha de Firma</label>
                        <input class="form-control" type="date" name="signed_date"
                            value="{{ old('signed_date', date('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Archivo PDF del Documento</label>
                        <input class="form-control" type="file" name="document_file" accept=".pdf">
                        <small class="text-muted">
                            <i class="fas fa-file-pdf me-1"></i>Sube el documento firmado cuando lo tengas (formato PDF)
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Notas del Documento</label>
                        <textarea class="form-control" name="document_notes" rows="3"
                            placeholder="Notas adicionales sobre el documento...">{{ old('document_notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i>Guardar Asignación
            </button>
        </div>
    </form>

    @push('scripts')
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
                const SCAN_SPEED_THRESHOLD = 50;
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
                            assetSelect.style.borderColor = '#10b981';
                            assetSelect.style.boxShadow = '0 0 0 0.2rem rgba(16, 185, 129, 0.25)';
                            setTimeout(() => {
                                assetSelect.style.borderColor = '';
                                assetSelect.style.boxShadow = '';
                            }, 2000);

                            // Scroll al select
                            assetSelect.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

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
    @endpush
@endsection
