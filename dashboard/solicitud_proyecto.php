<?php
require_once("../includes/conexion.php");

/* if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: proyectos.php");
    exit;
} */

//$id = (int) $_GET['id'];

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0)
    die("ID inválido.");


$sql = "SELECT *
        FROM proyectos 
        WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT sp.*,
        c.nombre AS cliente
        FROM solicitudes_proyecto sp
        INNER JOIN clientes c ON sp.cliente_id = c.id 
         ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$solicitudes_proyecto = $stmt->fetch(PDO::FETCH_ASSOC);


 
/* if (!$proyecto) {
    header("Location: proyectos.php");
    exit;
} */
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-4" id="mainContent">
    <div class="container">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center rounded-top-4">
                <h4 class="mb-0"><i class="bi bi-folder2-open"></i> Proyecto: <?= htmlspecialchars($proyecto['nombre']) ?></h4>
                <div>
                    <a href="editar_proyecto.php?id=<?= $proyecto['id'] ?>" class="btn btn-warning btn-sm me-1">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="eliminar_proyecto.php?id=<?= $proyecto['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este proyecto?')">
                        <i class="bi bi-trash"></i> Eliminar
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Nombre del proyecto</h6>
                        <p class="fw-bold"><?= htmlspecialchars($proyecto['nombre']) ?></p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted">Cliente</h6>
                        <p class="fw-bold"><?= htmlspecialchars($solicitudes_proyecto['cliente']) ?></p>
                    </div>

                    <div class="col-md-12">
                        <h6 class="text-muted">Descripción</h6>
                        <div class="bg-light p-3 rounded border">
                            <?= nl2br(htmlspecialchars($proyecto['descripcion'])) ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-muted">Estado</h6>
                        <span class="badge bg-<?= $proyecto['estado'] === 'completado' ? 'success' : ($proyecto['estado'] === 'en_proceso' ? 'warning text-dark' : 'secondary') ?>">
                            <?= ucfirst($proyecto['estado']) ?>
                        </span>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-muted">Fecha de inicio</h6>
                        <p><?= $proyecto['fecha_inicio'] ? date('d/m/Y', strtotime($proyecto['fecha_inicio'])) : 'No especificada' ?></p>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-muted">Fecha estimada de finalización</h6>
                        <p><?= $proyecto['fecha_entrega'] ? date('d/m/Y', strtotime($proyecto['fecha_entrega'])) : 'No especificada' ?></p>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-muted">Fecha de creación</h6>
                        <p><?= date('d/m/Y H:i', strtotime($proyecto['creado_en'])) ?></p>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-muted">Costo estimado</h6>
                        <p class="fw-bold text-success">
                            <?= number_format($solicitudes_proyecto['estimacion_total'], 2, ',', '.') ?> €
                        </p>
                    </div>

                    <?php if (!empty($proyecto['responsable'])): ?>
                    <div class="col-md-4">
                        <h6 class="text-muted">Responsable</h6>
                        <p><?= htmlspecialchars($proyecto['responsable']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-footer bg-white border-0 d-flex justify-content-between">
                <a href="proyectos.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Volver a la lista
                </a>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
