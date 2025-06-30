<?php
require_once __DIR__ . '/../../../app/Http/Controllers/SpecialtyController.php';
require_once __DIR__ . '/../../../config/auth.php';


$controller = new SpecialtyController();
$data = $controller->index();

$specialties = $data['specialties'];
$search      = $data['search'];
$success     = $data['success'];
$errors      = $data['errors'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Especialidades - Clínica Dental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
<a class="btn btn-outline-secondary" href="/resources/views/layouts/index.php">← Volver al menú</a>            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearEspecialidadModal">➕ Nueva Especialidad</button>
        </div>
        <form method="GET" class="d-flex" style="gap: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar especialidad..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
            <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></a>
        </form>
        <?php if (isset($_GET['search']) && strlen(trim($_GET['search'])) < 2): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Swal.fire({
        icon: 'warning',
        title: 'Búsqueda demasiado corta',
        text: 'Por favor, escribe al menos 2 letras para buscar por paciente.',
        confirmButtonColor: '#ffc107'
      });

      // Limpiar la URL de los parámetros después de mostrar el alert
      if (window.history.replaceState) {
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
      }
    });
  </script>
<?php endif; ?>

    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="text-center mb-3"><?= count($specialties) ?> ESPECIALIDADES EN TOTAL</h5>
            <table class="table table-bordered align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Especialidad</th>
                        <th>Descripción</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($specialties) > 0): ?>
                    <?php foreach ($specialties as $i => $esp): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= strtoupper(htmlspecialchars($esp['name'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($esp['description'] ?? 'Sin descripción') ?></td>
                            <td>
                                <a href="editar_especialidad.php?id=<?= $esp['id'] ?>" class="btn btn-sm btn-info text-white">✏️</a>
                                <form action="eliminar_especialidad.php" method="POST" class="d-inline form-eliminar">
                                    <input type="hidden" name="id" value="<?= $esp['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">❌</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No hay especialidades registradas.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="crearEspecialidadModal" tabindex="-1" aria-labelledby="crearEspecialidadLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="guardar_especialidad.php" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Nueva Especialidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Opcional"></textarea>
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

<!-- Scripts mínimos -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Confirmación para eliminar
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    <?php if ($success): ?>
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: '<?= $success ?>',
        timer: 2500,
        showConfirmButton: false
    });
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: '<?= implode("<br>", array_map("htmlspecialchars", $errors)) ?>'
    });
    <?php endif; ?>
</script>

</body>
</html>
