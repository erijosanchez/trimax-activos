@extends('layouts.app')

@section('title', 'Nueva Programación')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('maintenance-schedules.index') }}">Programación</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Nueva Programación de Mantenimiento</h1>
        <p class="page-subtitle">Configura el calendario de mantenimiento para un activo</p>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Datos de la Programación</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('maintenance-schedules.store') }}" method="POST">
                        @csrf

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Selecciona un activo y la frecuencia con la que debe recibir mantenimiento preventivo.
                        </div>

                        <div class="mb-4">
                            <label for="asset_id" class="form-label">
                                <i class="fas fa-box me-2"></i>Activo *
                            </label>
                            <select name="asset_id" id="asset_id"
                                class="form-select @error('asset_id') is-invalid @enderror" required>
                                <option value="">Seleccionar activo...</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}"
                                        {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->code }} - {{ $asset->brand }} {{ $asset->model }}
                                        ({{ $asset->category->name }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Solo se muestran activos sin programación activa</small>
                            @error('asset_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="frequency" class="form-label">
                                <i class="fas fa-sync me-2"></i>Frecuencia de Mantenimiento *
                            </label>
                            <select name="frequency" id="frequency"
                                class="form-select @error('frequency') is-invalid @enderror" required>
                                <option value="">Seleccionar frecuencia...</option>
                                <option value="mensual" {{ old('frequency') == 'mensual' ? 'selected' : '' }}>Mensual (cada
                                    mes)</option>
                                <option value="trimestral" {{ old('frequency') == 'trimestral' ? 'selected' : '' }}>
                                    Trimestral (cada 3 meses)</option>
                                <option value="semestral" {{ old('frequency') == 'semestral' ? 'selected' : '' }}>Semestral
                                    (cada 6 meses)</option>
                                <option value="anual" {{ old('frequency') == 'anual' ? 'selected' : '' }}>Anual (cada año)
                                </option>
                            </select>
                            @error('frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="last_maintenance_date" class="form-label">
                                <i class="fas fa-calendar-check me-2"></i>Fecha del Último Mantenimiento (Opcional)
                            </label>
                            <input type="date" name="last_maintenance_date" id="last_maintenance_date"
                                class="form-control @error('last_maintenance_date') is-invalid @enderror"
                                value="{{ old('last_maintenance_date') }}">
                            <small class="text-muted">Si el activo ya tuvo un mantenimiento, indica la fecha. Si no, se
                                usará la fecha actual.</small>
                            @error('last_maintenance_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-2"></i>Notas Adicionales
                            </label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Observaciones o indicaciones especiales para el mantenimiento...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Crear Programación
                            </button>
                            <a href="{{ route('maintenance-schedules.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2 text-warning"></i>Información Importante</h6>
                    <ul class="mb-0">
                        <li class="mb-2">La fecha del próximo mantenimiento se calculará automáticamente según la
                            frecuencia seleccionada.</li>
                        <li class="mb-2">Recibirás notificaciones cuando se acerque la fecha del mantenimiento.</li>
                        <li class="mb-2">Puedes desactivar o modificar la programación en cualquier momento.</li>
                        <li>Al completar un mantenimiento, el sistema actualizará automáticamente la siguiente fecha.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
