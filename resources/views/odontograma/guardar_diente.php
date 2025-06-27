<?php
require_once(__DIR__.'/../../../config/Database.php');
$conn = Database::connect();

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Extraer y validar datos
$paciente_id    = intval($data['paciente_id'] ?? 0);
$appointment_id = intval($data['appointment_id'] ?? 0);
$pieza          = trim($data['pieza'] ?? '');
$zona           = trim($data['zona'] ?? '');
$tratamiento_id = intval($data['tratamiento_id'] ?? 0);
$observaciones  = trim($data['observaciones'] ?? '');
$accion         = trim($data['accion'] ?? '');
$color_fondo    = trim($data['color_fondo'] ?? '');
$simbolo        = trim($data['simbolo'] ?? '');
$color_simbolo  = trim($data['color_simbolo'] ?? '');

// Validación mínima obligatoria
if (!$paciente_id || !$appointment_id || !$pieza || !$zona || !$accion) {
    echo json_encode(value: [
        'success' => false,
        'error' => 'Faltan campos obligatorios',
        'debug' => [
            'paciente_id' => $paciente_id,
            'appointment_id' => $appointment_id,
            'pieza' => $pieza,
            'zona' => $zona,
            'accion' => $accion
        ]
    ]);
    exit;
}

try {
    $sql = "INSERT INTO teeth (
                patient_id, appointment_id, piece, zone, treatment_id, action, observations,
                background_color, symbol, symbol_color, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                treatment_id = VALUES(treatment_id),
                action = VALUES(action),
                observations = VALUES(observations),
                background_color = VALUES(background_color),
                symbol = VALUES(symbol),
                symbol_color = VALUES(symbol_color),
                updated_at = NOW()";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $paciente_id,
        $appointment_id,
        $pieza,
        $zona,
        $tratamiento_id,
        $accion,
        $observaciones,
        $color_fondo,
        $simbolo,
        $color_simbolo
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error SQL: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
}
