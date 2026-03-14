<?php
require_once "conexion.php";

class EmpleadoModel
{
    /**
     * Lista todos los colaboradores (Estructura de la fuerza laboral)
     */
    public static function listar()
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "SELECT * FROM empleados ORDER BY fecha_registro DESC";

        $stmt = $conexion->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtiene la ficha técnica de un empleado por su ID
     */
    public static function obtener($id)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "SELECT * FROM empleados WHERE id_empleado = ?";

        $stmt = $conexion->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Registra un nuevo colaborador en el activo de la empresa
     */
    public static function crear($datos)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "INSERT INTO empleados 
                (nombre, apellido, documento, telefono, correo, direccion, rol_laboral, salario_base, fecha_contratacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([
            $datos["nombre"],
            $datos["apellido"],
            $datos["documento"],
            $datos["telefono"],
            $datos["correo"],
            $datos["direccion"],
            $datos["rol_laboral"],
            $datos["salario_base"],
            $datos["fecha_contratacion"]
        ]);
    }

    /**
     * Actualiza la información profesional del empleado
     */
    public static function actualizar($datos)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "UPDATE empleados 
                SET nombre=?, apellido=?, documento=?, telefono=?, correo=?, direccion=?, rol_laboral=?, salario_base=?, fecha_contratacion=? 
                WHERE id_empleado=?";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([
            $datos["nombre"],
            $datos["apellido"],
            $datos["documento"],
            $datos["telefono"],
            $datos["correo"],
            $datos["direccion"],
            $datos["rol_laboral"],
            $datos["salario_base"],
            $datos["fecha_contratacion"],
            $datos["id_empleado"]
        ]);
    }

    /**
     * Método Especial para el Switch: Alterna el estado Alta/Baja
     * (Bandura: Control directo y simplificado para el CEO)
     */
    public static function actualizarEstado($id)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        // Primero obtenemos el estado actual para invertirlo
        $sqlActual = "SELECT activo FROM empleados WHERE id_empleado = ?";
        $stmtActual = $conexion->pdo->prepare($sqlActual);
        $stmtActual->execute([$id]);
        $empleado = $stmtActual->fetch();

        if ($empleado) {
            $nuevoEstado = $empleado['activo'] == 1 ? 0 : 1;
            $sql = "UPDATE empleados SET activo = ? WHERE id_empleado = ?";
            $stmt = $conexion->pdo->prepare($sql);
            return $stmt->execute([$nuevoEstado, $id]);
        }
        
        return false;
    }

    /**
     * Elimina el registro del empleado (Uso restringido)
     */
    public static function eliminar($id)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "DELETE FROM empleados WHERE id_empleado = ?";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
}