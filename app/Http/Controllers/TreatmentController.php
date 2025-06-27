<?php
namespace App\Http\Controllers;

require_once __DIR__ . '/../../Models/Treatment.php';

use App\Models\Treatment;

class TreatmentController {
    public function index() {
        $search = $_GET['search'] ?? '';
        $tratamientos = Treatment::all($search);
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

