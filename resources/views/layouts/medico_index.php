<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user'])) {
    header("Location: /resources/views/auth/login.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clínica Dental</title>
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
  <a class="navbar-brand fw-bold text-primary" href="/resources/views/layouts/index.php">🦷 ODONTOLOGÍA TC</a>
  <div class="collapse navbar-collapse">
    <div class="navbar-nav">
      <a class="nav-link" href="/resources/views/layouts/index.php">Inicio</a>
      <a class="nav-link" href="#">Mantenimiento</a>
      <a class="nav-link" href="/resources/views/citas/index.php">Citas</a>
      <a class="nav-link" href="/resources/views/historial/index.php">Historial Citas</a>
      <a class="nav-link" href="/resources/views/calendario/index.php">Calendario</a>
    </div>
  </div>
  <div class="ms-auto">
    <span class="text-success me-3"><?= $_SESSION['user']['name'] ?></span>
        <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</nav>
<!-- Dashboard Grid -->
<div class="container">
    <div class="row g-4">


       
      
        <div class="col-6 col-md-3">
            <a href="/resources/views/pantalla_medico/index.php" class="text-decoration-none text-dark">
                <div class="dashboard-card">
                    <i class="bi bi-calendar-check"></i>
                    <span>Citas</span>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="/resources/views/pantalla_medico/historial_medico.php" class="text-decoration-none text-dark">
                <div class="dashboard-card">
                    <i class="bi bi-journal-medical"></i>
                    <span>Historial Citas</span>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="/resources/views/pantalla_medico/calendar_medico.php" class="text-decoration-none text-dark">
                <div class="dashboard-card">
                    <i class="bi bi-calendar-event"></i>
                    <span>Calendario</span>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="/resources/views/pagos/index.php" class="text-decoration-none text-dark">
                <div class="dashboard-card">
                    <i class="bi bi-currency-dollar"></i>
                    <span>Pagos</span>
                </div>
            </a>
        </div>

   
       

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>