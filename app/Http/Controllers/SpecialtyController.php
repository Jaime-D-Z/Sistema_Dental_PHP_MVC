<?php
require_once __DIR__ . '/../../Models/Specialty.php';

class SpecialtyController {
    public function index() {
        $search = trim($_GET['search'] ?? '');

        // Solo buscar si tiene al menos 2 caracteres, igual que en citas
        if (strlen($search) >= 2) {
            $specialties = Specialty::all($search);
        } else if (isset($_GET['search'])) {
            $specialties = []; // escribió algo pero menos de 2 letras → mostrar vacío
        } else {
            $specialties = Specialty::all(); // sin búsqueda → mostrar todo
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $success = $_SESSION['success'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['success'], $_SESSION['errors']);

        return [
            'specialties' => $specialties,
            'search'      => $search,
            'success'     => $success,
            'errors'      => $errors
        ];
    }
}
