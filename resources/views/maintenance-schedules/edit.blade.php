@extends('layouts.app')

@section('title', 'Editar Programación')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('maintenance-schedules.index') }}">Programación</a></li>
    <li class="breadcrumb-item"><a href="{{ route('maintenance-schedules.show', $maintenanceSchedule) }}">Detalle</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Editar Programación de Mantenimiento</h1>
        <p class="page-subtitle">Actualiza la configuración del calendario de mantenimiento</p>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Datos de la Programación</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('maintenance-schedules.update', $maintenanceSchedule) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Activo Info (read-only) -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-box me-2"></i>Activo
                            </label>
                            <div class="p-3" style="background: #f8fafc; border-radius: 8px;">
                                <div class="fw-bold">{{ $maintenanceSchedule->asset->code }}</div>
                                <div>{{ $maintenanceSchedule->asset->brand }} {{ $maintenanceSchedule->asset->model }}</div>
                                <small class="text-muted">{{ $maintenanceSchedule->asset->category->name }}</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="frequency" class="form-label">
                                <i class="fas fa-sync me-2"></i>Frecuencia de Mantenimiento *
                            </label>
                            <select name="frequency" id="frequency"
                                class="form-select @error('frequency') is-invalid @enderror" required>
                                <option value="mensual"
                                    {{ old('frequency', $maintenanceSchedule->frequency) == 'mensual' ? 'selected' : '' }}>
                                    Mensual (cada mes)</option>
                                <option value="trimestral"
                                    {{ old('frequency', $maintenanceSchedule->frequency) == 'trimestral' ? 'selected' : '' }}>
                                    Trimestral (cada 3 meses)</option>
                                <option value="semestral"
                                    {{ old('frequency', $maintenanceSchedule->frequency) == 'semestral' ? 'selected' : '' }}>
                                    Semestral (cada 6 meses)</option>
                                <option value="anual"
                                    {{ old('frequency', $maintenanceSchedule->frequency) == 'anual' ? 'selected' : '' }}>
                                    Anual (cada año)</option>
                            </select>
                            <small class="text-muted">Al cambiar la frecuencia, se recalculará automáticamente la próxima
                                fecha de mantenimiento</small>
                            @error('frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Estado</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1"
                                    {{ old('is_active', $maintenanceSchedule->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Programación Activa
                                </label>
                            </div>
                            <small class="text-muted">Desactiva la programación si no deseas recibir más alertas de
                                mantenimiento</small>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-2"></i>Notas Adicionales
                            </label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Observaciones o indicaciones especiales...">{{ old('notes', $maintenanceSchedule->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info actual -->
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Último Mantenimiento:</strong><br>
                                    {{ $maintenanceSchedule->last_maintenance_date ? $maintenanceSchedule->last_maintenance_date->format('d/m/Y') : 'Sin registro' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Próximo Mantenimiento:</strong><br>
                                    {{ $maintenanceSchedule->next_maintenance_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('maintenance-schedules.show', $maintenanceSchedule) }}"
                                class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
