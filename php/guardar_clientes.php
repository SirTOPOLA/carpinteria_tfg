<?php
require_once '../includes/conexion.php';

  // Generar código de acceso con datos del usuario
  function generarCodigoAcceso($nombre, $dip, $direccion)
  {
      // 1. Primera letra del nombre
      $letraNombre = strtoupper(substr(trim($nombre), 0, 2));

      // 2. Últimos 2 dígitos del DIP
      $dipNumeros = preg_replace('/\D/', '', $dip);
      $ultimosDip = substr($dipNumeros, -2);

      // 3. Primeras 2 letras de la dirección
      $letrasDireccion = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $direccion), 0, 3));

      // 4. Un carácter aleatorio alfanumérico
      $caracterAleatorio = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3));

      // 5. Combinar
      $codigo = $letraNombre . $ultimosDip . $letrasDireccion . $caracterAleatorio;

      return str_pad($codigo, 9, 'X'); // Asegura longitud
  }



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $dip = trim($_POST['codigo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    if ($nombre === '') {
        die('El nombre es obligatorio.');
    }

    $codigo_acceso = generarCodigoAcceso($_POST['nombre'], $_POST['codigo'], $_POST['direccion']);


    try {
        $sql = "INSERT INTO clientes (nombre, email, codigo, codigo_acceso, telefono, direccion) 
                VALUES (:nombre, :correo, :dip, :codigo_acceso, :telefono, :direccion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo ?: null,
            ':dip' => $dip,
            ':codigo_acceso' => $codigo_acceso,
            ':telefono' => $telefono,
            ':direccion' => $direccion
        ]);

        header("Location: ../dashboard/clientes.php");
        exit;
    } catch (PDOException $e) {
        die("Error al guardar el cliente: " . $e->getMessage());
    }
} else {
    die("Acceso no permitido.");
}
