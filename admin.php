<?php
require_once   'app/config/db.php';
$pwd = '123456'; // cambiar
$hash = password_hash($pwd, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO empleados (nombre, apellido) VALUES (:n,:a)");
$stmt->execute([':n'=>'Admin',':a'=>'Super']);
$id_empleado = $pdo->lastInsertId();

$stmt = $pdo->prepare("INSERT INTO usuarios (id_empleado, username, password_hash, id_rol, activo) VALUES (:ide, :u, :ph, :rol, 1)");
$stmt->execute([':ide'=>$id_empleado, ':u'=>'admin', ':ph'=>$hash, ':rol'=>1]);
echo "Admin creado\n";
