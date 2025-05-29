<?php
include('../../config/conexion.php');

// Obtener ID de solicitud por GET
$solicitud_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Cargar información general
$stmt = $pdo->prepare("
    SELECT sp.id, sp.fecha_solicitud, c.nombre AS cliente, c.direccion, c.telefono, p.nombre AS proyecto
    FROM solicitudes_proyecto sp
    JOIN clientes c ON sp.cliente_id = c.id
    JOIN proyectos p ON sp.proyecto_id = p.id
    WHERE sp.id = ?
");
$stmt->execute([$solicitud_id]);
$solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$solicitud) {
    echo "Solicitud no encontrada";
    exit;
}

// Cargar materiales solicitados
$stmt = $pdo->prepare("
    SELECT m.nombre, dsm.cantidad, dsm.precio_unitario, dsm.subtotal, m.unidad_medida
    FROM detalles_solicitud_material dsm
    JOIN materiales m ON dsm.material_id = m.id
    WHERE dsm.solicitud_id = ?
");
$stmt->execute([$solicitud_id]);
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cargar configuración de empresa
$config = $pdo->query("SELECT * FROM configuracion LIMIT 1")->fetch(PDO::FETCH_ASSOC);

$baseUrl = "https://tu-dominio.com"; // Cambia aquí al dominio donde está el sistema
$pdfUrl = "$baseUrl/controladores/pdf_proforma.php?id=$solicitud_id";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Proforma #<?= $solicitud['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 14px;
        }

        .logo {
            max-height: 80px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="p-4">
    <div class="container border p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h3>Proforma N° <?= $solicitud['id'] ?></h3>

        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="fw-bold"><?= htmlspecialchars($config['nombre_empresa']) ?></h4>
                <p class="mb-1"><?= htmlspecialchars($config['direccion']) ?></p>
                <p class="mb-1">Tel: <?= htmlspecialchars($config['telefono']) ?></p>
                <p class="mb-1">Email: <?= htmlspecialchars($config['correo']) ?></p>
            </div>
            <div class="col-md-6 text-end">
                <?php if ($config['logo']): ?>
                    <img src="../uploads/<?= htmlspecialchars($config['logo']) ?>" class="logo" alt="Logo">
                <?php endif; ?>
                <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Cliente:</h6>
                <p class="mb-1"><strong><?= htmlspecialchars($solicitud['cliente']) ?></strong></p>
                <p class="mb-1">Dirección: <?= htmlspecialchars($solicitud['direccion']) ?></p>
                <p class="mb-1">Teléfono: <?= htmlspecialchars($solicitud['telefono']) ?></p>
            </div>
            <div class="col-md-6">
                <h6>Proyecto:</h6>
                <p class="mb-1"><?= htmlspecialchars($solicitud['proyecto']) ?></p>
            </div>
        </div>

        <h6>Materiales Solicitados</h6>
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th>Material</th>
                    <th>Unidad</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario (<?= htmlspecialchars($config['moneda']) ?>)</th>
                    <th>Subtotal (<?= htmlspecialchars($config['moneda']) ?>)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($materiales as $mat):
                    $total += $mat['subtotal'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($mat['nombre']) ?></td>
                        <td><?= htmlspecialchars($mat['unidad_medida']) ?></td>
                        <td><?= htmlspecialchars($mat['cantidad']) ?></td>
                        <td><?= number_format($mat['precio_unitario'], 2) ?></td>
                        <td><?= number_format($mat['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total</th>
                    <th><?= number_format($total, 2) ?></th>
                </tr>
                <?php if ($config['iva']):
                    $iva = $total * ($config['iva'] / 100);
                    ?>
                    <tr>
                        <th colspan="4" class="text-end">IVA (<?= $config['iva'] ?>%)</th>
                        <th><?= number_format($iva, 2) ?></th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Total con IVA</th>
                        <th><?= number_format($total + $iva, 2) ?></th>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>

        <div class="mt-5">
            <p class="fst-italic">* Esta proforma no representa un compromiso definitivo. Está sujeta a cambios por
                disponibilidad de materiales o modificaciones del proyecto.</p>
        </div>

        <!-- Botón WhatsApp para enviar el PDF -->
        <div class="row no-print mt-3">

            <div class="col-md-6 ">
                <a href="https://wa.me/?text=Hola,%20aquí%20tienes%20tu%20proforma%20<?= urlencode($pdfUrl) ?>"
                    target="_blank" class="btn btn-success">
                    📱 Enviar por WhatsApp
                </a>
            </div>
            <div class="col-md-6">
                <button onclick="window.print()" class="btn btn-outline-primary me-2">
                    🖨️ Imprimir
                </button>
                <a href="<?= $pdfUrl ?>" target="_blank" class="btn btn-success">
                    📥 Descargar PDF
                </a>
            </div>
        </div>
    </div>
</body>

</html>