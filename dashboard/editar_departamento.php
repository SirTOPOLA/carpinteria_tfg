<?php
require_once("../includes/conexion.php");

$id = $_GET['id'] ?? null;
$mensaje = "";
$error = "";

// Validación de ID
if (!$id || !is_numeric($id)) {
    header("Location: departamentos.php");
    exit;
}

// Obtener los datos actuales
$stmt = $pdo->prepare("SELECT * FROM departamentos WHERE id = ?");
$stmt->execute([$id]);
$departamento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$departamento) {
    header("Location: departamentos.php");
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    // Validaciones
    if (empty($nombre)) {
        $error = "El nombre es obligatorio.";
        header("Location: departamentos.php");
        exit;
    } else {
        try {
            $sql = "UPDATE departamentos SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':id' => $id
            ]);
            $mensaje = "Departamento actualizado correctamente.";
            $departamento['nombre'] = $nombre;
            $departamento['descripcion'] = $descripcion;

            header("Location: departamentos.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Ya existe un departamento con ese nombre.";
                header("Location: departamentos.php");
                exit;
            } else {
                $error = "Error al actualizar: " . $e->getMessage();
                header("Location: departamentos.php");
                exit;
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

   <!-- Contenido -->
   <div class="container-fluid py-4">
    <div class="container">
        <h4>Editar Departamento</h4>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= $mensaje ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre *</label>
                <input type="text" name="nombre" id="nombre" class="form-control"
                    value="<?= htmlspecialchars($departamento['nombre']) ?>" required>
            </div>
            <div class="col-md-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control"
                    rows="4"><?= htmlspecialchars($departamento['descripcion']) ?></textarea>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="departamentos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>