<?php
 
require_once '../config/conexion.php';

$venta_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Datos de configuración de empresa
$stmt = $pdo->query("SELECT * FROM configuracion LIMIT 1");
$config = $stmt->fetch(PDO::FETCH_ASSOC);

// Venta y cliente
$stmtVenta = $pdo->prepare("
    SELECT v.*, c.nombre AS cliente_nombre, c.direccion AS cliente_direccion, c.telefono AS cliente_telefono
    FROM ventas v
    LEFT JOIN clientes c ON v.cliente_id = c.id
    WHERE v.id = ?
");
$stmtVenta->execute([$venta_id]);
$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

// Detalles de venta
$stmtDetalle = $pdo->prepare("
    SELECT d.*, 
           p.nombre AS producto_nombre, 
           s.nombre AS servicio_nombre
    FROM detalles_venta d
    LEFT JOIN productos p ON d.producto_id = p.id
    LEFT JOIN servicios s ON d.servicio_id = s.id
    WHERE d.venta_id = ?
");
$stmtDetalle->execute([$venta_id]);
$detalles = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #<?= $venta_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }

        .factura {
            background: #fff;
            max-width: 850px;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.07);
        }

        .factura h1 {
            font-size: 1.8rem;
        }

        .factura .logo {
            max-height: 70px;
        }

        .table thead {
            background: #e9ecef;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .resumen {
            text-align: right;
            margin-top: 2rem;
        }

        .resumen p {
            margin: 0;
        }

        .resumen h4 {
            margin-top: 0.5rem;
            font-weight: 600;
            color: #212529;
        }

        .btn-imprimir {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        @media print {
            .btn-imprimir, .btn-volver {
                display: none;
            }
            body {
                background: #fff;
            }
            .factura {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="factura">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><?= htmlspecialchars($config['nombre_empresa']) ?></h1>
            <p class="mb-1"><?= $config['direccion'] ?></p>
            <p class="mb-1">Tel: <?= $config['telefono'] ?> | Email: <?= $config['correo'] ?></p>
        </div>
        <div>
            <?php if (!empty($config['logo'])): ?>
                <img src="<?= $config['logo'] ?>" class="logo" alt="Logo">
            <?php endif; ?>
        </div>
    </div>

    <hr>

    <div class="row mb-4">
        <div class="col-md-6">
            <h6>Factura #<?= str_pad($venta['id'], 6, '0', STR_PAD_LEFT) ?></h6>
            <p>Fecha: <?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></p>
        </div>
        <div class="col-md-6 text-md-end">
            <h6>Cliente</h6>
            <p class="mb-0"><?= htmlspecialchars($venta['cliente_nombre']) ?></p>
            <p class="mb-0"><?= htmlspecialchars($venta['cliente_direccion']) ?></p>
            <p><?= htmlspecialchars($venta['cliente_telefono']) ?></p>
        </div>
    </div>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Descuento %</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $item): ?>
                <tr>
                    <td><?= ucfirst($item['tipo']) ?></td>
                    <td><?= $item['tipo'] === 'producto' ? $item['producto_nombre'] : $item['servicio_nombre'] ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td><?= number_format($item['precio_unitario'], 2) ?></td>
                   <!--  <td><?=  number_format($item['descuento'], 2) ?></td> -->
                    <td><?=  rtrim(rtrim(number_format($item['descuento'], 1, '.', ''), '0'), '.')?> %</td>
                    <td><?= number_format($item['subtotal'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
        $subtotal = $venta['total'] / (1 + ($config['iva'] / 100));
        $iva = $venta['total'] - $subtotal;
    ?>

    <div class="resumen">
        <p>Subtotal: <?= number_format($subtotal, 2) ?> </p>
        <p>IVA (<?= $config['iva'] ?>%): <?= number_format($iva, 2) ?></p>
        <h4>Total: <?= number_format($venta['total'], 2) ?></h4>
    </div>
</div>

<!-- Botón de impresión -->
<div class="btn-imprimir">
    <button onclick="window.print()" class="btn btn-primary shadow">🖨 Imprimir</button>
   <!--  <a href="index.php?vista=ventas" class="btn btn-secondary btn-volver mt-2">← Volver</a> -->
</div>

</body>
</html>
