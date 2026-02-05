@extends('layouts.app')

@section('title', 'Asignaciones')

@section('breadcrumb')
    <li class="breadcrumb-item active">Asignaciones</li>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Asignaciones</h1>
        <p class="page-subtitle">Gestión de asignaciones de activos a empleados</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-value">{{ $assignments->total() }}</div>
                <div class="stat-label">Total Asignaciones</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value" style="color: #10b981;">
                    {{ $assignments->where('is_active', true)->count() }}
                </div>
                <div class="stat-label">Asignaciones Activas</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #f3f4f6; color: #64748b;">
                    <i class="fas fa-archive"></i>
                </div>
                <div class="stat-value" style="color: #64748b;">
                    {{ $assignments->where('is_active', false)->count() }}
                </div>
                <div class="stat-label">Asignaciones Finalizadas</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value" style="color: #f59e0b;">
                    {{ $assignments->unique('employee_id')->count() }}
                </div>
                <div class="stat-label">Empleados con Activos</div>
            </div>
        </div>
    </div>

    <!-- Search and Actions -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-8">
                    <label class="form-label">
                        <i class="fas fa-search me-2"></i>Buscar Asignación
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" id="assignment-search-input"
                            placeholder="Buscar por código de activo, empleado o DNI..." autofocus>
                        <button class="btn btn-outline-secondary" type="button" id="clear-assignment-search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-barcode me-1"></i>Puedes escanear el código de barras del activo
                    </small>
                </div>
                <div class="col-lg-4">
                    <a href="{{ route('assignments.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Nueva Asignación
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Info Alert -->
    <div id="assignment-search-info" class="alert alert-info mb-4" style="display: none;">
        <i class="fas fa-info-circle me-2"></i>
        <span id="assignment-search-info-text"></span>
        <button type="button" class="btn-close float-end" aria-label="Close"
            onclick="document.getElementById('assignment-search-info').style.display='none'"></button>
    </div>

    <!-- Assignments Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Asignaciones</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="assignments-table">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="border-0">Código</th>
                            <th class="border-0">Activo</th>
                            <th class="border-0">Empleado</th>
                            <th class="border-0">Fecha Asignación</th>
                            <th class="border-0">Fecha Devolución</th>
                            <th class="border-0">Días Uso</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr class="assignment-row" data-asset-code="{{ strtolower($assignment->asset->code) }}"
                                data-employee="{{ strtolower($assignment->employee->full_name) }}"
                                data-dni="{{ $assignment->employee->dni }}">
                                <td class="fw-bold">{{ $assignment->asset->code }}</td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $assignment->asset->brand }}
                                            {{ $assignment->asset->model }}</div>
                                        <small class="text-muted">{{ $assignment->asset->category->name }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $assignment->employee->full_name }}</div>
                                        <small class="text-muted">DNI: {{ $assignment->employee->dni }}</small>
                                    </div>
                                </td>
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
                                        <i class="fas fa-calendar-days me-1"></i>{{ $assignment->usage_days }} días
                                    </span>
                                </td>
                                <td>
                                    @if ($assignment->is_active)
                                        <span class="badge" style="background: #d1fae5; color: #065f46;">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge" style="background: #f3f4f6; color: #374151;">
                                            <i class="fas fa-archive me-1"></i>Finalizado
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('assignments.show', $assignment) }}"
                                        class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <p class="mb-0">No hay asignaciones registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($assignments->hasPages())
            <div class="card-footer bg-white text-center py-3">
                {{ $assignments->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('assignment-search-input');
                const clearSearchBtn = document.getElementById('clear-assignment-search');
                const searchInfo = document.getElementById('assignment-search-info');
                const searchInfoText = document.getElementById('assignment-search-info-text');
                const assignmentRows = document.querySelectorAll('.assignment-row');

                // Variables para detección de escaneo
                let scanBuffer = '';
                let scanTimeout;
                const SCAN_TIMEOUT = 100;

                // Detección de escaneo de código de barras
                searchInput.addEventListener('keypress', function(e) {
                    clearTimeout(scanTimeout);
                    scanBuffer += e.key;

                    scanTimeout = setTimeout(function() {
                        if (scanBuffer.length >= 8) {
                            searchInput.value = scanBuffer;
                            performSearch();
                            searchInfo.className = 'alert alert-success mb-4';
                            searchInfo.style.display = 'block';
                            searchInfoText.textContent = '✓ Código escaneado: ' + scanBuffer;
                        }
                        scanBuffer = '';
                    }, SCAN_TIMEOUT);
                });

                // Búsqueda en tiempo real
                let searchTimeout2;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout2);
                    searchTimeout2 = setTimeout(performSearch, 300);
                });

                // Botón limpiar búsqueda
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    searchInfo.style.display = 'none';
                    assignmentRows.forEach(row => row.style.display = '');
                    searchInput.focus();
                });

                // Limpiar con ESC
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        searchInfo.style.display = 'none';
                        assignmentRows.forEach(row => row.style.display = '');
                    }
                });

                function performSearch() {
                    const searchTerm = searchInput.value.toLowerCase().trim();
                    let foundCount = 0;
                    let exactMatch = null;

                    assignmentRows.forEach(row => {
                        const assetCode = row.dataset.assetCode;
                        const employee = row.dataset.employee;
                        const dni = row.dataset.dni;

                        const matches = assetCode.includes(searchTerm) ||
                            employee.includes(searchTerm) ||
                            dni.includes(searchTerm);

                        if (matches) {
                            row.style.display = '';
                            foundCount++;

                            // Check for exact match
                            if (assetCode === searchTerm) {
                                exactMatch = row;
                            }
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Highlight exact match
                    if (exactMatch) {
                        exactMatch.style.backgroundColor = '#d4edda';
                        setTimeout(() => {
                            exactMatch.style.backgroundColor = '';
                        }, 3000);

                        exactMatch.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    // Update search info
                    if (searchTerm) {
                        searchInfo.style.display = 'block';
                        if (foundCount === 0) {
                            searchInfo.className = 'alert alert-warning mb-4';
                            searchInfoText.textContent = 'No se encontraron resultados para: "' + searchTerm + '"';
                        } else if (exactMatch) {
                            searchInfo.className = 'alert alert-success mb-4';
                            searchInfoText.textContent = '✓ Asignación encontrada: ' + searchTerm.toUpperCase();
                        } else {
                            searchInfo.className = 'alert alert-info mb-4';
                            searchInfoText.textContent = foundCount + ' asignación(es) encontrada(s)';
                        }
                    } else {
                        searchInfo.style.display = 'none';
                    }
                }
            });
        </script>
    @endpush
@endsection
