<?php
require_once '../config/conexion.php'; // Ajusta según tu ruta

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre_empresa'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $moneda = trim($_POST['moneda'] ?? '');
    $iva = floatval($_POST['iva'] ?? 0);
    $mision = trim($_POST['mision'] ?? '');
    $vision = trim($_POST['vision'] ?? '');
    $historia = trim($_POST['historia'] ?? '');

    // Validación mínima (puedes expandirla)
    if ($id <= 0 || empty($nombre) || empty($correo)) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Datos incompletos']);
        exit;
    }

    // Procesar el logo si se subió
// Obtener ruta del logo actual desde la base de datos
$stmtLogo = $pdo->prepare("SELECT logo FROM configuracion WHERE id = ?");
$stmtLogo->execute([$id]);
$configActual = $stmtLogo->fetch(PDO::FETCH_ASSOC);
$logo_anterior = $configActual['logo'] ?? null;

$logo_ruta = null;
if (!empty($_FILES['logo']['name'])) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    if (in_array($_FILES['logo']['type'], $permitidos)) {
        // Eliminar logo anterior si existe
        if ($logo_anterior && file_exists($logo_anterior)) {
            unlink($logo_anterior); // elimina el archivo
        }

        $nombreArchivo = 'logo_' . uniqid() . '_' . basename($_FILES['logo']['name']);
        $rutaDestino = 'uploads/configuracion/' . $nombreArchivo;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $rutaDestino)) {
            $logo_ruta = $rutaDestino;
        }
    }
}


    // Actualizar en la base de datos
    $sql = "UPDATE configuracion SET 
                nombre_empresa = ?, direccion = ?, telefono = ?, correo = ?, 
                moneda = ?, iva = ?, mision = ?, vision = ?, historia = ?";

    if ($logo_ruta) {
        $sql .= ", logo = ?";
    }

    $sql .= " WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $params = [$nombre, $direccion, $telefono, $correo, $moneda, $iva, $mision, $vision, $historia];
    if ($logo_ruta) {
        $params[] = $logo_ruta;
    }
    $params[] = $id;

    if ($stmt->execute($params)) {
        echo json_encode(['status' => 'ok', 'mensaje' => 'Configuración actualizada correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al actualizar']);
    }
}
