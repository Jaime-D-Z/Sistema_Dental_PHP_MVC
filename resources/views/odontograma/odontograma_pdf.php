<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

$patient = null;
$appointment = null;
$dientes = [];

if (!empty($_GET['dni'])) {
  $stmt = $conn->prepare("SELECT * FROM patients WHERE dni = ?");
  $stmt->execute([$_GET['dni']]);
  $patient = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($patient) {
    $stmt2 = $conn->prepare("SELECT * FROM appointments WHERE patient_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt2->execute([$patient['id']]);
    $appointment = $stmt2->fetch(PDO::FETCH_ASSOC);
  }
}

$appointment_id = isset($appointment['id']) ? intval($appointment['id']) : 0;

// Obtener dientes pintados
if ($appointment_id) {
  $stmt3 = $conn->prepare("SELECT * FROM teeth WHERE appointment_id = ?");
  $stmt3->execute([$appointment_id]);
  $dientes_raw = $stmt3->fetchAll(PDO::FETCH_ASSOC);

  // Agrupar por pieza y zona
  foreach ($dientes_raw as $d) {
    $pieza = $d['piece'];
    $zona = $d['zone'];
    $dientes[$pieza][$zona] = [
      'color' => $d['background_color'],
      'symbol' => $d['symbol'],
      'symbol_color' => $d['symbol_color']
    ];
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Odontograma - Vista para Imprimir</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
  @page {
    size: A4 portrait;
    margin: 10mm;
  }

  @media print {
    nav, form, .btn, .topbar,
    .navbar, .modal, .modal-backdrop,
    .no-print, .action-zone-general, .center-tools {
      display: none !important;
    }

    body {
      background: white !important;
      color: black !important;
    }

    .container, .odontograma-section, .row, .card {
      page-break-inside: avoid;
      break-inside: avoid;
    }

    .tooth-wrapper .diente {
      box-shadow: none !important;
      border: 1px solid black !important;
    }

    .zona {
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }

    .zona.centro .symbol {
      color: black !important;
    }

    .diente::before,
    .diente::after {
      display: block !important;
      background-color: black !important;
    }
  }

  .tooth-row {
    display: flex;
    justify-content: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
  }

  .tooth-wrapper {
    margin: 3px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .tooth-number {
    margin-bottom: 3px;
    font-weight: bold;
  }

  .diente {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid #000;
    background-color: white;
    overflow: hidden;
  }

  .diente::before,
  .diente::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 2px;
    background-color: black;
    top: 50%;
    left: 0;
    transform-origin: center;
    z-index: 1;
  }

  .diente::before {
    transform: rotate(45deg);
  }

  .diente::after {
    transform: rotate(-45deg);
  }

  .zona {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 2;
    background-color: transparent;
    transition: background 0.3s;
  }

  .zona.superior {
    clip-path: polygon(50% 50%, 100% 0%, 0% 0%);
  }

  .zona.inferior {
    clip-path: polygon(50% 50%, 0% 100%, 100% 100%);
  }

  .zona.izquierda {
    clip-path: polygon(0% 0%, 0% 100%, 50% 50%);
  }

  .zona.derecha {
    clip-path: polygon(100% 0%, 100% 100%, 50% 50%);
  }

  .zona.centro {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: 2px solid black;
    background-color: white;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    position: absolute;
    z-index: 3;
  }

  .zona.centro .symbol {
    font-size: 16px;
    font-weight: bold;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>

</head>
<body>
<div class="container mt-4">
  <?php if ($patient): ?>
    <div class="text-center mb-4">
      <h4 class="fw-bold">DNI: <?= htmlspecialchars($patient['dni']) ?></h4>
    </div>
    <div class="card p-3 mb-4">
      <table class="table table-bordered mb-0">
        <tbody>
          <tr><th>DNI</th><td><?= $patient['dni'] ?></td><th>Referido por</th><td><?= $patient['referred_by'] ?></td></tr>
          <tr><th>Nombres</th><td><?= $patient['first_name'] . ' ' . $patient['last_name'] ?></td><th>Bajo tratamiento</th><td><?= $patient['under_treatment'] ? 'Sí' : 'No' ?></td></tr>
          <tr><th>Correo</th><td><?= $patient['email'] ?></td><th>Hemorragia</th><td><?= $patient['bleeding'] ? 'Sí' : 'No' ?></td></tr>
          <tr><th>Teléfono</th><td><?= $patient['phone'] ?></td><th>Alergia</th><td><?= $patient['allergy'] ? 'Sí' : 'No' ?></td></tr>
          <tr><th>Motivo</th><td><?= $patient['reason'] ?></td><th>Hipertenso</th><td><?= $patient['hypertensive'] ? 'Sí' : 'No' ?></td></tr>
          <tr><th>Diagnóstico</th><td><?= $patient['diagnosis'] ?></td><th>Diabético</th><td><?= $patient['diabetic'] ? 'Sí' : 'No' ?></td></tr>
          <tr><th>Observaciones</th><td><?= $patient['observations'] ?></td><th>Embarazada</th><td><?= $patient['pregnant'] ? 'Sí' : 'No' ?></td></tr>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

<?php
$filas = [
  ['label' => '🦷 Fila 1 - Dientes superiores', 'teeth' => [18, 17, 16, 15, 14, 13, 12, 11, 'gap', 21, 22, 23, 24, 25, 26, 27, 28]],
  ['label' => '🦷 Fila 2 - Zona media superior', 'teeth' => [55, 54, 53, 52, 51, 'gap', 61, 62, 63, 64, 65]],
  ['label' => '🦷 Fila 3 - Cara lingual/palatina', 'teeth' => [85, 84, 83, 82, 81, 'gap', 71, 72, 73, 74, 75]],
  ['label' => '🦷 Fila 4 - Dientes inferiores', 'teeth' => [48, 47, 46, 45, 44, 43, 42, 41, 'gap', 31, 32, 33, 34, 35, 36, 37, 38]],
];

foreach ($filas as $fila):
?>
  <div class="odontograma-section">
    <div class="section-label fw-bold mb-1"><?= $fila['label'] ?></div>
    <div class="tooth-row">
      <?php foreach ($fila['teeth'] as $tooth): ?>
        <?php if ($tooth === 'gap'): ?>
          <div style="width:30px;"></div>
        <?php else: ?>
          <?php $zones = $dientes[$tooth] ?? []; ?>
          <div class="tooth-wrapper">
            <div class="tooth-number"><?= $tooth ?></div>
            <div class="diente">
              <?php foreach (['superior', 'inferior', 'izquierda', 'derecha'] as $zona): ?>
                <?php
                $color = $zones[$zona]['color'] ?? '';
                ?>
                <div class="zona <?= $zona ?>" style="background-color: <?= $color ?>"></div>
              <?php endforeach; ?>
              <?php
              $centro = $zones['centro'] ?? null;
              ?>
              <div class="zona centro" style="background-color: <?= $centro['color'] ?? '' ?>;">
                <?php if ($centro && $centro['symbol']): ?>
                  <span class="symbol" style="color: <?= $centro['symbol_color'] ?>; font-weight:bold;">
                    <?= htmlspecialchars($centro['symbol']) ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
<?php endforeach; ?>
<h4 class="text-primary fw-bold mb-3">🩺 Indicadores de Salud Bucal</h4>

<div class="row">
  <div class="col-md-9">
    <table class="table table-bordered text-center align-middle bg-white">
      <thead class="table-light">
        <tr>
          <th rowspan="2">Piezas Dentales</th>
          <th colspan="3">Higiene Oral Simplificada</th>
          <th rowspan="2">Enfermedad Periodontal</th>
          <th rowspan="2">Mal Oclusión</th>
          <th rowspan="2">Fluorosis</th>
        </tr>
        <tr>
          <th>Placa<br>0-1-2-3</th>
          <th>Cálculo<br>0-1-2-3</th>
          <th>Gingivitis<br>0-1</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $piezas = [
          [16, 17, 55],
          [11, 21, 51],
          [26, 27, 65],
          [36, 37, 75],
          [31, 41, 71],
          [46, 47, 85],
        ];
        foreach ($piezas as $grupo): ?>
          <tr>
            <td><?= implode(' - ', $grupo) ?></td>
            <td></td><td></td><td></td>
            <td></td><td></td><td></td>
          </tr>
        <?php endforeach; ?>
        <tr class="table-light fw-bold">
          <td>Totales</td>
          <td></td><td></td><td></td>
          <td></td><td></td><td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="col-md-3">
    <table class="table table-bordered text-center align-middle bg-white">
      <thead class="table-light">
        <tr><th colspan="4">ÍNDICES CPO - ceo</th></tr>
      </thead>
      <tbody>
        <tr><th></th><th>C</th><th>P</th><th>O</th></tr>
        <tr><td>D</td><td></td><td></td><td></td></tr>
        <tr class="table-light"><td>Total</td><td></td><td></td><td></td></tr>
        <tr><td>d</td><td>c</td><td>e</td><td>o</td></tr>
        <tr class="table-light"><td>Total</td><td></td><td></td><td></td></tr>
      </tbody>
    </table>
  </div>
</div>

</div>
</body>
</html>
