<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Activos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { background: #f0f0f0; padding: 10px; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; }
        nav a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .btn { padding: 5px 10px; text-decoration: none; background: #007bff; color: white; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        .btn-warning { background: #ffc107; color: #333; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 3px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        form { max-width: 600px; }
        input, select, textarea { width: 100%; padding: 5px; margin: 5px 0 15px 0; }
        label { font-weight: bold; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('assets.index') }}">Activos</a>
        <a href="{{ route('employees.index') }}">Empleados</a>
        <a href="{{ route('assignments.index') }}">Asignaciones</a>
        <a href="{{ route('reports.index') }}">Reportes</a>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</body>
</html>