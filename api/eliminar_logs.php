<?php
 
header('Content-Type: application/json');

$datos = json_decode(file_get_contents("php://input"), true);

if (!isset($datos["index"])) {
    echo json_encode(["success" => false, "msg" => "Índice no recibido."]);
    exit;
}

$index = intval($datos["index"]);
$archivo = "logs.txt";

if (!file_exists($archivo)) {
    echo json_encode(["success" => false, "msg" => "Archivo no encontrado."]);
    exit;
}

$lineas = file($archivo, FILE_IGNORE_NEW_LINES);
if ($index < 0 || $index >= count($lineas)) {
    echo json_encode(["success" => false, "msg" => "Índice fuera de rango."]);
    exit;
}

unset($lineas[$index]);
file_put_contents($archivo, implode(PHP_EOL, $lineas));

echo json_encode(["success" => true]);
