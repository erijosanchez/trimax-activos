@extends('layouts.app')

@section('title', 'Detalle de Mantenimiento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('maintenances.index') }}">Mantenimientos</a></li>
    <li class="breadcrumb-item active">Detalle</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Detalle del Mantenimiento #{{ $maintenance->id }}</h1>
        <p class="page-subtitle">Información completa del mantenimiento</p>
    </div>

    <div class="row g-4">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Mantenimiento</h5>
                    <div>
                        @if ($maintenance->status == 'programado')
                            <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 1rem;">
                                <i class="fas fa-clock me-1"></i>{{ $maintenance->status_name }}
                            </span>
                        @elseif($maintenance->status == 'en_proceso')
                            <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 1rem;">
                                <i class="fas fa-spinner me-1"></i>{{ $maintenance->status_name }}
                            </span>
                        @elseif($maintenance->status == 'completado')
                            <span class="badge" style="background: #d1fae5; color: #065f46; font-size: 1rem;">
                                <i class="fas fa-check me-1"></i>{{ $maintenance->status_name }}
                            </span>
                        @else
                            <span class="badge" style="background: #f3f4f6; color: #374151; font-size: 1rem;">
                                <i class="fas fa-times me-1"></i>{{ $maintenance->status_name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Activo</label>
                            <div class="fw-bold">{{ $maintenance->asset->code }}</div>
                            <div>{{ $maintenance->asset->brand }} {{ $maintenance->asset->model }}</div>
                            <small class="text-muted">{{ $maintenance->asset->category->name }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tipo de Mantenimiento</label>
                            <div>
                                @if ($maintenance->type == 'preventivo')
                                    <span class="badge" style="background: #dbeafe; color: #1e40af;">Preventivo</span>
                                @elseif($maintenance->type == 'correctivo')
                                    <span class="badge" style="background: #fee2e2; color: #991b1b;">Correctivo</span>
                                @else
                                    <span class="badge" style="background: #fce7f3; color: #9f1239;">Predictivo</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Técnico Responsable</label>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2"
                                    style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ strtoupper(substr($maintenance->technician->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $maintenance->technician->name }}</div>
                                    <small class="text-muted">{{ $maintenance->technician->role_name }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Usuario</label>
                            @if ($maintenance->user)
                                <div class="fw-medium">{{ $maintenance->user->name }}</div>
                                <small class="text-muted">{{ $maintenance->user->email }}</small>
                            @else
                                <div class="text-muted">No asignado</div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Fecha Programada</label>
                            <div class="fw-bold">{{ $maintenance->scheduled_date->format('d/m/Y') }}</div>
                            @if ($maintenance->isOverdue() && $maintenance->status == 'programado')
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Vencido hace {{ now()->diffInDays($maintenance->scheduled_date) }} días
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Fecha Completado</label>
                            @if ($maintenance->completed_date)
                                <div class="fw-bold">{{ $maintenance->completed_date->format('d/m/Y') }}</div>
                            @else
                                <div class="text-muted">Pendiente</div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted small">Descripción</label>
                        <div>{{ $maintenance->description }}</div>
                    </div>

                    @if ($maintenance->observations)
                        <div class="mb-3">
                            <label class="text-muted small">Observaciones</label>
                            <div>{{ $maintenance->observations }}</div>
                        </div>
                    @endif

                    @if ($maintenance->activities_performed)
                        <div class="mb-3">
                            <label class="text-muted small">Actividades Realizadas</label>
                            <div>{{ $maintenance->activities_performed }}</div>
                        </div>
                    @endif

                    @if ($maintenance->recommendations)
                        <div class="mb-3">
                            <label class="text-muted small">Recomendaciones</label>
                            <div>{{ $maintenance->recommendations }}</div>
                        </div>
                    @endif

                    @if ($maintenance->cost)
                        <div class="mb-3">
                            <label class="text-muted small">Costo</label>
                            <div class="fw-bold">S/ {{ number_format($maintenance->cost, 2) }}</div>
                        </div>
                    @endif

                    @if ($maintenance->duration_minutes)
                        <div class="mb-3">
                            <label class="text-muted small">Duración</label>
                            <div>{{ $maintenance->duration_minutes }} minutos</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Checklist -->
            @if ($maintenance->checklists->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Checklist de Actividades</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach ($maintenance->checklists as $item)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $item->is_checked ? 'checked' : '' }} disabled>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <div
                                            class="{{ $item->is_checked ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $item->item }}
                                        </div>
                                        @if ($item->notes)
                                            <small class="text-muted">{{ $item->notes }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Partes Reemplazadas -->
            @if ($maintenance->parts_replaced && count($maintenance->parts_replaced) > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Partes Reemplazadas</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach ($maintenance->parts_replaced as $part)
                                <li><i class="fas fa-check text-success me-2"></i>{{ $part }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Acciones -->
            @can('canPerformMaintenance', auth()->user())
                @if ($maintenance->status != 'completado' && $maintenance->status != 'cancelado')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Acciones</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if ($maintenance->status == 'programado')
                                    <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="en_proceso">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-play me-2"></i>Iniciar Mantenimiento
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Editar
                                </a>

                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#completeModal">
                                    <i class="fas fa-check me-2"></i>Completar Mantenimiento
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

            <!-- Firmas -->
            @if ($maintenance->status == 'completado')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-signature me-2"></i>Firmas Digitales</h5>
                    </div>
                    <div class="card-body">
                        <!-- Firma del Técnico -->
                        <div class="mb-3 p-3" style="background: #f8fafc; border-radius: 8px;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-bold">Técnico</div>
                                @if ($maintenance->technician_signed_at)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Firmado
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Pendiente
                                    </span>
                                @endif
                            </div>
                            <div class="text-muted small">{{ $maintenance->technician->name }}</div>
                            @if ($maintenance->technician_signed_at)
                                <div class="text-muted small">
                                    {{ $maintenance->technician_signed_at->format('d/m/Y H:i') }}
                                </div>
                            @else
                                @if (auth()->id() == $maintenance->technician_id || auth()->user()->isAdmin())
                                    <form action="{{ route('maintenances.sign-technician', $maintenance) }}"
                                        method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success w-100">
                                            <i class="fas fa-pen me-1"></i>Firmar como Técnico
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>

                        <!-- Firma del Usuario -->
                        @if ($maintenance->user_id)
                            <div class="p-3" style="background: #f8fafc; border-radius: 8px;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="fw-bold">Usuario</div>
                                    @if ($maintenance->user_signed_at)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Firmado
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Pendiente
                                        </span>
                                    @endif
                                </div>
                                <div class="text-muted small">{{ $maintenance->user->name }}</div>
                                @if ($maintenance->user_signed_at)
                                    <div class="text-muted small">
                                        {{ $maintenance->user_signed_at->format('d/m/Y H:i') }}
                                    </div>
                                @else
                                    @if (auth()->id() == $maintenance->user_id || auth()->user()->isAdmin())
                                        <form action="{{ route('maintenances.sign-user', $maintenance) }}" method="POST"
                                            class="mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success w-100">
                                                <i class="fas fa-pen me-1"></i>Firmar como Usuario
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        @endif

                        @if ($maintenance->isFullySigned())
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Mantenimiento Completo</strong><br>
                                <small>Todas las firmas han sido completadas</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Generar Acta -->
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('maintenances.document', $maintenance) }}" class="btn btn-danger w-100"
                            target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>Generar Acta PDF
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Completar -->
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('maintenances.complete', $maintenance) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle me-2"></i>Completar Mantenimiento
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Actividades Realizadas *</label>
                            <textarea name="activities_performed" class="form-control" rows="4" required
                                placeholder="Describe las actividades que se realizaron..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="2" placeholder="Observaciones adicionales..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Recomendaciones</label>
                            <textarea name="recommendations" class="form-control" rows="2"
                                placeholder="Recomendaciones para el usuario..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Costo (S/)</label>
                                <input type="number" name="cost" class="form-control" step="0.01" min="0"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Duración (minutos)</label>
                                <input type="number" name="duration_minutes" class="form-control" min="0"
                                    placeholder="60">
                            </div>
                        </div>

                        <!-- Checklist -->
                        @if ($maintenance->checklists->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Marcar Actividades Completadas</label>
                                <div class="list-group">
                                    @foreach ($maintenance->checklists as $item)
                                        <div class="list-group-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="checklist[{{ $item->id }}]" value="1"
                                                    id="check{{ $item->id }}">
                                                <label class="form-check-label" for="check{{ $item->id }}">
                                                    {{ $item->item }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Completar Mantenimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
