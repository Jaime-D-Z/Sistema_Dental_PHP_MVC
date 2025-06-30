<?php
require_once(__DIR__ . '/../../../config/Database.php');
require_once __DIR__ . '/../../../config/theme.php';

$conn = Database::connect();
$buttons = $conn->query("SELECT * FROM odontograma_buttons WHERE is_active = 1 AND is_deleted = 0 ORDER BY zona ASC, nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configurar Botones de Odontograma</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { background-color: #f8f9fa; }
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
<body class="bg-light">

  <!-- NAVBAR -->
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
        <i class="bi bi-moon theme-toggle" onclick="toggleTheme()" id="themeIcon" title="Cambiar tema"></i>
      <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </a>
    </div>
  </nav>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="container py-4">
    <h2 class="mb-4">⚙️ Configuración de Botones de Odontograma</h2>
<div class="d-flex justify-content-between align-items-center mb-3">
  <a class="btn btn-outline-secondary" href="/resources/views/layouts/index.php">
    ← Volver al menú
  </a>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearModal">
    ➕ Agregar nuevo botón
  </button>
</div>

    <table class="table table-bordered table-hover bg-white">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Zona</th>
          <th>Nombre</th>
          <th>Color</th>
          <th>Vista previa</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($buttons as $btn): ?>
          <tr>
            <td><?= $btn['id'] ?></td>
            <td><?= ucfirst($btn['zona']) ?></td>
            <td><?= htmlspecialchars($btn['simbolo'] ?: $btn['nombre']) ?></td>
            <td><code><?= $btn['color'] ?></code></td>
            <td>
              <button class="btn btn-sm text-white" style="background-color: <?= htmlspecialchars($btn['color']) ?>;">
                <?= htmlspecialchars($btn['simbolo'] ?: $btn['nombre']) ?>
              </button>
            </td>
            <td>
              <a href="editar.php?id=<?= $btn['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
              <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(<?= $btn['id'] ?>)">Eliminar</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- MODAL DE CREACIÓN -->
  <div class="modal fade" id="crearModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="crear.php">
          <div class="modal-header">
            <h5 class="modal-title">➕ Nuevo botón</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Zona</label>
              <select name="zona" class="form-select" required>
                <option value="superior">Superior</option>
                <option value="inferior">Inferior</option>
                <option value="izquierda">Izquierda</option>
                <option value="derecha">Derecha</option>
                <option value="centro">Centro</option>
                <option value="todos">Todos</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Color</label>
              <input type="color" name="color" class="form-control form-control-color" required value="#000000">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function confirmarEliminacion(id) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: '¡Este botón será eliminado!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `eliminar.php?id=${id}`;
        }
      });
    }

    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.pathname);
    }
  </script>

  <?php if (isset($_GET['editado'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Cambios guardados',
        text: 'El botón ha sido actualizado correctamente'
      });
    </script>
  <?php endif; ?>

  <?php if (isset($_GET['eliminado'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Botón eliminado',
        text: 'El botón fue eliminado exitosamente.'
      });
    </script>
  <?php endif; ?>

</body>
</html>
