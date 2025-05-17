<?php
require_once('../config/conexion.php');
header('Content-Type: application/json');

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $errores = [];

    // Recoger y validar datos
    $nombre            = trim($_POST['nombre'] ?? '');
    $apellido          = trim($_POST['apellido'] ?? '');
    $genero            = trim($_POST['genero'] ?? '');
    $codigo            = trim($_POST['codigo'] ?? '');
    $fecha_nacimiento  = trim($_POST['fecha_nacimiento'] ?? '');
    $telefono          = trim($_POST['telefono'] ?? '');
    $email             = trim($_POST['email'] ?? '');
    $direccion         = trim($_POST['direccion'] ?? '');
    $fecha_ingreso     = trim($_POST['fecha_ingreso'] ?? '');
    $horario_trabajo   = trim($_POST['horario_trabajo'] ?? '');
    $salario           = trim($_POST['salario'] ?? '');

    // Validaciones obligatorias
    if ($nombre === '') $errores[] = 'El nombre es obligatorio.';
    if ($apellido === '') $errores[] = 'El apellido es obligatorio.';
    if (!in_array($genero, ['M', 'F'])) $errores[] = 'El género es inválido.';
    if ($codigo === '' || strlen($codigo) < 6) $errores[] = 'El código debe tener al menos 6 caracteres.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'Correo electrónico inválido.';
    if ($salario !== '' && (!is_numeric($salario) || floatval($salario) < 0)) $errores[] = 'El salario debe ser un número positivo.';

    if (!empty($telefono) && !preg_match('/^\d{4,}$/', $telefono)) {
        $errores[] = 'El teléfono debe tener al menos 9 dígitos.';
    }

    if (!empty($fecha_nacimiento)) {
        $fecha_nac = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
        $min_fecha = (new DateTime())->modify('-10 years');
        if (!$fecha_nac || $fecha_nac > $min_fecha) {
            $errores[] = 'La fecha de nacimiento debe ser al menos 10 años antes de hoy.';
        }
    }

    if (!empty($fecha_ingreso)) {
        $fecha_ing = DateTime::createFromFormat('Y-m-d', $fecha_ingreso);
        if (!$fecha_ing || $fecha_ing > new DateTime()) {
            $errores[] = 'La fecha de ingreso no puede ser posterior a hoy.';
        }
    }

    if (!empty($horario_trabajo) && !preg_match('/^[\p{L},\s\-0-9:.apmAPM]+$/u', $horario_trabajo)) {
        $errores[] = 'Formato de horario no válido. Ejemplo: lunes a viernes, 08:00 a.m - 14:00 p.m.';
    }

    // Verificar duplicados por código y email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE codigo = ? OR LOWER(email) = LOWER(?)");
    $stmt->execute([$codigo, $email]);
    if ($stmt->fetchColumn() > 0) {
        $errores[] = 'Ya existe un empleado registrado con ese código o correo electrónico.';
    }

    // Si hay errores, detener
    if (!empty($errores)) {
        echo json_encode(['status' => false, 'message' => $errores]);
        exit;
    }

    // Insertar empleado
    $stmt = $pdo->prepare("INSERT INTO empleados 
        (nombre, apellido, genero, codigo, fecha_nacimiento, telefono, email, direccion, fecha_ingreso, horario_trabajo, salario)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $nombre,
        $apellido,
        $genero,
        $codigo,
        $fecha_nacimiento ?: null,
        $telefono ?: null,
        $email,
        $direccion,
        $fecha_ingreso ?: null,
        $horario_trabajo,
        $salario !== '' ? $salario : null
    ]);

    echo json_encode([
        'status' => true,
        'message' => 'Empleado registrado exitosamente.',
        'empleado_id' => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
