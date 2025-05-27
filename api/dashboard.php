<?php
session_start();
header('Content-Type: application/json');

require_once '../config/conexion.php';

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No hay sesión activa']);
    exit;
}

$rol = strtolower($_SESSION['usuario']['rol']); // Normaliza el rol para evitar errores de mayúsculas

$tarjetas = [];

switch ($rol) {
    case 'administrador':
        $tarjetas = [
            ['titulo' => 'Total Clientes', 'tabla' => 'clientes', 'icono' => 'bi-people-fill'],
            ['titulo' => 'Total Empleados', 'tabla' => 'empleados', 'icono' => 'bi-person-badge-fill'],
            ['titulo' => 'Total Ventas', 'tabla' => 'ventas', 'icono' => 'bi-cash-stack'],
            ['titulo' => 'Total Compras', 'tabla' => 'compras', 'icono' => 'bi-bag-check-fill'],
            ['titulo' => 'Total Productos', 'tabla' => 'productos', 'icono' => 'bi-box-seam'],
            ['titulo' => 'Total Servicios', 'tabla' => 'servicios', 'icono' => 'bi-gear-fill'],
            ['titulo' => 'Total Materiales', 'tabla' => 'materiales', 'icono' => 'bi-hammer'],
            ['titulo' => 'Proyectos Activos', 'tabla' => 'proyectos', 'filtro' => "estado != 'finalizado'", 'icono' => 'bi-kanban-fill'],
        ];
        break;
    /*  */

    /*  */

    case 'vendedor':
        $tarjetas = [
            ['titulo' => 'Mis Ventas', 'tabla' => 'ventas', 'icono' => 'bi-cash-stack'],
            ['titulo' => 'Clientes', 'tabla' => 'clientes', 'icono' => 'bi-people-fill']
        ];
        break;

    case 'operario':
        $tarjetas = [
            ['titulo' => 'Producciones Activas', 'tabla' => 'producciones', 'filtro' => "estado != 'terminado'", 'icono' => 'bi-tools'],
            ['titulo' => 'Total Materiales', 'tabla' => 'materiales', 'icono' => 'bi-hammer']
        ];
        break;

    case 'diseñador':
        $tarjetas = [
            ['titulo' => 'Proyectos en diseño', 'tabla' => 'proyectos', 'filtro' => "estado = 'en diseño'", 'icono' => 'bi-pencil-square']
        ];
        break;

    case 'cliente':
        $cliente_id = $_SESSION['usuario']['id'];
        $tarjetas = [
            ['titulo' => 'Mis Pedidos', 'tabla' => 'solicitudes_proyecto', 'filtro' => "cliente_id = $cliente_id", 'icono' => 'bi-cart-check']
        ];
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Rol no reconocido']);
        exit;
}

$resultado = [];

foreach ($tarjetas as $card) {
    $tabla = $card['tabla'];
    $titulo = $card['titulo'];
    $filtro = isset($card['filtro']) ? "WHERE {$card['filtro']}" : '';
    $icono = $card['icono'] ?? 'bi-card-text'; // icono por defecto

    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla $filtro");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $resultado[] = [
            'titulo' => $titulo,
            'total' => $row['total'],
            'icono' => $icono
        ];
    } catch (PDOException $e) {
        $resultado[] = [
            'titulo' => $titulo,
            'total' => 'Error',
            'icono' => $icono
        ];
    }
}
echo json_encode(['success' => true, 'data' => $resultado]);
