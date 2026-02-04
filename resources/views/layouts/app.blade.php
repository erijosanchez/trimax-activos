<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Trimax Activos') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-bg: #0f172a;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --card-shadow-hover: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 260px;
            background: var(--sidebar-bg);
            color: white;
            padding: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-logo img {
            height: 40px;
            width: auto;
        }

        .sidebar-logo h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            opacity: 0.8;
            margin: 0;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section-title {
            padding: 1rem 1.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255,255,255,0.5);
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar-nav .nav-link.active {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* TOPBAR */
        .topbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: var(--card-shadow);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 0.5rem;
        }

        .breadcrumb {
            margin: 0;
            background: none;
            padding: 0;
        }

        .breadcrumb-item {
            font-size: 0.9rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        /* USER PROFILE DROPDOWN */
        .user-profile {
            position: relative;
        }

        .user-profile-toggle {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-profile-toggle:hover {
            background: #f8fafc;
            box-shadow: var(--card-shadow);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-secondary);
            line-height: 1.2;
        }

        .user-role-badge {
            display: inline-block;
            padding: 0.15rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .role-admin { background: #fee2e2; color: #991b1b; }
        .role-ti { background: #dbeafe; color: #1e40af; }
        .role-servicios_generales { background: #d1fae5; color: #065f46; }
        .role-marketing { background: #fce7f3; color: #9f1239; }
        .role-viewer { background: #f3f4f6; color: #374151; }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .user-dropdown-header .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .user-dropdown-header .user-email {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .user-dropdown-menu {
            padding: 0.5rem;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .user-dropdown-item:hover {
            background: #f8fafc;
        }

        .user-dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .user-dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 0.5rem 0;
        }

        .logout-item {
            color: var(--danger-color);
        }

        /* PAGE CONTAINER */
        .page-container {
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        /* CARDS */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* STATS CARDS */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* ALERTS */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-error, .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* BUTTONS */
        .btn {
            border-radius: 8px;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .topbar {
                padding: 1rem;
            }

            .page-container {
                padding: 1rem;
            }

            .user-info {
                display: none;
            }

            .breadcrumb {
                display: none;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Trimax">
            </div>
            <p class="sidebar-subtitle">Sistema de Gestión de Activos</p>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Principal</div>
            <a href="{{ route('dashboard') }}" 
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>

            @can('canManageInventory', auth()->user())
            <div class="nav-section-title">Inventario</div>
            <a href="{{ route('asset.index') }}" 
               class="nav-link {{ request()->routeIs('asset.*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i>
                <span>Activos</span>
            </a>
            <a href="{{ route('employees.index') }}" 
               class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Empleados</span>
            </a>
            <a href="{{ route('assignments.index') }}" 
               class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i>
                <span>Asignaciones</span>
            </a>
            @endcan

            <div class="nav-section-title">Mantenimiento</div>
            <a href="{{ route('maintenances.dashboard') }}" 
               class="nav-link {{ request()->routeIs('maintenances.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('maintenances.index') }}" 
               class="nav-link {{ request()->routeIs('maintenances.index') || request()->routeIs('maintenances.show') ? 'active' : '' }}">
                <i class="fas fa-tools"></i>
                <span>Mantenimientos</span>
            </a>
            
            @can('canPerformMaintenance', auth()->user())
            <a href="{{ route('maintenance-schedules.index') }}" 
               class="nav-link {{ request()->routeIs('maintenance-schedules.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-days"></i>
                <span>Programación</span>
            </a>
            @endcan

            <div class="nav-section-title">Reportes</div>
            <a href="{{ route('reports.index') }}" 
               class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fas fa-file-export"></i>
                <span>Exportar Datos</span>
            </a>

            @if(auth()->user()->isAdmin())
            <div class="nav-section-title">Administración</div>
            <a href="{{ route('users.index') }}" 
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span>Gestión de Usuarios</span>
            </a>
            @endif
        </nav>
    </aside>

    <!-- Overlay para móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>

            <div class="topbar-right">
                <!-- User Profile Dropdown -->
                <div class="user-profile">
                    <div class="user-profile-toggle" onclick="toggleUserDropdown()">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">
                                <span class="user-role-badge role-{{ auth()->user()->role }}">
                                    {{ auth()->user()->role_name }}
                                </span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 0.8rem; color: #94a3b8;"></i>
                    </div>

                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-header">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-email">{{ auth()->user()->email }}</div>
                            <div class="mt-2">
                                <span class="user-role-badge role-{{ auth()->user()->role }}">
                                    {{ auth()->user()->role_name }}
                                </span>
                            </div>
                        </div>
                        <div class="user-dropdown-menu">
                            <a href="{{ route('profile.edit') }}" class="user-dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>Mi Perfil</span>
                            </a>
                            <div class="user-dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="user-dropdown-item logout-item" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer;">
                                    <i class="fas fa-right-from-bracket"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="page-container">
            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Toggle User Dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userProfile = document.querySelector('.user-profile');
            const dropdown = document.getElementById('userDropdown');
            
            if (!userProfile.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
