<?php
include('../../config/conexion.php');

$solicitud_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($solicitud_id <= 0) {
  echo "ID inválido";
  exit;
}

// 1. Datos generales del pedido y cliente
$stmt = $pdo->prepare("
  SELECT
    p.id, p.fecha_solicitud, 
    p.precio_obra, p.estimacion_total, p.proyecto, p.piezas,
    p.adelanto,
    c.nombre AS cliente, c.direccion, c.telefono,
    s.nombre AS servicio, s.precio_base AS precio_servicio
  FROM pedidos p
  JOIN clientes c ON p.cliente_id = c.id
  LEFT JOIN servicios s ON p.servicio_id = s.id
  WHERE p.id = ?
");
$stmt->execute([$solicitud_id]);
$solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$solicitud) {
  echo "Solicitud no encontrada";
  exit;
}

// 2. Total piezas (sumado desde detalles si quieres exactitud real)
/* $stmt = $pdo->prepare("
  SELECT SUM(cantidad) AS total_piezas
  FROM detalles_pedido_material
  WHERE pedido_id = ?
");
$stmt->execute([$solicitud_id]);
$totalPiezasRow = $stmt->fetch(PDO::FETCH_ASSOC);
$total_piezas = intval($totalPiezasRow['total_piezas'] ?? $solicitud['piezas']);
 */
