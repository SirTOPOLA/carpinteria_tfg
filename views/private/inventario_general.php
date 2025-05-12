<?php
require_once '../includes/conexion.php';


/* simulacion de datos */
$total_usuarios = 12;
$total_clientes = 25;
$total_productos = 48;
$total_ventas = 19;


 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe General - Carpintería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container my-4">
    <h2 class="mb-4">Informe General</h2>

 
    <div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Usuarios</h5>
                <p class="card-text display-6"><?= $total_usuarios ?></p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="usuarios/usuarios.php" class="btn btn-light btn-sm">Ver más</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Clientes</h5>
                <p class="card-text display-6"><?= $total_clientes ?></p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="clientes/clientes.php" class="btn btn-light btn-sm">Ver más</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <h5 class="card-title">Productos</h5>
                <p class="card-text display-6"><?= $total_productos ?></p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="productos/productos.php" class="btn btn-light btn-sm">Ver más</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger h-100">
            <div class="card-body">
                <h5 class="card-title">Ventas</h5>
                <p class="card-text display-6"><?= $total_ventas ?></p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="ventas/ventas.php" class="btn btn-light btn-sm">Ver más</a>
            </div>
        </div>
    </div>
</div>

 
</div>

 
</body>
</html>
