<?php
require_once("../includes/conexion.php");

$errores = [];
$exito = "";

// Obtener departamentos para el select
$stmt_deptos = $pdo->query("SELECT id, nombre FROM departamentos ORDER BY nombre");
$departamentos = $stmt_deptos->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $codigo = trim($_POST['codigo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $departamento_id = is_numeric($_POST['departamento_id'] ?? '') ? (int)$_POST['departamento_id'] : null;
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
            $sql = "INSERT INTO empleados (nombre, apellido, codigo, telefono, direccion, departamento_id, email, salario, horario_trabajo, fecha_ingreso)
                    VALUES (:nombre, :apellido, :codigo, :telefono, :direccion, :departamento_id, :email, :salario, :horario_trabajo, :fecha_ingreso)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':codigo' => $codigo,
                ':telefono' => $telefono,
                ':direccion' => $direccion,
                ':departamento_id' => $departamento_id ?: null,
                ':email' => $email,
                ':salario' => $salario,
                ':horario_trabajo' => $horario_trabajo,
                ':fecha_ingreso' => $fecha_ingreso
            ]);
            $exito = "Empleado registrado correctamente.";
            header("Location: empleados.php");
    exit;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errores[] = "El código o correo ya existe.";
                header("Location: empleados.php");
    exit;
            } else {
                $errores[] = "Error al registrar el empleado: " . $e->getMessage();
           
                header("Location: empleados.php");
    exit;
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
        <h4 class="mb-3">Registrar Empleado</h4>

        <?php if (!empty($exito)): ?>
            <div class="alert alert-success"><?= $exito ?></div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Código *</label>
                <input type="text" name="codigo" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-12">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Departamento</label>
                <select name="departamento_id" class="form-select">
                    <option value="">Seleccione</option>
                    <?php foreach ($departamentos as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Salario</label>
                <input type="number" name="salario" step="0.01" min="0" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" class="form-control">
            </div>
            <div class="col-md-12">
                <label class="form-label">Horario de Trabajo</label>
                <input type="text" name="horario_trabajo" class="form-control" placeholder="Ej: Lunes a Viernes, 8am - 5pm">
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="empleados.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