// 3. Detalles de materiales
$stmt = $pdo->prepare("
  SELECT m.id AS material_id, m.nombre, m.unidad_medida,
         dpm.cantidad, dc.max_precio_unitario AS precio_unitario,
         (dpm.cantidad * dc.max_precio_unitario) AS subtotal
  FROM detalles_pedido_material dpm
  JOIN materiales m ON m.id = dpm.material_id
  JOIN (
    SELECT material_id, MAX(precio_unitario) AS max_precio_unitario
    FROM detalles_compra
    GROUP BY material_id
  ) dc ON dc.material_id = dpm.material_id
  WHERE dpm.pedido_id = ?
  ORDER BY m.nombre
");
$stmt->execute([$solicitud_id]);
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Configuración empresa
$config = $pdo->query("SELECT * FROM configuracion LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// 5. URL PDF
$baseUrl = "https://tu-dominio.com";
$pdfUrl = "$baseUrl/controladores/pdf_proforma.php?id=$solicitud_id";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Proforma #<?= htmlspecialchars($solicitud['id']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap y Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary: #2c3e50;
      --accent: #18bc9c;
      --text: #2d3436;
      --font-main: 'Segoe UI', sans-serif;
    }

    body {
      font-family: var(--font-main);
      background: #f0f2f5;
      color: var(--text);
      margin: 0;
      padding: 2rem;
    }

    .card {
      border: 0;
      border-radius: 1rem;
      box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    .section {
      margin-bottom: 2rem;
    }

    .section-title {
      border-bottom: 2px solid var(--accent);
      padding-bottom: 0.25rem;
      margin-bottom: 1rem;
      color: var(--primary);
      font-weight: 600;
    }

    .table th {
      background: #ecf0f1;
    }

    .no-print {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    @media print {
      body {
        background: #fff;
        padding: 0;
      }

      .no-print,
      .card {
        box-shadow: none;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card p-4 bg-white">
      <!-- Cabezera -->
      <div class="d-flex justify-content-between align-items-center section">
        <div>
          <h2 class="text-uppercase mb-0">
            <?= ($solicitud['adelanto'] > 0) ? 'Factura' : 'Proforma' ?> #<?= htmlspecialchars($solicitud['id']) ?>
          </h2>

          <small class="text-muted">Fecha: <?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?></small>
        </div>
        <?php if (!empty($config['logo'])): ?>
          <img src="../uploads/<?= htmlspecialchars($config['logo']) ?>" alt="Logo" style="max-height:80px">
        <?php endif; ?>
      </div>

      <!-- Empresa & Cliente -->
      <div class="row section">
        <div class="col-md-6">
          <h5 class="section-title">Empresa</h5>
          <p class="mb-1"><i
              class="bi bi-building-fill me-2 text-info"></i><strong><?= htmlspecialchars($config['nombre_empresa']) ?></strong>
          </p>
          <p class="mb-1"><i class="bi bi-geo-alt-fill me-2"></i><?= htmlspecialchars($config['direccion']) ?></p>
          <p class="mb-1"><i class="bi bi-telephone-fill me-2"></i><?= htmlspecialchars($config['telefono']) ?></p>
          <p class="mb-1"><i class="bi bi-envelope-fill me-2"></i><?= htmlspecialchars($config['correo']) ?></p>
        </div>
        <div class="col-md-6">
          <h5 class="section-title">Cliente</h5>
          <p class="mb-1"><strong><?= htmlspecialchars($solicitud['cliente']) ?></strong></p>
          <p class="mb-1"><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($solicitud['direccion']) ?></p>
          <p class="mb-1"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($solicitud['telefono']) ?></p>
        </div>
      </div>

      <!-- Proyecto y Servicio -->
      <div class="section">
        <h5 class="section-title">Proyecto</h5>
        <p><?= htmlspecialchars($solicitud['proyecto']) ?></p>
        <?php if ($solicitud['servicio']): ?>
          <h5 class="section-title">Servicio</h5>
          <p><?= htmlspecialchars($solicitud['servicio']) ?></p>
        <?php endif; ?>
        <p class="mt-3"><strong>Total de piezas:</strong> <?= htmlspecialchars($solicitud['piezas']) ?> unidades</p>
      </div>

      <!-- Materiales -->
      <div class="section">
        <h5 class="section-title">Materiales Solicitados</h5>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Material</th>
                <th>Unidad</th>
                <th>Cantidad</th>
                <th>PU (<?= htmlspecialchars($config['moneda']) ?>)</th>
                <th>Subtotal (<?= htmlspecialchars($config['moneda']) ?>)</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $totalMat = 0;
              foreach ($materiales as $i => $m):
                $totalMat += $m['subtotal'];
                ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($m['nombre']) ?></td>
                  <td><?= htmlspecialchars($m['unidad_medida']) ?></td>
                  <td><?= htmlspecialchars($m['cantidad']) ?></td>
                  <td><?= number_format($m['precio_unitario'], 2) ?></td>
                  <td><?= number_format($m['subtotal'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Totales -->
      <?php
      $precioServicio = floatval($solicitud['precio_servicio'] ?? 0);
      $manoObra = floatval($solicitud['precio_obra']);
      $subtotal = $totalMat + $precioServicio + $manoObra;
      $iva = !empty($config['iva']) ? $subtotal * ($config['iva'] / 100) : 0;
      ?>
      <div class="section text-end">
        <table class="table table-sm w-auto ms-auto">
          <tr>
            <th>Total Materiales:</th>
            <td><?= number_format($totalMat, 2) ?> <?= htmlspecialchars($config['moneda']) ?></td>
          </tr>
          <?php if ($precioServicio): ?>
            <tr>
              <th>Precio Servicio:</th>
              <td><?= number_format($precioServicio, 2) ?>   <?= htmlspecialchars($config['moneda']) ?></td>
            </tr>
          <?php endif; ?>
          <tr>
            <th>Mano de Obra:</th>
            <td><?= number_format($manoObra, 2) ?> <?= htmlspecialchars($config['moneda']) ?></td>
          </tr>
          <tr class="border-top">
            <th>Subtotal:</th>
            <td><?= number_format($subtotal, 2) ?> <?= htmlspecialchars($config['moneda']) ?></td>
          </tr>
          <?php if ($iva > 0): ?>
            <tr>
              <th>IVA (<?= htmlspecialchars($config['iva']) ?>%):</th>
              <td><?= number_format($iva, 2) ?>   <?= htmlspecialchars($config['moneda']) ?></td>
            </tr>
            <tr>
              <th>Total con IVA:</th>
              <td class="fw-bold"><?= number_format($subtotal + $iva, 2) ?>   <?= htmlspecialchars($config['moneda']) ?>
              </td>
            </tr>
          <?php endif; ?>
        </table>
      </div>
      <?php if ($solicitud['adelanto'] > 0): ?>
        <?php
        $totalConIVA = $subtotal + $iva;
        $adelanto = floatval($solicitud['adelanto']);
        $restante = $totalConIVA - $adelanto;
        ?>
        <div class="section text-end">
          <table class="table table-sm w-auto ms-auto">
            <tr>
              <th>Adelanto Pagado:</th>
              <td class="text-success fw-semibold"><?= number_format($adelanto, 2) ?>
                <?= htmlspecialchars($config['moneda']) ?></td>
            </tr>
            <tr>
              <th>Saldo Pendiente:</th>
              <td class="text-danger fw-semibold"><?= number_format($restante, 2) ?>
                <?= htmlspecialchars($config['moneda']) ?></td>
            </tr>
          </table>
        </div>
      <?php endif; ?>

      <?php if ($solicitud['adelanto'] <= 0): ?>
        <p class="text-muted fst-italic small section">
          * Esta proforma no representa un compromiso definitivo. Está sujeta a disponibilidad de materiales o
          modificaciones del proyecto.
        </p>
      <?php else: ?>
        <p class="text-muted fst-italic small section">
          * Esta factura refleja un pago parcial y el saldo pendiente está sujeto a verificación al momento de entrega.
        </p>
      <?php endif; ?>


      <!-- Botones -->
      <div class="no-print justify-content-end">
        <a href="https://wa.me/?text=Hola,%20aquí%20tienes%20tu%20proforma:%20<?= urlencode($pdfUrl) ?>"
          class="btn btn-success"><i class="bi bi-whatsapp me-2"></i> WhatsApp</a>
        <button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer me-2"></i>
          Imprimir</button>
        <a href="<?= $pdfUrl ?>" target="_blank" class="btn btn-primary"><i
            class="bi bi-file-earmark-pdf-fill me-2"></i> PDF</a>
      </div>

    </div>
  </div>
</body>

</html>