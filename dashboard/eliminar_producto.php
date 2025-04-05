<?php
require_once("../includes/conexion.php");

// Validar que se recibió un ID válido por GET
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: productos.php?error=ID inválido");
    exit;
}

// Verificar si el producto existe
$sql_verificar = "SELECT id FROM productos WHERE id = :id";
$stmt_verificar = $pdo->prepare($sql_verificar);
$stmt_verificar->execute([':id' => $id]);

if (!$stmt_verificar->fetch()) {
    header("Location: productos.php?error=Producto no encontrado");
    exit;
}

try {
    // Eliminar imágenes asociadas primero (si la integridad no es ON DELETE CASCADE)
    $sql_imagenes = "DELETE FROM imagenes_producto WHERE producto_id = :id";
    $stmt_img = $pdo->prepare($sql_imagenes);
    $stmt_img->execute([':id' => $id]);

    // Eliminar el producto
    $sql_eliminar = "DELETE FROM productos WHERE id = :id";
    $stmt_eliminar = $pdo->prepare($sql_eliminar);
    $stmt_eliminar->execute([':id' => $id]);

    header("Location: productos.php?exito=Producto eliminado correctamente");
    exit;
} catch (PDOException $e) {
    header("Location: productos.php?error=Error al eliminar: " . urlencode($e->getMessage()));
    exit;
}
?>
