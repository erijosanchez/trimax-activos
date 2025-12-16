@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Detalle de Asignación</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('assignments.generate-delivery-document', $assignment) }}"
                        class="btn btn-success animated-entry">
                        <i class="fas fa-file-word"></i> Generar Acta de Entrega
                    </a>
                    @if (!$assignment->is_active && $assignment->returned_date)
                        <a href="{{ route('assignments.generate-return-document', $assignment) }}"
                            class="btn btn-warning animated-entry">
                            <i class="fas fa-file-word"></i> Generar Acta de Devolución
                        </a>
                    @endif
                    <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary animated-entry">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="row g-3">
                {{-- INFORMACIÓN DEL ACTIVO --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-laptop me-2"></i>Información del Activo</h5>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-hover mb-0">
                                <tr>
                                    <th>Código:</th>
                                    <td>{{ $assignment->asset->code }}</td>
                                </tr>
                                <tr>
                                    <th>Categoría:</th>
                                    <td>{{ $assignment->asset->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Activo:</th>
                                    <td>{{ $assignment->asset->brand }} {{ $assignment->asset->model }}</td>
                                </tr>
                                <tr>
                                    <th>Serie:</th>
                                    <td>{{ $assignment->asset->serial_number }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- INFORMACIÓN DEL EMPLEADO --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información del Empleado</h5>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-hover mb-0">
                                <tr>
                                    <th>DNI:</th>
                                    <td>{{ $assignment->employee->dni }}</td>
                                </tr>
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $assignment->employee->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Departamento:</th>
                                    <td>{{ $assignment->employee->department }}</td>
                                </tr>
                                <tr>
                                    <th>Cargo:</th>
                                    <td>{{ $assignment->employee->position }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- INFORMACIÓN DE LA ASIGNACIÓN --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Información de la Asignación</h5>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-hover mb-0">
                                <tr>
                                    <th>Fecha de Entrega:</th>
                                    <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Devolución:</th>
                                    <td>{{ $assignment->returned_date?->format('d/m/Y') ?? 'En uso' }}</td>
                                </tr>
                                <tr>
                                    <th>Días de Uso:</th>
                                    <td>{{ $assignment->usage_days }} días</td>
                                </tr>
                                <tr>
                                    <th>Condición al Entregar:</th>
                                    <td>{{ $assignment->condition_on_assignment }}</td>
                                </tr>
                                <tr>
                                    <th>Condición al Devolver:</th>
                                    <td>{{ $assignment->condition_on_return ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- SUBIR DOCUMENTO DE ENTREGA --}}
                <div class="col-12 col-lg-6">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Documento de Entrega</h5>
                        </div>
                        <div class="card-body p-3">
                            @if ($assignment->deliveryDocument)
                                {{-- SI YA TIENE DOCUMENTO --}}
                                <div class="alert alert-success mb-3">
                                    <i class="fas fa-check-circle"></i> Documento ya subido
                                </div>
                                <table class="table table-hover mb-0">
                                    <tr>
                                        <th>Número:</th>
                                        <td>{{ $assignment->deliveryDocument->document_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Firma:</th>
                                        <td>{{ $assignment->deliveryDocument->signed_date->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Notas:</th>
                                        <td>{{ $assignment->deliveryDocument->notes ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Documento:</th>
                                        <td>
                                            <a href="{{ Storage::url($assignment->deliveryDocument->document_path) }}"
                                                target="_blank" class="btn btn-sm btn-success">
                                                <i class="fas fa-file-pdf"></i> Ver PDF
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                {{-- FORMULARIO PARA SUBIR --}}
                                <div class="alert alert-warning mb-3">
                                    <i class="fas fa-exclamation-triangle"></i> No se ha subido el acta de entrega firmada
                                </div>
                                <form action="{{ route('assignments.upload-document', $assignment) }}" method="POST"
                                    enctype="multipart/form-data" class="p-2">
                                    @csrf
                                    <input type="hidden" name="document_type" value="delivery">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Número de Documento: *</label>
                                        <input class="form-control" type="text" name="document_number" required
                                            placeholder="Ej: ACTA-ENT-{{ $assignment->asset->code }}-{{ date('Y') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Firma: *</label>
                                        <input class="form-control" type="date" name="signed_date"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Archivo PDF Firmado: *</label>
                                        <input class="form-control" type="file" name="document_file" accept=".pdf" required>
                                        <small class="text-muted">
                                            <i class="fas fa-file-pdf"></i> Solo PDF, máximo 5MB
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Notas:</label>
                                        <textarea class="form-control" name="document_notes" rows="2"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-upload"></i> Subir Acta de Entrega
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- DOCUMENTO DE DEVOLUCIÓN O FORMULARIO DE DEVOLUCIÓN --}}
                @if (!$assignment->is_active && $assignment->returned_date)
                    {{-- YA ESTÁ DEVUELTO - MOSTRAR FORMULARIO PARA SUBIR DOCUMENTO --}}
                    <div class="col-12 col-lg-6">
                        <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                            <div class="card-header bg-warning">
                                <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Documento de Devolución</h5>
                            </div>
                            <div class="card-body p-3">
                                @if ($assignment->returnDocument)
                                    {{-- SI YA TIENE DOCUMENTO --}}
                                    <div class="alert alert-success mb-3">
                                        <i class="fas fa-check-circle"></i> Documento ya subido
                                    </div>
                                    <table class="table table-hover mb-0">
                                        <tr>
                                            <th>Número:</th>
                                            <td>{{ $assignment->returnDocument->document_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Fecha de Firma:</th>
                                            <td>{{ $assignment->returnDocument->signed_date->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Notas:</th>
                                            <td>{{ $assignment->returnDocument->notes ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Documento:</th>
                                            <td>
                                                <a href="{{ Storage::url($assignment->returnDocument->document_path) }}"
                                                    target="_blank" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-file-pdf"></i> Ver PDF
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                @else
                                    {{-- FORMULARIO PARA SUBIR --}}
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-exclamation-triangle"></i> No se ha subido el acta de devolución firmada
                                    </div>
                                    <form action="{{ route('assignments.upload-document', $assignment) }}" method="POST"
                                        enctype="multipart/form-data" class="p-2">
                                        @csrf
                                        <input type="hidden" name="document_type" value="return">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Número de Documento: *</label>
                                            <input class="form-control" type="text" name="document_number" required
                                                placeholder="Ej: ACTA-DEV-{{ $assignment->asset->code }}-{{ date('Y') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Fecha de Firma: *</label>
                                            <input class="form-control" type="date" name="signed_date"
                                                value="{{ date('Y-m-d') }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Archivo PDF Firmado: *</label>
                                            <input class="form-control" type="file" name="document_file" accept=".pdf" required>
                                            <small class="text-muted">
                                                <i class="fas fa-file-pdf"></i> Solo PDF, máximo 5MB
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Notas:</label>
                                            <textarea class="form-control" name="document_notes" rows="2"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="fas fa-upload"></i> Subir Acta de Devolución
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- FORMULARIO DE DEVOLUCIÓN CON OPCIÓN DE SUBIR DOCUMENTO --}}
                    <div class="col-12 col-lg-6">
                        <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-undo me-2"></i>Registrar Devolución</h5>
                            </div>
                            <div class="card-body p-3">
                                <form action="{{ route('assignments.return', $assignment) }}"
                                    class="needs-validation p-2" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Fecha de Devolución: *</label>
                                            <input class="form-control" type="date" name="returned_date"
                                                value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Condición al Devolver: *</label>
                                            <select class="form-select" name="condition_on_return" required>
                                                <option value="good">Bueno</option>
                                                <option value="fair">Regular</option>
                                                <option value="poor">Malo</option>
                                                <option value="damaged">Dañado</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Observaciones de Devolución:</label>
                                            <textarea class="form-control" name="return_observations" rows="2"></textarea>
                                        </div>

                                        {{-- SECCIÓN PARA SUBIR DOCUMENTO DE DEVOLUCIÓN (OPCIONAL) --}}
                                        <div class="col-12">
                                            <hr>
                                            <h6 class="text-muted">
                                                <i class="fas fa-file-pdf"></i> Documento de Devolución (Opcional)
                                            </h6>
                                            <p class="small text-muted">Si ya tienes el acta firmada, puedes subirla ahora. Si no, podrás hacerlo después.</p>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Número de Documento:</label>
                                            <input class="form-control" type="text" name="return_document_number"
                                                placeholder="Ej: ACTA-DEV-{{ $assignment->asset->code }}-{{ date('Y') }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Fecha de Firma:</label>
                                            <input class="form-control" type="date" name="return_signed_date"
                                                value="{{ date('Y-m-d') }}">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Archivo PDF Firmado:</label>
                                            <input class="form-control" type="file" name="return_document_file" accept=".pdf">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Solo PDF, máximo 5MB. Puedes dejarlo vacío y subir después.
                                            </small>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Notas del Documento:</label>
                                            <textarea class="form-control" name="return_document_notes" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="fas fa-undo"></i> Registrar Devolución
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection