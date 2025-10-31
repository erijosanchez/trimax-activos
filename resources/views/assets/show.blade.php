@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Detalle del Activo</h2>
            </div>

            <div class="row g-3">
                {{-- INFORMACIÓN GENERAL --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Informacion General</h5>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tr>
                                        <th>Código:</th>
                                        <td>{{ $asset->code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Categoría:</th>
                                        <td>{{ $asset->category->name }}</td>
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
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                @php
                    $categoryName = strtolower($asset->category->name);
                @endphp

                <!-- DETALLES PARA CELULAR -->
                @if ($categoryName === 'celular')
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Informacion del Celular</h5>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <tr>
                                            <th>IMEI 1:</th>
                                            <td>{{ $asset->imei }}</td>
                                        </tr>
                                        @if ($asset->imei_2)
                                            <tr>
                                                <th>IMEI 2:</th>
                                                <td>{{ $asset->imei_2 }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Número Telefónico:</th>
                                            <td>{{ $asset->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Operador:</th>
                                            <td>{{ $asset->operator_name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- DETALLES PARA PC O LAPTOP -->
                @if ($categoryName === 'pc' || $categoryName === 'laptop')
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Epecificaciones del PC</h5>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <tr>
                                            <th>Procesador:</th>
                                            <td>{{ $asset->processor }}</td>
                                        </tr>
                                        <tr>
                                            <th>Memoria RAM:</th>
                                            <td>{{ $asset->ram }}</td>
                                        </tr>
                                        <tr>
                                            <th>Almacenamiento:</th>
                                            <td>{{ $asset->storage }} ({{ $asset->storage_type }})</td>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- DETALLES PARA MONITOR -->
                @if ($categoryName === 'monitor')
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Especificaciones del Monitor</h5>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <tr>
                                            <th>Tamaño:</th>
                                            <td>{{ $asset->screen_size_monitor }}</td>
                                        </tr>
                                        <tr>
                                            <th>Resolución:</th>
                                            <td>{{ $asset->resolution }}</td>
                                        </tr>
                                        @if ($asset->panel_type)
                                            <tr>
                                                <th>Tipo de Panel:</th>
                                                <td>{{ $asset->panel_type }}</td>
                                            </tr>
                                        @endif
                                        @if ($asset->refresh_rate)
                                            <tr>
                                                <th>Tasa de Refresco:</th>
                                                <td>{{ $asset->refresh_rate }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- DETALLES PARA MOUSE O TECLADO -->
                @if ($categoryName === 'mouse' || $categoryName === 'teclado')
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Especificaciones Teclado</h5>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <tr>
                                            <th>Tipo de Conexión:</th>
                                            <td>{{ $asset->connection_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Inalámbrico:</th>
                                            <td>{{ $asset->is_wireless ? 'Sí' : 'No' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- DETALLES PARA AUDÍFONOS -->
                @if ($categoryName === 'audífonos')
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Especificaciones del Audifono</h5>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <tr>
                                            <th>Tipo de Conexión:</th>
                                            <td>{{ $asset->connection_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Inalámbrico:</th>
                                            <td>{{ $asset->is_wireless ? 'Sí' : 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tipo:</th>
                                            <td>{{ $asset->audio_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tiene Micrófono:</th>
                                            <td>{{ $asset->has_microphone ? 'Sí' : 'No' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- INFORMACIÓN ADICIONAL -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Informacion Adicional</h5>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tr>
                                        <th>Fecha de Compra:</th>
                                        <td>{{ $asset->purchase_date?->format('d/m/Y') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Precio de Compra:</th>
                                        <td>{{ $asset->purchase_price ? 'S/. ' . number_format($asset->purchase_price, 2) : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Estado:</th>
                                        <td>{{ $asset->status }}</td>
                                    </tr>
                                    <tr>
                                        <th>Observaciones:</th>
                                        <td>{{ $asset->observations ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Días Total de Uso:</th>
                                        <td>{{ $asset->total_usage_days }} días</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-5 pt-4 d-flex gap-2 ">
                    <a href="{{ route('asset.edit', $asset) }}" class="btn btn-warning animated-entry">Editar <i
                            class="fas fa-edit"></i></a>
                    <a href="{{ route('reports.asset.history.export', $asset) }}" class="btn btn-success">Descargar
                        Historial
                        Excel <i class="fas fa-file-excel"></i></a>
                </div>

                <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Asignaciones</h5>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            @if ($asset->assignmentHistory->count() > 0)
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Empleado</th>
                                            <th>Fecha Entrega</th>
                                            <th>Fecha Devolución</th>
                                            <th>Días Uso</th>
                                            <th>Estado Entrega</th>
                                            <th>Estado Devolución</th>
                                            <th>Documento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asset->assignmentHistory as $assignment)
                                            <tr>
                                                <td>{{ $assignment->employee->full_name }}</td>
                                                <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                                <td>{{ $assignment->returned_date?->format('d/m/Y') ?? 'En uso' }}</td>
                                                <td>{{ $assignment->usage_days }} días</td>
                                                <td>{{ $assignment->condition_on_assignment }}</td>
                                                <td>{{ $assignment->condition_on_return ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($assignment->responsibilityDocument)
                                                        <a href="{{ Storage::url($assignment->responsibilityDocument->document_path) }}"
                                                            target="_blank" class="btn btn-secondary">Ver PDF</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No hay historial de asignaciones para este activo.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <br>
                <a href="{{ route('asset.index') }}" class="btn btn-primary">Volver al listado</a>
            </div>
    </main>
@endsection
