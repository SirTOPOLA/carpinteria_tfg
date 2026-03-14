<?php

require_once "conexion.php";

class DashboardModel
{

    public static function stats()
    {

        $conexion = new Conexion();
        $conexion->conectar();

        $pdo = $conexion->pdo;

        $stats = [

            // Clientes registrados
            "clientes_total" => self::count($pdo, "clientes"),

            // Proyectos totales
            "proyectos_total" => self::count($pdo, "proyectos"),

            // Proyectos activos (no finalizados)
            "proyectos_activos" => self::countWhere(
                $pdo,
                "proyectos",
                "fecha_entrega IS NULL"
            ),

            // Materiales con stock bajo
            "stock_bajo" => self::countWhere(
                $pdo,
                "materiales",
                "stock_actual <= stock_minimo"
            ),

            // Ventas pendientes
            "ventas_pendientes" => self::countWhere(
                $pdo,
                "ventas",
                "estado = 'pendiente'"
            ),

            // Cotizaciones enviadas
            "cotizaciones_activas" => self::countWhere(
                $pdo,
                "cotizaciones",
                "estado IN ('enviada','borrador')"
            ),

            // Órdenes de compra pendientes
            "compras_pendientes" => self::countWhere(
                $pdo,
                "ordenes_compra",
                "estado = 'pendiente'"
            ),

            // Gastos operativos registrados
            "gastos_total" => self::sum(
                $pdo,
                "gastos_operativos",
                "monto"
            ),

            // Ventas totales
            "ventas_total" => self::sum(
                $pdo,
                "ventas",
                "total"
            )

        ];

        $conexion->desconectar();

        return $stats;
    }


    private static function count($pdo, $tabla)
    {

        $stmt = $pdo->query("SELECT COUNT(*) FROM $tabla");

        return (int) $stmt->fetchColumn();
    }


    private static function countWhere($pdo, $tabla, $condicion)
    {

        $stmt = $pdo->query("SELECT COUNT(*) FROM $tabla WHERE $condicion");

        return (int) $stmt->fetchColumn();
    }


    private static function sum($pdo, $tabla, $campo)
    {

        $stmt = $pdo->query("SELECT IFNULL(SUM($campo),0) FROM $tabla");

        return (float) $stmt->fetchColumn();
    }

}