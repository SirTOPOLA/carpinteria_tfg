<?php
require '../config/conexion.php';
 
// Clientes
$stmtClientes = $pdo->query("SELECT id, nombre FROM clientes");
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

// Servicios activos
$stmtServicios = $pdo->query("SELECT id, nombre, precio_base FROM servicios WHERE activo = 1");
$servicios = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);

// Materiales con precio más alto (desde detalles_compra) y stock > stock_minimo
$stmtMateriales = $pdo->query("
    SELECT 
        m.id, 
        m.nombre, 
        MAX(dc.precio_unitario) AS precio_unitario
    FROM 
        materiales m
    JOIN 
        detalles_compra dc ON m.id = dc.material_id
    WHERE 
        m.stock_actual > m.stock_minimo
    GROUP BY 
        m.id, m.nombre
");
$materiales = $stmtMateriales->fetchAll(PDO::FETCH_ASSOC);

// Proyectos
$stmtProyectos = $pdo->query("SELECT id, nombre FROM proyectos");
$proyectos = $stmtProyectos->fetchAll(PDO::FETCH_ASSOC);

// Devolver respuesta en JSON
echo json_encode([
    'clientes' => $clientes,
    'materiales' => $materiales,
    'servicios' => $servicios,
    'proyectos' => $proyectos
]);
