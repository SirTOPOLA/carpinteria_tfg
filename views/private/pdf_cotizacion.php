<?php
require_once '../../vendor/autoload.php'; // Ajusta ruta si Dompdf está en otro lugar

use Dompdf\Dompdf;

include('../../config/conexion.php');

// Obtener ID de solicitud
$solicitud_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$solicitud_id) {
    die("ID no válido");
}

// Obtener datos igual que en el template
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
    die("Solicitud no encontrada");
}

$stmt = $pdo->prepare("
    SELECT m.nombre, dsm.cantidad, dsm.precio_unitario, dsm.subtotal, m.unidad_medida
    FROM detalles_solicitud_material dsm
    JOIN materiales m ON dsm.material_id = m.id
    WHERE dsm.solicitud_id = ?
");
$stmt->execute([$solicitud_id]);
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

$config = $pdo->query("SELECT * FROM configuracion LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Construir HTML para PDF (puedes mejorar estilos aquí)
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proforma #<?= $solicitud['id'] ?></title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h4 { margin-bottom: 0; }
        .logo { max-height: 60px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background: #eee; }
        .text-end { text-align: right; }
        .small { font-size: 10px; }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td>
                <h4><?= htmlspecialchars($config['nombre_empresa']) ?></h4>
                <p class="small"><?= htmlspecialchars($config['direccion']) ?></p>
                <p class="small">Tel: <?= htmlspecialchars($config['telefono']) ?></p>
                <p class="small">Email: <?= htmlspecialchars($config['correo']) ?></p>
            </td>
            <td style="text-align:right;">
                <?php if ($config['logo']) : ?>
                    <img src="../../uploads/<?= htmlspecialchars($config['logo']) ?>" class="logo" alt="Logo">
                <?php endif; ?>
                <h4>Proforma N° <?= $solicitud['id'] ?></h4>
                <p class="small">Fecha: <?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?></p>
            </td>
        </tr>
    </table>

    <hr>

    <h5>Cliente:</h5>
    <p><strong><?= htmlspecialchars($solicitud['cliente']) ?></strong></p>
    <p>Dirección: <?= htmlspecialchars($solicitud['direccion']) ?></p>
    <p>Teléfono: <?= htmlspecialchars($solicitud['telefono']) ?></p>

    <h5>Proyecto:</h5>
    <p><?= htmlspecialchars($solicitud['proyecto']) ?></p>

    <h5>Materiales Solicitados</h5>
    <table>
        <thead>
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
            foreach ($materiales as $mat) :
                $total += $mat['subtotal'];
            ?>
            <tr>
                <td><?= htmlspecialchars($mat['nombre']) ?></td>
                <td><?= htmlspecialchars($mat['unidad_medida']) ?></td>
                <td><?= $mat['cantidad'] ?></td>
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
            <?php if ($config['iva']) : 
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

    <p style="margin-top:30px; font-style: italic; font-size: 10px;">
        * Esta proforma no representa un compromiso definitivo. Está sujeta a cambios por disponibilidad de materiales o modificaciones del proyecto.
    </p>
</body>
</html>
<?php
$html = ob_get_clean();

// Crear instancia Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar PDF al navegador para descarga directa
$filename = "Proforma_{$solicitud['id']}.pdf";
$dompdf->stream($filename, ["Attachment" => true]);
exit;
