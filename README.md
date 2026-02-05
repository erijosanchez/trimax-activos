# 🏢 Sistema de Gestión de Activos - Trimax Perú

Sistema integral de gestión y control de activos de TI desarrollado para Laboratorio Óptico Trimax. Permite administrar inventario de equipos tecnológicos, asignaciones a empleados, mantenimientos programados y generación de documentos de responsabilidad.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange?style=flat-square&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Tecnologías](#️-tecnologías)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración](#️-configuración)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Módulos](#-módulos)
- [Capturas de Pantalla](#-capturas-de-pantalla)
- [API y Exportaciones](#-api-y-exportaciones)
- [Seguridad](#-seguridad)
- [Contribución](#-contribución)
- [Licencia](#-licencia)
- [Autor](#-autor)

## ✨ Características

### 🎯 Gestión de Activos
- ✅ Registro completo de activos de TI (PCs, laptops, monitores, celulares, periféricos)
- ✅ Generación automática de códigos de barras únicos
- ✅ Escaneo de códigos de barras para búsqueda rápida
- ✅ Seguimiento de especificaciones técnicas por categoría
- ✅ Control de estado (disponible, asignado, mantenimiento, dañado, retirado)
- ✅ Historial completo de asignaciones por activo

### 👥 Gestión de Empleados
- ✅ Registro de empleados con información de contacto
- ✅ Asignación de departamentos y cargos
- ✅ Control de activos asignados por empleado
- ✅ Historial de asignaciones y devoluciones
- ✅ Estados activo/inactivo

### 📋 Asignaciones y Devoluciones
- ✅ Asignación de activos a empleados con documentación
- ✅ Registro de condiciones de entrega y devolución
- ✅ Generación automática de actas de responsabilidad (Word)
- ✅ Carga de documentos firmados en PDF
- ✅ Seguimiento de días de uso
- ✅ Observaciones y notas en cada proceso

### 🔧 Mantenimientos
- ✅ Programación de mantenimientos preventivos
- ✅ Registro de mantenimientos correctivos
- ✅ Seguimiento de técnicos asignados
- ✅ Checklist personalizado por mantenimiento
- ✅ Control de costos y duración
- ✅ Generación de actas de mantenimiento (PDF)
- ✅ Dashboard de mantenimientos pendientes

### 📊 Reportes y Exportaciones
- ✅ Exportación a Excel de activos, empleados y asignaciones
- ✅ Reportes individuales por activo
- ✅ Gráficos estadísticos en dashboard
- ✅ Filtros avanzados de búsqueda

### 🔐 Seguridad y Roles
- ✅ Sistema de autenticación Laravel Breeze
- ✅ Roles de usuario (Admin, TI, Servicios Generales, Viewer)
- ✅ Políticas de autorización granulares
- ✅ Protección CSRF en formularios

## 🛠️ Tecnologías

### Backend
- **Laravel 11.x** - Framework PHP
- **PHP 8.2+** - Lenguaje de programación
- **MySQL 8.0+** - Base de datos

### Frontend
- **Bootstrap 5.3** - Framework CSS
- **Font Awesome 6** - Iconos
- **Chart.js 4** - Gráficos estadísticos
- **Vanilla JavaScript** - Interactividad

### Librerías Adicionales
- **PhpOffice/PhpWord** - Generación de documentos Word
- **Barryvdh/Laravel-DomPDF** - Generación de PDFs
- **Picqer/PHP-Barcode-Generator** - Generación de códigos de barras
- **Maatwebsite/Laravel-Excel** - Exportación a Excel

## 📦 Requisitos

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18.x (para compilar assets)
- Extensiones PHP:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - GD (para generación de códigos de barras)
  - ZIP

## 🚀 Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/trimax-activos.git
cd trimax-activos
```

### 2. Instalar dependencias
```bash
# Dependencias PHP
composer install

# Dependencias JavaScript (opcional)
npm install
```

### 3. Configurar el archivo .env
```bash
cp .env.example .env
```

Edita el archivo `.env` con tus configuraciones:
```env
APP_NAME="Trimax Activos"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trimax_activos
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

### 4. Generar la clave de aplicación
```bash
php artisan key:generate
```

### 5. Crear la base de datos

Crea una base de datos MySQL llamada `trimax_activos`:
```sql
CREATE DATABASE trimax_activos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Ejecutar migraciones y seeders
```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (datos de prueba)
php artisan db:seed
```

### 7. Crear enlace simbólico para storage
```bash
php artisan storage:link
```

### 8. Compilar assets (opcional)
```bash
npm run dev
# o para producción
npm run build
```

### 9. Iniciar el servidor
```bash
php artisan serve
```

El sistema estará disponible en: `http://localhost:8000`

## ⚙️ Configuración

### Usuarios por Defecto

Después de ejecutar los seeders, tendrás los siguientes usuarios de prueba:

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@trimax.com.pe | password | Admin |
| ti@trimax.com.pe | password | TI |
| servicios@trimax.com.pe | password | Servicios Generales |
| viewer@trimax.com.pe | password | Viewer |

### Categorías de Activos Preconfiguradas

- PC
- Laptop
- Monitor
- Mouse
- Teclado
- Celular
- Audífonos
- Impresora
- Router
- Switch

### Roles y Permisos

#### 🔴 Admin
- Acceso completo al sistema
- Gestión de usuarios
- Todas las operaciones CRUD

#### 🔵 TI
- Gestión de activos
- Asignaciones y devoluciones
- Mantenimientos
- Generación de reportes

#### 🟢 Servicios Generales
- Gestión de activos
- Asignaciones (limitado)
- Visualización de reportes

#### 🟡 Marketing
- Acceso a gestión de usuarios marketing
- Encuestas de satisfacción

#### ⚪ Viewer
- Solo lectura en todos los módulos

## 📁 Estructura del Proyecto
```
trimax-activos/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Controladores
│   │   ├── Middleware/         # Middleware personalizado
│   │   └── Requests/           # Form Requests
│   ├── Models/                 # Modelos Eloquent
│   ├── Policies/               # Políticas de autorización
│   └── Exports/                # Clases de exportación Excel
├── database/
│   ├── migrations/             # Migraciones de BD
│   └── seeders/                # Seeders
├── resources/
│   └── views/                  # Vistas Blade
│       ├── assets/             # Vistas de activos
│       ├── employees/          # Vistas de empleados
│       ├── assignments/        # Vistas de asignaciones
│       ├── maintenances/       # Vistas de mantenimientos
│       ├── reports/            # Vistas de reportes
│       └── layouts/            # Layouts base
├── routes/
│   └── web.php                 # Rutas web
├── public/
│   ├── assets/                 # Assets públicos
│   └── storage/                # Almacenamiento público
└── storage/
    └── app/
        └── public/
            ├── documents/      # Documentos PDF
            └── barcodes/       # Códigos de barras
```

## 📱 Módulos

### 1. Dashboard
- Estadísticas generales del sistema
- Gráficos de distribución de activos
- Últimas asignaciones
- Mantenimientos pendientes

### 2. Activos
- **Listado**: Búsqueda con soporte para escaneo de códigos de barras
- **Crear**: Formulario dinámico según categoría seleccionada
- **Editar**: Actualización de información
- **Detalle**: Vista completa con historial de asignaciones
- **Códigos de Barras**: Generación y descarga individual o masiva

### 3. Empleados
- **Listado**: Búsqueda por nombre, DNI o email
- **Crear**: Registro de nuevos empleados
- **Editar**: Actualización de información
- **Detalle**: Vista con activos asignados e historial

### 4. Asignaciones
- **Listado**: Búsqueda con escaneo de códigos de barras
- **Crear**: Asignación de activo a empleado
- **Detalle**: Información completa con documentos
- **Devolución**: Proceso de devolución con documentación
- **Documentos**: Generación automática de actas (Word)

### 5. Mantenimientos
- **Dashboard**: Vista general de mantenimientos
- **Listado**: Filtros por estado, tipo y activo
- **Programar**: Creación de mantenimientos preventivos
- **Ejecutar**: Registro de actividades realizadas
- **Completar**: Finalización con checklist
- **Documentos**: Generación de actas (PDF)

### 6. Reportes
- Exportación Excel de todos los activos
- Exportación Excel de todas las asignaciones
- Exportación Excel de todos los empleados
- Historial individual por activo

## 📸 Capturas de Pantalla

### Dashboard
![Dashboard](docs/screenshots/dashboard.png)

### Gestión de Activos
![Activos](docs/screenshots/activos.png)

### Asignaciones
![Asignaciones](docs/screenshots/asignaciones.png)

### Mantenimientos
![Mantenimientos](docs/screenshots/mantenimientos.png)

## 📡 API y Exportaciones

### Generación de Códigos de Barras
```php
GET /asset/{asset}/barcode
// Retorna imagen PNG del código de barras

GET /asset/{asset}/barcode/download
// Descarga el código de barras
```

### Exportaciones Excel
```php
GET /reports/assets/export
// Exporta todos los activos

GET /reports/assignments/export
// Exporta todas las asignaciones

GET /reports/employees/export
// Exporta todos los empleados

GET /reports/asset/{asset}/history/export
// Exporta historial de un activo específico
```

### Generación de Documentos
```php
GET /assignments/{assignment}/generate-delivery-document
// Genera acta de entrega (Word)

GET /assignments/{assignment}/generate-return-document
// Genera acta de devolución (Word)

GET /maintenances/{maintenance}/document
// Genera acta de mantenimiento (PDF)
```

## 🔒 Seguridad

### Autenticación
- Laravel Breeze con sesiones
- Hash de contraseñas con Bcrypt
- Protección contra fuerza bruta

### Autorización
- Políticas de Laravel para cada modelo
- Gates personalizados por rol
- Middleware de verificación de permisos

### Validación
- Form Requests personalizados
- Validación de archivos subidos
- Sanitización de inputs

### Protección CSRF
- Tokens CSRF en todos los formularios
- Verificación automática en POST/PUT/DELETE

### Almacenamiento Seguro
- Documentos almacenados fuera del directorio público
- URLs temporales para descarga de documentos
- Validación de tipos MIME

## 🤝 Contribución

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Guía de Estilo
- Seguir PSR-12 para código PHP
- Nombres de variables en inglés
- Comentarios en español
- Commits descriptivos en español

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

**Erick** - Desarrollador Full Stack

- GitHub: [@tu-usuario](https://github.com/erijosanchez)
- LinkedIn: [Tu Perfil](https://www.linkedin.com/in/erick-jos%C3%A9-s%C3%A1nchez-pinedo-5719802a6)
- Email: joseericksanchez7@gmail.com

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - Framework PHP
- [Bootstrap](https://getbootstrap.com) - Framework CSS
- [Font Awesome](https://fontawesome.com) - Iconos
- [Chart.js](https://www.chartjs.org) - Gráficos
- Laboratorio Óptico Trimax Perú

---

⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub

Desarrollado para Trimax Perú