<?php
$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? ''));
if ($rol == 'administrador') {
    include_once 'dashboard_administrador.php';
}
if ($rol == 'operario') {
    include_once 'dashboard_operario.php';
}
if ($rol == 'cliente') {
    include_once 'dashboard_cliente.php';
}