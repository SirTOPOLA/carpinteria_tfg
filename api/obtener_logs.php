<?php
// leer_logs.php
header("Content-Type: application/json");

$log_file =  "logs.txt";

if (!file_exists($log_file)) {
    echo json_encode(["success" => false, "mensajes" => []]);
    exit;
}

$lineas = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$mensajes = array_reverse($lineas); // Mostramos los más recientes primero

echo json_encode([
    "success" => true,
    "mensajes" => $mensajes
]);


