<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../config/conexion.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT nombre_empresa, direccion, telefono, correo, nif, moneda, logo FROM configuracion LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $config = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($config) {
        echo json_encode($config);
    } else {
        echo json_encode([]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener datos de configuración.']);
}
?>

