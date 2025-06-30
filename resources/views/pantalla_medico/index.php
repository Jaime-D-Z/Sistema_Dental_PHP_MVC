<?php
require_once __DIR__ . '/../../../app/Http/Controllers/DoctorCitaController.php';
require_once __DIR__ . '/../../../config/auth.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$controller = new DoctorCitaController();
$data = $controller->index();

$appointments = $data['appointments'];
$treatments   = $data['treatments'];
$doctor_id    = $data['doctor_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Citas - Panel Médico</title>
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

<?php if (isset($_SESSION['flash'])): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['type'] === 'success' ? 'Éxito' : 'Error' ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        confirmButtonColor: '#3085d6'
      });
    });
  </script>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Barra superior -->
<nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
  <a class="navbar-brand fw-bold text-primary" href="#">🩺 Médico</a>
  <div class="collapse navbar-collapse">
    <div class="navbar-nav">
      <a class="nav-link" href="/resources/views/layouts/index.php">Inicio</a>
      <a class="nav-link" href="#">Mantenimiento</a>
      <a class="nav-link" href="/resources/views/citas/index.php">Citas</a>
      <a class="nav-link" href="/resources/views/historial/index.php">Historial Citas</a>
      <a class="nav-link" href="/resources/views/calendario/index.php">Calendario</a>
    </div>
  </div>
  <span class="text-success me-3"><?= $_SESSION['user']['name'] ?></span>
  <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
  </a>
</nav>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">📅 Mis Citas</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaCitaModal">➕ Nueva Cita</button>
  </div>

  <table class="table table-bordered text-center align-middle">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Paciente</th>
        <th>Tratamiento</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Diagnóstico</th>
        <th>Estado</th>
        <th>Precio</th>
        <th>Pagado</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($appointments) > 0): ?>
        <?php foreach ($appointments as $i => $cita): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($cita['patient']) ?></td>
            <td><?= htmlspecialchars($cita['treatment']) ?></td>
            <td><?= $cita['date'] ?></td>
            <td><?= $cita['time'] ?></td>
            <td><?= $cita['diagnosis'] ?? '-' ?></td>
            <td><?= ucfirst($cita['status']) ?></td>
            <td>S/. <?= number_format($cita['cost'], 2) ?></td>
            <td>S/. <?= number_format($cita['paid'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="9">No tienes citas registradas.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal Nueva Cita -->
<div class="modal fade" id="nuevaCitaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="guardar_cita.php" method="POST" onsubmit="return validarFormulario();">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Registrar Nueva Cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
          <div class="mb-3">
            <label class="form-label">Tratamiento</label>
            <select name="treatment_id" class="form-select" required>
              <option selected disabled>Seleccione tratamiento</option>
              <?php foreach ($treatments as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">DNI del Paciente</label>
            <input type="text" name="dni_paciente" class="form-control" maxlength="8" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="time" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Diagnóstico</label>
            <input type="text" name="diagnosis" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select" required>
              <option value="asignado">Asignado</option>
              <option value="atendido">Atendido</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Costo (S/.)</label>
            <input type="number" name="cost" step="0.01" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Monto Pagado (S/.)</label>
            <input type="number" name="paid" step="0.01" min="0" value="0.00" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Notas</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-success" type="submit">Guardar Cita</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  function validarFormulario() {
    const dni = document.querySelector('[name="dni_paciente"]').value.trim();
    const tratamiento = document.querySelector('[name="treatment_id"]').value;
    const fecha = document.querySelector('[name="date"]').value;
    const hora = document.querySelector('[name="time"]').value;
    const estado = document.querySelector('[name="status"]').value;

    if (!dni || !tratamiento || !fecha || !hora || !estado) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos incompletos',
        text: 'Por favor, completa todos los campos requeridos.'
      });
      return false;
    }

    return true;
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
