<?php
require_once("../includes/conexion.php");

$nombre = '';
$descripcion = '';
$categoria_id = '';
$precio = '';
$errores = [];

// Obtener las categorías
$stmtCat = $pdo->query("SELECT id, nombre FROM categorias_producto ORDER BY nombre");
$categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');
    $categoria_id = $_POST["categoria_id"] ?? '';
    $precio = $_POST["precio"] ?? '';

    // === Validaciones ===
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

    // === Insertar en BD ===
    if (empty($errores)) {
        try {
            $sql = "INSERT INTO productos (nombre, descripcion, categoria_id, precio)
                    VALUES (:nombre, :descripcion, :categoria_id, :precio)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':categoria_id' => $categoria_id,
                ':precio' => $precio
            ]);
            $query = "SELECT *FROM categorias_producto";
            $stmt = $pdo->prepare($query);
            $stmt->execute() ;
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("Location: productos.php?exito=1");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al registrar el producto: " . $e->getMessage();
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
        <div class="col-md-7">
            <h4 class="mb-4">Registrar Nuevo Producto</h4>

            

            <form method="POST" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del producto"
                        value="<?= htmlspecialchars($nombre) ?>" required>
                    <label for="nombre">Nombre del producto</label>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción (opcional)</label>
                    <textarea name="descripcion" id="descripcion" class="form-control"
                        rows="3"><?= htmlspecialchars($descripcion) ?></textarea>
                </div>

                <div class="form-floating mb-3">
                    <select name="categoria_id" id="categoria_id" class="form-select" required>
                        <option value="" disabled selected>Seleccione una categoría</option>
                        <?php  
                        foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $categoria_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="categoria_id">Categoría</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="number" step="0.01" name="precio" id="precio" class="form-control" placeholder="Precio"
                        value="<?= htmlspecialchars($precio) ?>" required>
                    <label for="precio">Precio</label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="categorias.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<?php include_once("../includes/footer.php"); ?>