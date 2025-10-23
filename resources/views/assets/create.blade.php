@extends('layouts.app')

@section('content')
<h1>Nuevo Activo</h1>

<form action="{{ route('assets.store') }}" method="POST" id="assetForm">
    @csrf
    
    <label>Categoría: *</label>
    <select name="category_id" id="category_id" required>
        <option value="">Seleccionar</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" data-category-name="{{ strtolower($category->name) }}">
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <label>Código: *</label>
    <input type="text" name="code" value="{{ old('code') }}" required>

    <label>Marca: *</label>
    <input type="text" name="brand" value="{{ old('brand') }}" required>

    <label>Modelo: *</label>
    <input type="text" name="model" value="{{ old('model') }}" required>

    <label>Número de Serie:</label>
    <input type="text" name="serial_number" value="{{ old('serial_number') }}">

    <!-- CAMPOS PARA CELULAR -->
    <div id="celular-fields" class="category-fields" style="display: none;">
        <h3>Información del Celular</h3>
        
        <label>IMEI 1: *</label>
        <input type="text" name="imei" value="{{ old('imei') }}" maxlength="15">
        
        <label>IMEI 2:</label>
        <input type="text" name="imei_2" value="{{ old('imei_2') }}" maxlength="15">
        
        <label>Número Telefónico: *</label>
        <input type="text" name="phone" value="{{ old('phone') }}">
        
        <label>Operador: *</label>
        <input type="text" name="operator_name" value="{{ old('operator_name') }}" placeholder="Ej: Claro, Movistar, Entel">
    </div>

    <!-- CAMPOS PARA PC Y LAPTOP -->
    <div id="pc-laptop-fields" class="category-fields" style="display: none;">
        <h3>Especificaciones Técnicas</h3>
        
        <label>Procesador: *</label>
        <input type="text" name="processor" value="{{ old('processor') }}" placeholder="Ej: Intel Core i7-10700K">
        
        <label>Memoria RAM: *</label>
        <input type="text" name="ram" value="{{ old('ram') }}" placeholder="Ej: 16GB">
        
        <label>Almacenamiento: *</label>
        <input type="text" name="storage" value="{{ old('storage') }}" placeholder="Ej: 512GB">
        
        <label>Tipo de Almacenamiento: *</label>
        <select name="storage_type">
            <option value="">Seleccionar</option>
            <option value="SSD">SSD</option>
            <option value="HDD">HDD</option>
            <option value="NVMe">NVMe</option>
            <option value="Híbrido">Híbrido</option>
        </select>
        
        <label>Tarjeta Gráfica:</label>
        <input type="text" name="graphics_card" value="{{ old('graphics_card') }}" placeholder="Ej: NVIDIA RTX 3060">
        
        <label>Sistema Operativo: *</label>
        <input type="text" name="operating_system" value="{{ old('operating_system') }}" placeholder="Ej: Windows 11 Pro">
    </div>

    <!-- CAMPOS ADICIONALES SOLO PARA LAPTOP -->
    <div id="laptop-fields" class="category-fields" style="display: none;">
        <label>Tamaño de Pantalla: *</label>
        <input type="text" name="screen_size" value="{{ old('screen_size') }}" placeholder="Ej: 15.6 pulgadas">
    </div>

    <!-- CAMPOS PARA MONITOR -->
    <div id="monitor-fields" class="category-fields" style="display: none;">
        <h3>Especificaciones del Monitor</h3>
        
        <label>Tamaño de Pantalla: *</label>
        <input type="text" name="screen_size_monitor" value="{{ old('screen_size_monitor') }}" placeholder="Ej: 27 pulgadas">
        
        <label>Resolución: *</label>
        <input type="text" name="resolution" value="{{ old('resolution') }}" placeholder="Ej: 1920x1080">
        
        <label>Tipo de Panel:</label>
        <select name="panel_type">
            <option value="">Seleccionar</option>
            <option value="IPS">IPS</option>
            <option value="TN">TN</option>
            <option value="VA">VA</option>
            <option value="OLED">OLED</option>
        </select>
        
        <label>Tasa de Refresco:</label>
        <input type="text" name="refresh_rate" value="{{ old('refresh_rate') }}" placeholder="Ej: 144Hz">
    </div>

    <!-- CAMPOS PARA MOUSE Y TECLADO -->
    <div id="peripheral-fields" class="category-fields" style="display: none;">
        <h3>Especificaciones</h3>
        
        <label>Tipo de Conexión: *</label>
        <select name="connection_type">
            <option value="">Seleccionar</option>
            <option value="USB">USB</option>
            <option value="Bluetooth">Bluetooth</option>
            <option value="Inalámbrico 2.4GHz">Inalámbrico 2.4GHz</option>
            <option value="PS/2">PS/2</option>
        </select>
        
        <label>¿Es inalámbrico?: *</label>
        <select name="is_wireless">
            <option value="">Seleccionar</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <!-- CAMPOS PARA AUDÍFONOS -->
    <div id="headphone-fields" class="category-fields" style="display: none;">
        <h3>Especificaciones de Audífonos</h3>
        
        <label>Tipo de Conexión: *</label>
        <select name="connection_type">
            <option value="">Seleccionar</option>
            <option value="USB">USB</option>
            <option value="Bluetooth">Bluetooth</option>
            <option value="Jack 3.5mm">Jack 3.5mm</option>
            <option value="Inalámbrico 2.4GHz">Inalámbrico 2.4GHz</option>
        </select>
        
        <label>¿Es inalámbrico?: *</label>
        <select name="is_wireless">
            <option value="">Seleccionar</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
        
        <label>Tipo de Audífono: *</label>
        <select name="audio_type">
            <option value="">Seleccionar</option>
            <option value="Over-ear">Over-ear (Sobre la oreja)</option>
            <option value="On-ear">On-ear (En la oreja)</option>
            <option value="In-ear">In-ear (Dentro del oído)</option>
            <option value="Earbuds">Earbuds</option>
        </select>
        
        <label>¿Tiene micrófono?: *</label>
        <select name="has_microphone">
            <option value="">Seleccionar</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <!-- CAMPOS GENERALES -->
    <label>Fecha de Compra:</label>
    <input type="date" name="purchase_date" value="{{ old('purchase_date') }}">

    <label>Precio de Compra:</label>
    <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}">

    <label>Estado: *</label>
    <select name="status" required>
        <option value="available">Disponible</option>
        <option value="assigned">Asignado</option>
        <option value="maintenance">Mantenimiento</option>
        <option value="damaged">Dañado</option>
        <option value="retired">Retirado</option>
    </select>

    <label>Observaciones:</label>
    <textarea name="observations" rows="3">{{ old('observations') }}</textarea>

    <button type="submit" class="btn">Guardar</button>
    <a href="{{ route('assets.index') }}">Cancelar</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const allCategoryFields = document.querySelectorAll('.category-fields');
    
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
        // Ocultar todos los campos y quitar requeridos
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
            
            // Mostrar contenedores
            const containers = Array.isArray(mapping.container) ? mapping.container : [mapping.container];
            containers.forEach(containerId => {
                const container = document.getElementById(containerId);
                if (container) {
                    container.style.display = 'block';
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