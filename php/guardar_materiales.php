<?php
// Conexión a la base de datos
require_once("../includes/conexion.php");

// Verificar si se recibieron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados desde el formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? null;  // El campo descripción es opcional
    $unidad_medida = $_POST['unidad_medida'] ?? null;  // El campo unidad_medida es opcional
    $stock_actual = $_POST['stock_actual'] ?? 0 ;
    $stock_minimo = $_POST['stock_minimo'] ?? 0;

    // Preparar la sentencia SQL para insertar los datos en la tabla materiales
    $sql = "INSERT INTO materiales (nombre, descripcion, unidad_medida, stock_actual, stock_minimo)
            VALUES (:nombre, :descripcion, :unidad_medida, :stock_actual, :stock_minimo)";

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);

    // Vincular los parámetros
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':unidad_medida', $unidad_medida);
    $stmt->bindParam(':stock_actual', $stock_actual);
    $stmt->bindParam(':stock_minimo', $stock_minimo);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página de listado de materiales con un mensaje de éxito
        header("Location: ../dashboard/materiales.php?mensaje=Material registrado exitosamente");
        exit;
    } else {
        // Si hubo un error en la ejecución de la consulta
        echo "Error al registrar el material.";
    }
}
?>
