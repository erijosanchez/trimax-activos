@extends('layouts.app')

@section('title', 'Detalle de Asignación')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}">Asignaciones</a></li>
    <li class="breadcrumb-item active">{{ $assignment->asset->code }}</li>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title">Detalle de Asignación</h1>
                <p class="page-subtitle">Activo {{ $assignment->asset->code }} asignado a
                    {{ $assignment->employee->full_name }}</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('assignments.generate-delivery-document', $assignment) }}" class="btn btn-success">
                    <i class="fas fa-file-word me-2"></i>Generar Acta de Entrega
                </a>
                @if (!$assignment->is_active && $assignment->returned_date)
                    <a href="{{ route('assignments.generate-return-document', $assignment) }}" class="btn btn-warning">
                        <i class="fas fa-file-word me-2"></i>Generar Acta de Devolución
                    </a>
                @endif
                <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Estado de la asignación -->
    <div class="alert {{ $assignment->is_active ? 'alert-info' : 'alert-success' }} mb-4">
        <div class="d-flex align-items-center">
            <i class="fas {{ $assignment->is_active ? 'fa-clock' : 'fa-check-circle' }} fa-2x me-3"></i>
            <div>
                <strong>Estado:</strong>
                @if ($assignment->is_active)
                    Esta asignación está <strong>ACTIVA</strong>. El activo está en uso desde el
                    {{ $assignment->assigned_date->format('d/m/Y') }}
                    ({{ $assignment->usage_days }} días).
                @else
                    Esta asignación está <strong>FINALIZADA</strong>. El activo fue devuelto el
                    {{ $assignment->returned_date->format('d/m/Y') }}
                    después de {{ $assignment->usage_days }} días de uso.
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- INFORMACIÓN DEL ACTIVO --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                    <h5 class="mb-0" style="color: #1e40af;">
                        <i class="fas fa-box me-2"></i>Información del Activo
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th width="40%">Código:</th>
                                <td class="fw-bold">{{ $assignment->asset->code }}</td>
                            </tr>
                            <tr>
                                <th>Categoría:</th>
                                <td>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                        {{ $assignment->asset->category->name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Marca:</th>
                                <td>{{ $assignment->asset->brand }}</td>
                            </tr>
                            <tr>
                                <th>Modelo:</th>
                                <td>{{ $assignment->asset->model }}</td>
                            </tr>
                            <tr>
                                <th>N° Serie:</th>
                                <td><code>{{ $assignment->asset->serial_number ?? 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <th>Ver Activo:</th>
                                <td>
                                    <a href="{{ route('asset.show', $assignment->asset) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Ver detalles
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- INFORMACIÓN DEL EMPLEADO --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                    <h5 class="mb-0" style="color: #065f46;">
                        <i class="fas fa-user me-2"></i>Información del Empleado
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th width="40%">DNI:</th>
                                <td class="fw-bold">{{ $assignment->employee->dni }}</td>
                            </tr>
                            <tr>
                                <th>Nombre:</th>
                                <td class="fw-medium">{{ $assignment->employee->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Departamento:</th>
                                <td>
                                    @if ($assignment->employee->department)
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                            {{ $assignment->employee->department }}
                                        </span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Cargo:</th>
                                <td>
                                    @if ($assignment->employee->position)
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                            {{ $assignment->employee->position }}
                                        </span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>
                                    @if ($assignment->employee->email)
                                        <a href="mailto:{{ $assignment->employee->email }}" class="text-primary">
                                            {{ $assignment->employee->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ver Empleado:</th>
                                <td>
                                    <a href="{{ route('employees.show', $assignment->employee) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Ver detalles
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- INFORMACIÓN DE LA ASIGNACIÓN --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                    <h5 class="mb-0" style="color: #92400e;">
                        <i class="fas fa-calendar-check me-2"></i>Detalles de la Asignación
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th width="50%">Fecha Entrega:</th>
                                <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Fecha Devolución:</th>
                                <td>
                                    @if ($assignment->returned_date)
                                        {{ $assignment->returned_date->format('d/m/Y') }}
                                    @else
                                        <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                            <i class="fas fa-clock me-1"></i>En uso
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Días de Uso:</th>
                                <td>
                                    <span class="badge" style="background: #d1fae5; color: #065f46;">
                                        <i class="fas fa-calendar-days me-1"></i>{{ $assignment->usage_days }} días
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Condición Entrega:</th>
                                <td>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                        {{ ucfirst($assignment->condition_on_assignment) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Condición Devolución:</th>
                                <td>
                                    @if ($assignment->condition_on_return)
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                            {{ ucfirst($assignment->condition_on_return) }}
                                        </span>
                                    @else
                                        <span class="text-muted">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Observaciones:</th>
                                <td>
                                    <small class="text-muted">
                                        {{ $assignment->assignment_observations ?? 'Sin observaciones' }}
                                    </small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- DOCUMENTO DE ENTREGA --}}
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header" style="background: #10b981; color: white;">
                    <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Documento de Entrega</h5>
                </div>
                <div class="card-body">
                    @if ($assignment->deliveryDocument)
                        {{-- DOCUMENTO YA SUBIDO --}}
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Documento subido correctamente</strong>
                        </div>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th width="35%">N° Documento:</th>
                                    <td class="fw-bold">{{ $assignment->deliveryDocument->document_number }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Firma:</th>
                                    <td>{{ $assignment->deliveryDocument->signed_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Notas:</th>
                                    <td>
                                        <small class="text-muted">
                                            {{ $assignment->deliveryDocument->notes ?? 'Sin notas' }}
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Archivo:</th>
                                    <td>
                                        <a href="{{ Storage::url($assignment->deliveryDocument->document_path) }}"
                                            target="_blank" class="btn btn-sm btn-success">
                                            <i class="fas fa-file-pdf me-1"></i>Ver PDF
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        {{-- FORMULARIO PARA SUBIR --}}
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Pendiente:</strong> No se ha subido el acta de entrega firmada
                        </div>
                        <form action="{{ route('assignments.upload-document', $assignment) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="document_type" value="delivery">

                            <div class="mb-3">
                                <label class="form-label">N° de Documento <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="document_number" required
                                    placeholder="ACTA-ENT-{{ $assignment->asset->code }}-{{ date('Y') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha de Firma <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="signed_date"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Archivo PDF <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" name="document_file" accept=".pdf" required>
                                <small class="text-muted">
                                    <i class="fas fa-file-pdf me-1"></i>Solo PDF, máximo 5MB
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notas</label>
                                <textarea class="form-control" name="document_notes" rows="2"
                                    placeholder="Notas adicionales sobre el documento..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-upload me-2"></i>Subir Acta de Entrega
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- DOCUMENTO DE DEVOLUCIÓN O FORMULARIO DE DEVOLUCIÓN --}}
        @if (!$assignment->is_active && $assignment->returned_date)
            {{-- YA ESTÁ DEVUELTO - MOSTRAR DOCUMENTO DE DEVOLUCIÓN --}}
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header" style="background: #f59e0b; color: white;">
                        <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Documento de Devolución</h5>
                    </div>
                    <div class="card-body">
                        @if ($assignment->returnDocument)
                            {{-- DOCUMENTO YA SUBIDO --}}
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Documento subido correctamente</strong>
                            </div>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th width="35%">N° Documento:</th>
                                        <td class="fw-bold">{{ $assignment->returnDocument->document_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Firma:</th>
                                        <td>{{ $assignment->returnDocument->signed_date->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Notas:</th>
                                        <td>
                                            <small class="text-muted">
                                                {{ $assignment->returnDocument->notes ?? 'Sin notas' }}
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Archivo:</th>
                                        <td>
                                            <a href="{{ Storage::url($assignment->returnDocument->document_path) }}"
                                                target="_blank" class="btn btn-sm btn-warning">
                                                <i class="fas fa-file-pdf me-1"></i>Ver PDF
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            {{-- FORMULARIO PARA SUBIR --}}
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Pendiente:</strong> No se ha subido el acta de devolución firmada
                            </div>
                            <form action="{{ route('assignments.upload-document', $assignment) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="document_type" value="return">

                                <div class="mb-3">
                                    <label class="form-label">N° de Documento <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="document_number" required
                                        placeholder="ACTA-DEV-{{ $assignment->asset->code }}-{{ date('Y') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fecha de Firma <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="signed_date"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Archivo PDF <span class="text-danger">*</span></label>
                                    <input class="form-control" type="file" name="document_file" accept=".pdf"
                                        required>
                                    <small class="text-muted">
                                        <i class="fas fa-file-pdf me-1"></i>Solo PDF, máximo 5MB
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notas</label>
                                    <textarea class="form-control" name="document_notes" rows="2"
                                        placeholder="Notas adicionales sobre el documento..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-upload me-2"></i>Subir Acta de Devolución
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @else
            {{-- FORMULARIO DE DEVOLUCIÓN --}}
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
                        <h5 class="mb-0" style="color: #991b1b;">
                            <i class="fas fa-undo me-2"></i>Registrar Devolución
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assignments.return', $assignment) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Fecha Devolución <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="returned_date"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Condición al Devolver <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="condition_on_return" required>
                                        <option value="good">Bueno</option>
                                        <option value="fair">Regular</option>
                                        <option value="poor">Malo</option>
                                        <option value="damaged">Dañado</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Observaciones de Devolución</label>
                                    <textarea class="form-control" name="return_observations" rows="2"
                                        placeholder="Describa el estado del activo al devolverlo..."></textarea>
                                </div>

                                {{-- SECCIÓN DOCUMENTO (OPCIONAL) --}}
                                <div class="col-12">
                                    <hr>
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-file-pdf me-2"></i>Documento de Devolución (Opcional)
                                    </h6>
                                    <p class="small text-muted">
                                        Si ya tienes el acta firmada, puedes subirla ahora. Si no, podrás hacerlo después.
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">N° de Documento</label>
                                    <input class="form-control" type="text" name="return_document_number"
                                        placeholder="ACTA-DEV-{{ $assignment->asset->code }}-{{ date('Y') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Fecha de Firma</label>
                                    <input class="form-control" type="date" name="return_signed_date"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Archivo PDF</label>
                                    <input class="form-control" type="file" name="return_document_file"
                                        accept=".pdf">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>Solo PDF, máximo 5MB. Puedes dejarlo vacío y
                                        subir después.
                                    </small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Notas del Documento</label>
                                    <textarea class="form-control" name="return_document_notes" rows="2"
                                        placeholder="Notas adicionales sobre el documento..."></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-undo me-2"></i>Registrar Devolución
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
