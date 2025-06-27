<?php
require_once __DIR__ . '/../../../app/Http/Controllers/PaymentController.php';
require_once __DIR__ . '/../../../config/auth.php';

$controller = new PaymentController();
extract($controller->index()); // extrae $payments y $search
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos - Clínica Dental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .topbar { background-color: #ffffff; border-bottom: 1px solid #dee2e6; }
        .topbar a { color: #0d6efd; font-weight: 500; margin-right: 20px; text-decoration: none; }
        .topbar a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<!-- Menú superior -->
<nav class="navbar navbar-expand-lg topbar px-4 py-2">
     <a class="navbar-brand fw-bold text-primary" href="/resources/views/layouts/index.php">
        🦷 ODONTOLOGÍA TC
    </a>
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

<!-- Contenido -->
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
    <a href="/resources/views/layouts/index.php" class="btn btn-outline-secondary">← Volver al menú</a>
        </div>
        <form method="GET" action="pagos.php" class="d-flex" style="gap:10px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar por paciente..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-success"><i class="bi bi-search"></i></button>
            <a href="pagos.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>Tratamiento</th>
                    <th>Diagnóstico</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Precio</th>
                    <th>Pagado</th>
                    <th>Saldo</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($payments) > 0): ?>
                    <?php foreach ($payments as $i => $p): ?>
                        <?php
                            $price = floatval($p['cost'] ?? 0);
                            $paid = floatval($p['paid'] ?? 0);
                            $balance = $price - $paid;
                            $status = $p['status'] ?? 'pendiente';
                            $statusColor = in_array(strtolower($status), ['atendido', 'completado']) ? 'success' : 'warning';
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($p['patient_name']) ?></td>
                            <td><?= htmlspecialchars($p['treatment_name']) ?></td>
                            <td><?= htmlspecialchars($p['diagnosis']) ?></td>
                            <td><?= htmlspecialchars($p['date']) ?></td>
                            <td><?= htmlspecialchars($p['time']) ?></td>
                            <td>
                                <span class="badge bg-<?= $statusColor ?>">
                                    <?= ucfirst($status) ?>
                                </span>
                            </td>
                            <td><strong>S/. <?= number_format($price, 2) ?></strong></td>
                            <td class="fw-bold text-<?= $paid > 0 ? 'success' : 'danger' ?>">
                                S/. <?= number_format($paid, 2) ?>
                            </td>
                            <td class="fw-bold text-<?= $balance > 0 ? 'danger' : 'success' ?>">
                                S/. <?= number_format($balance, 2) ?>
                            </td>
                            <td>
                                <a href="ver_pago.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary" title="Ver Detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr><td colspan="11" class="text-center">No hay pagos registrados.</td></tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
