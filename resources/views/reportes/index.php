

<?php 
require_once __DIR__ . '/../../../config/auth.php';

 ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes - Clínica Dental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .topbar {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    }

    .topbar a {
      color: #0d6efd;
      font-weight: 500;
      margin-right: 20px;
      text-decoration: none;
    }

    .topbar a:hover {
      text-decoration: underline;
    }

    .dashboard-card {
      text-align: center;
      padding: 20px;
      border-radius: 15px;
      transition: all 0.3s ease;
      background-color: white;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card:hover {
      background-color: #e9f1ff;
      transform: translateY(-5px);
    }

    .dashboard-card i {
      font-size: 40px;
      margin-bottom: 10px;
      color: #0d6efd;
    }

    .dashboard-card span {
      display: block;
      margin-top: 10px;
      font-weight: 600;
    }

    .container {
      margin-top: 30px;
    }
  </style>
</head>
<body>

<!-- Menú superior -->
<nav class="navbar navbar-expand-lg topbar px-4 py-2">
     <a class="navbar-brand fw-bold text-primary" href="/resources/views/layouts/index.php">
        🦷 ODONTOLOGÍA TC
    </a>
    <div class="collapse navbar-collapse">
        <div class="navbar-nav">
            <a class="nav-link" href="/resources/views/layouts/index.php">Inicio</a>
        <a class="nav-link" href="/resources/views/config/index.php">Mantenimiento</a>
            <a class="nav-link" href="/resources/views/citas/index.php">Citas</a>
            <a class="nav-link" href="/resources/views/historial/index.php">Historial Citas</a>
            <a class="nav-link" href="/resources/views/calendario/index.php">Calendario</a>
        </div>
    </div>
    <div class="ms-auto">
        <span class="text-success me-3">Admin Tarea Completa</span>
        <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>
</nav>

<!-- Contenido de Reportes -->
<div class="container">
  <div class="row g-4">

    <!-- Reporte de Pacientes -->
    <div class="col-6 col-md-4">
      <a href="/resources/views/reportes/reporte_pacientes.php" target="_blank" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <i class="bi bi-person-vcard"></i>
          <span>Reporte de Pacientes</span>
        </div>
      </a>
    </div>

    <!-- Reporte de Médicos -->
  <div class="col-6 col-md-4">
  <a href="/resources/views/reportes/reporte_medicos.php" target="_blank" class="text-decoration-none text-dark">
    <div class="dashboard-card">
      <i class="bi bi-person-badge"></i>
      <span>Reporte de Médicos</span>
    </div>
  </a>
</div>


    <!-- Volver al Menú Principal -->
    <div class="col-6 col-md-4">
      <a href="/resources/views/layouts/index.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <i class="bi bi-arrow-left-circle"></i>
          <span>Volver al Menú Principal</span>
        </div>
      </a>
    </div>

  </div>
</div>

<!-- JS de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
