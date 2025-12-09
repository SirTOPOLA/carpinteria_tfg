<?php
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * LOGIN DE USUARIOS INTERNOS (empleados)
 * Tabla: usuarios
 * Clave: password_hash
 */
function login($pdo, $username, $password)
{
    $stmt = $pdo->prepare("
        SELECT 
            u.id_usuario,
            u.usuario,
            u.password_hash,
            u.activo,
            u.id_empleado,
            r.nombre AS rol,
            e.nombre AS nombre_empleado,
            e.apellido AS apellido_empleado
        FROM usuarios u
        INNER JOIN empleados e ON u.id_empleado = e.id_empleado
        INNER JOIN roles r ON u.id_rol = r.id_rol
        WHERE u.usuario = ?
        LIMIT 1
    ");

    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ¿Existe el usuario?
    if (!$user) {
        $_SESSION['alerta'] = "Usuario o contraseña incorrectos.";
        return false;
    }

    // ¿La contraseña coincide?
    if (!password_verify($password, $user['password_hash'])) {
        $_SESSION['alerta'] = "Usuario o contraseña incorrectos.";
        return false;
    }

    // ¿Está activo?
    if ((int)$user['activo'] !== 1) {
        $_SESSION['alerta'] = "Tu cuenta está inactiva. Contacta al administrador.";
        return false;
    }

    // LOGIN EXITOSO → crear sesión
    $_SESSION['usuario'] = [
        'id' => $user['id_usuario'],
        'empleado_id' => $user['id_empleado'],
        'usuario' => $user['usuario'],
        'nombre' => $user['nombre_empleado'] . " " . $user['apellido_empleado'],
        'rol' => $user['rol']
    ];

    $_SESSION['alerta'] = "Inicio de sesión exitoso.";
    return true;
}


/**
 * LOGOUT
 */
function logout()
{
    session_unset();
    session_destroy();
    header('Location: index.php?vista=inicio');
    exit;
}


/**
 * LOGIN PARA CLIENTES
 * Tabla: clientes
 * Acceso mediante código único: codigo_acceso
 */
function loginCliente($pdo, $codigo_acceso): bool
{
    $stmt = $pdo->prepare("
        SELECT 
            id_cliente,
            tipo,
            nombre,
            apellido,
            razon_social,
            telefono,
            correo,
            direccion,
            codigo_acceso
        FROM clientes
        WHERE codigo_acceso = ?
        LIMIT 1
    ");

    $stmt->execute([$codigo_acceso]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $_SESSION['usuario'] = [
            'id' => $cliente['id_cliente'],
            'nombre' => trim($cliente['nombre'] . " " . $cliente['apellido']),
            'razon_social' => $cliente['razon_social'],
            'telefono' => $cliente['telefono'],
            'direccion' => $cliente['direccion'],
            'email' => $cliente['correo'],
            'codigo_acceso' => $cliente['codigo_acceso'],
            'rol' => 'cliente'
        ];

        $_SESSION['alerta'] = "Inicio de sesión exitoso.";
        return true;
    }

    $_SESSION['alerta'] = "Credenciales de cliente incorrectas.";
    return false;
}
