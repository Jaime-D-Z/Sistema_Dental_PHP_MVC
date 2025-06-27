<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

$id = $_POST['id'] ?? null;

$name = trim($_POST['name']);
$role = $_POST['role'];
$document_type = $_POST['document_type'];
$document_number = $_POST['document_number'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'] ?? null;
$password_confirmation = $_POST['password_confirmation'] ?? null;

// Validación de contraseña solo si es nuevo
if (!$id && ($password !== $password_confirmation)) {
    $_SESSION['msg'] = "Las contraseñas no coinciden.";
    header("Location: index.php");
    exit;
}

// Procesar foto
$photoName = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['photo']['tmp_name'];
    $photoName = time() . '_' . basename($_FILES['photo']['name']);
    $destino = __DIR__ . '/uploads/' . $photoName;
    move_uploaded_file($tmp, $destino);
}

try {
    if (!$id) {
        // Crear nuevo usuario
        $stmt = $conn->prepare("INSERT INTO users (name, role, document_type, document_number, phone, email, password, photo)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->execute([
            $name,
            $role,
            $document_type,
            $document_number,
            $phone,
            $email,
            $hashedPassword,
            $photoName
        ]);

        $user_id = $conn->lastInsertId(); // Obtener el ID del nuevo usuario

        // Insertar en tabla relacionada según el rol
        if ($role === 'patient') {
            $stmt2 = $conn->prepare("INSERT INTO patients (user_id, document_type, first_name, last_name, dni, email, phone, created_at)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt2->execute([
                $user_id,
                $document_type,
                $name,
                '', // last_name lo puedes ajustar si lo agregas al formulario
                $document_number,
                $email,
                $phone
            ]);

        } elseif ($role === 'doctor') {
            $stmt3 = $conn->prepare("INSERT INTO doctors (user_id, first_name, last_name, dni, email, phone, specialty_id, address, created_at)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, '', NOW())");
            $stmt3->execute([
                $user_id,
                $name,
                '', // last_name
                $document_number,
                $email,
                $phone,
                1 // ID de especialidad por defecto
            ]);
        }

        $_SESSION['msg'] = "Usuario creado correctamente";

    } else {
        // Actualizar usuario existente
        $sql = "UPDATE users SET name=?, role=?, document_type=?, document_number=?, phone=?, email=?";
        $params = [$name, $role, $document_type, $document_number, $phone, $email];

        if (!empty($photoName)) {
            $sql .= ", photo=?";
            $params[] = $photoName;
        }

        $sql .= " WHERE id=?";
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        $_SESSION['msg'] = "Usuario actualizado correctamente";
    }

    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['msg'] = "Error: " . $e->getMessage();
    header("Location: index.php");
    exit;
}
