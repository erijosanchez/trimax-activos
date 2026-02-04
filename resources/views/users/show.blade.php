@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Perfil de Usuario</h1>
        <p class="page-subtitle">Información detallada del usuario</p>
    </div>

    <div class="row g-4">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información Personal</h5>
                    <div>
                        @if ($user->is_active)
                            <span class="badge" style="background: #d1fae5; color: #065f46; font-size: 1rem;">
                                <i class="fas fa-check-circle me-1"></i>Activo
                            </span>
                        @else
                            <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 1rem;">
                                <i class="fas fa-times-circle me-1"></i>Inactivo
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Avatar y Nombre -->
                    <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                        <div class="user-avatar me-3"
                            style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 2rem;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="mb-1">{{ $user->name }}</h3>
                            <div class="mb-2">
                                <span class="badge role-{{ $user->role }}" style="font-size: 0.9rem;">
                                    {{ $user->role_name }}
                                </span>
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                            </div>
                        </div>
                    </div>

                    <!-- Detalles -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Teléfono</label>
                            <div class="fw-medium">{{ $user->phone ?: 'No especificado' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Cargo</label>
                            <div class="fw-medium">{{ $user->position ?: 'No especificado' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Departamento</label>
                            <div class="fw-medium">{{ $user->department ?: 'No especificado' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Último Login</label>
                            <div class="fw-medium">
                                @if ($user->last_login_at)
                                    {{ $user->last_login_at->format('d/m/Y H:i') }}
                                    <small class="text-muted">({{ $user->last_login_at->diffForHumans() }})</small>
                                @else
                                    <span class="text-muted">Nunca</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Fecha de Registro</label>
                            <div class="fw-medium">{{ $user->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad del Usuario -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Actividad</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #f8fafc; border-radius: 8px;">
                                <div class="fs-3 fw-bold text-primary">{{ $user->maintenancesAsTechnician->count() }}</div>
                                <div class="text-muted small">Mantenimientos Realizados</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #f8fafc; border-radius: 8px;">
                                <div class="fs-3 fw-bold text-success">
                                    {{ $user->maintenancesAsTechnician->where('status', 'completado')->count() }}</div>
                                <div class="text-muted small">Completados</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #f8fafc; border-radius: 8px;">
                                <div class="fs-3 fw-bold text-warning">
                                    {{ $user->maintenancesAsTechnician->where('status', 'programado')->count() }}</div>
                                <div class="text-muted small">Pendientes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Acciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar Usuario
                        </a>

                        @if ($user->id !== auth()->id())
                            <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="btn btn-outline-{{ $user->is_active ? 'danger' : 'success' }} w-100">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} me-2"></i>
                                    {{ $user->is_active ? 'Desactivar' : 'Activar' }} Usuario
                                </button>
                            </form>

                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Eliminar Usuario
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Permisos del Rol -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Permisos</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Ver Dashboard</span>
                            <i class="fas fa-check text-success"></i>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Gestionar Inventario</span>
                            @if ($user->canManageInventory())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Realizar Mantenimientos</span>
                            @if ($user->canPerformMaintenance())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Gestionar Usuarios</span>
                            @if ($user->isAdmin())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
