<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: empleados.php");
    exit;
}

// Obtener datos del empleado
$stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = ?");
$stmt->execute([$id]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    header("Location: empleados.php");
    exit;
}

 
// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $codigo = trim($_POST['codigo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    
    $email = trim($_POST['email'] ?? '');
    $salario = is_numeric($_POST['salario'] ?? '') ? (float)$_POST['salario'] : null;
    $horario_trabajo = trim($_POST['horario_trabajo'] ?? '');
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

    // Validaciones
    if ($nombre === '') $errores[] = "El nombre es obligatorio.";
    if ($codigo === '') $errores[] = "El código es obligatorio.";
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "El email no es válido.";
    if ($fecha_ingreso !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_ingreso)) $errores[] = "La fecha de ingreso no es válida.";

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("UPDATE empleados SET 
            nombre = :nombre,
            apellido = :apellido,
            codigo = :codigo,
            telefono = :telefono,
            direccion = :direccion, 
            email = :email,
            salario = :salario,
            horario_trabajo = :horario_trabajo,
            fecha_ingreso = :fecha_ingreso
            WHERE id = :id
        ");

        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':codigo' => $codigo,
            ':telefono' => $telefono,
            ':direccion' => $direccion, 
            ':email' => $email,
            ':salario' => $salario,
            ':horario_trabajo' => $horario_trabajo,
            ':fecha_ingreso' => $fecha_ingreso,
            ':id' => $id
        ]);
            $exito = "Empleado registrado correctamente.";
            header("location: empleados.php");
            exit();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errores[] = "El código o correo ya existe.";
                header("location: empleados.php");
                exit();
            } else {
                $errores[] = "Error al registrar el empleado: " . $e->getMessage();
                header("location: empleados.php");
                exit();
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container">
        <h4>Editar Empleado</h4>

        <form  method="POST" class="row g-3 needs-validation" novalidate>
            <input type="hidden" name="id" value="<?= $empleado['id'] ?>">

            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre *</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($empleado['nombre']) ?>" required>
            </div>

            <div class="col-md-6">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="apellido" id="apellido" class="form-control" value="<?= htmlspecialchars($empleado['apellido']) ?>">
            </div>

            <div class="col-md-4">
                <label for="codigo" class="form-label">Código *</label>
                <input type="text" name="codigo" id="codigo" class="form-control" value="<?= htmlspecialchars($empleado['codigo']) ?>" required>
            </div>

            <div class="col-md-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($empleado['telefono']) ?>">
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($empleado['email']) ?>">
            </div>

            <div class="col-md-12">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($empleado['direccion']) ?>">
            </div>

           

            <div class="col-md-3">
                <label for="salario" class="form-label">Salario</label>
                <input type="number" step="0.01" name="salario" id="salario" class="form-control" value="<?= $empleado['salario'] ?>">
            </div>

            <div class="col-md-3">
                <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="<?= $empleado['fecha_ingreso'] ?>">
            </div>

            <div class="col-md-12">
                <label for="horario_trabajo" class="form-label">Horario de Trabajo</label>
                <input type="text" name="horario_trabajo" id="horario_trabajo" class="form-control" value="<?= htmlspecialchars($empleado['horario_trabajo']) ?>">
            </div>

            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="empleados.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>


<script>
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include '../includes/footer.php'; ?>
