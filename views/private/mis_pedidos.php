<?php


$cliente_id = $_SESSION['usuario']['id'] ?? null;
if (!$cliente_id) {
    die('Acceso denegado');
}

// Obtener los pedidos del cliente
$sql = "SELECT p.*, c.nombre AS cliente, s.nombre AS servicio_nombre, e.nombre AS estado_nombre
        FROM pedidos p
        INNER JOIN clientes c ON p.cliente_id = c.id 
        LEFT JOIN servicios s ON p.servicio_id = s.id
        LEFT JOIN estados e ON p.estado_id = e.id
        WHERE p.cliente_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$cliente_id]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener producciones y avances
$producciones_data = [];
foreach ($pedidos as $pedido) {
    $sql = "SELECT pr.id AS produccion_id, pr.estado_id AS produccion_estado_id, pr.fecha_inicio, pr.fecha_fin,
                   e.nombre AS estado_nombre
            FROM producciones pr
            LEFT JOIN estados e ON pr.estado_id = e.id
            WHERE pr.solicitud_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pedido['id']]);
    $produccion = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener avances
    $avances = [];
    if ($produccion) {
        $sql = "SELECT descripcion, imagen, fecha 
                FROM avances_produccion 
                WHERE produccion_id = ? 
                ORDER BY fecha DESC LIMIT 3";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$produccion['produccion_id']]);
        $avances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcular progreso (tareas completadas / total)
        $sql = "SELECT COUNT(*) AS total, 
                       SUM(CASE WHEN estado_id = 3 THEN 1 ELSE 0 END) AS completadas
                FROM tareas_produccion
                WHERE produccion_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$produccion['produccion_id']]);
        $progreso = $stmt->fetch(PDO::FETCH_ASSOC);
        $porcentaje = $progreso['total'] > 0 ? round(($progreso['completadas'] / $progreso['total']) * 100) : 0;
    } else {
        $porcentaje = 0;
        $avances = [];
    }

    $producciones_data[] = [
        'pedido' => $pedido,
        'produccion' => $produccion,
        'avances' => $avances,
        'progreso' => $porcentaje
    ];
}
?>

<div id="content" class="container-fluid py-4">


    <div class="container">
        <div class="mb-4">
            <h2 class="fw-bold"><i class="bi bi-person-circle me-2"></i>Bienvenido,
                <?= htmlspecialchars($_SESSION['usuario']['usuario']) ?></h2>
        </div>

        <?php foreach ($producciones_data as $data):
            $pedido = $data['pedido'];
            $produccion = $data['produccion'];
            $avances = $data['avances'];
            $progreso = $data['progreso'];
            ?>
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-box-seam me-2"></i><?= htmlspecialchars($pedido['proyecto']) ?>
                            <span class="badge bg-primary"><?= htmlspecialchars($pedido['estado_nombre']) ?></span>
                        </h4>
                        <small class="text-muted">
                            <i class="bi bi-calendar-check"></i> Entrega: <?= $pedido['fecha_entrega'] ?>
                        </small>
                    </div>

                    <p class="mb-2"><i class="bi bi-wrench-adjustable me-2"></i><strong>Servicio:</strong>
                        <?= htmlspecialchars($pedido['servicio_nombre']) ?></p>
                    <p class="mb-4"><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($pedido['descripcion'])) ?></p>

                    <h6><i class="bi bi-bar-chart-line me-2"></i>Progreso de Producción</h6>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progreso ?>%;"
                            aria-valuenow="<?= $progreso ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= $progreso ?>%
                        </div>
                    </div>

                    <h6 class="mb-3"><i class="bi bi-image me-2"></i>Últimos Avances</h6>
                    <div class="row g-3">
                        <?php foreach ($avances as $avance): ?>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <img src="uploads/produccion/<?= htmlspecialchars($avance['imagen']) ?>"
                                        class="card-img-top" alt="Avance" style="max-height:200px; object-fit:cover;">
                                    <div class="card-body p-2">
                                        <p class="card-text fw-bold"><?= htmlspecialchars($avance['descripcion']) ?></p>
                                        <p class="text-muted small mb-0"><i
                                                class="bi bi-clock-history me-1"></i><?= date('d/m/Y H:i', strtotime($avance['fecha'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Contacto -->
        <div class="card bg-light mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="card-title"><i class="bi bi-envelope-at me-2"></i>¿Necesitas ayuda?</h4>
                <p class="card-text mb-4">Contacta con nuestro equipo para cualquier duda o problema relacionado con tu
                    pedido.</p>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required
                            placeholder="Tu nombre">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="email" name="email" required
                            placeholder="Tu correo">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary" onclick="event.preventDefault()">
                            <i class="bi bi-send me-1"></i>Enviar mensaje
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>