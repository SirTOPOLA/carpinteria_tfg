<?php
 
 // Verificar si la sesión ya está iniciada
 if (session_status() == PHP_SESSION_NONE) {
     // Si la sesión no está iniciada, se inicia
     session_start();
 }  
 
require_once 'config/conexion.php';
require_once 'auth/permisos.php'; 
require_once 'auth/auth.php'; 
require_once 'components/alerta.php'; 
 


$publicas = ['inicio','producto','contacto', 'nosotro'];  // vistas públicas

$vista = $_GET['vista'] ?? 'dashboard';

if (in_array($vista, $publicas)) {
    // --- PÚBLICO ---
    include 'layouts/public/header.php';
    include "views/public/{$vista}.php";
    include 'layouts/public/footer.php';
} else {
    // --- PRIVADO ---
    verificarAcceso($vista);
    include 'layouts/private/header.php';
      include 'layouts/private/navbar.php';
    include 'layouts/private/sidebar.php';
    include "views/private/{$vista}.php";
    include 'layouts/private/footer.php';
}



 
 