<?php

require '../config/conexion.php';
header('Content-Type: application/json');

//funcion para singularizar
function singularizar($palabra) {
  // Reglas simples para plural en español
  $reglas = [
      '/ces$/' => 'z',     // peces → pez
      '/es$/' => '',       // papeles → papel
      '/s$/' => '',        // árboles → árbol
  ];
  foreach ($reglas as $patron => $reemplazo) {
      if (preg_match($patron, $palabra)) {
          return preg_replace($patron, $reemplazo, $palabra);
      }
  }
  return $palabra;
}

// Función para normalizar texto
function normalizarTexto($cadena) {
    $cadena = mb_strtolower(trim($cadena), 'UTF-8');
    $cadena = preg_replace('/[áàäâ]/u', 'a', $cadena);
    $cadena = preg_replace('/[éèëê]/u', 'e', $cadena);
    $cadena = preg_replace('/[íìïî]/u', 'i', $cadena);
    $cadena = preg_replace('/[óòöô]/u', 'o', $cadena);
    $cadena = preg_replace('/[úùüû]/u', 'u', $cadena);
    $cadena = preg_replace('/[^a-z0-9\s]/u', '', $cadena);
    $cadena = preg_replace('/[^a-z0-9\s]/', '', $cadena); // quita caracteres raros
    $palabras = preg_split('/\s+/', $cadena); 
    // Singularizar y eliminar duplicados
    $reducido = array_unique(array_map('singularizar', $palabras));
    sort($reducido); // para orden consistente
    return implode(' ', $reducido); // cadena canónica
}

// Validar existencia de datos esperados
if (!isset($_POST['nombre'], $_POST['precio_base'], $_POST['unidad'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}


$nombre = trim($_POST['nombre']);
$nombre_normalizado = normalizarTexto($nombre);

$descripcion = trim($_POST['descripcion'] ?? '');
$unidad = trim($_POST['unidad']);
$activo = (isset($_POST['activo']) && $_POST['activo'] === '1') ? 1 : 0;

// Validación robusta de precio_base
if (!is_numeric($_POST['precio_base']) || floatval($_POST['precio_base']) < 0) {
    echo json_encode(['success' => false, 'message' => 'El precio base debe ser un número válido y positivo.']);
    exit;
}
$precio_base = floatval($_POST['precio_base']);

// Validar longitud del campo descripción (TEXT = hasta 65,535 caracteres, pero limita razonablemente)
if (strlen($descripcion) > 1000) {
    echo json_encode(['success' => false, 'message' => 'La descripción es demasiado larga (máx. 1000 caracteres recomendados).']);
    exit;
}

try {
    // Buscar duplicados con comparación normalizada
    $stmt = $pdo->prepare("SELECT id, nombre FROM servicios");
    $stmt->execute();
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($servicios as $servicio) {
        $nombreExistente = normalizarTexto($servicio['nombre']);
        if ($nombreExistente === $nombre_normalizado) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un servicio con un nombre similar.']);
            exit;
        }
    }

    // Insertar el nuevo servicio
    $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, precio_base, unidad, activo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $precio_base, $unidad, $activo]);

    echo json_encode(['success' => true, 'message' => 'Servicio registrado correctamente.']);
} catch (PDOException $e) {
    // Log real para desarrolladores (opcional, no enviar al cliente)
   // error_log("Error al guardar servicio: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error inesperado al guardar el servicio.']);
}








/* <?php
require '../config/conexion.php';

header('Content-Type: application/json');

// Validar si vienen los datos esperados desde $_POST
if (!isset($_POST['nombre'], $_POST['precio_base'], $_POST['unidad'])) {
  echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
  exit;
}

$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion'] ?? '');
$precio_base = floatval($_POST['precio_base']);
$unidad = trim($_POST['unidad']);
$activo = isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : 0;

try {
  $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, precio_base, unidad, activo) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$nombre, $descripcion, $precio_base, $unidad, $activo]);
  echo json_encode(['success' => true, 'message' => 'Servicio registrado correctamente.']);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Error al guardar el servicio.']);
} */
