<?php
require_once("../includes/conexion.php");

// Verificar si el ID de usuario está presente
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Obtener el estado actual del usuario
    $sql = "SELECT activo FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Cambiar el estado: si está activo, desactivarlo, y si está inactivo, activarlo
        $nuevo_estado = $usuario['activo'] ? 0 : 1;

        // Actualizar el estado del usuario
        $sql_update = "UPDATE usuarios SET activo = :activo WHERE id = :id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindValue(':activo', $nuevo_estado, PDO::PARAM_INT);
        $stmt_update->bindValue(':id', $usuario_id, PDO::PARAM_INT);
        $stmt_update->execute();

        // Redirigir a la página de listado de usuarios
        header("Location: usuarios.php?mensaje=" . ($nuevo_estado ? 'Usuario activado con éxito' : 'Usuario desactivado con éxito'));
        exit;
    } else {
        // Si el usuario no existe
        header("Location: usuarios.php?error=Usuario no encontrado");
        exit;
    }
} else {
    // Si no se pasó un ID de usuario válido
    header("Location: usuarios.php?error=ID de usuario no válido");
    exit;
}
?>
