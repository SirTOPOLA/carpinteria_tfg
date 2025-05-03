<?php
require_once("../includes/conexion.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Eliminar el usuario de la base de datos
    $sql_delete = "DELETE FROM usuarios WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->bindValue(':id', $usuario_id, PDO::PARAM_INT);

    if ($stmt_delete->execute()) {
        // Redirigir a la lista de usuarios con mensaje de éxito
        header("Location: usuarios.php?mensaje=Usuario eliminado con éxito");
        exit;
    } else {
        // Si ocurre un error
        header("Location: usuarios.php?error=Error al eliminar el usuario");
        exit;
    }
} else {
    // Si no se pasa un ID válido
    header("Location: usuarios.php?error=ID de usuario no válido");
    exit;
}
?>
