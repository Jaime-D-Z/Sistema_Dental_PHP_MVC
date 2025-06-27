<?php
require_once __DIR__ . '/../../../app/Http/Controllers/AppointmentController.php';
require_once __DIR__ . '/../../../config/auth.php';


$controller = new AppointmentController();
$data = $controller->index();

$appointments = $data['appointments'];
$treatments   = $data['treatments'];
$doctors      = $data['doctors'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas - Clínica Dental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <span class="text-success me-3">Admin Tarea Completa</span>
        <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</nav>

<!-- Contenido -->
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
<a class="btn btn-outline-secondary" href="/resources/views/layouts/index.php">← Volver al menú</a>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaCitaModal">➕ Nueva Cita</button>
    </div>
    <form method="GET" action="index.php" class="d-flex" style="gap: 10px;">
      <input type="text" name="search" class="form-control" placeholder="Buscar por paciente o motivo...">
      <button class="btn btn-success"><i class="bi bi-search"></i></button>
      <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></a>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead class="table-primary">
        <tr>
          <th>#</th>
          <th>Tratamiento</th>
          <th>Médico</th>
          <th>Paciente</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Enfermedad</th>
          <th>Precio</th>
          <th>Pagado</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($appointments) > 0): ?>
          <?php foreach ($appointments as $i => $app): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($app['treatment']) ?></td>
              <td><?= htmlspecialchars($app['doctor']) ?></td>
              <td><?= htmlspecialchars($app['patient']) ?></td>
              <td><?= htmlspecialchars($app['date']) ?></td>
              <td><?= htmlspecialchars($app['time']) ?></td>
              <td><?= htmlspecialchars($app['diagnosis']) ?></td>
              <td><strong>S/. <?= number_format($app['cost'], 2) ?></strong></td>
              <td>
                <?php if ($app['paid'] > 0): ?>
                  <span class="text-success fw-bold">S/. <?= number_format($app['paid'], 2) ?></span>
                <?php else: ?>
                  <span class="text-danger fw-bold">S/. 0.00</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="edit.php?id=<?= $app['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                  <i class="bi bi-pencil"></i>
                </a>
                <button class="btn btn-sm btn-danger" title="Eliminar" onclick="confirmarEliminacion(<?= $app['id'] ?>)">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="10">No hay citas registradas.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>


<!-- Modal Nueva Cita -->
<div class="modal fade" id="nuevaCitaModal" tabindex="-1" aria-labelledby="nuevaCitaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="guardar_cita.php" method="POST">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Registrar Nueva Cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Tratamiento -->
          <div class="mb-3">
            <label class="form-label">Tratamiento</label>
            <select name="treatment_id" class="form-select" required>
              <option selected disabled>Seleccione un tratamiento</option>
              <?php foreach ($treatments as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Médico -->
          <div class="mb-3">
            <label class="form-label">Médico</label>
            <select name="doctor_id" class="form-select" required>
              <option selected disabled>Seleccione un médico</option>
              <?php foreach ($doctors as $m): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['full_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Paciente DNI -->
          <div class="mb-3">
            <label class="form-label">Paciente (DNI)</label>
            <input type="text" name="dni_paciente" class="form-control" maxlength="8" required>
          </div>

          <!-- Fecha -->
          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="date" class="form-control" required>
          </div>

          <!-- Hora -->
          <div class="mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="time" class="form-control" required>
          </div>

          <!-- Diagnóstico -->
          <div class="mb-3">
            <label class="form-label">Diagnóstico</label>
            <input type="text" name="diagnosis" class="form-control">
          </div>

          <!-- Estado -->
          <div class="mb-3">
            <label class="form-label">Estado de la cita</label>
            <select name="status" class="form-select" required>
              <option value="asignado">Asignado</option>
              <option value="atendido">Atendido</option>
            </select>
          </div>

          <!-- Costo -->
          <div class="mb-3">
            <label class="form-label">Costo (S/.)</label>
            <input type="number" name="cost" class="form-control" step="0.01" required>
          </div>

          <!-- Monto pagado -->
          <div class="mb-3">
            <label class="form-label">Monto Pagado (S/.)</label>
            <input type="number" name="paid" class="form-control" step="0.01" min="0" value="0.00" required>
          </div>

          <!-- Notas -->
          <div class="mb-3">
            <label class="form-label">Notas</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar Cita</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function confirmarEliminacion(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "Esta acción eliminará la cita definitivamente.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      // Redirige y marca como eliminada en la URL
      window.location.href = 'eliminar_cita.php?id=' + id + '&confirm=1';
    }
  });
}
</script>

<?php if (!empty($_GET['error']) && $_GET['error'] === 'paciente_no_encontrado' && !empty($_GET['dni'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
      icon: 'error',
      title: 'Paciente no encontrado',
      text: 'No se encontró al paciente con DNI <?= htmlspecialchars($_GET["dni"]) ?>. Verifica que esté registrado.',
      confirmButtonText: 'Entendido',
      confirmButtonColor: '#3085d6'
    }).then(() => {
      const modal = new bootstrap.Modal(document.getElementById('nuevaCitaModal'));
      modal.show();
    });

    if (window.history.replaceState) {
      const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
      window.history.replaceState({}, document.title, cleanUrl);
    }
  });
</script>
<?php endif; ?>

<?php if (!empty($_GET['success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    <?php if ($_GET['success'] === 'registrada'): ?>
      Swal.fire({
        icon: 'success',
        title: '¡Cita registrada!',
        text: 'La nueva cita fue registrada correctamente.',
        confirmButtonColor: '#198754'
      });
    <?php elseif ($_GET['success'] === 'eliminada'): ?>
      Swal.fire({
        icon: 'success',
        title: 'Cita eliminada',
        text: 'La cita fue eliminada con éxito.',
        confirmButtonColor: '#198754',
        timer: 2000,
        showConfirmButton: false
      });
    <?php elseif ($_GET['success'] === 'actualizada'): ?>
      Swal.fire({
        icon: 'success',
        title: 'Cita actualizada',
        text: 'Los datos de la cita se actualizaron correctamente.',
        confirmButtonColor: '#198754'
      });
    <?php endif; ?>

    if (window.history.replaceState) {
      const cleanUrl = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, cleanUrl);
    }
  });
</script>
<?php endif; ?>






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 