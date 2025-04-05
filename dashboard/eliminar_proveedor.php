<?php
require_once("../includes/conexion.php");

// Validar ID
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    // Verificar si el proveedor existe
    $stmt = $pdo->prepare("SELECT id FROM proveedores WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($proveedor) {
        // Eliminar proveedor
        $stmt = $pdo->prepare("DELETE FROM proveedores WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}

header("Location: proveedores.php");
exit;
?>
