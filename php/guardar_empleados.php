<?php
require_once("../includes/conexion.php");

$errores = [];
$exito = "";


// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    // Función para generar código único
    function generarCodigoAcceso($nombre, $direccion)
    {
        $prefijo = "E";
        $iniciales = strtoupper(substr(preg_replace('/[^A-Z]/i', '', $nombre), 0, 2));
        $anio = date('y');
        $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 2));
        return substr($prefijo . $iniciales . $anio . $direccion . $random, 0, 11);
    }

    $codigo = generarCodigoAcceso($nombre, $direccion);

    if (empty($errores)) {
        try {
            $sql = "INSERT INTO empleados (nombre, apellido, fecha_nacimiento, codigo, genero, telefono, direccion,  email,  horario_trabajo, fecha_ingreso)
                    VALUES (:nombre, :apellido,:fecha_nacimiento, :codigo, :genero, :telefono, :direccion,  :email,   :horario_trabajo, :fecha_ingreso)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':fecha_nacimiento' => $fecha_nacimiento,
                ':codigo' => $codigo,
                ':genero' => $genero,
                ':telefono' => $telefono,
                ':direccion' => $direccion,
                ':email' => $email,
                ':horario_trabajo' => $horario_trabajo,
                ':fecha_ingreso' => $fecha_ingreso
            ]);
            $exito = "empleados registrado correctamente.";
            header("Location: ../dashboard/empleados.php");
            exit;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errores[] = "El código o correo ya existe.";
                header("Location: ../dashboard/empleados.php");
                exit;
            } else {
                $errores[] = "Error al registrar el empleados: " . $e->getMessage();

                header("Location: ../dashboard/empleados.php");
                exit;
            }
        }
    }
}
?>