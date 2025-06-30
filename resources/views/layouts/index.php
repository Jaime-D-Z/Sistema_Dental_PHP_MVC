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
        :root {
            --bg-light: #f8f9fa;
            --text-dark: #212529;
            --card-bg-light: white;
            --card-hover-light: #e9f1ff;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
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
            background-color: var(--card-bg-light);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card:hover {
            background-color: var(--card-hover-light);
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

        body.dark-mode {
            --bg-light: #121212;
            --text-dark: #f1f1f1;
            --card-bg-light: #1e1e1e;
            --card-hover-light: #2c2c2c;
        }

        .dark-mode .topbar {
            background-color: #1f1f1f;
            border-bottom: 1px solid #444;
        }

        .dark-mode .topbar a {
            color: #66b2ff;
        }

        .dark-mode .dashboard-card i {
            color: #66b2ff;
        }

        .dark-mode a.text-dark {
            color: #f1f1f1 !important;
        }

        .theme-toggle {
            cursor: pointer;
            font-size: 1.25rem;
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
      <a class="nav-link" href="/resources/views/config/index.php">Mantenimiento</a>
      <a class="nav-link" href="/resources/views/citas/index.php">Citas</a>
      <a class="nav-link" href="/resources/views/historial/index.php">Historial Citas</a>
      <a class="nav-link" href="/resources/views/calendario/index.php">Calendario</a>
    </div>
  </div>
  <div class="ms-auto d-flex align-items-center gap-3">
    <span class="text-success">Admin Tarea Completa</span>
    <i class="bi bi-moon theme-toggle" onclick="toggleTheme()" id="themeIcon"></i>
    <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</nav>

<!-- Dashboard Grid -->
<div class="container mt-4">
  <div class="row g-4">

    <?php
    $items = [
        ["Usuarios", "bi-people", "usuarios"],
        ["Pacientes", "bi-person-vcard", "pacientes"],
        ["Especialidades", "bi-clipboard-plus", "especialidades"],
        ["Médicos", "bi-person-badge", "medicos"],
        ["Tratamientos", "bi-capsule", "tratamientos"],
        ["Citas", "bi-calendar-check", "citas"],
        ["Odontograma", "bi-emoji-smile", "odontograma"],
        ["Historial Citas", "bi-journal-medical", "historial"],
        ["Calendario", "bi-calendar-event", "calendario"],
        ["Pagos", "bi-currency-dollar", "pagos"],
        ["Reportes", "bi-printer", "reportes"],
        ["Resumen", "bi-bar-chart-line", "resumen"],
        ["Tendencia", "bi-graph-up-arrow", "tendencias"]
    ];

    foreach ($items as [$name, $icon, $url]) {
        echo <<<HTML
        <div class="col-6 col-md-3">
            <a href="/resources/views/$url/index.php" class="text-decoration-none text-dark">
                <div class="dashboard-card">
                    <i class="bi $icon"></i>
                    <span>$name</span>
                </div>
            </a>
        </div>
        HTML;
    }
    ?>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleTheme() {
    const body = document.body;
    body.classList.toggle("dark-mode");

    const icon = document.getElementById("themeIcon");
    if (body.classList.contains("dark-mode")) {
      icon.classList.remove("bi-moon");
      icon.classList.add("bi-sun");
      localStorage.setItem("theme", "dark");
    } else {
      icon.classList.remove("bi-sun");
      icon.classList.add("bi-moon");
      localStorage.setItem("theme", "light");
    }
  }

  // Aplicar preferencia guardada
  window.onload = () => {
    const theme = localStorage.getItem("theme");
    const icon = document.getElementById("themeIcon");

    if (theme === "dark") {
      document.body.classList.add("dark-mode");
      icon.classList.remove("bi-moon");
      icon.classList.add("bi-sun");
    }
  };
</script>
</body>
</html>
