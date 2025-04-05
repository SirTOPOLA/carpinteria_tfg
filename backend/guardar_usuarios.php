<?php
// ========================================
// PROCESAR REGISTRO DE USUARIO
// ========================================

// Conexión a la base de datos
require_once("../includes/conexion.php");

// Bloque para manejar errores
$errores = [];

// ===========================
// VALIDAR Y SANITIZAR DATOS
// ===========================

// Verificar si llegaron los datos por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // --- NOMBRE ---
    $nombre = trim($_POST["nombre"] ?? '');
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    } elseif (!preg_match("/^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{3,100}$/", $nombre)) {
        $errores[] = "El nombre solo debe contener letras y espacios, mínimo 3 caracteres.";
    }

    // --- CORREO ---
    $correo = trim($_POST["correo"] ?? '');
    if (empty($correo)) {
        $errores[] = "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Formato de correo inválido.";
    }

    // --- CONTRASEÑA ---
    $contrasena = $_POST["contrasena"] ?? '';
    if (empty($contrasena)) {
        $errores[] = "La contraseña es obligatoria.";
    } elseif (strlen($contrasena) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    // --- ROL ---
    $rol_id = $_POST["rol_id"] ?? '';
    if (empty($rol_id) || !is_numeric($rol_id)) {
        $errores[] = "Debe seleccionar un rol válido.";
    } else {
        // Verificar si el rol existe en la base de datos
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE id = ?");
        $stmt->execute([$rol_id]);
        if ($stmt->fetchColumn() == 0) {
            $errores[] = "El rol seleccionado no existe.";
        }
    }

    // ===================
    // PROCESAR REGISTRO
    // ===================
    if (empty($errores)) {
        try {
            // Verificar si el correo ya está registrado
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = ?");
            $stmt->execute([$correo]);
            if ($stmt->fetchColumn() > 0) {
                $errores[] = "El correo ya está registrado.";
            } else {
                // Hashear la contraseña
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);

                // Insertar el usuario
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nombre, $correo, $hash, $rol_id]);

                // Redirigir o mostrar éxito
                header("Location: usuarios_lista.php?mensaje=usuario_registrado");
                exit;
            }

        } catch (PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
        }
    }
} else {
    $errores[] = "Acceso no válido al formulario.";
}

// ============================
// MOSTRAR ERRORES SI HAY
// ============================

if (!empty($errores)) {
    echo "<div class='container mt-4'><div class='alert alert-danger'><ul>";
    foreach ($errores as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul><a href='javascript:history.back()' class='btn btn-secondary mt-2'>Volver</a></div></div>";
}
?>
