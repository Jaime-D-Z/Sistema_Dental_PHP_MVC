<?php
namespace App\Http\Controllers;

require_once __DIR__ . '/../../Models/Treatment.php';

use App\Models\Treatment;

class TreatmentController {
   public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $search = trim($_GET['search'] ?? '');
        $tratamientos = [];

        if ($search === '' || mb_strlen($search) >= 3) {
            $tratamientos = Treatment::all($search);
        }

        return ['tratamientos' => $tratamientos, 'search' => $search];
    }

    public function store($data) {
        return Treatment::store($data);
    }

    public function edit($id) {
        return Treatment::find($id);
    }

    public function update($id, $data) {
        return Treatment::update($id, $data);
    }

    public function destroy($id) {
        return Treatment::delete($id);
    }
}

