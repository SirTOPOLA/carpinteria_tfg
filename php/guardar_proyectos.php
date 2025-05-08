<?php
require_once '../includes/conexion.php';

$errores = [];
$exito = '';
$clientes = [];

 

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? ''); 
    $fecha_inicio = trim($_POST['fecha_inicio'] ?? '');
    $fecha_entrega = trim($_POST['fecha_entrega'] ?? '');
    $estado = trim($_POST['estado'] ?? 'Pendiente');  

    

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO proyectos (nombre, descripcion, estado, fecha_inicio, fecha_entrega   )
                                   VALUES (:nombre, :descripcion,   :estado, :fecha_inicio, :fecha_entrega    )");
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion, 
                ':estado' => $estado,
                ':fecha_inicio' => $fecha_inicio ?: null,
                ':fecha_entrega' => $fecha_entrega ?: null 
            ]);
            $exito = 'Proyecto registrado correctamente.';
            header('location: ../dashboard/proyectos.php');
        } catch (PDOException $e) {
            $errores[] = 'Error al registrar el proyecto: ' . $e->getMessage();
            //header('location: ../dashboard/registrar_proyectos.php');
        echo 'errores: '.$e->getMessage(); 
        }  
    }else {
        echo 'errores';
    }
}
?>