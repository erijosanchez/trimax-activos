@extends('layouts.app')

@section('title', 'Editar Mantenimiento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('maintenances.index') }}">Mantenimientos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('maintenances.show', $maintenance) }}">Detalle</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Editar Mantenimiento #{{ $maintenance->id }}</h1>
        <p class="page-subtitle">Actualiza la información del mantenimiento</p>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Datos del Mantenimiento</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="asset_id" class="form-label">
                                <i class="fas fa-box me-2"></i>Activo *
                            </label>
                            <select name="asset_id" id="asset_id"
                                class="form-select @error('asset_id') is-invalid @enderror" required>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}"
                                        {{ old('asset_id', $maintenance->asset_id) == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->code }} - {{ $asset->brand }} {{ $asset->model }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="type" class="form-label">
                                    <i class="fas fa-tag me-2"></i>Tipo *
                                </label>
                                <select name="type" id="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="preventivo"
                                        {{ old('type', $maintenance->type) == 'preventivo' ? 'selected' : '' }}>Preventivo
                                    </option>
                                    <option value="correctivo"
                                        {{ old('type', $maintenance->type) == 'correctivo' ? 'selected' : '' }}>Correctivo
                                    </option>
                                    <option value="predictivo"
                                        {{ old('type', $maintenance->type) == 'predictivo' ? 'selected' : '' }}>Predictivo
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label">
                                    <i class="fas fa-flag me-2"></i>Estado *
                                </label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="programado"
                                        {{ old('status', $maintenance->status) == 'programado' ? 'selected' : '' }}>
                                        Programado</option>
                                    <option value="en_proceso"
                                        {{ old('status', $maintenance->status) == 'en_proceso' ? 'selected' : '' }}>En
                                        Proceso</option>
                                    <option value="completado"
                                        {{ old('status', $maintenance->status) == 'completado' ? 'selected' : '' }}>
                                        Completado</option>
                                    <option value="cancelado"
                                        {{ old('status', $maintenance->status) == 'cancelado' ? 'selected' : '' }}>
                                        Cancelado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="scheduled_date" class="form-label">
                                    <i class="fas fa-calendar me-2"></i>Fecha Programada *
                                </label>
                                <input type="date" name="scheduled_date" id="scheduled_date"
                                    class="form-control @error('scheduled_date') is-invalid @enderror"
                                    value="{{ old('scheduled_date', $maintenance->scheduled_date->format('Y-m-d')) }}"
                                    required>
                                @error('scheduled_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="completed_date" class="form-label">
                                    <i class="fas fa-calendar-check me-2"></i>Fecha Completado
                                </label>
                                <input type="date" name="completed_date" id="completed_date"
                                    class="form-control @error('completed_date') is-invalid @enderror"
                                    value="{{ old('completed_date', $maintenance->completed_date?->format('Y-m-d')) }}">
                                @error('completed_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="technician_id" class="form-label">
                                    <i class="fas fa-user-cog me-2"></i>Técnico *
                                </label>
                                <select name="technician_id" id="technician_id"
                                    class="form-select @error('technician_id') is-invalid @enderror" required>
                                    @foreach ($technicians as $technician)
                                        <option value="{{ $technician->id }}"
                                            {{ old('technician_id', $maintenance->technician_id) == $technician->id ? 'selected' : '' }}>
                                            {{ $technician->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('technician_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="user_id" class="form-label">
                                    <i class="fas fa-user me-2"></i>Usuario
                                </label>
                                <select name="user_id" id="user_id"
                                    class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">Ninguno</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $maintenance->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Descripción *
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $maintenance->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="activities_performed" class="form-label">
                                <i class="fas fa-tasks me-2"></i>Actividades Realizadas
                            </label>
                            <textarea name="activities_performed" id="activities_performed" rows="3"
                                class="form-control @error('activities_performed') is-invalid @enderror">{{ old('activities_performed', $maintenance->activities_performed) }}</textarea>
                            @error('activities_performed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="observations" class="form-label">
                                <i class="fas fa-clipboard me-2"></i>Observaciones
                            </label>
                            <textarea name="observations" id="observations" rows="2"
                                class="form-control @error('observations') is-invalid @enderror">{{ old('observations', $maintenance->observations) }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="recommendations" class="form-label">
                                <i class="fas fa-lightbulb me-2"></i>Recomendaciones
                            </label>
                            <textarea name="recommendations" id="recommendations" rows="2"
                                class="form-control @error('recommendations') is-invalid @enderror">{{ old('recommendations', $maintenance->recommendations) }}</textarea>
                            @error('recommendations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="cost" class="form-label">
                                    <i class="fas fa-dollar-sign me-2"></i>Costo (S/)
                                </label>
                                <input type="number" name="cost" id="cost"
                                    class="form-control @error('cost') is-invalid @enderror" step="0.01"
                                    min="0" value="{{ old('cost', $maintenance->cost) }}" placeholder="0.00">
                                @error('cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="duration_minutes" class="form-label">
                                    <i class="fas fa-clock me-2"></i>Duración (minutos)
                                </label>
                                <input type="number" name="duration_minutes" id="duration_minutes"
                                    class="form-control @error('duration_minutes') is-invalid @enderror" min="0"
                                    value="{{ old('duration_minutes', $maintenance->duration_minutes) }}"
                                    placeholder="60">
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('maintenances.show', $maintenance) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
