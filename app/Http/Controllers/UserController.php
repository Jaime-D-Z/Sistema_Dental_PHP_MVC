<?php
require_once __DIR__ . '/../../Models/User.php';

class UserController {
    public function index() {
        $search = $_GET['search'] ?? '';
        $search = trim($search);

        $users = User::all($search);

        // Retorna variables para la vista
        return [
            'users' => $users,
            'search' => $search
        ];
    }
}
