<?php
// app/config/app.php
declare(strict_types=1);

// Mejor iniciar la sesión con configuración segura
ini_set('session.use_strict_mode', '1');
session_name('carpinteria_session');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',      // ajustar dominio si procede
    'secure' => isset($_SERVER['HTTPS']), // true en HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers de seguridad básicos
header_remove('X-Powered-By'); // quitar info del servidor
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
