@extends('layouts.app')

@section('content')
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">Asignaciones</h2>
            </div>
            <div class="mb-3">
                <div class="input-group" style="max-width: 500px;">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="assignment-search-input"
                        placeholder="Buscar por código de activo, empleado o DNI..." autofocus>
                    <button class="btn btn-outline-secondary" type="button" id="clear-assignment-search">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <small class="text-muted">
                    <i class="fas fa-barcode"></i> Puedes escanear el código de barras del activo
                </small>
            </div>

            <div id="assignment-search-info" class="alert alert-info mb-3" style="display: none;">
                <i class="fas fa-info-circle"></i>
                <span id="assignment-search-info-text"></span>
                <button type="button" class="btn-close float-end"
                    onclick="document.getElementById('assignment-search-info').style.display='none'"></button>
            </div>

            <div class="table-card animated-entry" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de asignaciones</h5>
                        <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">
                            <i class="fas fa-plus me-2"></i>Nueva Asignación
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Activo</th>
                                    <th>Empleado</th>
                                    <th>Fecha Asignación</th>
                                    <th>Fecha Devolución</th>
                                    <th>Días Uso</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignments as $assignment)
                                    <tr class="assignment-row" data-asset-code="{{ $assignment->asset->code }}"
                                        data-employee="{{ strtolower($assignment->employee->full_name) }}"
                                        data-dni="{{ $assignment->employee->dni }}">
                                        <td>{{ $assignment->asset->code }} - {{ $assignment->asset->brand }}
                                            {{ $assignment->asset->model }}
                                        </td>
                                        <td>{{ $assignment->employee->full_name }}</td>
                                        <td>{{ $assignment->assigned_date->format('d/m/Y') }}</td>
                                        <td>{{ $assignment->returned_date?->format('d/m/Y') ?? 'En uso' }}</td>
                                        <td>{{ $assignment->usage_days }} días</td>
                                        <td>{{ $assignment->is_active ? 'Activo' : 'Finalizado' }}</td>
                                        <td>
                                            <a href="{{ route('assignments.show', $assignment) }}"
                                                class="btn btn-sm btn-outline-primary btn-action me-1"><i
                                                    class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    {{ $assignments->links() }}

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
            const SCAN_SPEED_THRESHOLD = 50;
            let lastKeyTime = Date.now();

            function performSearch(searchTerm) {
                searchTerm = searchTerm.toLowerCase().trim();
                let visibleCount = 0;
                let foundExact = false;

                assignmentRows.forEach(row => {
                    const assetCode = row.dataset.assetCode.toLowerCase();
                    const employee = row.dataset.employee;
                    const dni = row.dataset.dni;

                    if (searchTerm === '' ||
                        assetCode.includes(searchTerm) ||
                        employee.includes(searchTerm) ||
                        dni.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;

                        if (assetCode === searchTerm) {
                            foundExact = true;
                            row.style.backgroundColor = '#d4edda';
                            setTimeout(() => {
                                row.style.backgroundColor = '';
                            }, 3000);
                            row.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (searchTerm !== '') {
                    if (foundExact) {
                        searchInfo.className = 'alert alert-success mb-3';
                        searchInfoText.textContent =
                            `✓ Asignación encontrada para el activo: ${searchTerm.toUpperCase()}`;
                        searchInfo.style.display = 'block';
                    } else if (visibleCount > 0) {
                        searchInfo.className = 'alert alert-info mb-3';
                        searchInfoText.textContent =
                            `Se encontraron ${visibleCount} asignación(es) que coinciden con: "${searchTerm}"`;
                        searchInfo.style.display = 'block';
                    } else {
                        searchInfo.className = 'alert alert-warning mb-3';
                        searchInfoText.textContent = `No se encontraron asignaciones para: "${searchTerm}"`;
                        searchInfo.style.display = 'block';
                    }
                } else {
                    searchInfo.style.display = 'none';
                }
            }

            // Detectar escaneo rápido
            searchInput.addEventListener('keypress', function(e) {
                const currentTime = Date.now();
                const timeDiff = currentTime - lastKeyTime;
                lastKeyTime = currentTime;

                if (timeDiff < SCAN_SPEED_THRESHOLD) {
                    scanBuffer += e.key;
                } else {
                    scanBuffer = e.key;
                }

                clearTimeout(scanTimeout);
                scanTimeout = setTimeout(() => {
                    scanBuffer = '';
                }, 200);

                if (e.key === 'Enter') {
                    e.preventDefault();
                    const code = this.value.trim();
                    if (code) {
                        performSearch(code);
                    }
                }
            });

            // Búsqueda en tiempo real
            let searchTimeout2;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout2);
                searchTimeout2 = setTimeout(() => {
                    performSearch(this.value);
                }, 300);
            });

            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                performSearch('');
                searchInput.focus();
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    performSearch('');
                }
            });
        });
    </script>
@endsection
