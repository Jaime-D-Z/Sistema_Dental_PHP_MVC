<?php
require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zona = $_POST['zona'];
    $nombre = trim($_POST['nombre']);
    $simbolo = trim($_POST['simbolo']);
    $color = trim($_POST['color']);

    $stmt = $conn->prepare("UPDATE odontograma_buttons SET zona = ?, nombre = ?, simbolo = ?, color = ? WHERE id = ?");
    $stmt->execute([$zona, $nombre, $simbolo, $color, $id]);

    header("Location: index.php?editado=1");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM odontograma_buttons WHERE id = ?");
$stmt->execute([$id]);
$btn = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- Formulario simple de edición -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar botón</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h3>✏️ Editar Botón</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Zona</label>
        <select name="zona" class="form-select" required>
          <?php foreach (['superior','inferior','izquierda','derecha','centro','todos'] as $zona): ?>
            <option value="<?= $zona ?>" <?= $btn['zona'] === $zona ? 'selected' : '' ?>><?= ucfirst($zona) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($btn['nombre']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Símbolo</label>
        <input type="text" name="simbolo" class="form-control" value="<?= htmlspecialchars($btn['simbolo']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Color</label>
        <input type="color" name="color" class="form-control form-control-color" value="<?= htmlspecialchars($btn['color']) ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
  
</body>
</html>
