<?php
require_once("../includes/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['material_id'];
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? ''); 
    $unidad = trim($_POST['unidad_medida'] ?? '');
    $stock = (int) $_POST['stock_minimo']; 

    if ($id > 0 && $nombre !== '' && $stock >= 0 && $precio >= 0) {
        $stmt = $pdo->prepare("UPDATE materiales SET nombre = ?, descripcion = ? , unidad_medida = ?, stock_minimo = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion,  $unidad, $stock, $id]);
        
          }

    header("Location: ../dashboard/materiales.php");
    exit;
}



?>