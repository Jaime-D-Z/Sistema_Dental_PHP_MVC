<?php
require_once(__DIR__ . '/../../../config/Database.php');
require_once __DIR__ . '/../../../config/auth.php';


$conn = Database::connect();

// Meses abreviados en español
$meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
$citasPorMes = array_fill(0, 12, 0);

// Año actual
$year = date('Y');

// Consulta usando appointments y filtros activos
$stmt = $conn->prepare("
    SELECT MONTH(date) AS mes, COUNT(*) AS total
    FROM appointments
    WHERE YEAR(date) = ? AND is_active = 1 AND is_deleted = 0
    GROUP BY mes
");
$stmt->execute([$year]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultados as $fila) {
    $indice = (int)$fila['mes'] - 1;
    $citasPorMes[$indice] = (int)$fila['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Citas - Clínica Dental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .topbar { background-color: #ffffff; border-bottom: 1px solid #dee2e6; }
        .topbar a { color: #0d6efd; font-weight: 500; margin-right: 20px; text-decoration: none; }
        .topbar a:hover { text-decoration: underline; }
    </style>
</head>
<body>

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
  <div class="ms-auto">
    <span class="text-success me-3">Admin Tarea Completa</span>
        <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</nav>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
<a class="btn btn-outline-secondary" href="/resources/views/layouts/index.php">← Volver al menú</a>
    <h3 class="m-0 flex-grow-1 text-center">Resumen de Citas por Mes (<?= $year ?>)</h3>
    <div style="width: 150px;"></div>
  </div>

  <div id="datos-citas"
       data-labels="<?= implode(',', $meses) ?>"
       data-values="<?= implode(',', $citasPorMes) ?>">
  </div>

  <canvas id="citasChart" height="100"></canvas>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const contenedor = document.getElementById('datos-citas');
    const etiquetas = contenedor.dataset.labels.split(',');
    const valores = contenedor.dataset.values.split(',').map(v => parseInt(v) || 0);

    const ctx = document.getElementById('citasChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Citas por mes',
                data: valores,
                backgroundColor: 'rgba(13, 110, 253, 0.6)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Cantidad de Citas' },
                    ticks: { stepSize: 1 }
                },
                x: {
                    title: { display: true, text: 'Meses' }
                }
            }
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
