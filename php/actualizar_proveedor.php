<?php
require_once("../includes/conexion.php");

 
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $contacto = trim($_POST["contacto"] ?? '');
    $direccion = trim($_POST["direccion"] ?? '');

    // Validaciones
    if (empty($nombre))
        $errores[] = "El nombre es obligatorio.";
    if (!empty($correo)) {

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL))
            $errores[] = "Correo inválido.";
    } else {
        $correo = '';
    }
    if (!preg_match('/^[0-9]{7,15}$/', $telefono))
        $errores[] = "Teléfono inválido (7-15 dígitos).";

    if (empty($errores)) {
        $sql = "UPDATE proveedores SET nombre = :nombre, telefono = :telefono, contacto = :contacto, correo = :correo, direccion = :direccion WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':telefono' => $telefono,
            ':contacto' => $contacto,
            ':correo' => $correo,
            ':direccion' => $direccion,
            ':id' => $id
        ]);
        header("Location: proveedores.php");
        exit;
    }
}
?>