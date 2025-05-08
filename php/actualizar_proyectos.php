<?php
// Si se envió el formulario
require_once("../includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = trim($_POST['proyecto_id'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? ''); 
    $fecha_inicio = trim($_POST['fecha_inicio'] ?? '');
    $fecha_entrega = trim($_POST['fecha_entrega'] ?? '');
    $estado = trim($_POST['estado'] ?? 'Pendiente');  


    // Validaciones básicas
    if ($nombre === "" ||   $estado === "") {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
        
        echo 'faltan datos';
    } else {
        // Actualizar proyecto
        $sql_update = "UPDATE proyectos SET nombre = :nombre, descripcion = :descripcion, fecha_inicio = :fecha_inicio, fecha_entrega = :fecha_entrega, estado = :estado WHERE id = :id";
        $stmt_update = $pdo->prepare($sql_update);
        $exito = $stmt_update->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_entrega' => $fecha_entrega,
            ':estado' => $estado,
            ':id' => $id
        ]);

        header("Location: ../dashboard/proyectos.php?editado=1");
       
    }
}

?>