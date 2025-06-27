<?php
try {
    // Iniciar sesión si no ha sido iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Control de caché (solo si no se han enviado encabezados antes)
    if (!headers_sent()) {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    // Validar sesión activa
    if (empty($_SESSION['user'])) {
        // Mensaje de error opcional para el login
        $_SESSION['errors'] = ['⚠️ Debe iniciar sesión para continuar.'];
        header("Location: /resources/views/auth/login.php");
        exit;
    }

} catch (Throwable $e) {
    // Mostrar error amigable solo en desarrollo
    if ($_SERVER['APP_ENV'] ?? 'local' === 'local') {
        echo "<h2>Error de autenticación</h2>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    } else {
        header("Location: /resources/views/auth/login.php");
    }
    exit;
}
?>
