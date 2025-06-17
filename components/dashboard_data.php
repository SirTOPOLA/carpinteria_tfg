<?php
require_once '../config/conexion.php';


// Obtener estadísticas generales
function getGeneralStats()
{
    try {
        $stats = [];

        // Total de clientes
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM clientes");
        $stats['total_clientes'] = $stmt->fetch()['total'];

        // Total de empleados
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM empleados");
        $stats['total_empleados'] = $stmt->fetch()['total'];

        // Pedidos pendientes
        $stmt = $pdo->prepare("
                SELECT COUNT(*) as total 
                FROM pedidos p 
                JOIN estados e ON p.estado_id = e.id 
                WHERE e.nombre IN ('Pendiente', 'En Proceso')
            ");
        $stats['pedidos_pendientes'] = $stmt->fetch()['total'];

        // Producciones activas
        $stmt = $pdo->prepare("
                SELECT COUNT(*) as total 
                FROM producciones p 
                JOIN estados e ON p.estado_id = e.id 
                WHERE e.nombre IN ('En Proceso', 'Iniciado')
            ");
        $stats['producciones_activas'] = $stmt->fetch()['total'];

        // Ingresos del mes actual
        $stmt = $pdo->prepare("
                SELECT COALESCE(SUM(total), 0) as total 
                FROM ventas 
                WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) 
                AND YEAR(fecha) = YEAR(CURRENT_DATE())
            ");
        $stats['ingresos_mes'] = $stmt->fetch()['total'];

        // Materiales con stock bajo
        $stmt = $pdo->prepare("
                SELECT COUNT(*) as total 
                FROM materiales 
                WHERE stock_actual <= stock_minimo
            ");
        $stats['materiales_stock_bajo'] = $stmt->fetch()['total'];

        return $stats;

    } catch (PDOException $e) {
        error_log("Error en getGeneralStats: " . $e->getMessage());
        return [];
    }
}

// Obtener pedidos recientes
function getRecentOrders($limit = 5)
{
    try {
        $stmt = $pdo->prepare("
                SELECT 
                    p.id,
                    p.proyecto,
                    c.nombre as cliente,
                    p.fecha_solicitud,
                    p.fecha_entrega,
                    p.precio_obra,
                    e.nombre as estado
                FROM pedidos p
                JOIN clientes c ON p.cliente_id = c.id
                JOIN estados e ON p.estado_id = e.id
                ORDER BY p.fecha_solicitud DESC
                LIMIT :limit
            ");

        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();

    } catch (PDOException $e) {
        error_log("Error en getRecentOrders: " . $e->getMessage());
        return [];
    }
}

// Obtener producciones en proceso
function getActiveProductions($limit = 5)
{
    try {
        $stmt = $pdo->prepare("
                SELECT 
                    pr.id,
                    p.proyecto,
                    CONCAT(e.nombre, ' ', e.apellido) as responsable,
                    pr.fecha_inicio,
                    pr.fecha_fin,
                    est.nombre as estado
                FROM producciones pr
                LEFT JOIN pedidos p ON pr.solicitud_id = p.id
                LEFT JOIN empleados e ON pr.responsable_id = e.id
                JOIN estados est ON pr.estado_id = est.id
                WHERE est.entidad = 'produccion'
                ORDER BY pr.fecha_inicio DESC
                LIMIT :limit
            ");

        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();

    } catch (PDOException $e) {
        error_log("Error en getActiveProductions: " . $e->getMessage());
        return [];
    }
}

// Obtener materiales con stock bajo
function getLowStockMaterials($limit = 10)
{
    try {
        $stmt = $pdo->prepare("
                SELECT 
                    nombre,
                    stock_actual,
                    stock_minimo,
                    unidad_medida
                FROM materiales 
                WHERE stock_actual <= stock_minimo
                ORDER BY (stock_actual - stock_minimo) ASC
                LIMIT :limit
            ");

        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();

    } catch (PDOException $e) {
        error_log("Error en getLowStockMaterials: " . $e->getMessage());
        return [];
    }
}

// Obtener ventas por mes (últimos 6 meses)
function getSalesChart()
{
    try {
        $stmt = $pdo->query("
                SELECT 
                    DATE_FORMAT(fecha, '%Y-%m') as mes,
                    SUM(total) as total_ventas
                FROM ventas 
                WHERE fecha >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(fecha, '%Y-%m')
                ORDER BY mes ASC
            ");

        return $stmt->fetchAll();

    } catch (PDOException $e) {
        error_log("Error en getSalesChart: " . $e->getMessage());
        return [];
    }
}

// Obtener configuración de la empresa
function getCompanyConfig()
{
    try {
        $stmt = $pdo->query("SELECT * FROM configuracion LIMIT 1");
        return $stmt->fetch();

    } catch (PDOException $e) {
        error_log("Error en getCompanyConfig: " . $e->getMessage());
        return [];
    }
}

?>