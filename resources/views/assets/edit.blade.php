@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Editar Activo</h2>
            </div>

            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <form action="{{ route('asset.update', $asset) }}" method="POST" id="assetForm"
                    class="needs-validation form-control p-4" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Categoría: *</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        data-category-name="{{ strtolower($category->name) }}"
                                        {{ $asset->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Código: *</label>
                            <input type="text" class="form-control" name="code"
                                value="{{ old('code', $asset->code) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Marca: *</label>
                            <input type="text" class="form-control" name="brand"
                                value="{{ old('brand', $asset->brand) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Modelo: *</label>
                            <input type="text" class="form-control" name="model"
                                value="{{ old('model', $asset->model) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Número de Serie:</label>
                            <input type="text" class="form-control" name="serial_number"
                                value="{{ old('serial_number', $asset->serial_number) }}">
                        </div>
                    </div>

                    {{-- CAMPOS POR CATEGORÍA --}}
                    <div id="celular-fields" class="category-fields mt-4" style="display: none;">
                        <h5 class="text-primary fw-bold mb-3">📱 Información del Celular</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">IMEI 1: *</label>
                                <input type="text" class="form-control" name="imei"
                                    value="{{ old('imei', $asset->imei) }}" maxlength="15">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">IMEI 2:</label>
                                <input type="text" class="form-control" name="imei_2"
                                    value="{{ old('imei_2', $asset->imei_2) }}" maxlength="15">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número Telefónico: *</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $asset->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Operador: *</label>
                                <input type="text" class="form-control" name="operator_name"
                                    value="{{ old('operator_name', $asset->operator_name) }}"
                                    placeholder="Ej: Claro, Movistar, Entel">
                            </div>
                        </div>
                    </div>

                    {{-- CAMPOS PC/LAPTOP --}}
                    <div id="pc-laptop-fields" class="category-fields mt-4" style="display: none;">
                        <h5 class="text-primary fw-bold mb-3">💻 Especificaciones Técnicas</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Procesador: *</label>
                                <input type="text" class="form-control" name="processor"
                                    value="{{ old('processor', $asset->processor) }}"
                                    placeholder="Ej: Intel Core i7-10700K">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Memoria RAM: *</label>
                                <input type="text" class="form-control" name="ram"
                                    value="{{ old('ram', $asset->ram) }}" placeholder="Ej: 16GB">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Almacenamiento: *</label>
                                <input type="text" class="form-control" name="storage"
                                    value="{{ old('storage', $asset->storage) }}" placeholder="Ej: 512GB">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Almacenamiento: *</label>
                                <select name="storage_type" class="form-select">
                                    <option value="">Seleccionar</option>
                                    <option value="SSD"
                                        {{ old('storage_type', $asset->storage_type) == 'SSD' ? 'selected' : '' }}>SSD
                                    </option>
                                    <option value="HDD"
                                        {{ old('storage_type', $asset->storage_type) == 'HDD' ? 'selected' : '' }}>HDD
                                    </option>
                                    <option value="NVMe"
                                        {{ old('storage_type', $asset->storage_type) == 'NVMe' ? 'selected' : '' }}>NVMe
                                    </option>
                                    <option value="Híbrido"
                                        {{ old('storage_type', $asset->storage_type) == 'Híbrido' ? 'selected' : '' }}>
                                        Híbrido
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tarjeta Gráfica:</label>
                                <input type="text" class="form-control" name="graphics_card"
                                    value="{{ old('graphics_card', $asset->graphics_card) }}"
                                    placeholder="Ej: NVIDIA RTX 3060">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sistema Operativo: *</label>
                                <input type="text" class="form-control" name="operating_system"
                                    value="{{ old('operating_system', $asset->operating_system) }}"
                                    placeholder="Ej: Windows 11 Pro">
                            </div>
                        </div>
                    </div>

                    {{-- CAMPOS LAPTOP --}}
                    <div id="laptop-fields" class="category-fields mt-4" style="display: none;">
                        <label class="form-label">Tamaño de Pantalla: *</label>
                        <input type="text" class="form-control" name="screen_size"
                            value="{{ old('screen_size', $asset->screen_size) }}" placeholder="Ej: 15.6 pulgadas">
                    </div>

                    {{-- CAMPOS MONITOR --}}
                    <div id="monitor-fields" class="category-fields mt-4" style="display: none;">
                        <h5 class="text-primary fw-bold mb-3">🖥️ Especificaciones del Monitor</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tamaño de Pantalla: *</label>
                                <input type="text" class="form-control" name="screen_size_monitor"
                                    value="{{ old('screen_size_monitor', $asset->screen_size_monitor) }}"
                                    placeholder="Ej: 27 pulgadas">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Resolución: *</label>
                                <input type="text" class="form-control" name="resolution"
                                    value="{{ old('resolution', $asset->resolution) }}" placeholder="Ej: 1920x1080">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Panel:</label>
                                <select name="panel_type" class="form-select">
                                    <option value="">Seleccionar</option>
                                    <option value="IPS"
                                        {{ old('panel_type', $asset->panel_type) == 'IPS' ? 'selected' : '' }}>IPS</option>
                                    <option value="TN"
                                        {{ old('panel_type', $asset->panel_type) == 'TN' ? 'selected' : '' }}>TN</option>
                                    <option value="VA"
                                        {{ old('panel_type', $asset->panel_type) == 'VA' ? 'selected' : '' }}>VA</option>
                                    <option value="OLED"
                                        {{ old('panel_type', $asset->panel_type) == 'OLED' ? 'selected' : '' }}>OLED
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tasa de Refresco:</label>
                                <input type="text" class="form-control" name="refresh_rate"
                                    value="{{ old('refresh_rate', $asset->refresh_rate) }}" placeholder="Ej: 144Hz">
                            </div>
                        </div>
                    </div>

                    {{-- CAMPOS GENERALES --}}
                    <div class="mt-4 row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Compra:</label>
                            <input type="date" class="form-control" name="purchase_date"
                                value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio de Compra:</label>
                            <input type="number" step="0.01" class="form-control" name="purchase_price"
                                value="{{ old('purchase_price', $asset->purchase_price) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado: *</label>
                            <select name="status" class="form-select" required>
                                <option value="available" {{ $asset->status == 'available' ? 'selected' : '' }}>Disponible
                                </option>
                                <option value="assigned" {{ $asset->status == 'assigned' ? 'selected' : '' }}>Asignado
                                </option>
                                <option value="maintenance" {{ $asset->status == 'maintenance' ? 'selected' : '' }}>
                                    Mantenimiento</option>
                                <option value="damaged" {{ $asset->status == 'damaged' ? 'selected' : '' }}>Dañado
                                </option>
                                <option value="retired" {{ $asset->status == 'retired' ? 'selected' : '' }}>Retirado
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Observaciones:</label>
                            <textarea class="form-control" name="observations" rows="3">{{ old('observations', $asset->observations) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Actualizar</button>
                        <a href="{{ route('asset.show', $asset) }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const allCategoryFields = document.querySelectorAll('.category-fields');

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
                    required: ['connection_type', 'is_wireless']
                },
                'teclado': {
                    container: 'peripheral-fields',
                    required: ['connection_type', 'is_wireless']
                },
                'audífonos': {
                    container: 'headphone-fields',
                    required: ['connection_type', 'is_wireless', 'audio_type', 'has_microphone']
                }
            };

            function toggleFields() {
                allCategoryFields.forEach(fieldGroup => {
                    fieldGroup.style.display = 'none';
                    const inputs = fieldGroup.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        input.removeAttribute('required');
                    });
                });

                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                const categoryName = selectedOption.getAttribute('data-category-name');

                if (categoryName && fieldMappings[categoryName]) {
                    const mapping = fieldMappings[categoryName];

                    const containers = Array.isArray(mapping.container) ? mapping.container : [mapping.container];
                    containers.forEach(containerId => {
                        const container = document.getElementById(containerId);
                        if (container) {
                            container.style.display = 'block';
                        }
                    });

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
            toggleFields();
        });
    </script>
@endsection
