<?php
require_once("../includes/conexion.php");

// Validar ID
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header("Location: proveedores.php");
    exit;
}

// Obtener datos del proveedor
$stmt = $pdo->prepare("SELECT * FROM proveedores WHERE id = :id");
$stmt->execute([':id' => $id]);
$proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proveedor) {
    header("Location: proveedores.php");
    exit;
}

// Procesar formulario
$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $direccion = trim($_POST["direccion"] ?? '');

    // Validaciones
    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
    if(!empty($correo)){

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
    }else{
        $correo = '';
    }
    if (!preg_match('/^[0-9]{7,15}$/', $telefono)) $errores[] = "Teléfono inválido (7-15 dígitos).";

    if (empty($errores)) {
        $sql = "UPDATE proveedores SET nombre = :nombre, telefono = :telefono, correo = :correo, direccion = :direccion WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':telefono' => $telefono,
            ':correo' => $correo,
            ':direccion' => $direccion,
            ':id' => $id
        ]);
        header("Location: proveedores.php");
        exit;
    }
}
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
    <h4>Editar Proveedor</h4>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($proveedor['telefono']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($proveedor['correo']) ?>" >
        </div>
        <div class="col-md-6">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($proveedor['direccion']) ?>">
        </div>
        <div class="col-12 text-end">
            <a href="proveedores.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i>Guardar Cambios</button>
        </div>
    </form>
</div>
                </main>
<?php include_once("../includes/footer.php"); ?>
