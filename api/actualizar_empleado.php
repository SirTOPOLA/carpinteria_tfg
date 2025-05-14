<?php
require_once('../config/conexion.php');
header('Content-Type: application/json');

try {
    $errores = [];

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        echo json_encode(['success' => false, 'errors' => ['id' => 'ID inválido.']]);
        exit;
    }

    // Recoger y sanear entradas
    $nombre           = trim($_POST['nombre'] ?? '');
    $apellido         = trim($_POST['apellido'] ?? '');
    $genero           = trim($_POST['genero'] ?? '');
    $codigo           = trim($_POST['codigo'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $telefono         = trim($_POST['telefono'] ?? '');
    $email            = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $direccion        = trim($_POST['direccion'] ?? '');
    $fecha_ingreso    = trim($_POST['fecha_ingreso'] ?? '');
    $horario_trabajo  = trim($_POST['horario_trabajo'] ?? '');
    $salario = trim($_POST['salario'] ?? '');

    if (floatval($salario) < -1) {
        $errores[] = 'El salario debe ser un número positivo.';
    }
    // Validación de campos obligatorios
    if ($nombre === '') $errores[ ] = 'El nombre es obligatorio.';
    if ($apellido === '') $errores[ ] = 'El apellido es obligatorio.';
    if (!in_array($genero, ['M', 'F'])) $errores[ ] = 'El género es inválido.';
    
    // Validación de código/DNI
    if ($codigo === '' || strlen($codigo) < 6) {
        $errores[ ] = 'El código/DNI debe tener al menos 6 caracteres.';
    }

    // Validación de teléfono
    if (!empty($telefono) && !preg_match('/^\d{9,}$/', $telefono)) {
        $errores[ ] = 'El teléfono debe tener al menos 9 dígitos.';
    }

    // Validación de email
    if ($email === ''  ) {
        $errores[ ] = 'El correo electrónico es inválido.';
    }

    // Validar duplicados por código y email (excluyendo al mismo ID)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE (codigo = ? OR LOWER(email) = LOWER(?)) AND id != ?");
    $stmt->execute([$codigo, $email, $id]);
    if ($stmt->fetchColumn() > 0) {
        $errores[ ] = 'Ya existe otro empleado con ese código o correo electrónico.';
    }

    // Validar fecha de nacimiento (mínimo 10 años atrás)
    if ($fecha_nacimiento !== '') {
        $fecha_nac = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
        $hoy = new DateTime();
        $min_fecha = (clone $hoy)->modify('-10 years');
        if (!$fecha_nac || $fecha_nac > $min_fecha) {
            $errores['fecha_nacimiento'] = 'La fecha de nacimiento debe ser al menos 10 años antes de hoy.';
        }
    }

    // Validar fecha de ingreso (no puede ser futura)
    if ($fecha_ingreso !== '') {
        $fecha_ing = DateTime::createFromFormat('Y-m-d', $fecha_ingreso);
        if (!$fecha_ing || $fecha_ing > new DateTime()) {
            $errores[ ] = 'La fecha de ingreso no puede ser posterior a hoy.';
        }
    }

    // Validación del horario de trabajo
   /*  if (!empty($horario_trabajo) && !preg_match('/^[\p{L},\s\-0-9:.apmAPM]+$/u', $horario_trabajo)) {
        $errores[ ] = 'El horario debe tener un formato válido. Ejemplo: lunes a viernes, 08:00 a.m - 14:00 p.m.';
    } */

    // Si hay errores, retornarlos
    if (!empty($errores)) {
        echo json_encode(['success' => false, 'message' => $errores]);
        exit;
    }

    // Actualización segura
    $stmt = $pdo->prepare("UPDATE empleados SET 
        nombre = ?, 
        apellido = ?, 
        genero = ?, 
        codigo = ?, 
        fecha_nacimiento = ?, 
        telefono = ?, 
        email = ?, 
        direccion = ?, 
        fecha_ingreso = ?, 
        horario_trabajo = ?,
        salario = ? 
        WHERE id = ?");

    $stmt->execute([
        htmlspecialchars($nombre),
        htmlspecialchars($apellido),
        htmlspecialchars($genero),
        $codigo,
        $fecha_nacimiento ?: null,
        $telefono ?: null,
        $email ?: null,
        htmlspecialchars($direccion),
        $fecha_ingreso ?: null,
        htmlspecialchars($horario_trabajo),
       intval( $salario) ?: null,
        $id
    ]);

    echo json_encode(['success' => true, 'message' => 'Empleado actualizado correctamente.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' =>   'Error en la base de datos: ' . $e->getMessage() ]);
}
