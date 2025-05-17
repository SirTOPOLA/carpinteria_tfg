<?php
session_start();
header('Content-Type: application/json');

require_once '../config/conexion.php'; 

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'No hay sesión activa']);
    exit;
}

$rol = strtolower($_SESSION['usuario']['rol']); // Normaliza el rol para evitar errores de mayúsculas

$tarjetas = [];

switch ($rol) {
    case 'administrador':
        $tarjetas = [
            ['titulo' => 'Total Clientes',     'tabla' => 'clientes'],
            ['titulo' => 'Total Empleados',    'tabla' => 'empleados'],
            ['titulo' => 'Total Ventas',       'tabla' => 'ventas'],
            ['titulo' => 'Total Compras',      'tabla' => 'compras'],
            ['titulo' => 'Total Productos',    'tabla' => 'productos'],
            ['titulo' => 'Total Servicios',    'tabla' => 'servicios'],
            ['titulo' => 'Total Materiales',   'tabla' => 'materiales'],
            ['titulo' => 'Proyectos Activos',  'tabla' => 'proyectos', 'filtro' => "estado != 'finalizado'"],
        ];
        break;

    case 'vendedor':
        $tarjetas = [
            ['titulo' => 'Mis Ventas', 'tabla' => 'ventas'],
            ['titulo' => 'Clientes',   'tabla' => 'clientes']
        ];
        break;

    case 'operario':
        $tarjetas = [
            ['titulo' => 'Producciones Activas', 'tabla' => 'producciones', 'filtro' => "estado != 'terminado'"],
            ['titulo' => 'Total Materiales',     'tabla' => 'materiales']
        ];
        break;

    case 'diseñador':
        $tarjetas = [
            ['titulo' => 'Proyectos en diseño', 'tabla' => 'proyectos', 'filtro' => "estado = 'en diseño'"]
        ];
        break;

    case 'cliente':
        $cliente_id = $_SESSION['usuario']['id'];
        $tarjetas = [
            ['titulo' => 'Mis Pedidos', 'tabla' => 'solicitudes_proyecto', 'filtro' => "cliente_id = $cliente_id"]
        ];
        break;

    default:
        echo json_encode(['error' => 'Rol no reconocido']);
        exit;
}

$resultado = [];

foreach ($tarjetas as $card) {
    $tabla = $card['tabla'];
    $titulo = $card['titulo'];
    $filtro = isset($card['filtro']) ? "WHERE {$card['filtro']}" : '';

    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla $filtro");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $resultado[] = [
            'titulo' => $titulo,
            'total'  => $row['total']
        ];
    } catch (PDOException $e) {
        $resultado[] = [
            'titulo' => $titulo,
            'total'  => 'Error'
        ];
    }
}

echo json_encode($resultado);
