
<?php
require_once "../config/conexion.php";

if (!isset($_POST['produccion_id'])) {
    echo json_encode([]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400); // Solicitud inválida
    echo json_encode(['error' => 'Método no válido o faltan parámetros.']);
    exit;
}

    $produccion_id = intval($_POST['produccion_id']);

    

    $sql = "
        SELECT 
            m.id AS material_id,
            m.nombre,
            dsm.precio_unitario,
            dsm.cantidad AS cantidad_solicitada,
            COALESCE(SUM(mm.cantidad), 0) AS cantidad_movida,
            (dsm.cantidad - COALESCE(SUM(mm.cantidad), 0)) AS max_salida,
            m.stock_actual AS stock_disponible
        FROM producciones p
        INNER JOIN proyectos pr ON p.proyecto_id = pr.id
        INNER JOIN solicitudes_proyecto sp ON sp.proyecto_id = pr.id
        INNER JOIN detalles_solicitud_material dsm ON dsm.solicitud_id = sp.id
        INNER JOIN materiales m ON m.id = dsm.material_id
        LEFT JOIN movimientos_material mm 
            ON mm.material_id = m.id AND mm.produccion_id = p.id AND mm.tipo_movimiento = 'salida'
        WHERE p.id = ?
        GROUP BY m.id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$produccion_id]);
    $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($materiales);
    exit;
 


 
/* require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'get' && isset($_POST['produccion_id'])) {
    $produccion_id = intval($_POST['produccion_id']);

   
    $pdo = $conexion->getConexion();

    $sql = "
        SELECT 
            m.id AS material_id,
            m.nombre,
            dsm.precio_unitario,
            dsm.cantidad AS cantidad_solicitada,
            COALESCE(SUM(mm.cantidad), 0) AS cantidad_movida,
            (dsm.cantidad - COALESCE(SUM(mm.cantidad), 0)) AS max_salida,
            m.stock_actual AS stock_disponible
        FROM producciones p
        INNER JOIN proyectos pr ON p.proyecto_id = pr.id
        INNER JOIN solicitudes_proyecto sp ON sp.proyecto_id = pr.id
        INNER JOIN detalles_solicitud_material dsm ON dsm.solicitud_id = sp.id
        INNER JOIN materiales m ON m.id = dsm.material_id
        LEFT JOIN movimientos_material mm 
            ON mm.material_id = m.id AND mm.produccion_id = p.id AND mm.tipo_movimiento = 'salida'
        WHERE p.id = ?
        GROUP BY m.id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$produccion_id]);
    $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($materiales);
    exit;
}else{
    
    echo json_encode(['metodo no valido']);
}
 */
?>