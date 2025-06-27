<?php

session_start();
$success = $_SESSION['success'] ?? null;
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['success'], $_SESSION['errors']);
require_once __DIR__ . '/../../../app/Http/Controllers/TreatmentController.php';
use App\Http\Controllers\TreatmentController;

$controller = new TreatmentController();
$data = $controller->index();
$tratamientos = $data['tratamientos'];
$search = $data['search'];
?>

<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><title>Tratamientos - Clínica Dental</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
        body { background-color: #f8f9fa; }
        .topbar { background-color: #ffffff; border-bottom: 1px solid #dee2e6; }
        .topbar a { color: #0d6efd; font-weight: 500; margin-right: 20px; text-decoration: none; }
        .topbar a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg topbar px-4 py-2">
    <a class="navbar-brand fw-bold text-primary" href="/resources/views/layouts/index.php">
        🦷 ODONTOLOGÍA TC
    </a>
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
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
<a class="btn btn-outline-secondary" href="/resources/views/layouts/index.php">← Volver al menú</a>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearTratamientoModal">
        ➕ Nuevo Tratamiento
      </button>
    </div>
    <form method="GET" class="d-flex" style="gap:10px;">
      <input type="text" name="search" class="form-control" placeholder="Buscar Tratamientos..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
      <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></a>
    </form>
  </div>

  <table class="table table-bordered align-middle text-center">
    <thead class="table-light">
      <tr><th>#</th><th>Tratamiento</th><th>Precio</th><th>Acciones</th></tr>
    </thead><tbody>
    <?php if (count($tratamientos)): foreach ($tratamientos as $i => $t): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($t['name']) ?></td>
        <td class="fw-bold text-primary">S/. <?= number_format($t['price'],2) ?></td>
        <td>
          <a href="editar_tratamiento.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
          <button class="btn btn-sm btn-danger btn-eliminar" data-href="eliminar_tratamiento.php?id=<?= $t['id'] ?>">🗑️</button>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="4">No hay tratamientos registrados.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal nuevo tratamiento -->
<div class="modal fade" id="crearTratamientoModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="guardar_tratamiento.php" method="POST">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Nuevo Tratamiento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label>Nombre del Tratamiento</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3"><label>Descripción</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <div class="mb-3"><label>Precio (S/.)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php
$success = $success ?? null;
$errors = $errors ?? [];
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // SweetAlert si hay éxito
  <?php if ($success): ?>
    Swal.fire({
      icon: 'success',
      title: 'Éxito',
      text: '<?= htmlspecialchars($success) ?>',
      confirmButtonColor: '#3085d6'
    });
  <?php endif; ?>

  // SweetAlert si hay errores
  <?php if ($errors): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: '<?= implode("<br>", array_map("htmlspecialchars", $errors)) ?>',
      confirmButtonColor: '#d33'
    });
  <?php endif; ?>

  // Confirmación de eliminación con redirección
  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault(); // evita salto inmediato
      Swal.fire({
        title: '¿Eliminar tratamiento?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar'
      }).then(result => {
        if (result.isConfirmed) {
          window.location.href = btn.getAttribute('data-href');
        }
      });
    });
  });
</script>
</body></html>
