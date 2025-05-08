<?php
require_once("../includes/conexion.php"); 

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $fecha_inicio = trim($_POST['fecha_inicio']);  
    $fecha_fin = trim($_POST['fecha_fin']);  
    $proyecto_id = trim($_POST['proyecto_id']);
    $estado_produccion = trim($_POST['estado_produccion']);
    $responsable_id = trim($_POST['responsable_id']) ?? null;
  

    // Validar los campos
    if (empty($fecha_inicio) || 
    empty($fecha_fin) || 
    empty($estado_produccion) || 
    empty($responsable_id) || 
    empty($proyecto_id)) {
        $errores[]=  "Todos los campos son obligatorios.";
        echo 'error ';
       // header("Location: ../dashboard/registrar_usuarios.php");
        //exit;
    } 
    
    // Preparar la consulta para insertar el nueva produccion
    $sql = "INSERT INTO producciones (proyecto_id, responsable_id, fecha_inicio, fecha_fin, estado ) 
            VALUES (:proyecto_id, :responsable_id, :fecha_inicio, :fecha_fin, :estado_produccion )";
    
    // Ejecutar la consulta
    $stmt = $pdo->prepare($sql);
    $params = [
        ':proyecto_id' => $proyecto_id,
        ':responsable_id' => $responsable_id,
        ':fecha_inicio' => $fecha_inicio,
        ':fecha_fin' => $fecha_fin,
        ':estado_produccion' => $estado_produccion 
    ];
    
    try {
        $pdo->beginTransaction();
        $stmt->execute($params); 
        $pdo->commit();
        header("Location: ../dashboard/producciones.php ");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $errores[] = "Error al registrar el usuario: " . $e->getMessage();
       // header("Location: ../dashboard/registrar_producciones.php");
       echo "error: ".$e->getMessage();
        exit;
    }
}
 