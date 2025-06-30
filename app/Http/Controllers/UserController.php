<?php
require_once __DIR__ . '/../../Models/User.php';

class UserController {
    public function index() {
        $search = trim($_GET['search'] ?? '');
        $users = [];

        if ($search === '' || mb_strlen($search) >= 3) {
            $users = User::all($search);
        }

        return [
            'users' => $users,
            'search' => $search
        ];
    }
}
