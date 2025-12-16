@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Nuevo Activo</h2>
            </div>

            {{-- MENSAJES DE ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>¡Error!</strong> Por favor corrige los siguientes errores:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>¡Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <form action="{{ route('asset.store') }}" method="POST" class="needs-validation form-control p-4"
                    id="assetForm">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Categoría: *</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Seleccionar</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        data-category-name="{{ strtolower($category->name) }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="code" id="asset-code"
                                    value="{{ old('code') }}" placeholder="Se generará automáticamente">
                                <button type="button" class="btn btn-outline-secondary" id="preview-code"
                                    title="Ver próximo código">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Formato: GMSAC202500001 (Se genera automáticamente si se deja
                                vacío)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Marca: *</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Modelo: *</label>
                            <input type="text" class="form-control" name="model" value="{{ old('model') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Número de Serie:</label>
                            <input type="text" class="form-control" name="serial_number"
                                value="{{ old('serial_number') }}">
                        </div>
                    </div>

                    <!-- CAMPOS PARA CELULAR -->
                    <div id="celular-fields" class="category-fields mt-4" style="display: none;">
                        <h5 class="text-primary fw-bold mb-3">📱 Información del Celular</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">IMEI 1: *</label>
                                <input type="text" class="form-control" name="imei" value="{{ old('imei') }}"
                                    maxlength="15">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">IMEI 2:</label>
                                <input type="text" class="form-control" name="imei_2" value="{{ old('imei_2') }}"
                                    maxlength="15">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número Telefónico: *</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Operador: *</label>
                                <input type="text" class="form-control" name="operator_name"
                                    value="{{ old('operator_name') }}" placeholder="Ej: Claro, Movistar, Entel">
                            </div>
                        </div>
                    </div>

                    <!-- CAMPOS PARA PC Y LAPTOP -->
                    <div id="pc-laptop-fields" class="category-fields mt-4" style="display: none;">
                        <h5 class="text-primary fw-bold mb-3">💻 Especificaciones Técnicas</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Procesador: *</label>
                                <input class="form-control" type="text" name="processor"
                                    value="{{ old('processor') }}" placeholder="Ej: Intel Core i7-10700K">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Memoria RAM: *</label>
                                <input class="form-control" type="text" name="ram" value="{{ old('ram') }}"
                                    placeholder="Ej: 16GB">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Almacenamiento: *</label>
                                <input class="form-control" type="text" name="storage" value="{{ old('storage') }}"
                                    placeholder="Ej: 512GB">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Almacenamiento: *</label>
                                <select class="form-select" name="storage_type">
                                    <option value="">Seleccionar</option>
                                    <option value="SSD">SSD</option>
                                    <option value="HDD">HDD</option>
                                    <option value="NVMe">NVMe</option>
                                    <option value="Híbrido">Híbrido</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tarjeta Gráfica:</label>
                                <input class="form-control" type="text" name="graphics_card"
                                    value="{{ old('graphics_card') }}" placeholder="Ej: NVIDIA RTX 3060">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sistema Operativo: *</label>
                                <input class="form-control" type="text" name="operating_system"
                                    value="{{ old('operating_system') }}" placeholder="Ej: Windows 11 Pro">
                            </div>
                        </div>
                    </div>

                    <!-- CAMPOS ADICIONALES SOLO PARA LAPTOP -->
                    <div id="laptop-fields" class="category-fields mt-4" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tamaño de Pantalla: *</label>
                                <input class="form-control" type="text" name="screen_size"
                                    value="{{ old('screen_size') }}" placeholder="Ej: 15.6 pulgadas">
                            </div>
                        </div>
                    </div>

                    <!-- CAMPOS PARA MONITOR -->
                    <div id="monitor-fields" class="category-fields mt-4" style="display: none;">
                        <h5 class="text-primary fw-bold mb-3">🖥️ Especificaciones del Monitor</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tamaño de Pantalla: *</label>
                                <input class="form-control" type="text" name="screen_size_monitor"
                                    value="{{ old('screen_size_monitor') }}" placeholder="Ej: 27 pulgadas">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Resolución: *</label>
                                <input class="form-control" type="text" name="resolution"
                                    value="{{ old('resolution') }}" placeholder="Ej: 1920x1080">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Panel:</label>
                                <select class="form-select" name="panel_type">
                                    <option value="">Seleccionar</option>
                                    <option value="IPS">IPS</option>
                                    <option value="TN">TN</option>
                                    <option value="VA">VA</option>
                                    <option value="OLED">OLED</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tasa de Refresco:</label>
                                <input class="form-control" type="text" name="refresh_rate"
                                    value="{{ old('refresh_rate') }}" placeholder="Ej: 144Hz">
                            </div>
                        </div>
                    </div>

                    <!-- CAMPOS PARA MOUSE Y TECLADO -->
                    <div id="peripheral-fields" class="category-fields mt-4" style="display: none;">
                        <h5 class="text-primary fw-bold mb-3"> Especificaciones</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Conexión: *</label>
                                <select class="form-select" name="connection_type" id="peripheral_connection_type">
                                    <option value="">Seleccionar</option>
                                    <option value="USB">USB</option>
                                    <option value="Bluetooth">Bluetooth</option>
                                    <option value="Inalámbrico 2.4GHz">Inalámbrico 2.4GHz</option>
                                    <option value="PS/2">PS/2</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">¿Es inalámbrico?:</label>
                                <select class="form-select" name="is_wireless" id="peripheral_is_wireless">
                                    <option value="">Seleccionar</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- CAMPOS PARA AUDÍFONOS -->
                    <div id="headphone-fields" class="category-fields mt-4" style="display: none;">
                        <h5 class="text-primary fw-bold mb-3"> Especificaciones del Audifonos</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Conexión: *</label>
                                <select class="form-select" name="connection_type" id="headphone_connection_type">
                                    <option value="">Seleccionar</option>
                                    <option value="USB">USB</option>
                                    <option value="Bluetooth">Bluetooth</option>
                                    <option value="Jack 3.5mm">Jack 3.5mm</option>
                                    <option value="Inalámbrico 2.4GHz">Inalámbrico 2.4GHz</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">¿Es inalámbrico?:</label>
                                <select class="form-select" name="is_wireless" id="headphone_is_wireless">
                                    <option value="">Seleccionar</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Audífono: *</label>
                                <select class="form-select" name="audio_type">
                                    <option value="">Seleccionar</option>
                                    <option value="Over-ear">Over-ear (Sobre la oreja)</option>
                                    <option value="On-ear">On-ear (En la oreja)</option>
                                    <option value="In-ear">In-ear (Dentro del oído)</option>
                                    <option value="Earbuds">Earbuds</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">¿Tiene micrófono?:</label>
                                <select class="form-select" name="has_microphone">
                                    <option value="">Seleccionar</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- CAMPOS GENERALES -->
                    <div class="mt-4 row g-3">
                        <div class="col-md-6">
                            <!-- CAMPOS GENERALES -->
                            <label class="form-label">Fecha de Compra:</label>
                            <input type="date" class="form-control" name="purchase_date"
                                value="{{ old('purchase_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio de Compra:</label>
                            <input type="number" step="0.01" class="form-control" name="purchase_price"
                                value="{{ old('purchase_price') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado: *</label>
                            <select class="form-select" name="status" required>
                                <option value="available">Disponible</option>
                                <option value="assigned">Asignado</option>
                                <option value="maintenance">Mantenimiento</option>
                                <option value="damaged">Dañado</option>
                                <option value="retired">Retirado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Observaciones:</label>
                            <textarea name="observations" class="form-control" rows="3">{{ old('observations') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Guardar</button>
                        <a href="{{ route('asset.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const allCategoryFields = document.querySelectorAll('.category-fields');

            // Vista previa del próximo código
            const previewCodeBtn = document.getElementById('preview-code');
            const codeInput = document.getElementById('asset-code');

            previewCodeBtn.addEventListener('click', function() {
                fetch('{{ route('asset.next-code') }}')
                    .then(response => response.json())
                    .then(data => {
                        codeInput.value = data.code;
                        codeInput.classList.add('bg-light');
                        setTimeout(() => {
                            codeInput.classList.remove('bg-light');
                        }, 1000);
                    })
                    .catch(error => console.error('Error:', error));
            });

            // Mapeo de campos por categoría
            const fieldMappings = {
                'celular': {
                    container: 'celular-fields',
                    required: ['imei', 'phone', 'operator_name']
                },
                'pc': {
                    container: 'pc-laptop-fields',
                    required: ['processor', 'ram', 'storage', 'storage_type', 'operating_system']
                },
                'laptop': {
                    container: ['pc-laptop-fields', 'laptop-fields'],
                    required: ['processor', 'ram', 'storage', 'storage_type', 'operating_system', 'screen_size']
                },
                'monitor': {
                    container: 'monitor-fields',
                    required: ['screen_size_monitor', 'resolution']
                },
                'mouse': {
                    container: 'peripheral-fields',
                    required: ['connection_type']
                },
                'teclado': {
                    container: 'peripheral-fields',
                    required: ['connection_type']
                },
                'audífonos': {
                    container: 'headphone-fields',
                    required: ['connection_type', 'audio_type']
                }
            };

            function toggleFields() {
                // Ocultar todos los campos y quitar requeridos Y DESHABILITARLOS
                allCategoryFields.forEach(fieldGroup => {
                    fieldGroup.style.display = 'none';
                    const inputs = fieldGroup.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        input.removeAttribute('required');
                        input.setAttribute('disabled', 'disabled'); // DESHABILITAR campos ocultos
                    });
                });

                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                const categoryName = selectedOption.getAttribute('data-category-name');

                if (categoryName && fieldMappings[categoryName]) {
                    const mapping = fieldMappings[categoryName];

                    // Mostrar contenedores y HABILITAR campos
                    const containers = Array.isArray(mapping.container) ? mapping.container : [mapping.container];
                    containers.forEach(containerId => {
                        const container = document.getElementById(containerId);
                        if (container) {
                            container.style.display = 'block';
                            // HABILITAR todos los campos del contenedor visible
                            const inputs = container.querySelectorAll('input, select');
                            inputs.forEach(input => {
                                input.removeAttribute('disabled');
                            });
                        }
                    });

                    // Marcar campos requeridos
                    mapping.required.forEach(fieldName => {
                        const field = document.querySelector(`[name="${fieldName}"]`);
                        if (field) {
                            field.setAttribute('required', 'required');
                        }
                    });
                }
            }

            categorySelect.addEventListener('change', toggleFields);

            // Ejecutar al cargar
            if (categorySelect.value) {
                toggleFields();
            }
        });
    </script>
@endsection
