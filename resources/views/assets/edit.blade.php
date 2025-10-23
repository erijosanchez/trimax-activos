@extends('layouts.app')

@section('content')
<h1>Editar Activo</h1>

<form action="{{ route('assets.update', $asset) }}" method="POST" id="assetForm">
    @csrf
    @method('PUT')
    
    <label>Categoría: *</label>
    <select name="category_id" id="category_id" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" 
                    data-category-name="{{ strtolower($category->name) }}"
                    {{ $asset->category_id == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <label>Código: *</label>
    <input type="text" name="code" value="{{ old('code', $asset->code) }}" required>

    <label>Marca: *</label>
    <input type="text" name="brand" value="{{ old('brand', $asset->brand) }}" required>

    <label>Modelo: *</label>
    <input type="text" name="model" value="{{ old('model', $asset->model) }}" required>

    <label>Número de Serie:</label>
    <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}">

    <!-- CAMPOS PARA CELULAR -->
    <div id="celular-fields" class="category-fields" style="display: none;">
        <h3>Información del Celular</h3>
        
        <label>IMEI 1: *</label>
        <input type="text" name="imei" value="{{ old('imei', $asset->imei) }}" maxlength="15">
        
        <label>IMEI 2:</label>
        <input type="text" name="imei_2" value="{{ old('imei_2', $asset->imei_2) }}" maxlength="15">
        
        <label>Número Telefónico: *</label>
        <input type="text" name="phone" value="{{ old('phone', $asset->phone) }}">
        
        <label>Operador: *</label>
        <input type="text" name="operator_name" value="{{ old('operator_name', $asset->operator_name) }}" placeholder="Ej: Claro, Movistar, Entel">
    </div>

    <!-- CAMPOS PARA PC Y LAPTOP -->
    <div id="pc-laptop-fields" class="category-fields" style="display: none;">
        <h3>Especificaciones Técnicas</h3>
        
        <label>Procesador: *</label>
        <input type="text" name="processor" value="{{ old('processor', $asset->processor) }}" placeholder="Ej: Intel Core i7-10700K">
        
        <label>Memoria RAM: *</label>
        <input type="text" name="ram" value="{{ old('ram', $asset->ram) }}" placeholder="Ej: 16GB">
        
        <label>Almacenamiento: *</label>
        <input type="text" name="storage" value="{{ old('storage', $asset->storage) }}" placeholder="Ej: 512GB">
        
        <label>Tipo de Almacenamiento: *</label>
        <select name="storage_type">
            <option value="">Seleccionar</option>
            <option value="SSD" {{ old('storage_type', $asset->storage_type) == 'SSD' ? 'selected' : '' }}>SSD</option>
            <option value="HDD" {{ old('storage_type', $asset->storage_type) == 'HDD' ? 'selected' : '' }}>HDD</option>
            <option value="NVMe" {{ old('storage_type', $asset->storage_type) == 'NVMe' ? 'selected' : '' }}>NVMe</option>
            <option value="Híbrido" {{ old('storage_type', $asset->storage_type) == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
        </select>
        
        <label>Tarjeta Gráfica:</label>
        <input type="text" name="graphics_card" value="{{ old('graphics_card', $asset->graphics_card) }}" placeholder="Ej: NVIDIA RTX 3060">
        
        <label>Sistema Operativo: *</label>
        <input type="text" name="operating_system" value="{{ old('operating_system', $asset->operating_system) }}" placeholder="Ej: Windows 11 Pro">
    </div>

    <!-- CAMPOS ADICIONALES SOLO PARA LAPTOP -->
    <div id="laptop-fields" class="category-fields" style="display: none;">
        <label>Tamaño de Pantalla: *</label>
        <input type="text" name="screen_size" value="{{ old('screen_size', $asset->screen_size) }}" placeholder="Ej: 15.6 pulgadas">
    </div>

    <!-- CAMPOS PARA MONITOR -->
    <div id="monitor-fields" class="category-fields" style="display: none;">
        <h3>Especificaciones del Monitor</h3>
        
        <label>Tamaño de Pantalla: *</label>
        <input type="text" name="screen_size_monitor" value="{{ old('screen_size_monitor', $asset->screen_size_monitor) }}" placeholder="Ej: 27 pulgadas">
        
        <label>Resolución: *</label>
        <input type="text" name="resolution" value="{{ old('resolution', $asset->resolution) }}" placeholder="Ej: 1920x1080">
        
        <label>Tipo de Panel:</label>
        <select name="panel_type">
            <option value="">Seleccionar</option>
            <option value="IPS" {{ old('panel_type', $asset->panel_type) == 'IPS' ? 'selected' : '' }}>IPS</option>
            <option value="TN" {{ old('panel_type', $asset->panel_type) == 'TN' ? 'selected' : '' }}>TN</option>
            <option value="VA" {{ old('panel_type', $asset->panel_type) == 'VA' ? 'selected' : '' }}>VA</option>
            <option value="OLED" {{ old('panel_type', $asset->panel_type) == 'OLED' ? 'selected' : '' }}>OLED</option>
        </select>
        
        <label>Tasa de Refresco:</label>
        <input type="text" name="refresh_rate" value="{{ old('refresh_rate', $asset->refresh_rate) }}" placeholder="Ej: 144Hz">
    </div>

    <!-- CAMPOS PARA MOUSE Y TECLADO -->
    <div id="peripheral-fields" class="category-fields" style="display: none;">
        <h3>Especificaciones</h3>
        
        <label>Tipo de Conexión: *</label>
        <select name="connection_type">
            <option value="">Seleccionar</option>
            <option value="USB" {{ old('connection_type', $asset->connection_type) == 'USB' ? 'selected' : '' }}>USB</option>
            <option value="Bluetooth" {{ old('connection_type', $asset->connection_type) == 'Bluetooth' ? 'selected' : '' }}>Bluetooth</option>
            <option value="Inalámbrico 2.4GHz" {{ old('connection_type', $asset->connection_type) == 'Inalámbrico 2.4GHz' ? 'selected' : '' }}>Inalámbrico 2.4GHz</option>
            <option value="PS/2" {{ old('connection_type', $asset->connection_type) == 'PS/2' ? 'selected' : '' }}>PS/2</option>
        </select>
        
        <label>¿Es inalámbrico?: *</label>
        <select name="is_wireless">
            <option value="">Seleccionar</option>
            <option value="1" {{ old('is_wireless', $asset->is_wireless) == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('is_wireless', $asset->is_wireless) == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <!-- CAMPOS PARA AUDÍFONOS -->
    <div id="headphone-fields" class="category-fields" style="display: none;">
        <h3>Especificaciones de Audífonos</h3>
        
        <label>Tipo de Conexión: *</label>
        <select name="connection_type">
            <option value="">Seleccionar</option>
            <option value="USB" {{ old('connection_type', $asset->connection_type) == 'USB' ? 'selected' : '' }}>USB</option>
            <option value="Bluetooth" {{ old('connection_type', $asset->connection_type) == 'Bluetooth' ? 'selected' : '' }}>Bluetooth</option>
            <option value="Jack 3.5mm" {{ old('connection_type', $asset->connection_type) == 'Jack 3.5mm' ? 'selected' : '' }}>Jack 3.5mm</option>
            <option value="Inalámbrico 2.4GHz" {{ old('connection_type', $asset->connection_type) == 'Inalámbrico 2.4GHz' ? 'selected' : '' }}>Inalámbrico 2.4GHz</option>
        </select>
        
        <label>¿Es inalámbrico?: *</label>
        <select name="is_wireless">
            <option value="">Seleccionar</option>
            <option value="1" {{ old('is_wireless', $asset->is_wireless) == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('is_wireless', $asset->is_wireless) == '0' ? 'selected' : '' }}>No</option>
        </select>
        
        <label>Tipo de Audífono: *</label>
        <select name="audio_type">
            <option value="">Seleccionar</option>
            <option value="Over-ear" {{ old('audio_type', $asset->audio_type) == 'Over-ear' ? 'selected' : '' }}>Over-ear (Sobre la oreja)</option>
            <option value="On-ear" {{ old('audio_type', $asset->audio_type) == 'On-ear' ? 'selected' : '' }}>On-ear (En la oreja)</option>
            <option value="In-ear" {{ old('audio_type', $asset->audio_type) == 'In-ear' ? 'selected' : '' }}>In-ear (Dentro del oído)</option>
            <option value="Earbuds" {{ old('audio_type', $asset->audio_type) == 'Earbuds' ? 'selected' : '' }}>Earbuds</option>
        </select>
        
        <label>¿Tiene micrófono?: *</label>
        <select name="has_microphone">
            <option value="">Seleccionar</option>
            <option value="1" {{ old('has_microphone', $asset->has_microphone) == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('has_microphone', $asset->has_microphone) == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <!-- CAMPOS GENERALES -->
    <label>Fecha de Compra:</label>
    <input type="date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}">

    <label>Precio de Compra:</label>
    <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $asset->purchase_price) }}">

    <label>Estado: *</label>
    <select name="status" required>
        <option value="available" {{ $asset->status == 'available' ? 'selected' : '' }}>Disponible</option>
        <option value="assigned" {{ $asset->status == 'assigned' ? 'selected' : '' }}>Asignado</option>
        <option value="maintenance" {{ $asset->status == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
        <option value="damaged" {{ $asset->status == 'damaged' ? 'selected' : '' }}>Dañado</option>
        <option value="retired" {{ $asset->status == 'retired' ? 'selected' : '' }}>Retirado</option>
    </select>

    <label>Observaciones:</label>
    <textarea name="observations" rows="3">{{ old('observations', $asset->observations) }}</textarea>

    <button type="submit" class="btn">Actualizar</button>
    <a href="{{ route('assets.show', $asset) }}">Cancelar</a>
</form>

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