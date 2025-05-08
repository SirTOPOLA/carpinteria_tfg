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
        $sql = "INSERT INTO proveedores (nombre,  contacto, telefono, email, direccion)
        VALUES (:nombre,  :contacto,:telefono, :correo, :direccion)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':contacto', $contacto);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':direccion', $direccion);

        $stmt->execute();

        header("Location: ../dashboard/proveedores.php");
        exit;
    }
}
?>