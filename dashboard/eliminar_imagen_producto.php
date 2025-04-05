<?php
require_once("../includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;

    if ($id && is_numeric($id)) {
        // Buscar la imagen
        $stmt = $pdo->prepare("SELECT ruta_imagen FROM imagenes_producto WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $imagen = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($imagen) {
            $ruta = $imagen['ruta_imagen'];

            // Eliminar de la base de datos
            $stmtDel = $pdo->prepare("DELETE FROM imagenes_producto WHERE id = :id");
            $stmtDel->execute([':id' => $id]);

            // Eliminar del servidor
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }
    }

    header("Location: imagenes_producto.php?eliminado=1");
    exit;
}
