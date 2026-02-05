@extends('layouts.app')

@section('title', 'Detalle del Activo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('asset.index') }}">Activos</a></li>
    <li class="breadcrumb-item active">{{ $asset->code }}</li>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title">Detalle del Activo</h1>
                <p class="page-subtitle">Información completa del activo {{ $asset->code }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('asset.edit', $asset) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('reports.asset.history.export', $asset) }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Descargar Historial
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @php
            $categoryName = strtolower($asset->category->name);
        @endphp

        {{-- INFORMACIÓN GENERAL --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th width="45%">Código:</th>
                                <td><span class="fw-bold">{{ $asset->code }}</span></td>
                            </tr>
                            <tr>
                                <th>Categoría:</th>
                                <td>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                        {{ $asset->category->name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Marca:</th>
                                <td>{{ $asset->brand }}</td>
                            </tr>
                            <tr>
                                <th>Modelo:</th>
                                <td>{{ $asset->model }}</td>
                            </tr>
                            <tr>
                                <th>Número de Serie:</th>
                                <td>{{ $asset->serial_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    @if ($asset->status === 'available')
                                        <span class="badge" style="background: #d1fae5; color: #065f46;">
                                            <i class="fas fa-check-circle me-1"></i>Disponible
                                        </span>
                                    @elseif($asset->status === 'assigned')
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                            <i class="fas fa-user-check me-1"></i>Asignado
                                        </span>
                                    @elseif($asset->status === 'maintenance')
                                        <span class="badge" style="background: #fef3c7; color: #92400e;">
                                            <i class="fas fa-tools me-1"></i>Mantenimiento
                                        </span>
                                    @elseif($asset->status === 'damaged')
                                        <span class="badge" style="background: #fee2e2; color: #991b1b;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Dañado
                                        </span>
                                    @else
                                        <span class="badge" style="background: #f3f4f6; color: #374151;">
                                            <i class="fas fa-times-circle me-1"></i>Retirado
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Código de barras -->
                    <div class="text-center mt-4 p-3"
                        style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <h6 class="mb-3 fw-bold"><i class="fas fa-barcode me-2"></i>Código de Barras</h6>
                        <img src="{{ route('asset.barcode', $asset) }}" alt="Código de barras" class="img-fluid mb-3"
                            style="max-width: 250px;">
                        <br>
                        <a href="{{ route('asset.barcode.download', $asset) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download me-2"></i>Descargar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETALLES PARA CELULAR -->
        @if ($categoryName === 'celular')
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                        <h5 class="mb-0" style="color: #1e40af;">
                            <i class="fas fa-mobile-alt me-2"></i>Información del Celular
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th width="45%">IMEI 1:</th>
                                    <td><code>{{ $asset->imei }}</code></td>
                                </tr>
                                @if ($asset->imei_2)
                                    <tr>
                                        <th>IMEI 2:</th>
                                        <td><code>{{ $asset->imei_2 }}</code></td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Número Telefónico:</th>
                                    <td>{{ $asset->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Operador:</th>
                                    <td>
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                            {{ $asset->operator_name }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- DETALLES PARA PC O LAPTOP -->
        @if ($categoryName === 'pc' || $categoryName === 'laptop')
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);">
                        <h5 class="mb-0" style="color: #3730a3;">
                            <i class="fas fa-laptop me-2"></i>Especificaciones Técnicas
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th width="45%">Procesador:</th>
                                    <td>{{ $asset->processor }}</td>
                                </tr>
                                <tr>
                                    <th>Memoria RAM:</th>
                                    <td><span class="badge bg-secondary">{{ $asset->ram }}</span></td>
                                </tr>
                                <tr>
                                    <th>Almacenamiento:</th>
                                    <td>
                                        {{ $asset->storage }}
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                            {{ $asset->storage_type }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($asset->graphics_card)
                                    <tr>
                                        <th>Tarjeta Gráfica:</th>
                                        <td>{{ $asset->graphics_card }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Sistema Operativo:</th>
                                    <td>{{ $asset->operating_system }}</td>
                                </tr>
                                @if ($categoryName === 'laptop' && $asset->screen_size)
                                    <tr>
                                        <th>Tamaño de Pantalla:</th>
                                        <td>{{ $asset->screen_size }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- DETALLES PARA MONITOR -->
        @if ($categoryName === 'monitor')
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                        <h5 class="mb-0" style="color: #065f46;">
                            <i class="fas fa-desktop me-2"></i>Especificaciones del Monitor
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th width="45%">Tamaño:</th>
                                    <td>{{ $asset->screen_size_monitor }}</td>
                                </tr>
                                <tr>
                                    <th>Resolución:</th>
                                    <td><code>{{ $asset->resolution }}</code></td>
                                </tr>
                                @if ($asset->panel_type)
                                    <tr>
                                        <th>Tipo de Panel:</th>
                                        <td>
                                            <span class="badge" style="background: #d1fae5; color: #065f46;">
                                                {{ $asset->panel_type }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                                @if ($asset->refresh_rate)
                                    <tr>
                                        <th>Tasa de Refresco:</th>
                                        <td>{{ $asset->refresh_rate }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- DETALLES PARA MOUSE O TECLADO -->
        @if ($categoryName === 'mouse' || $categoryName === 'teclado')
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                        <h5 class="mb-0" style="color: #92400e;">
                            <i class="fas fa-keyboard me-2"></i>Especificaciones del Periférico
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th width="45%">Tipo de Conexión:</th>
                                    <td>{{ $asset->connection_type }}</td>
                                </tr>
                                <tr>
                                    <th>Inalámbrico:</th>
                                    <td>
                                        @if ($asset->is_wireless)
                                            <span class="badge" style="background: #d1fae5; color: #065f46;">
                                                <i class="fas fa-check me-1"></i>Sí
                                            </span>
                                        @else
                                            <span class="badge" style="background: #f3f4f6; color: #374151;">
                                                <i class="fas fa-times me-1"></i>No
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- DETALLES PARA AUDÍFONOS -->
        @if ($categoryName === 'audífonos')
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header" style="background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);">
                        <h5 class="mb-0" style="color: #9f1239;">
                            <i class="fas fa-headphones me-2"></i>Especificaciones de los Audífonos
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th width="45%">Tipo de Conexión:</th>
                                    <td>{{ $asset->connection_type }}</td>
                                </tr>
                                <tr>
                                    <th>Inalámbrico:</th>
                                    <td>
                                        @if ($asset->is_wireless)
                                            <span class="badge" style="background: #d1fae5; color: #065f46;">
                                                <i class="fas fa-check me-1"></i>Sí
                                            </span>
                                        @else
                                            <span class="badge" style="background: #f3f4f6; color: #374151;">
                                                <i class="fas fa-times me-1"></i>No
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>{{ $asset->audio_type }}</td>
                                </tr>
                                <tr>
                                    <th>Tiene Micrófono:</th>
                                    <td>
                                        @if ($asset->has_microphone)
                                            <span class="badge" style="background: #d1fae5; color: #065f46;">
                                                <i class="fas fa-check me-1"></i>Sí
                                            </span>
                                        @else
                                            <span class="badge" style="background: #f3f4f6; color: #374151;">
                                                <i class="fas fa-times me-1"></i>No
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- INFORMACIÓN ADICIONAL -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Información Adicional</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th width="45%">Fecha de Compra:</th>
                                <td>{{ $asset->purchase_date?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Precio de Compra:</th>
                                <td>
                                    @if ($asset->purchase_price)
                                        <span class="fw-bold text-success">
                                            S/. {{ number_format($asset->purchase_price, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Días Total de Uso:</th>
                                <td>
                                    <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                        <i class="fas fa-calendar-days me-1"></i>{{ $asset->total_usage_days }} días
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Observaciones:</th>
                                <td>
                                    <small class="text-muted">{{ $asset->observations ?? 'Sin observaciones' }}</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORIAL DE ASIGNACIONES -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Asignaciones</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                @if ($asset->assignmentHistory->count() > 0)
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th class="border-0">Empleado</th>
                                <th class="border-0">Fecha Entrega</th>
                                <th class="border-0">Fecha Devolución</th>
                                <th class="border-0">Días Uso</th>
                                <th class="border-0">Estado Entrega</th>
                                <th class="border-0">Estado Devolución</th>
                                <th class="border-0 text-center">Documento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asset->assignmentHistory as $assignment)
                                <tr>
                                    <td class="fw-medium">{{ $assignment->employee->full_name }}</td>
                                    <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($assignment->returned_date)
                                            {{ $assignment->returned_date->format('d/m/Y') }}
                                        @else
                                            <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                                <i class="fas fa-clock me-1"></i>En uso
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #d1fae5; color: #065f46;">
                                            {{ $assignment->usage_days }} días
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                            {{ $assignment->condition_on_assignment }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($assignment->condition_on_return)
                                            <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                                {{ $assignment->condition_on_return }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($assignment->responsibilityDocument)
                                            <a href="{{ Storage::url($assignment->responsibilityDocument->document_path) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-pdf me-1"></i>Ver PDF
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <p class="mb-0">No hay historial de asignaciones para este activo.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Botón volver -->
    <div class="mt-4">
        <a href="{{ route('asset.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver al listado
        </a>
    </div>
@endsection
