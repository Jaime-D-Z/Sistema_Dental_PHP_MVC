<?php
require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

$zona = $_POST['zona'] ?? '';
$nombre = trim($_POST['nombre'] ?? '');
$simbolo = trim($_POST['simbolo'] ?? '');
$color = trim($_POST['color'] ?? '');

if ($zona && $nombre && $color) {
    $stmt = $conn->prepare("INSERT INTO odontograma_buttons (zona, nombre, simbolo, color, is_active, is_deleted, created_at) VALUES (?, ?, ?, ?, 1, 0, NOW())");
    $stmt->execute([$zona, $nombre, $simbolo, $color]);
    header("Location: index.php?creado=1");
    exit;
} else {
    header("Location: index.php?error=1");
    exit;
}
