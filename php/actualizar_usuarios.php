<?php
require_once("../includes/conexion.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id =  trim($_POST['usuario_id']);
    $usuario_nombre = trim($_POST['usuario']);
    $rol = trim( $_POST['rol']) ?? '';
    $nueva_password = trim($_POST['password']);

    // Validaciones
    if (empty($usuario_nombre) || empty($rol)) {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    } else {
        // Preparar SQL
        $sql_update = "UPDATE usuarios SET usuario = :usuario, rol_id = :rol";
        if (!empty($nueva_password)) {
            $sql_update .= ", password = :password";
        }
        $sql_update .= " WHERE id = :id";

        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':usuario', $usuario_nombre, PDO::PARAM_STR);
        $stmt_update->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt_update->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        if (!empty($nueva_password)) {
            $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
            $stmt_update->bindParam(':password', $password_hash, PDO::PARAM_STR);
        }

        if ($stmt_update->execute()) {
            header("Location: ../dashboard/usuarios.php?mensaje=Usuario actualizado correctamente");
            exit;
        } else {
            $mensaje = "Error al actualizar el usuario.";
        }
    }
}

?>