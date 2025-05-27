<?php
require '../config/conexion.php';
require '../vendor/autoload.php'; // Asegúrate de haber instalado dompdf vía Composer

use Dompdf\Dompdf;

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido");
}
$venta_id = intval($_GET['id']);

// Obtener configuración de empresa
$stmtConfig = $pdo->query("SELECT * FROM configuracion LIMIT 1");
$config = $stmtConfig->fetch(PDO::FETCH_ASSOC);
$logo = $config['logo'] ? '../ruta/logos/' . $config['logo'] : ''; // Ajusta la ruta real

// Obtener datos de la venta
$stmtVenta = $pdo->prepare("
    SELECT v.id, v.fecha, v.total, c.nombre AS cliente, u.nombre AS usuario
    FROM ventas v
    JOIN clientes c ON v.cliente_id = c.id
    JOIN usuarios u ON v.usuario_id = u.id
    WHERE v.id = :venta_id
");
$stmtVenta->execute([':venta_id' => $venta_id]);
$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    die("Venta no encontrada");
}

// Obtener detalles
$stmtDetalles = $pdo->prepare("
    SELECT 
        dv.cantidad,
        dv.precio_unitario,
        COALESCE(p.nombre, s.nombre) AS nombre,
        dv.tipo
    FROM detalles_venta dv
    LEFT JOIN productos p ON dv.producto_id = p.id
    LEFT JOIN servicios s ON dv.servicio_id = s.id
    WHERE dv.venta_id = :venta_id
");
$stmtDetalles->execute([':venta_id' => $venta_id]);
$detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

// Generar HTML para el PDF
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <?php if ($logo && file_exists($logo)): ?>
        <img src="<?= $logo ?>" style="height: 80px;">
    <?php endif; ?>
    <div style="text-align: right;">
        <h3 style="margin: 0;"><?= htmlspecialchars($config['nombre_empresa']) ?></h3>
        <p style="margin: 0;"><?= htmlspecialchars($config['direccion']) ?></p>
        <p style="margin: 0;">Tel: <?= htmlspecialchars($config['telefono']) ?></p>
        <p style="margin: 0;">Correo: <?= htmlspecialchars($config['correo']) ?></p>
    </div>
</div>

<hr>

<h2>Factura / Detalle de Venta</h2>
<p><strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente']) ?></p>
<p><strong>Usuario:</strong> <?= htmlspecialchars($venta['usuario']) ?></p>
<p><strong>Fecha:</strong> <?= date("d/m/Y H:i", strtotime($venta['fecha'])) ?></p>
<p><strong>Total:</strong> $<?= number_format($venta['total'], 2) ?></p>

<h4>Detalle:</h4>
<table>
    <thead>
        <tr>
            <th>Producto/Servicio</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detalles as $item): 
            $subtotal = $item['cantidad'] * $item['precio_unitario'];
        ?>
        <tr>
            <td><?= htmlspecialchars($item['nombre']) ?></td>
            <td><?= ucfirst($item['tipo']) ?></td>
            <td><?= $item['cantidad'] ?></td>
            <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
            <td>$<?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
<?php
$html = ob_get_clean();

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("factura_venta_{$venta_id}.pdf", ["Attachment" => true]);
?>
