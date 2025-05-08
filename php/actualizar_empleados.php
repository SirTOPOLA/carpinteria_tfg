<?php
require_once("../includes/conexion.php");


// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    //$salario = is_numeric($_POST['salario'] ?? '') ? (float)$_POST['salario'] : null;
    $horario_trabajo = trim($_POST['horario_trabajo'] ?? '');
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

    // Validaciones
    if ($nombre === '')
        $errores[] = "El nombre es obligatorio.";
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errores[] = "El email no es válido.";
    if ($fecha_ingreso !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_ingreso))
        $errores[] = "La fecha de ingreso no es válida.";

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("UPDATE empleados SET 
            nombre = :nombre,
            apellido = :apellido,
            fecha_nacimiento = :fecha_nacimiento, 
            genero = :genero,
            telefono = :telefono,
            direccion = :direccion, 
            email = :email, 
            horario_trabajo = :horario_trabajo,
            fecha_ingreso = :fecha_ingreso
            WHERE id = :id
        ");

            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':fecha_nacimiento' => $fecha_nacimiento, 
                ':genero' => $genero,
                ':telefono' => $telefono,
                ':direccion' => $direccion,
                ':email' => $email,
                ':horario_trabajo' => $horario_trabajo,
                ':fecha_ingreso' => $fecha_ingreso,
                ':id' => $id
            ]);
            $exito = "Empleado registrado correctamente.";
            header("location: ../dashboard/empleados.php");
            exit();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                //echo "El código o correo ya existe.";
                header("location: ../dashboard/registrar_empleado.php");
                exit();
            } else {
               //echo "Error al registrar el empleado: " . $e->getMessage();
                header("location: ../dashboard/registrar_empleado.php");
                exit();
            }
        }
    }
}
?>