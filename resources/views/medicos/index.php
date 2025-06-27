<?php
// resources/views/doctores/index.php
require_once __DIR__ . '/../../../app/Http/Controllers/DoctorController.php';
require_once __DIR__ . '/../../../config/auth.php';

$controller = new DoctorController();
$data = $controller->index();

$doctors = $data['doctors'];
$especialidades = $data['specialties'];
$search = $data['search'];
$success = $data['success'];
$errors = $data['errors'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Medicos - Clínica Dental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<!-- NAV -->
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

<!-- CONTENIDO -->
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
<a class="btn btn-outline-secondary" href="/resources/views/layouts/index.php">← Volver al menú</a>            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearDoctorModal">&plus; Nuevo Medico</button>
        </div>
        <form method="GET" class="d-flex" style="gap: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar Doctor..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
            <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></a>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="text-center mb-3"><?= count($doctors) ?> MEDICOS REGISTRADOS</h5>
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Especialidad</th>
                        <th>Dirección</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($doctors) > 0): ?>
                        <?php foreach ($doctors as $doc): ?>
                            <tr>
                                <td><?= htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name']) ?></td>
                                <td><?= htmlspecialchars($doc['dni']) ?></td>
                                <td><?= htmlspecialchars($doc['specialty']) ?></td>
                                <td><?= htmlspecialchars($doc['address'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($doc['email']) ?></td>
                                <td><?= htmlspecialchars($doc['phone'] ?? '-') ?></td>
                                <td>
                                    <a href="editar_medico.php?id=<?= $doc['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                    <a href="eliminar_medico.php?id=<?= $doc['id'] ?>" class="btn btn-sm btn-danger btn-eliminar">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No hay medicos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="crearDoctorModal" tabindex="-1" aria-labelledby="crearDoctorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="guardar_medico.php" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Nuevo Medico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nombres</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Apellidos</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">DNI</label><input type="text" name="dni" class="form-control" maxlength="8" required></div>
                    <div class="mb-3">
                        <label class="form-label">Especialidad</label>
                        <select name="specialty_id" class="form-select" required>
                            <option disabled selected>Seleccione una especialidad</option>
                            <?php foreach ($especialidades as $esp): ?>
                                <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Dirección</label><input type="text" name="address" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Correo electrónico</label><input type="email" name="email" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="phone" class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Medico</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    <?php if ($success): ?>
        Swal.fire({ icon: 'success', title: 'Éxito', text: '<?= $success ?>', timer: 2500, showConfirmButton: false });
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        Swal.fire({ icon: 'error', title: 'Error', html: '<?= implode("<br>", array_map("htmlspecialchars", $errors)) ?>' });
    <?php endif; ?>

    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
</body>
</html>
