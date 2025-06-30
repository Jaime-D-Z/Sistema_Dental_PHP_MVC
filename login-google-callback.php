<?php
session_start();

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../config/google-config.php';

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);

        // Obtener datos del usuario
        $google_oauth = new Google_Service_Oauth2($client);
        $google_user = $google_oauth->userinfo->get();

        $email = $google_user->email;
        $name = $google_user->name;

        $conn = Database::connect();

        // Verifica si ya existe
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_deleted = 0");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Crear nuevo usuario con rol 'patient'
            $stmt = $conn->prepare("INSERT INTO users (name, email, role, is_active, is_deleted) VALUES (?, ?, 'patient', 1, 0)");
            $stmt->execute([$name, $email]);

            $user_id = $conn->lastInsertId();
            $user = [
                'id' => $user_id,
                'name' => $name,
                'role' => 'patient'
            ];
        } else {
            if (!$user['is_active']) {
                $_SESSION['errors'] = ['Usuario desactivado. Contacte al administrador.'];
                header("Location: login.php");
                exit;
            }
        }

        // Guardar en sesión
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        // Redirigir según el rol
        if ($user['role'] === 'doctor') {
            header("Location: /resources/views/layouts/medico_index.php");
        } else {
            header("Location: /resources/views/layouts/index.php");
        }
        exit;

    } catch (Exception $e) {
        $_SESSION['errors'] = ['Error al iniciar sesión con Google: ' . $e->getMessage()];
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['errors'] = ['Acceso denegado o cancelado'];
    header("Location: login.php");
    exit;
}
