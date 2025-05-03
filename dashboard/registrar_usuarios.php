<?php
require_once("../includes/conexion.php"); 

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];
    $rol = $_POST['rol'];
    $empleado_id = $_POST['empleado_id'] ?? null;
    $activo = isset($_POST['activo']) ? (bool) $_POST['activo'] : false;

    // Validar los campos
    if (empty($usuario) || empty($password) || empty($rol)) {
        $errores[]=  "Todos los campos son obligatorios.";
        header("Location: usuarios.php");
        exit;
    }

    // Sanitizar los datos
    $usuario = htmlspecialchars($usuario);
    $rol = htmlspecialchars($rol);

    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario");
    $stmt->execute([':usuario' => $usuario]);
    $existe_usuario = $stmt->fetchColumn();

    if ($existe_usuario) {
      $errores[] = "El nombre de usuario ya está en uso.";
        header("Location: usuarios.php");
        exit;
        
    }

    // Hash de la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (usuario, password, rol, empleado_id, activo) 
            VALUES (:usuario, :password, :rol, :empleado_id, :activo)";
    
    // Ejecutar la consulta
    $stmt = $pdo->prepare($sql);
    $params = [
        ':usuario' => $usuario,
        ':password' => $password_hash,
        ':rol' => $rol,
        ':empleado_id' => $empleado_id,
        ':activo' => $activo
    ];
    
    try {
        $pdo->beginTransaction();
        $stmt->execute($params);
        $pdo->commit();
        header("Location: usuarios.php ");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $errores[] = "Error al registrar el usuario: " . $e->getMessage();
        header("Location: usuarios.php");
        exit;
    }
}
 

// Obtener lista de empleados para asociar al usuario
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM empleados ORDER BY nombre");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container">
        <h4 class="mb-4">Registrar Usuario</h4>

        <form   method="POST" class="row g-3 needs-validation" novalidate>

            <div class="col-md-6">
                <label for="usuario" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                <input type="text" name="usuario" id="usuario" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                <select name="rol" id="rol" class="form-select" required>
                    <option value="">Seleccione un rol</option>
                    <option value="administrador">Administrador</option>
                    <option value="vendedor">Vendedor</option>
                    <option value="operario">Operario</option>
                    <option value="diseñador">Diseñador</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="empleado_id" class="form-label">Empleado Asociado</label>
                <select name="empleado_id" id="empleado_id" class="form-select">
                    <option value="">Sin asociar</option>
                    <?php foreach ($empleados as $emp): ?>
                        <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['nombre_completo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">¿Activo?</label>
                <select name="activo" class="form-select">
                    <option value="1" selected>Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Usuario
                </button>
                <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
