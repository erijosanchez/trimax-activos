<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activos - Trimax</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

</head>
<body>
    <!-- Navbar móvil -->
    <nav class="mobile-navbar">
        <h3><i class="fas fa-boxes"></i> Inventario</h3>
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Overlay para cerrar sidebar en móvil -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

     <!-- Sidebar de Navegación -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('assets/img/LOGOTIPO TRIMAX 2025-01 (1).png') }}" alt="">
            <small style="opacity: 0.7;">Sistema de Gestión</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard') }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('asset.index') }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-box"></i>
                    <span>Activos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('employees.index') }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-users"></i>
                    <span>Empleados</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('assignments.index') }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-cog"></i>
                    <span>Asignaciones</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.index') }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-chart-line"></i>
                    <span>Reportes</span>
                </a>
            </li>
        </ul>
    </aside>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</body>
</html>