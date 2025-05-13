<?php
 

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: index.php?vista=clientes");
    exit;
}

$errores = [];

// Obtener datos actuales del cliente
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    header("Location: index.php?vista=clientes");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // Validaciones
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }

    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE clientes SET nombre = :nombre, correo = :correo, telefono = :telefono, direccion = :direccion
                WHERE id = :id
            ");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                header("Location: index.php?vista=clientes&exito=1");
                exit;
            } else {
                $errores[] = "Error al actualizar los datos.";
            }
        } catch (PDOException $e) {
            $errores[] = "Error: " . $e->getMessage();
        }
    }
}
?>
 
   <!-- Contenido -->
   <div id="content"  class="container-fluid py-4">
        <div class="col-md-7">
            <h4 class="mb-4">Editar Cliente</h4>

           

            <form id="form" method="POST" novalidate>
                <div class="row"> 
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control"
                        value="<?= htmlspecialchars($cliente['email']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control"
                        value="<?= htmlspecialchars($cliente['telefono']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea name="direccion" id="direccion"
                        class="form-control"><?= htmlspecialchars($cliente['direccion']) ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?vista=clientes" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
                </div>
            </form>
        </div>
    </div>

 