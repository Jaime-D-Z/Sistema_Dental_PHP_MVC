<?php
session_start();
require_once __DIR__ . '/../config/Database.php';

class AuthController {

    // Mostrar formulario de login
    public function showLoginForm() {
        include 'views/auth/login.php'; // Asegúrate de tener este archivo
    }

    // Validar credenciales e iniciar sesión
    public function login($postData) {
        if (empty($postData['email']) || empty($postData['password'])) {
            $_SESSION['error'] = 'Correo y contraseña son obligatorios.';
            header('Location: login.php');
            exit;
        }

        $email = filter_var($postData['email'], FILTER_SANITIZE_EMAIL);
        $password = $postData['password'];

        // Conexión a la base de datos (ajusta las credenciales)
        $pdo = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');

        // Buscar usuario por email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Redirigir según el rol
            switch ($user['rol']) {
                case 'admin':
                    header('Location: dashboard.php');
                    exit;
                case 'medico':
                    header('Location: medico/dashboard.php');
                    exit;
                case 'paciente':
                    header('Location: paciente/dashboard.php');
                    exit;
                default:
                    session_destroy();
                    $_SESSION['error'] = 'Rol no autorizado.';
                    header('Location: login.php');
                    exit;
            }
        } else {
            $_SESSION['error'] = 'Las credenciales son incorrectas.';
            header('Location: login.php');
            exit;
        }
    }

    // Cerrar sesión
    public function logout() {
        session_destroy();
        header('Location: login.php');
        exit;
    }
}
