<?php
require_once("../includes/conexion.php"); 

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $email = trim($_POST['usuario']);  
    $password = $_POST['password'];
    $rol = trim($_POST['rol']);
    $empleado_id = trim($_POST['empleado_id']) ?? null;
    $activo = 0;

    // Validar los campos
    if (empty($email) || empty($password) || empty($rol)) {
        $errores[]=  "Todos los campos son obligatorios.";
        echo ' '.$rol.' '.$email .' '.$empleado_id;
       // header("Location: ../dashboard/registrar_usuarios.php");
        //exit;
    }

    // Sanitizar los datos
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $rol = htmlspecialchars($rol);

    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :email");
    $stmt->execute([':email' => $email]);
    $existe_usuario = $stmt->fetchColumn();

    if ($existe_usuario) {
      $errores[] = "El nombre de usuario ya está en uso.";
        header("Location: ../dashboard/usuarios.php");
        exit;
        
    }

    // Hash de la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta para insertar el nuevo email
    $sql = "INSERT INTO usuarios (usuario, password, rol_id, empleado_id, activo) 
            VALUES (:email, :password, :rol, :empleado_id, :activo)";
    
    // Ejecutar la consulta
    $stmt = $pdo->prepare($sql);
    $params = [
        ':email' => $email,
        ':password' => $password_hash,
        ':rol' => $rol,
        ':empleado_id' => $empleado_id,
        ':activo' => $activo
    ];
    
    try {
        $pdo->beginTransaction();
        $stmt->execute($params);
        $pdo->commit();
        header("Location: ../dashboard/usuarios.php ");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $errores[] = "Error al registrar el usuario: " . $e->getMessage();
        header("Location: ../dashboard/registrar_usuarios.php");
        exit;
    }
}
 