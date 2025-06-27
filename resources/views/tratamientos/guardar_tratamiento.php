<?php
session_start();
require_once(__DIR__.'/../../../config/Database.php');
$conn = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    if ($name === '') {
        $_SESSION['errors'] = ['El nombre es obligatorio.'];
    } elseif ($price <= 0) {
        $_SESSION['errors'] = ['El precio debe ser mayor que 0.'];
    } else {
        try {
            $stmt = $conn->prepare("
                INSERT INTO treatments (name, description, price, is_active, is_deleted, created_at)
                VALUES (?, ?, ?, 1, 0, NOW())
            ");
            $stmt->execute([
                $name,
                $description !== '' ? $description : null,
                $price
            ]);
            $_SESSION['success'] = 'Tratamiento creado correctamente.';
        } catch (PDOException $e) {
            $_SESSION['errors'] = ['Error al guardar: ' . $e->getMessage()];
        }
    }
}

header('Location: index.php');
exit;
