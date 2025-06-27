<?php
session_start();

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/config/google-config.php';

// Validar código de Google
if (!isset($_GET['code'])) {
    exit('No se recibió código de Google.');
}

try {
    // Obtener token de acceso desde el código de Google
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        throw new Exception(json_encode($token));
    }

    $client->setAccessToken($token['access_token']);

    // Obtener info del usuario
    $google_service = new Google_Service_Oauth2($client);
    $user_info = $google_service->userinfo->get();

    // Buscar o insertar usuario en la base de datos
    $conn = Database::connect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_deleted = 0");
    $stmt->execute([$user_info->email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($u) {
        // Usuario ya existe
        $_SESSION['user'] = [
            'id' => $u['id'],
            'name' => $u['name'],
            'role' => $u['role']
        ];
    } else {
        // Crear nuevo usuario como "patient"
        $stmt = $conn->prepare("INSERT INTO users (name, email, role, password) VALUES (?, ?, 'patient', '')");
        $stmt->execute([$user_info->name, $user_info->email]);
        $id = $conn->lastInsertId();

        $_SESSION['user'] = [
            'id' => $id,
            'name' => $user_info->name,
            'role' => 'patient'
        ];
    }

    // Redirigir al panel principal por el puerto 3000
    header("Location: http://localhost:3000/resources/views/layouts/index.php");
    exit;

} catch (Exception $e) {
    echo 'Error Google: ', htmlspecialchars($e->getMessage());
    exit;
}
