<?php
require '../config/conexion.php';

$produccion_id = intval($_GET['produccion_id'] ?? 0);
if ($produccion_id <= 0) {
    echo json_encode(['total_porcentaje' => 0]);
    exit;
}

$stmt = $pdo->prepare("SELECT SUM(porcentaje) AS total FROM avances_produccion WHERE produccion_id = ?");
$stmt->execute([$produccion_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['total_porcentaje' => intval($row['total'] ?? 0)]);
