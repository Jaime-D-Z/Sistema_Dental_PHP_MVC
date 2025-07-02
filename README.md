# 🦷 Sistema de Gestión Dental en PHP (MVC)

Este es un **Sistema de Gestión Dental** desarrollado en **PHP** bajo el patrón **MVC**. El sistema permite administrar pacientes, doctores, citas, diagnósticos, tratamientos, pagos, historias clínicas, productos, proveedores y más.

## 📌 Características

- Inicio de sesión de usuarios (`auth/login.php`)
- Gestión de pacientes, doctores y citas.
- Registro y seguimiento de tratamientos y diagnósticos.
- Administración de historias clínicas y recetas.
- Control de inventario de productos y proveedores.
- Gestión de pagos, saldos y reportes.
- Búsqueda en tiempo real con AJAX.
- Edición en línea tipo Excel (inline) con validaciones.
- Alertas interactivas con SweetAlert2.
- Diseño responsive con Bootstrap 5.
- Uso de DataTables para paginación y filtros.
- Seguridad básica con sesiones y control de acceso.

## 🛠️ Tecnologías utilizadas

- PHP 8.x
- MySQL
- Bootstrap 5
- JavaScript / AJAX
- jQuery
- DataTables
- SweetAlert2
- HTML5 / CSS3



## 🧱 Estructura del Proyecto

```plaintext
Sistema_Dental_PHP_MVC/
│
├── app/
│   ├── Http/
│   │   └── Controllers/        # Controladores MVC
│   └── Models/                 # Modelos conectados a la base de datos
│
├── auth/                       # Lógica de autenticación (login.php, logout.php)
├── config/                     # Configuración de conexión a DB y autenticación
├── public/                     # Archivos accesibles públicamente (assets, index.php)
│
├── resources/
│   └── views/                  # Vistas HTML + PHP
│
├── routes/                     # Rutas del proyecto
└── sql/                        # Script de base de datos (.sql)


## ⚙️ Instalación y uso

### Requisitos

- PHP 8.x
- MySQL 5.7 o superior
- Servidor local (XAMPP, Laragon, UniServer, etc.)

### Pasos

1. Clona el repositorio:

   ```bash
   git clone https://github.com/Jaime-D-Z/Sistema_Dental_PHP_MVC.git


   Importa el archivo sql/dental_db.sql a tu gestor de bases de datos (por ejemplo, phpMyAdmin).

Configura la conexión en config/Database.php:


define('DB_HOST', 'localhost');
define('DB_NAME', 'clinic');
define('DB_USER', 'root');
define('DB_PASS', '');
Abre el navegador y accede al sistema desde la pantalla de inicio de sesión:


http://localhost/Sistema_Dental_PHP_MVC/auth/login.php
