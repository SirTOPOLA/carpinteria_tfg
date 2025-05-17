<?php 

$rol_actual = $_SESSION['usuario']['rol'] ?? 'cliente'; // Rol del usuario autenticado
$modulos = $permisos[$rol_actual] ?? [];

function contar($tabla, $pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM $tabla");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

$resumen = [];

if (in_array('clientes', $modulos)) {
    $resumen['clientes'] = contar('clientes', $pdo);
}
if (in_array('ventas', $modulos)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total, SUM(total) AS monto_total FROM ventas");
    $stmt->execute();
    $resumen['ventas'] = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (in_array('productos', $modulos)) {
    $resumen['productos'] = contar('productos', $pdo);
}
if (in_array('servicios', $modulos)) {
    $resumen['servicios'] = contar('servicios', $pdo);
}
if (in_array('materiales', $modulos)) {
    $resumen['materiales'] = contar('materiales', $pdo);
}
if (in_array('usuarios', $modulos)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM usuarios WHERE activo = 1");
    $stmt->execute();
    $resumen['usuarios_activos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
?>



<div id="content" class="container-fluid px-4">

    <?php if (isset($resumen['usuarios_activos'])): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted">Usuarios activos</h6>
                    <h4 class="fw-bold"><?= $resumen['usuarios_activos'] ?></h4>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($resumen['ventas'])): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted">Ventas realizadas</h6>
                    <h4 class="fw-bold"><?= $resumen['ventas']['total'] ?> ventas</h4>
                    <div class="text-muted small">Total: $<?= number_format($resumen['ventas']['monto_total'], 2) ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($resumen['clientes'])): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted">Clientes registrados</h6>
                    <h4 class="fw-bold"><?= $resumen['clientes'] ?></h4>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($resumen['productos'])): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted">Productos</h6>
                    <h4 class="fw-bold"><?= $resumen['productos'] ?></h4>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($resumen['servicios'])): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-secondary border-4">
                <div class="card-body">
                    <h6 class="text-muted">Servicios activos</h6>
                    <h4 class="fw-bold"><?= $resumen['servicios'] ?></h4>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($resumen['materiales'])): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-danger border-4">
                <div class="card-body">
                    <h6 class="text-muted">Materiales en stock</h6>
                    <h4 class="fw-bold"><?= $resumen['materiales'] ?></h4>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>