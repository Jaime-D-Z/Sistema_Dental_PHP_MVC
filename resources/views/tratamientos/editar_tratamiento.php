<?php
session_start();
require_once(__DIR__.'/../../../config/Database.php');
$conn = Database::connect();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Obtener tratamiento
$stmt = $conn->prepare("SELECT * FROM treatments WHERE id = ? AND is_deleted = 0");
$stmt->execute([$id]);
$t = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$t) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    $errors = [];

    if ($name === '') {
        $errors[] = 'El nombre del tratamiento es obligatorio.';
    }
    if ($price <= 0) {
        $errors[] = 'El precio debe ser mayor que 0.';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
    } else {
        try {
            $stmt = $conn->prepare("UPDATE treatments SET 
                name = ?, 
                description = ?, 
                price = ?, 
                updated_at = NOW()
                WHERE id = ? AND is_deleted = 0");

            $stmt->execute([$name, $description ?: null, $price, $id]);

            $_SESSION['success'] = 'Tratamiento actualizado correctamente.';
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['errors'] = ['Error al actualizar: ' . $e->getMessage()];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Tratamiento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-4">
  <h3>Editar Tratamiento</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nombre del Tratamiento</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($t['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Descripción</label>
      <textarea name="description" class="form-control"><?= htmlspecialchars($t['description'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label>Precio (S/.)</label>
      <input type="number" name="price" step="0.01" class="form-control" value="<?= htmlspecialchars($t['price']) ?>" required>
    </div>
    <button class="btn btn-primary">Guardar Cambios</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
<script>
<?php if (isset($_SESSION['errors'])): ?>
Swal.fire({
  icon: 'error',
  title: 'Error',
  html: '<?= implode('<br>', array_map('htmlspecialchars', $_SESSION['errors'])) ?>'
});
<?php unset($_SESSION['errors']); endif; ?>

</script>
</body>
</html>
