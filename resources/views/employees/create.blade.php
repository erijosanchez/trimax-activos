@extends('layouts.app')

@section('title', 'Nuevo Empleado')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Empleados</a></li>
    <li class="breadcrumb-item active">Nuevo Empleado</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Nuevo Empleado</h1>
        <p class="page-subtitle">Registra un nuevo empleado en el sistema</p>
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

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <!-- Información Personal -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">DNI <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="dni" value="{{ old('dni') }}" maxlength="8"
                            pattern="[0-9]{8}" placeholder="12345678" required>
                        <small class="text-muted">Ingrese 8 dígitos</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="first_name" value="{{ old('first_name') }}"
                            placeholder="Nombres del empleado" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Apellido <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="last_name" value="{{ old('last_name') }}"
                            placeholder="Apellidos del empleado" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                            placeholder="ejemplo@trimax.com.pe" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" type="text" name="phone" value="{{ old('phone') }}"
                            placeholder="999 999 999" maxlength="15">
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Laboral -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Información Laboral</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Departamento</label>
                        <input class="form-control" type="text" name="department" value="{{ old('department') }}"
                            placeholder="Ej: Tecnología, Administración, Ventas">
                        <small class="text-muted">Área o departamento al que pertenece</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cargo</label>
                        <input class="form-control" type="text" name="position" value="{{ old('position') }}"
                            placeholder="Ej: Analista, Gerente, Asistente">
                        <small class="text-muted">Posición o rol del empleado</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-select" name="active" required>
                            <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>
                                Inactivo
                            </option>
                        </select>
                        <small class="text-muted">Los empleados inactivos no pueden recibir nuevas asignaciones</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i>Guardar Empleado
            </button>
        </div>
    </form>
@endsection
