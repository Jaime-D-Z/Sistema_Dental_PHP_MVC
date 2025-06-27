<?php
require_once(__DIR__.'/../../../config/Database.php');
$conn = Database::connect();

$paciente_id    = intval($_GET['paciente_id'] ?? 0);
$appointment_id = intval($_GET['appointment_id'] ?? 0);

if (!$paciente_id || !$appointment_id) {
  echo json_encode(['success' => false, 'error' => 'ID de paciente o cita inválido']);
  exit;
}

$stmt = $conn->prepare("SELECT * FROM teeth WHERE patient_id = ? AND appointment_id = ?");
$stmt->execute([$paciente_id, $appointment_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];

foreach ($rows as $row) {
  $clave = $row['piece'] . '_' . $row['zone'];

  $data[$clave] = [
    'accion'         => $row['action'],
    'color_fondo'    => $row['background_color'],
    'simbolo'        => $row['symbol'],
    'color_simbolo'  => $row['symbol_color'],
    'observaciones'  => $row['observations'],
    'tratamiento_id' => $row['treatment_id']
  ];
}

echo json_encode(['success' => true, 'data' => $data]);
