<?php
// Asegúrate de iniciar la sesión antes de usar $_SESSION
session_start();

// Simulamos datos para que funcione directamente sin base de datos
$_SESSION['user_name'] = $_SESSION['user_name'] ?? 'Admin'; // Nombre de usuario
$totalPacientes = $totalPacientes ?? 12;
$citasHoy = $citasHoy ?? 4;
$ingresosHoy = $ingresosHoy ?? 250.75;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Control</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
  <h2 class="mb-4">Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?></h2>

  <div class="row g-4">
    <!-- Pacientes Registrados -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <h5 class="card-title">
            <i class="bi bi-person-vcard fs-3 text-primary"></i>
          </h5>
          <h6 class="card-subtitle mb-2 text-muted">Pacientes Registrados</h6>
          <h3><?= $totalPacientes ?></h3>
        </div>
      </div>
    </div>

    <!-- Citas Hoy -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <h5 class="card-title">
            <i class="bi bi-calendar-check fs-3 text-success"></i>
          </h5>
          <h6 class="card-subtitle mb-2 text-muted">Citas Hoy</h6>
          <h3><?= $citasHoy ?></h3>
        </div>
      </div>
    </div>

    <!-- Ingresos del Día -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <h5 class="card-title">
            <i class="bi bi-cash-coin fs-3 text-warning"></i>
          </h5>
          <h6 class="card-subtitle mb-2 text-muted">Ingresos del Día</h6>
          <h3>S/ <?= number_format($ingresosHoy, 2) ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
