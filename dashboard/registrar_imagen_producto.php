<?php
require_once("../includes/conexion.php");

// =======================
// GUARDADO DE IMÁGENES
// =======================
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $directorio_destino = "../uploads/";
    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
    $tamano_maximo = 2 * 1024 * 1024; // 2MB

    $producto_id = isset($_POST['producto_id']) ? (int) $_POST['producto_id'] : 0;
    $imagenes = $_FILES['imagenes'] ?? [];

    $errores = [];
    $rutas_guardadas = [];

    for ($i = 0; $i < count($imagenes['name']); $i++) {
        $nombre_original = $imagenes['name'][$i];
        $temporal = $imagenes['tmp_name'][$i];
        $error = $imagenes['error'][$i];
        $tamano = $imagenes['size'][$i];

        if ($error !== UPLOAD_ERR_OK) {
            $errores[] = "Error al subir '$nombre_original'.";
            continue;
        }

        if ($tamano > $tamano_maximo) {
            $errores[] = "La imagen '$nombre_original' supera los 2MB.";
            continue;
        }

        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
        if (!in_array($extension, $extensiones_permitidas)) {
            $errores[] = "El archivo '$nombre_original' no tiene un formato permitido.";
            continue;
        }

        $nombre_nuevo = uniqid("img_", true) . "." . $extension;
        $ruta_relativa = "uploads/" . $nombre_nuevo;
        $ruta_destino = "../" . $ruta_relativa;

        if (!move_uploaded_file($temporal, $ruta_destino)) {
            $errores[] = "Error al mover '$nombre_original'.";
            continue;
        }

        $rutas_guardadas[] = $ruta_relativa;
    }

    if (!empty($rutas_guardadas)) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO imagenes_producto (producto_id, ruta_imagen) VALUES (:producto_id, :ruta)");
            foreach ($rutas_guardadas as $ruta) {
                $stmt->execute([
                    ':producto_id' => $producto_id,
                    ':ruta' => $ruta
                ]);
            }
            $pdo->commit();
            $mensaje = "<div class='alert alert-success'>Imágenes subidas correctamente.</div>";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensaje = "<div class='alert alert-danger'>Error al guardar en la base de datos: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>No se subió ninguna imagen válida.</div>";
    }
}

// =======================
// OBTENER PRODUCTOS
// =======================
$stmt = $pdo->query("SELECT id, nombre FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
   <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Subir Imágenes para Productos</h4>
        <a href="productos.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>

    <?= $mensaje ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="producto_id" class="form-label">Producto</label>
            <select name="producto_id" id="producto_id" class="form-select" required>
                <option value="">Seleccione un producto</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="contenedor-inputs">
            <div class="mb-3 input-group">
                <input type="file" name="imagenes[]" accept="image/*" class="form-control" required>
            </div>
        </div>

        <div class="mb-3 d-flex gap-2">
            <button type="button" class="btn btn-success" id="btn-agregar">
                <i class="bi bi-plus-circle"></i>
            </button>
            <button type="button" class="btn btn-danger" id="btn-quitar">
                <i class="bi bi-dash-circle"></i>
            </button>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-upload"></i> Subir Imágenes
        </button>
    </form>
</div>
              
<script>
// Agregar nuevo input file
document.getElementById('btn-agregar').addEventListener('click', function () {
    const contenedor = document.getElementById('contenedor-inputs');
    const nuevoInput = document.createElement('div');
    nuevoInput.className = 'mb-3 input-group';
    nuevoInput.innerHTML = `
        <input type="file" name="imagenes[]" accept="image/*" class="form-control" required>
    `;
    contenedor.appendChild(nuevoInput);
});

// Quitar último input file (si hay más de uno)
document.getElementById('btn-quitar').addEventListener('click', function () {
    const contenedor = document.getElementById('contenedor-inputs');
    const inputs = contenedor.querySelectorAll('.input-group');
    if (inputs.length > 1) {
        contenedor.removeChild(inputs[inputs.length - 1]);
    }
});
</script>

<?php include_once("../includes/footer.php"); ?>
