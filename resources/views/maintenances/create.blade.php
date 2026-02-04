@extends('layouts.app')

@section('title', 'Nuevo Mantenimiento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('maintenances.index') }}">Mantenimientos</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Programar Mantenimiento</h1>
        <p class="page-subtitle">Registra un nuevo mantenimiento preventivo o correctivo</p>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Datos del Mantenimiento</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('maintenances.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="asset_id" class="form-label">
                                <i class="fas fa-box me-2"></i>Activo *
                            </label>
                            <select name="asset_id" id="asset_id"
                                class="form-select @error('asset_id') is-invalid @enderror" required>
                                <option value="">Seleccionar activo...</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}"
                                        {{ old('asset_id', request('asset_id')) == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->code }} - {{ $asset->brand }} {{ $asset->model }}
                                        @if ($asset->maintenanceSchedule)
                                            (Programación: {{ $asset->maintenanceSchedule->frequency_name }})
                                        @endif
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
                                    <i class="fas fa-tag me-2"></i>Tipo de Mantenimiento *
                                </label>
                                <select name="type" id="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="preventivo" {{ old('type') == 'preventivo' ? 'selected' : '' }}>
                                        Preventivo</option>
                                    <option value="correctivo" {{ old('type') == 'correctivo' ? 'selected' : '' }}>
                                        Correctivo</option>
                                    <option value="predictivo" {{ old('type') == 'predictivo' ? 'selected' : '' }}>
                                        Predictivo</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="scheduled_date" class="form-label">
                                    <i class="fas fa-calendar me-2"></i>Fecha Programada *
                                </label>
                                <input type="date" name="scheduled_date" id="scheduled_date"
                                    class="form-control @error('scheduled_date') is-invalid @enderror"
                                    value="{{ old('scheduled_date', now()->format('Y-m-d')) }}" required>
                                @error('scheduled_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="technician_id" class="form-label">
                                    <i class="fas fa-user-cog me-2"></i>Técnico Responsable *
                                </label>
                                <select name="technician_id" id="technician_id"
                                    class="form-select @error('technician_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar técnico...</option>
                                    @foreach ($technicians as $technician)
                                        <option value="{{ $technician->id }}"
                                            {{ old('technician_id', auth()->id()) == $technician->id ? 'selected' : '' }}>
                                            {{ $technician->name }} - {{ $technician->role_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('technician_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="user_id" class="form-label">
                                    <i class="fas fa-user me-2"></i>Usuario (Opcional)
                                </label>
                                <select name="user_id" id="user_id"
                                    class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">Ninguno</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                <i class="fas fa-align-left me-2"></i>Descripción del Mantenimiento *
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="form-control @error('description') is-invalid @enderror" placeholder="Describe las actividades a realizar..."
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="observations" class="form-label">
                                <i class="fas fa-clipboard me-2"></i>Observaciones
                            </label>
                            <textarea name="observations" id="observations" rows="2"
                                class="form-control @error('observations') is-invalid @enderror" placeholder="Observaciones adicionales...">{{ old('observations') }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Checklist Items -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-tasks me-2"></i>Checklist de Actividades (Opcional)
                            </label>
                            <div id="checklist-container">
                                <div class="input-group mb-2">
                                    <input type="text" name="checklist_items[]" class="form-control"
                                        placeholder="Ej: Verificar estado de componentes">
                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="this.parentElement.remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addChecklistItem()">
                                <i class="fas fa-plus me-2"></i>Agregar Item
                            </button>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Programar Mantenimiento
                            </button>
                            <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function addChecklistItem() {
                const container = document.getElementById('checklist-container');
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                div.innerHTML = `
                    <input type="text" name="checklist_items[]" class="form-control" placeholder="Nueva actividad...">
                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                container.appendChild(div);
            }
        </script>
    @endpush
@endsection
