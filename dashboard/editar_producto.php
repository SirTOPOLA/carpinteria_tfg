<?php
require_once("../includes/conexion.php");

// Inicialización de variables
$id = $_GET["id"] ?? null;
$nombre = $descripcion = $categoria_id = $precio = '';
$errores = [];

// Obtener el producto a editar
if (!$id || !is_numeric($id)) {
    header("Location: productos.php?error=ID inválido");
    exit;
}

$stmtProd = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
$stmtProd->execute([':id' => $id]);
$producto = $stmtProd->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header("Location: productos.php?error=Producto no encontrado");
    exit;
}

// Obtener categorías
$stmtCat = $pdo->query("SELECT id, nombre FROM categorias_producto ORDER BY nombre");
$categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

// Establecer valores iniciales del formulario
$nombre = $producto['nombre'];
$descripcion = $producto['descripcion'];
$categoria_id = $producto['categoria_id'];
$precio = $producto['precio'];

// Procesamiento del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');
    $categoria_id = $_POST["categoria_id"] ?? '';
    $precio = $_POST["precio"] ?? '';

    // Validaciones
    if (empty($nombre)) {
        $errores[] = "El nombre del producto es obligatorio.";
    } elseif (strlen($nombre) > 100) {
        $errores[] = "El nombre no puede superar los 100 caracteres.";
    }

    if (!empty($descripcion) && strlen($descripcion) > 1000) {
        $errores[] = "La descripción es demasiado larga.";
    }

    if (!is_numeric($precio) || $precio < 0) {
        $errores[] = "El precio debe ser un número positivo.";
    }

    if (empty($categoria_id) || !is_numeric($categoria_id)) {
        $errores[] = "Debe seleccionar una categoría válida.";
    }

    // Actualización en la base de datos
    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, categoria_id = :categoria_id, precio = :precio WHERE id = :id");
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':categoria_id' => $categoria_id,
                ':precio' => $precio,
                ':id' => $id
            ]);
            header("Location: productos.php?actualizado=1");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al actualizar el producto: " . $e->getMessage();
        }
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
<div class="container-fluid py-4">
    <div class="col-md-6">
        <h4>Editar Producto</h4>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="  p-4">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required
                    value="<?= htmlspecialchars($nombre) ?>">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control"
                    rows="3"><?= htmlspecialchars($descripcion) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="categoria_id" class="form-label">Categoría</label>
                <select name="categoria_id" id="categoria_id" class="form-select" required>
                    <option value="">Seleccione una categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $categoria_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" name="precio" id="precio" class="form-control" step="0.01" required
                    value="<?= htmlspecialchars($precio) ?>">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Cambios
            </button>
            <a href="productos.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </form>
    </div>
</div>
 

<?php include_once("../includes/footer.php"); ?>