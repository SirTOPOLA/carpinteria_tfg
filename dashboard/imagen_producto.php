<?php
require_once("../includes/conexion.php");

// Obtener imágenes con su producto
$sql = "SELECT ip.id, ip.ruta_imagen, p.nombre AS producto_nombre 
        FROM imagenes_producto ip
        JOIN productos p ON ip.producto_id = p.id
        ORDER BY ip.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h4>Listado de Imágenes de Productos</h4>

            <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] == 1): ?>
                <div class="alert alert-success">Imagen eliminada correctamente.</div>
            <?php endif; ?>

            <div class="row">
                <?php if (count($imagenes) > 0): ?>
                    <?php foreach ($imagenes as $img): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="<?= htmlspecialchars($img['ruta_imagen']) ?>" class="card-img-top img-fluid"
                                    style="max-height: 200px; object-fit: contain;" alt="Imagen">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($img['producto_nombre']) ?></h5>
                                    <form action="eliminar_imagen.php" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar esta imagen?');">
                                        <input type="hidden" name="id" value="<?= $img['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">No hay imágenes registradas.</div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="d-flex justify-content-between">
                <a href="productos.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i>
                        <a href="registrar_imagen_producto.php" class="btn btn-primary">Subir nueva imagen</a>
                </button>
            </div>
        </div> 
    </div>
</div>
 
<?php include_once("../includes/footer.php"); ?>