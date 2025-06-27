<?php
require_once __DIR__ . '/../../../app/Http/Controllers/AppointmentHistoryController.php';
require_once __DIR__ . '/../../../config/auth.php';


$controller = new AppointmentHistoryController();
extract($controller->index()); // Extrae $historiales y $search
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Citas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .topbar { background-color: #ffffff; border-bottom: 1px solid #dee2e6; }
    .topbar a { color: #0d6efd; font-weight: 500; margin-right: 20px; text-decoration: none; }
    .topbar a:hover { text-decoration: underline; }
    .table thead th {
      background-color: #e3f2fd !important;
    }
    .table td, .table th {
      vertical-align: middle;
    }
  </style>
</head>
<body>

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
    <span class="text-success me-3">Admin Tarea Completa</span>
    <a class="btn btn-outline-danger btn-sm" href="/clinica-dental/logout.php">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</nav>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="/resources/views/layouts/index.php" class="btn btn-outline-secondary">← Volver al menú</a>
    <form method="GET" class="d-flex" style="gap: 10px;">
      <input type="text" name="search" class="form-control" placeholder="Buscar historial..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-success"><i class="bi bi-search"></i></button>
      <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></a>
    </form>
  </div>

  <div class="card">
    <div class="card-body">
      <h5 class="text-center mb-3"><?= count($historiales) ?> HISTORIALES DE CITAS EN TOTAL</h5>
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead class="text-primary fw-semibold">
            <tr>
              <th>#</th>
              <th>Tratamiento</th>
              <th>Médico</th>
              <th>Paciente</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Diagnóstico</th>
              <th>Estado</th>
              <th>Pago</th>
              <th>Costo</th>
              <th>Pagado</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($historiales) > 0): ?>
              <?php foreach ($historiales as $i => $h): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($h['tratamiento_nombre']) ?></td>
                  <td><?= htmlspecialchars($h['medico_nombre']) ?></td>
                  <td><?= htmlspecialchars($h['paciente_nombre']) ?></td>
                  <td><?= htmlspecialchars($h['date']) ?></td>
                  <td><?= htmlspecialchars($h['time']) ?></td>
                  <td><?= htmlspecialchars($h['diagnosis']) ?></td>
                  <td>
                    <?php if ($h['status'] === 'atendido'): ?>
                      <span class="text-success"><i class="bi bi-check-circle-fill"></i> Atendido</span>
                    <?php else: ?>
                      <span class="text-danger"><i class="bi bi-exclamation-circle-fill"></i> Asignado</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($h['paid'] >= $h['cost']): ?>
                      <span class="text-success"><i class="bi bi-cash-coin"></i> Aplicado</span>
                    <?php elseif ($h['paid'] > 0): ?>
                      <span class="text-warning"><i class="bi bi-wallet2"></i> Parcial</span>
                    <?php else: ?>
                      <span class="text-danger"><i class="bi bi-x-circle"></i> Pendiente</span>
                    <?php endif; ?>
                  </td>
                  <td><strong>S/. <?= number_format($h['cost'], 2) ?></strong></td>
                  <td>
                    <?php if ($h['paid'] > 0): ?>
                      <span class="text-success fw-bold">S/. <?= number_format($h['paid'], 2) ?></span>
                    <?php else: ?>
                      <span class="text-danger fw-bold">S/. 0.00</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="11">No hay historiales registrados.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
