<?php
require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("UPDATE odontograma_buttons SET is_deleted = 1 WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php?eliminado=1");
exit;
