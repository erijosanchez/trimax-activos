@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Detalle de Asignación</h2>
            </div>

            <div class="row g-3">
                {{-- INFORMACIÓN DEL ACTIVO --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Información del activo</h5>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
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
                </div>


                <div class="col-12 col-md-6 col-lg-4">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Información del empleado</h5>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
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
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Información del activo</h5>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
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
                                    <tr>
                                        <th>Estado:</th>
                                        <td>{{ $assignment->is_active ? 'Activo' : 'Finalizado' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Observaciones Entrega:</th>
                                        <td>{{ $assignment->assignment_observations }}</td>
                                    </tr>
                                    <tr>
                                        <th>Observaciones Devolución:</th>
                                        <td>{{ $assignment->return_observations }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DOCUMENTO DE RESPONSABILIDAD --}}

                @if ($assignment->responsibilityDocument)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Documento de responsabilidad</h5>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <tr>
                                            <th>Número:</th>
                                            <td>{{ $assignment->responsibilityDocument->document_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Fecha Firma:</th>
                                            <td>{{ $assignment->responsibilityDocument->signed_date->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Notas:</th>
                                            <td>{{ $assignment->responsibilityDocument->notes }}</td>
                                        </tr>
                                        <tr>
                                            <th>Documento:</th>
                                            <td><a href="{{ Storage::url($assignment->responsibilityDocument->document_path) }}"
                                                    target="_blank" class="btn">Ver PDF</a></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($assignment->is_active)
                    <div class="col-12 col-md-6 col-lg-8">
                        <div class="table-card animated-entry h-100" style="animation-delay: 0.5s;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Información del activo</h5>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <form action="{{ route('assignments.return', $assignment) }}"
                                        class="needs-validation form-control p-4" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Fecha de Devolución:</label>
                                                <input class="form-control" type="date" name="returned_date"
                                                    value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Condición al Devolver:</label>
                                                <select class="form-select" name="condition_on_return" required>
                                                    <option value="good">Bueno</option>
                                                    <option value="fair">Regular</option>
                                                    <option value="poor">Malo</option>
                                                    <option value="damaged">Dañado</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Observaciones de Devolución:</label>
                                                <textarea class="form-control" name="return_observations" rows="3"></textarea>
                                            </div>

                                        </div>
                                        <div class="mt-4 d-flex justify-content-between">
                                            <button type="submit" class="btn btn-warning form-control">Registrar
                                                Devolución</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
    </main>
@endsection
