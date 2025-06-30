<?php
session_start();

require_once __DIR__ . '/../../../app/Http/Controllers/UserController.php';
require_once __DIR__ . '/../../../config/auth.php';


$controller = new UserController();
$data = $controller->index();

$usuarios = $data['users'];
$search = $data['search'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - Clínica Dental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; }
        .topbar { background-color: #ffffff; border-bottom: 1px solid #dee2e6; }
        .topbar a { color: #0d6efd; font-weight: 500; margin-right: 20px; text-decoration: none; }
        .topbar a:hover { text-decoration: underline; }
        .foto-usuario { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 1px solid #ccc; }
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
<a class="btn btn-outline-secondary" href="/resources/views/layouts/index.php">← Volver al menú</a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">➕ Nuevo Usuario</button>
        </div>
        <form method="GET" action="index.php"    class="d-flex" style="gap:10px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar usuario..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
            <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></a>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="text-center mb-3"><?= count($usuarios) ?> USUARIOS REGISTRADOS</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Rol</th>
                            <th>Tipo Documento</th>
                            <th>Número</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($usuarios) > 0): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($usuario['photo'])): ?>
                                            <img src="uploads/<?= htmlspecialchars($usuario['photo']) ?>" class="foto-usuario" alt="Foto">
                                        <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($usuario['name']) ?>" class="foto-usuario" alt="Avatar">
                                        <?php endif ?>
                                    </td>
                                    <td><?= htmlspecialchars($usuario['name']) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($usuario['role'])) ?></td>
                                    <td><?= htmlspecialchars($usuario['document_type']) ?></td>
                                    <td><?= htmlspecialchars($usuario['document_number']) ?></td>
                                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                                    <td><?= htmlspecialchars($usuario['phone']) ?></td>
                                    <td>
                                        <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(<?= $usuario['id'] ?>)">🗑️</button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr><td colspan="8">No hay usuarios registrados.</td></tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Nuevo Usuario -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="guardar_usuario.php" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Nuevo Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Foto de Perfil</label>
            <input type="file" name="photo" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Nombre Completo</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="role" class="form-select" required>
              <option value="" disabled selected>Seleccione un rol</option>
              <option value="admin">Admin</option>
              <option value="doctor">Médico</option>
              <option value="patient">Paciente</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipo de Documento</label>
            <select name="document_type" class="form-select" required>
              <option value="" disabled selected>Seleccione tipo</option>
              <option value="DNI">DNI</option>
              <option value="Pasaporte">Pasaporte</option>
              <option value="Carnet Ext.">Carnet Ext.</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Número de Documento</label>
            <input type="text" name="document_number" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required minlength="6">
          </div>
          <div class="mb-3">
            <label class="form-label">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="6">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Usuario</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function confirmarEliminacion(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: 'No podrás revertir esta acción',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `eliminar_usuario.php?id=${id}`;
    }
  });
}
</script>

<?php if (isset($_SESSION['msg'])): ?>
  <script>
    let mensaje = "<?= htmlspecialchars($_SESSION['msg']) ?>";
    let icono = mensaje.includes("creado") || mensaje.includes("actualizado") || mensaje.includes("eliminado")
      ? "success"
      : "error";

    Swal.fire({
      icon: icono,
      title: icono === "success" ? "Éxito" : "Error",
      text: mensaje,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Aceptar'
    });
  </script>
  <?php unset($_SESSION['msg']); ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (isset($_GET['search']) && mb_strlen(trim($_GET['search'])) < 3 && mb_strlen(trim($_GET['search'])) > 0): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Swal.fire({
        icon: 'warning',
        title: 'Búsqueda muy corta',
        text: 'Por favor, escribe al menos 3 letras para buscar usuarios.',
        confirmButtonColor: '#ffc107'
      });

      const cleanUrl = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, cleanUrl);
    });
  </script>
<?php endif; ?>

</body>
</html>
