<?php
session_start();
require_once(__DIR__.'/../../../config/Database.php');
$conn = Database::connect();

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Eliminación lógica
        $stmt = $conn->prepare("UPDATE treatments SET is_deleted = 1, updated_at = NOW() WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['success'] = 'Tratamiento eliminado correctamente.';
        } else {
            $_SESSION['errors'] = ['No se pudo eliminar el tratamiento.'];
        }
    } catch (PDOException $e) {
        $_SESSION['errors'] = ['Error en la base de datos: ' . $e->getMessage()];
    }
}

header('Location: index.php');
exit;
