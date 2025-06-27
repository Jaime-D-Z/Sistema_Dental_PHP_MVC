<?php
require_once __DIR__ . '/../../../config/Database.php';

$db = Database::connect();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die("ID de cita no proporcionado o inválido.");
}

try {
    // Eliminación lógica: marcar como eliminada e inactiva
    $stmt = $db->prepare("UPDATE appointments SET is_deleted = 1, is_active = 0 WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        header("Location: index.php?success=eliminada");
    } else {
        header("Location: index.php?error=no_encontrada");
    }
    exit;
} catch (PDOException $e) {
    header("Location: index.php?error=" . urlencode("Error al eliminar cita: " . $e->getMessage()));
    exit;
}
