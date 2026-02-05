@extends('layouts.app')

@section('title', 'Editar Activo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('asset.index') }}">Activos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('asset.show', $asset) }}">{{ $asset->code }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Editar Activo</h1>
        <p class="page-subtitle">Actualizar información del activo {{ $asset->code }}</p>
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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>¡Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('asset.update', $asset) }}" method="POST" id="assetForm">
        @csrf
        @method('PUT')

        <!-- Información Básica -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información Básica</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Categoría <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" data-category-name="{{ strtolower($category->name) }}"
                                    {{ $asset->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Código <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ old('code', $asset->code) }}"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Marca <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="brand" value="{{ old('brand', $asset->brand) }}"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Modelo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="model" value="{{ old('model', $asset->model) }}"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Número de Serie</label>
                        <input type="text" class="form-control" name="serial_number"
                            value="{{ old('serial_number', $asset->serial_number) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMPOS PARA CELULAR --}}
        <div id="celular-fields" class="category-fields card mb-4" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                <h5 class="mb-0" style="color: #1e40af;">
                    <i class="fas fa-mobile-alt me-2"></i>Información del Celular
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">IMEI 1 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="imei" value="{{ old('imei', $asset->imei) }}"
                            maxlength="15">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">IMEI 2</label>
                        <input type="text" class="form-control" name="imei_2"
                            value="{{ old('imei_2', $asset->imei_2) }}" maxlength="15">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Número Telefónico <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="phone"
                            value="{{ old('phone', $asset->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Operador <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="operator_name"
                            value="{{ old('operator_name', $asset->operator_name) }}"
                            placeholder="Ej: Claro, Movistar, Entel">
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMPOS PC/LAPTOP --}}
        <div id="pc-laptop-fields" class="category-fields card mb-4" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);">
                <h5 class="mb-0" style="color: #3730a3;">
                    <i class="fas fa-laptop me-2"></i>Especificaciones Técnicas
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Procesador <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="processor"
                            value="{{ old('processor', $asset->processor) }}" placeholder="Ej: Intel Core i7-10700K">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Memoria RAM <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ram" value="{{ old('ram', $asset->ram) }}"
                            placeholder="Ej: 16GB">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Almacenamiento <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="storage"
                            value="{{ old('storage', $asset->storage) }}" placeholder="Ej: 512GB">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Almacenamiento <span class="text-danger">*</span></label>
                        <select name="storage_type" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="SSD"
                                {{ old('storage_type', $asset->storage_type) == 'SSD' ? 'selected' : '' }}>SSD</option>
                            <option value="HDD"
                                {{ old('storage_type', $asset->storage_type) == 'HDD' ? 'selected' : '' }}>HDD</option>
                            <option value="NVMe"
                                {{ old('storage_type', $asset->storage_type) == 'NVMe' ? 'selected' : '' }}>NVMe</option>
                            <option value="Híbrido"
                                {{ old('storage_type', $asset->storage_type) == 'Híbrido' ? 'selected' : '' }}>Híbrido
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tarjeta Gráfica</label>
                        <input type="text" class="form-control" name="graphics_card"
                            value="{{ old('graphics_card', $asset->graphics_card) }}" placeholder="Ej: NVIDIA RTX 3060">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sistema Operativo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="operating_system"
                            value="{{ old('operating_system', $asset->operating_system) }}"
                            placeholder="Ej: Windows 11 Pro">
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMPOS LAPTOP --}}
        <div id="laptop-fields" class="category-fields card mb-4" style="display: none;">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tamaño de Pantalla <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="screen_size"
                            value="{{ old('screen_size', $asset->screen_size) }}" placeholder="Ej: 15.6 pulgadas">
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMPOS MONITOR --}}
        <div id="monitor-fields" class="category-fields card mb-4" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                <h5 class="mb-0" style="color: #065f46;">
                    <i class="fas fa-desktop me-2"></i>Especificaciones del Monitor
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tamaño de Pantalla <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="screen_size_monitor"
                            value="{{ old('screen_size_monitor', $asset->screen_size_monitor) }}"
                            placeholder="Ej: 27 pulgadas">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Resolución <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="resolution"
                            value="{{ old('resolution', $asset->resolution) }}" placeholder="Ej: 1920x1080">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Panel</label>
                        <select name="panel_type" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="IPS" {{ old('panel_type', $asset->panel_type) == 'IPS' ? 'selected' : '' }}>
                                IPS</option>
                            <option value="TN" {{ old('panel_type', $asset->panel_type) == 'TN' ? 'selected' : '' }}>
                                TN</option>
                            <option value="VA" {{ old('panel_type', $asset->panel_type) == 'VA' ? 'selected' : '' }}>
                                VA</option>
                            <option value="OLED"
                                {{ old('panel_type', $asset->panel_type) == 'OLED' ? 'selected' : '' }}>OLED</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tasa de Refresco</label>
                        <input type="text" class="form-control" name="refresh_rate"
                            value="{{ old('refresh_rate', $asset->refresh_rate) }}" placeholder="Ej: 144Hz">
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMPOS PARA MOUSE Y TECLADO --}}
        <div id="peripheral-fields" class="category-fields card mb-4" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                <h5 class="mb-0" style="color: #92400e;">
                    <i class="fas fa-keyboard me-2"></i>Especificaciones del Periférico
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Conexión <span class="text-danger">*</span></label>
                        <select class="form-select" name="connection_type" id="peripheral_connection_type">
                            <option value="">Seleccionar</option>
                            <option value="USB"
                                {{ old('connection_type', $asset->connection_type) == 'USB' ? 'selected' : '' }}>USB
                            </option>
                            <option value="Bluetooth"
                                {{ old('connection_type', $asset->connection_type) == 'Bluetooth' ? 'selected' : '' }}>
                                Bluetooth</option>
                            <option value="Inalámbrico 2.4GHz"
                                {{ old('connection_type', $asset->connection_type) == 'Inalámbrico 2.4GHz' ? 'selected' : '' }}>
                                Inalámbrico 2.4GHz</option>
                            <option value="PS/2"
                                {{ old('connection_type', $asset->connection_type) == 'PS/2' ? 'selected' : '' }}>PS/2
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">¿Es inalámbrico?</label>
                        <select class="form-select" name="is_wireless" id="peripheral_is_wireless">
                            <option value="">Seleccionar</option>
                            <option value="1" {{ old('is_wireless', $asset->is_wireless) == '1' ? 'selected' : '' }}>
                                Sí</option>
                            <option value="0" {{ old('is_wireless', $asset->is_wireless) == '0' ? 'selected' : '' }}>
                                No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMPOS PARA AUDÍFONOS --}}
        <div id="headphone-fields" class="category-fields card mb-4" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);">
                <h5 class="mb-0" style="color: #9f1239;">
                    <i class="fas fa-headphones me-2"></i>Especificaciones de los Audífonos
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Conexión <span class="text-danger">*</span></label>
                        <select class="form-select" name="connection_type" id="headphone_connection_type">
                            <option value="">Seleccionar</option>
                            <option value="USB"
                                {{ old('connection_type', $asset->connection_type) == 'USB' ? 'selected' : '' }}>USB
                            </option>
                            <option value="Bluetooth"
                                {{ old('connection_type', $asset->connection_type) == 'Bluetooth' ? 'selected' : '' }}>
                                Bluetooth</option>
                            <option value="Jack 3.5mm"
                                {{ old('connection_type', $asset->connection_type) == 'Jack 3.5mm' ? 'selected' : '' }}>
                                Jack 3.5mm</option>
                            <option value="Inalámbrico 2.4GHz"
                                {{ old('connection_type', $asset->connection_type) == 'Inalámbrico 2.4GHz' ? 'selected' : '' }}>
                                Inalámbrico 2.4GHz</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">¿Es inalámbrico?</label>
                        <select class="form-select" name="is_wireless" id="headphone_is_wireless">
                            <option value="">Seleccionar</option>
                            <option value="1" {{ old('is_wireless', $asset->is_wireless) == '1' ? 'selected' : '' }}>
                                Sí</option>
                            <option value="0" {{ old('is_wireless', $asset->is_wireless) == '0' ? 'selected' : '' }}>
                                No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Audífono <span class="text-danger">*</span></label>
                        <select class="form-select" name="audio_type">
                            <option value="">Seleccionar</option>
                            <option value="Over-ear"
                                {{ old('audio_type', $asset->audio_type) == 'Over-ear' ? 'selected' : '' }}>Over-ear (Sobre
                                la oreja)</option>
                            <option value="On-ear"
                                {{ old('audio_type', $asset->audio_type) == 'On-ear' ? 'selected' : '' }}>On-ear (En la
                                oreja)</option>
                            <option value="In-ear"
                                {{ old('audio_type', $asset->audio_type) == 'In-ear' ? 'selected' : '' }}>In-ear (Dentro
                                del oído)</option>
                            <option value="Earbuds"
                                {{ old('audio_type', $asset->audio_type) == 'Earbuds' ? 'selected' : '' }}>Earbuds</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">¿Tiene micrófono?</label>
                        <select class="form-select" name="has_microphone">
                            <option value="">Seleccionar</option>
                            <option value="1"
                                {{ old('has_microphone', $asset->has_microphone) == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0"
                                {{ old('has_microphone', $asset->has_microphone) == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMPOS GENERALES --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Información Adicional</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Compra</label>
                        <input type="date" class="form-control" name="purchase_date"
                            value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Precio de Compra</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.01" class="form-control" name="purchase_price"
                                value="{{ old('purchase_price', $asset->purchase_price) }}" placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="available" {{ $asset->status == 'available' ? 'selected' : '' }}>Disponible
                            </option>
                            <option value="assigned" {{ $asset->status == 'assigned' ? 'selected' : '' }}>Asignado
                            </option>
                            <option value="maintenance" {{ $asset->status == 'maintenance' ? 'selected' : '' }}>
                                Mantenimiento</option>
                            <option value="damaged" {{ $asset->status == 'damaged' ? 'selected' : '' }}>Dañado</option>
                            <option value="retired" {{ $asset->status == 'retired' ? 'selected' : '' }}>Retirado</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observations" rows="3"
                            placeholder="Ingrese observaciones adicionales...">{{ old('observations', $asset->observations) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="{{ route('asset.show', $asset) }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i>Actualizar Activo
            </button>
        </div>
    </form>

    @push('scripts')
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
                            input.setAttribute('disabled', 'disabled');
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
                toggleFields();
            });
        </script>
    @endpush
@endsection
