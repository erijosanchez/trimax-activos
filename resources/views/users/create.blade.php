@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Crear Nuevo Usuario</h1>
        <p class="page-subtitle">Registra un nuevo usuario en el sistema</p>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Datos del Usuario</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <!-- Información Personal -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Información Personal
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        Nombre Completo *
                                    </label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        required placeholder="Ej: Juan Pérez García">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        Email *
                                    </label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required placeholder="usuario@trimax.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        Teléfono
                                    </label>
                                    <input type="text" name="phone" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" placeholder="999 999 999">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="position" class="form-label">
                                        Cargo
                                    </label>
                                    <input type="text" name="position" id="position"
                                        class="form-control @error('position') is-invalid @enderror"
                                        value="{{ old('position') }}" placeholder="Ej: Técnico de Soporte">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Información del Sistema -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-shield-alt me-2"></i>Información del Sistema
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">
                                        Rol del Usuario *
                                    </label>
                                    <select name="role" id="role"
                                        class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="">Seleccionar rol...</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Administrador - Acceso total
                                        </option>
                                        <option value="ti" {{ old('role') == 'ti' ? 'selected' : '' }}>
                                            TI - Gestión técnica y mantenimiento
                                        </option>
                                        <option value="servicios_generales"
                                            {{ old('role') == 'servicios_generales' ? 'selected' : '' }}>
                                            Servicios Generales - Gestión de inventario
                                        </option>
                                        <option value="marketing" {{ old('role') == 'marketing' ? 'selected' : '' }}>
                                            Marketing - Gestión de inventario
                                        </option>
                                        <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>
                                            Visualizador - Solo lectura
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">
                                        Departamento
                                    </label>
                                    <input type="text" name="department" id="department"
                                        class="form-control @error('department') is-invalid @enderror"
                                        value="{{ old('department') }}" placeholder="Ej: Tecnología, Marketing">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-lock me-2"></i>Contraseña
                            </h6>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                La contraseña debe tener al menos 8 caracteres.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        Contraseña *
                                    </label>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" required
                                        minlength="8" placeholder="Mínimo 8 caracteres">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        Confirmar Contraseña *
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" required minlength="8" placeholder="Repite la contraseña">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Crear Usuario
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-info"></i>Descripción de Roles</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <span class="badge role-admin mb-2">Administrador</span>
                                <p class="small text-muted mb-0">Acceso completo al sistema, gestión de usuarios y
                                    configuración.</p>
                            </div>
                            <div class="mb-3">
                                <span class="badge role-ti mb-2">TI</span>
                                <p class="small text-muted mb-0">Gestión de activos, mantenimientos y asignaciones.</p>
                            </div>
                            <div class="mb-3">
                                <span class="badge role-servicios_generales mb-2">Servicios Generales</span>
                                <p class="small text-muted mb-0">Gestión de inventario de su departamento.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <span class="badge role-marketing mb-2">Marketing</span>
                                <p class="small text-muted mb-0">Gestión de inventario de su departamento.</p>
                            </div>
                            <div class="mb-3">
                                <span class="badge role-viewer mb-2">Visualizador</span>
                                <p class="small text-muted mb-0">Solo puede ver información, sin permisos de edición.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
