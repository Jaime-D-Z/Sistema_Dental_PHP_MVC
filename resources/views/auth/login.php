<?php
session_start();

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../config/google-config.php';

// Evita que se almacene en caché esta página
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$old_email = $_SESSION['old_email'] ?? '';
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_email']);

// PROCESAR LOGIN SI ES POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $_SESSION['errors'] = ['Debe completar todos los campos'];
        $_SESSION['old_email'] = $email;
        header("Location: login.php");
        exit;
    }

    try {
        $conn = Database::connect();

        // Validación con estado activo y no eliminado
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_deleted = 0 AND is_active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

       if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        if ($user['role'] === 'doctor') {
            header("Location: /resources/views/layouts/medico_index.php");
        } else {
            header("Location: /resources/views/layouts/index.php");
        }
        exit;
    } else {
        $_SESSION['errors'] = ['❌ Contraseña incorrecta'];
    }
}
 else {
            $_SESSION['errors'] = ['❌ Usuario no encontrado o desactivado'];
        }

        $_SESSION['old_email'] = $email;
        header("Location: login.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['errors'] = ['Error en el servidor: ' . $e->getMessage()];
        header("Location: login.php");
        exit;
    }
}

// URL de login con Google
$login_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Clínica Dental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    background: url('https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExbjM2eG5uc3cxZGtqY2ljcTJiYXZjYmoxb3k5ajd4MTh0OTJncmh5diZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/3o6Zt0EiSgr1cWE6WY/giphy.gif') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
}


        .card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease-in-out;
        }

        .card-header {
            background: #0d6efd;
            color: #fff;
            padding: 1.5rem 1rem;
        }

        .card-header h4 {
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .btn-outline-danger {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 500;
            margin-top: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header text-center">
        <h4>🦷 Clínica Dental</h4>
        <small class="d-block">Bienvenido, por favor inicia sesión</small>
    </div>
    <div class="card-body">

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- FORMULARIO DE LOGIN -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old_email) ?>" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="d-grid mb-2">
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </div>
        </form>

        <!-- BOTÓN GOOGLE LOGIN -->
        <a href="<?= $login_url ?>" class="btn btn-outline-danger w-100">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" width="20">
            Iniciar sesión con Google
        </a>
    </div>
</div>
</body>
</html>
