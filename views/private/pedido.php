<?php
// archivo: gestion_pedidos.php
include("../config/conexion.php");
$pdo = new Conexion();
$sql = "SELECT p.id, p.cliente_id, c.nombre AS cliente, p.fecha_pedido, p.estado, p.total
        FROM pedidos p
        INNER JOIN clientes c ON p.cliente_id = c.id
        ORDER BY p.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-primary"><i class="bi bi-bag-check me-2"></i>Gestión de Pedidos</h3>
    <button class="btn btn-success d-flex align-items-center" onclick="nuevoPedido()">
      <i class="bi bi-plus-circle me-2"></i>Nuevo Pedido
    </button>
  </div>

  <div class="card shadow rounded-4 border-0">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center" id="tablaPedidos">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Cliente</th>
              <th>Fecha</th>
              <th>Estado</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pedidos as $index => $pedido): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                <td><?= date("d/m/Y", strtotime($pedido['fecha_pedido'])) ?></td>
                <td>
                  <span class="badge bg-<?= $pedido['estado'] == 'pendiente' ? 'warning' : 'success' ?>">
                    <?= ucfirst($pedido['estado']) ?>
                  </span>
                </td>
                <td>$<?= number_format($pedido['total'], 2) ?></td>
                <td>
                  <button class="btn btn-sm btn-outline-primary me-1" onclick="editarPedido(<?= $pedido['id'] ?>)">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" onclick="eliminarPedido(<?= $pedido['id'] ?>)">
                    <i class="bi bi-trash3"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function nuevoPedido() {
  // Lógica para abrir modal nuevo pedido
  console.log("Nuevo pedido");
}

function editarPedido(id) {
  // Lógica para abrir modal edición
  console.log("Editar pedido", id);
}

function eliminarPedido(id) {
  if (confirm("¿Deseas eliminar este pedido?")) {
    // Lógica de eliminación con fetch
    console.log("Eliminar pedido", id);
  }
}
</script>
