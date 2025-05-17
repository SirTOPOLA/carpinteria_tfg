<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
    exit;
}

// Subida de archivos
$uploadDir = 'uploads/configuracion/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

function subirArchivo($inputName, $prefix) {
    global $uploadDir;
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return null;

    $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
    $filename = "{$prefix}_" . time() . '.' . $ext;
    $ruta = $uploadDir . $filename;

    return move_uploaded_file($_FILES[$inputName]['tmp_name'], $ruta) ? $ruta : null;
}

$logo   = subirArchivo('logo', 'logo');
$imagen = subirArchivo('imagen', 'imagen');

// Lista de campos permitidos
$campos = ['nombre_empresa', 'direccion', 'telefono', 'correo', 'iva', 'moneda', 'mision', 'vision', 'historia'];

$datosActualizados = [];
$params = [];

foreach ($campos as $campo) {
    if (isset($_POST[$campo]) && trim($_POST[$campo]) !== '') {
        // Validaciones específicas
        if ($campo === 'correo' && !filter_var($_POST[$campo], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => false, 'message' => 'Correo electrónico no válido.']);
            exit;
        }
        if ($campo === 'iva' && (!is_numeric($_POST[$campo]) || $_POST[$campo] < 0 || $_POST[$campo] > 100)) {
            echo json_encode(['status' => false, 'message' => 'IVA debe estar entre 0 y 100.']);
            exit;
        }

        $datosActualizados[$campo] = trim($_POST[$campo]);
        $params[":$campo"] = $datosActualizados[$campo];
    }
}

// Agregar archivos si fueron subidos
if ($logo) {
    $datosActualizados['logo'] = $logo;
    $params[':logo'] = $logo;
}
if ($imagen) {
    $datosActualizados['imagen'] = $imagen;
    $params[':imagen'] = $imagen;
}

try {
    $pdo->beginTransaction();

    $existe = $pdo->query("SELECT COUNT(*) FROM configuracion WHERE id = 1")->fetchColumn() > 0;

    if ($existe) {
        // Construir UPDATE dinámico
        if (empty($datosActualizados)) {
            echo json_encode(['status' => false, 'message' => 'No se proporcionaron campos para actualizar.']);
            exit;
        }

        $setParts = [];
        foreach ($datosActualizados as $campo => $valor) {
            $setParts[] = "$campo = :$campo";
        }

        $sql = "UPDATE configuracion SET " . implode(', ', $setParts) . " WHERE id = 1";
        $stmt = $pdo->prepare($sql);

    } else {
        // Verificar que existan todos los campos obligatorios
        $camposRequeridos = ['nombre_empresa', 'direccion', 'telefono', 'correo', 'iva', 'moneda'];
        foreach ($camposRequeridos as $req) {
            if (!isset($datosActualizados[$req])) {
                echo json_encode(['status' => false, 'message' => "El campo $req es obligatorio para el registro inicial."]);
                exit;
            }
        }

        $sql = "INSERT INTO configuracion 
                (id, " . implode(", ", array_keys($datosActualizados)) . ") 
                VALUES (1, " . implode(", ", array_map(fn($c) => ":$c", array_keys($datosActualizados))) . ")";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute($params);
    $pdo->commit();

    echo json_encode(['status' => true, 'message' => 'Configuración guardada correctamente.']);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
