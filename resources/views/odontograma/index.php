<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');
require_once __DIR__ . '/../../../config/auth.php';

$conn = Database::connect();

$patient = null;
$appointment = null;

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
$patient_id = isset($patient['id']) ? intval($patient['id']) : 0;
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Odontograma Profesional - Clínica Dental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
 

@page { size: A4 portrait; margin: 10mm; }
@media print {
  .modal, .modal-backdrop, form, #btnPrint {
    display: none !important;
  }

  @media print {
  .zona {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  .zona.centro .symbol {
    color: black !important;
  }
}

@media print {
  .diente::before,
  .diente::after {
    display: block !important;
    background-color: black !important;
  }
}

@media print {
  .diente {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
}

@media print {
  .no-print {
    display: none !important;
  }
}


@media print {
  nav, form, .btn, .topbar,
  .navbar, .modal, .modal-backdrop {
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

  .center-tools, .action-zone-general {
    display: none !important;
  }
}


  body, .container {
    width: auto !important;
    background: white !important;
    margin: 0;
    padding: 0;
  }
  .odontograma-section, .row {
    page-break-inside: avoid;
  }
  svg.diente-svg {
    page-break-inside: avoid;
  }
}


      body {
        background: white;
      }
    
    body {
      background-color: #eef3f9;
    }
    .topbar {
      background: #fff;
      border-bottom: 1px solid #dee2e6;
    }
    .topbar a {
      color: #0d6efd;
      font-weight: 500;
      margin-right: 20px;
      text-decoration: none;
    }
    .odontograma-section {
      margin-bottom: 20px;
    }
    .tooth-row {
      display: flex;
      justify-content: center;
      margin-bottom: 10px;
      flex-wrap: wrap;
    }
    .tooth-wrapper {
      margin: 3px;
      position: relative;
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
      cursor: pointer;
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
    .diente::before { transform: rotate(45deg); }
    .diente::after { transform: rotate(-45deg); }
    .zona {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 2;
      background-color: transparent;
      transition: background 0.3s;
    }
    .zona.superior { clip-path: polygon(50% 50%, 100% 0%, 0% 0%); }
    .zona.inferior { clip-path: polygon(50% 50%, 0% 100%, 100% 100%); }
    .zona.izquierda { clip-path: polygon(0% 0%, 0% 100%, 50% 50%); }
    .zona.derecha { clip-path: polygon(100% 0%, 100% 100%, 50% 50%); }
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
      z-index: 2;
    }
    .zona.centro .symbol {
      font-size: 16px;
      font-weight: bold;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    .zona:hover {
      background-color: rgba(0, 123, 255, 0.2);
    }
    .center-tools {
      display: flex;
      justify-content: center;
      margin-top: 6px;
      gap: 5px;
      flex-wrap: wrap;
    }
   .center-tools button,
.action-zone-general button {
  margin: 4px;
  min-width: 70px;
}

  </style>
</head>
<body>
  <!-- Menú superior -->
<nav class="navbar navbar-expand-lg topbar px-4 py-2">
  <a class="navbar-brand fw-bold text-primary" href="/resources/views/layouts/index.php">🦷 ODONTOLOGÍA TC</a>
  <div class="collapse navbar-collapse">
    <div class="navbar-nav">
      <a class="nav-link" href="/resources/views/layouts/index.php">Inicio</a>
      <a class="nav-link" href="#">Mantenimiento</a>
      <a class="nav-link" href="/resources/views/citas/index.php">Citas</a>
      <a class="nav-link" href="/resources/views/historial/index.php">Historial Citas</a>
      <a class="nav-link" href="/resources/views/calendario/index.php">Calendario</a>
    </div>
  </div>
  <div class="ms-auto">
    <span class="text-success me-3">Admin Tarea Completa</span>
        <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</nav>

  <div class="container mt-4">
   <form method="GET" action="" class="d-flex mb-3 no-print" style="gap:10px;">

      <input type="text" name="dni" placeholder="Buscar DNI..." class="form-control" required>
      <button class="btn btn-primary">🔍</button>
    </form>
  <?php if ($patient): ?>
 <a class="btn btn-success" href="odontograma_pdf.php?dni=<?= $patient['dni'] ?>" target="_blank">
  🖨️ Imprimir / PDF
</a>

<?php endif; ?>

    </div>

   <?php if ($patient): ?>
  <div class="text-center mb-4">
    <h4 class="fw-bold">DNI: <?= htmlspecialchars($patient['dni']) ?></h4>
  </div>
  <div class="card p-3 mb-4">
    <table class="table table-bordered mb-0">
      <tbody>
        <tr>
          <th>DNI</th>
          <td><?= htmlspecialchars($patient['dni']) ?></td>
          <th>Referido por</th>
          <td><?= htmlspecialchars($patient['referred_by']) ?></td>
        </tr>
        <tr>
          <th>Nombres</th>
          <td><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
          <th>Bajo tratamiento</th>
          <td><?= $patient['under_treatment'] ? 'Sí' : 'No' ?></td>
        </tr>
        <tr>
          <th>Correo</th>
          <td><?= htmlspecialchars($patient['email']) ?></td>
          <th>Hemorragia</th>
          <td><?= $patient['bleeding'] ? 'Sí' : 'No' ?></td>
        </tr>
        <tr>
          <th>Teléfono</th>
          <td><?= htmlspecialchars($patient['phone']) ?></td>
          <th>Alergia</th>
          <td><?= $patient['allergy'] ? 'Sí' : 'No' ?></td>
        </tr>
        <tr>
          <th>Motivo</th>
          <td><?= htmlspecialchars($patient['reason']) ?></td>
          <th>Hipertenso</th>
          <td><?= $patient['hypertensive'] ? 'Sí' : 'No' ?></td>
        </tr>
        <tr>
          <th>Diagnóstico</th>
          <td><?= htmlspecialchars($patient['diagnosis']) ?></td>
          <th>Diabético</th>
          <td><?= $patient['diabetic'] ? 'Sí' : 'No' ?></td>
        </tr>
        <tr>
          <th>Observaciones</th>
          <td><?= htmlspecialchars($patient['observations']) ?></td>
          <th>Embarazada</th>
          <td><?= $patient['pregnant'] ? 'Sí' : 'No' ?></td>
        </tr>
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
              <div class="tooth-wrapper">
                <div class="tooth-number"><?= $tooth ?></div>
                <div class="diente">
                  <?php foreach (['superior', 'inferior', 'izquierda', 'derecha'] as $zona): ?>
                    <div class="zona <?= $zona ?>" data-tooth="<?= $tooth ?>" data-zone="<?= $zona ?>"></div>
                  <?php endforeach; ?>
                  <div class="zona centro" data-tooth="<?= $tooth ?>" data-zone="centro">
                    <span class="symbol"></span>
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

  <<!-- Modal para seleccionar tratamiento -->
<div class="modal fade" id="toothModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formTooth">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="modalTitle" class="modal-title">Diente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="toothNumber">
          <input type="hidden" id="toothZone">

          <div class="mb-3">
            <label>Tratamiento</label>
            <select id="treatmentSelect" class="form-select">
              <?php foreach ($conn->query("SELECT id, name, price FROM treatments") as $t): ?>
                <option value="<?= $t['id'] ?>">
                  <?= htmlspecialchars($t['name']) ?> - S/<?= number_format($t['price'], 2) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label>Observación</label>
            <textarea id="observation" class="form-control" rows="2"></textarea>
          </div>

          <!-- Acciones generales -->
          <div class="mb-3 action-zone-general text-center">
            <label class="d-block mb-2">Acción</label>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
              <button type="button" class="btn btn-sm btn-danger actionBtn" data-action="X">X</button>
              <button type="button" class="btn btn-sm btn-warning actionBtn" data-action="C">Curación</button>
              <button type="button" class="btn btn-sm btn-primary actionBtn" data-action="E">Extracción</button>
              <button type="button" class="btn btn-sm btn-secondary actionBtn" data-action="V">Vacío</button>
            </div>
          </div>

          <!-- Acciones para zona centro -->
          <!-- Centro (solo visible si zona == centro) -->
<div class="mb-3 center-tools d-none">
  <!-- Primera fila: acciones -->
  <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
    <button type="button" class="btn btn-sm btn-danger centerActionBtn" data-symbol="X" data-color="red">X</button>
    <button type="button" class="btn btn-sm btn-warning centerActionBtn" data-symbol="C" data-color="yellow">Curación</button>
    <button type="button" class="btn btn-sm btn-primary centerActionBtn" data-symbol="E" data-color="blue">Extracción</button>
    <button type="button" class="btn btn-sm btn-secondary centerActionBtn" data-symbol="V" data-color="gray">Vacío</button>
  </div>

  <!-- Segunda fila: símbolos en rojo -->
  <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
    <?php foreach (['X','^','O','S','i'] as $sym): ?>
      <button type="button" class="btn btn-sm btn-outline-danger centerBtn" data-symbol="<?= $sym ?>" data-color="red"><?= $sym ?></button>
    <?php endforeach; ?>
  </div>

  <!-- Tercera fila: símbolos en azul -->
  <div class="d-flex justify-content-center gap-2 flex-wrap">
    <?php foreach (['X','^','O','S','i'] as $sym): ?>
      <button type="button" class="btn btn-sm btn-outline-primary centerBtn" data-symbol="<?= $sym ?>" data-color="blue"><?= $sym ?></button>
    <?php endforeach; ?>
  </div>
</div>


        <div class="modal-footer">
          <button type="button" id="saveTooth" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = new bootstrap.Modal(document.getElementById('toothModal'));
  let selZone = null;
  let selActionFondo = null, selColorFondo = null;
  let selSimbolo = null, selColorSimbolo = null;

  // Corregido: ahora estas variables vienen de PHP correctamente
  const appointmentId = <?= intval($appointment_id) ?>;
  const patientId = <?= intval($patient_id) ?>;

  console.log('appointment_id:', appointmentId);
  console.log('paciente_id:', patientId);

  document.querySelectorAll('.zona').forEach(z => z.addEventListener('click', () => {
    selZone = z;
    const isCenter = z.dataset.zone === 'centro';

    document.querySelector('.center-tools').classList.toggle('d-none', !isCenter);
    document.querySelector('.action-zone-general').classList.toggle('d-none', isCenter);

    document.getElementById('toothNumber').value = z.dataset.tooth;
    document.getElementById('toothZone').value = z.dataset.zone;
    document.getElementById('modalTitle').textContent = `Diente ${z.dataset.tooth} - ${z.dataset.zone}`;
    document.getElementById('observation').value = '';

    selActionFondo = null;
    selColorFondo = null;
    selSimbolo = null;
    selColorSimbolo = null;

    document.querySelectorAll('.actionBtn, .centerBtn, .centerActionBtn').forEach(b => b.classList.remove('active'));
    modal.show();
  }));

  document.querySelectorAll('.actionBtn').forEach(b => {
    b.addEventListener('click', () => {
      selActionFondo = b.dataset.action || '';
      selColorFondo = getColorBySymbol(selActionFondo);
      document.querySelectorAll('.actionBtn').forEach(x => x.classList.remove('active'));
      b.classList.add('active');
    });
  });

  document.querySelectorAll('.centerActionBtn').forEach(b => {
    b.addEventListener('click', () => {
      selActionFondo = b.dataset.symbol || '';
      selColorFondo = b.dataset.color || '';
      document.querySelectorAll('.actionBtn, .centerActionBtn').forEach(x => x.classList.remove('active'));
      b.classList.add('active');
    });
  });

  document.querySelectorAll('.centerBtn').forEach(b => {
    b.addEventListener('click', () => {
      selSimbolo = b.dataset.symbol || '';
      selColorSimbolo = b.dataset.color || 'black';
      document.querySelectorAll('.centerBtn').forEach(x => x.classList.remove('active'));
      b.classList.add('active');
    });
  });

 document.getElementById('saveTooth').addEventListener('click', (e) => {
  e.preventDefault();

  if (!selZone || (!selActionFondo && !selSimbolo)) {
    return alert('Selecciona una acción o símbolo');
  }

  // Validación opcional
 

  const zona = document.getElementById('toothZone').value;
  const pieza = document.getElementById('toothNumber').value;

  const payload = {
    appointment_id: appointmentId,
    paciente_id: patientId,
    pieza: pieza,
    zona: zona,
    tratamiento_id: document.getElementById('treatmentSelect').value,
    observaciones: document.getElementById('observation').value,
   accion: selActionFondo || '', // acción como texto (ej. C, E, etc.)
color_fondo: zona === 'centro' ? (selColorFondo || '') : (getColorBySymbol(selActionFondo) || ''),
simbolo: zona === 'centro' ? (selSimbolo || '') : '',
color_simbolo: zona === 'centro' ? (selColorSimbolo || '') : ''

  };

  console.log('Payload:', payload);

  fetch('guardar_diente.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      pintarZona(pieza, zona, selActionFondo, payload.color_fondo, selSimbolo, selColorSimbolo);
      modal.hide();
    } else {
      alert(res.error || 'Hubo un error al guardar');
    }
  });
});


 function pintarZona(pieza, zona, accion, colorFondo = null, simbolo = null, colorSimbolo = null) {
  const elem = document.querySelector(`.zona[data-tooth="${pieza}"][data-zone="${zona}"]`);
  if (!elem) return;

  const span = elem.querySelector('.symbol');

  if (zona === 'centro') {
    if (colorFondo) elem.style.backgroundColor = colorFondo;
    if (span && simbolo) {
      span.textContent = simbolo;
      span.style.color = colorSimbolo || 'black';
    } else if (span) {
      span.textContent = '';
    }
  } else {
    elem.style.backgroundColor = colorFondo || getColorBySymbol(accion);
  }
}


  if (patientId && appointmentId) {
    fetch(`obtener_dientes.php?paciente_id=${patientId}&appointment_id=${appointmentId}`)
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          Object.entries(res.data).forEach(([k, val]) => {
            const [pieza, zona] = k.split('_');
            const accion = val.accion || '';
            const colorFondo = val.color_fondo || '';
            const simbolo = val.simbolo || '';
            const colorSimbolo = val.color_simbolo || '';
            pintarZona(pieza, zona, accion, colorFondo, simbolo, colorSimbolo);
          });
        }
      });
  }

  function getColorBySymbol(s) {
    const cmap = {
      'X': 'red', 'C': 'yellow', 'E': 'blue', 'V': 'gray',
      '^': 'orange', 'O': 'black', 'S': 'green', 'i': 'purple'
    };
    return cmap[s] || 'lightgray';
  }
});
</script>


</body>
</html>
