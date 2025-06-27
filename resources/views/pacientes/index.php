<?php
session_start(); // ← ESTA LÍNEA FALTABA
require_once __DIR__ . '/../../../app/Http/Controllers/PatientController.php';
require_once __DIR__ . '/../../../config/auth.php';


$controller = new PatientController();
$data = $controller->index();

$pacientes = $data['patients'];
$search   = $data['search'];
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes - Clínica Dental</title>
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

<!-- Menú superior -->
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

<!-- Contenido -->
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
<a class="btn btn-outline-secondary" href="/resources/views/layouts/index.php">← Volver al menú</a>            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoPaciente">
                <i class="bi bi-person-plus"></i> Nuevo Paciente
            </button>
        </div>

        <form method="GET" class="d-flex" style="gap:10px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar paciente..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
            <a href="pacientes.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($pacientes) > 0): ?>
                    <?php foreach ($pacientes as $i => $paciente): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($paciente['first_name']) ?></td>
                            <td><?= htmlspecialchars($paciente['dni']) ?></td>
                            <td><?= htmlspecialchars($paciente['phone']) ?></td>
                            <td><?= htmlspecialchars($paciente['email']) ?></td>
                            <td>
                                <a href="editar_paciente.php?id=<?= $paciente['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                <a href="eliminar_paciente.php?id=<?= $paciente['id'] ?>" class="btn btn-sm btn-danger btn-eliminar">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay pacientes registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal: Nuevo Paciente -->
<div class="modal fade" id="modalNuevoPaciente" tabindex="-1" aria-labelledby="nuevoPacienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="guardar_paciente.php">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Nuevo Paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipo de Documento</label>
                            <select name="tipo_documento" class="form-select" required>
                                <option value="DNI">DNI</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="Carnet Ext.">Carnet Ext.</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nro Documento</label>
                            <input type="text" name="dni" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Correo</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Historia Clínica</label>
                            <input type="text" name="historia_clinica" class="form-control">
                        </div>
                    </div>

                    <hr>
                    <h5>Antecedentes Médicos</h5>
                    <div class="row">
                        <?php
                        $checkboxes = [
                            'bajo_tratamiento' => 'Bajo tratamiento médico',
                            'hemorragia' => 'Propenso a la hemorragia',
                            'alergia' => 'Alergico a medicamentos',
                            'hipertenso' => 'Hipertenso',
                            'diabetico' => 'Diabético',
                            'embarazada' => 'Embarazada'
                        ];
                        foreach ($checkboxes as $name => $label): ?>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= $label ?></label>
                                <select name="<?= $name ?>" class="form-select" required>
                                    <option value="">Selecciona...</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Motivo de la consulta</label>
                        <textarea name="motivo" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mt-2">
                        <label class="form-label">Diagnóstico</label>
                        <textarea name="diagnostico" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mt-2">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mt-2">
                        <label class="form-label">Referido por</label>
                        <input type="text" name="referido_por" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Paciente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Scripts necesarios -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert de mensajes -->
<?php if (isset($_SESSION['msg'])):
    $msg = $_SESSION['msg'];
    $icon = 'success';

    if (str_contains($msg, 'Error') || str_contains($msg, 'error') || str_contains($msg, '❌')) {
        $icon = 'error';
    } elseif (str_contains($msg, '⚠️') || str_contains($msg, 'advertencia') || str_contains($msg, 'ya está registrado')) {
        $icon = 'warning';
    }
?>
<script>
    Swal.fire({
        icon: "<?= $icon ?>",
        title: "Mensaje",
        text: "<?= $msg ?>",
        confirmButtonColor: '#3085d6'
    });
</script>
<?php unset($_SESSION['msg']); endif; ?>

<!-- Confirmación antes de eliminar -->
<script>
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.href;

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>


</body>
</html>
