<?php

 
$sql = "SELECT 
u.*,
r.nombre AS rol,
e.nombre AS empleado_nombre,
e.apellido AS empleado_apellido
FROM usuarios u
LEFT JOIN roles r ON u.rol_id = r.id
LEFT JOIN empleados e ON u.empleado_id = e.id
";


$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
