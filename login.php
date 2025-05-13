<?php
if (session_status() == PHP_SESSION_NONE) {
  // Si la sesión no está iniciada, se inicia
  session_start();
}
require_once 'config/conexion.php';
require_once 'auth/auth.php';

// Si ya está logueado, redirige
if (isset($_SESSION['usuario'])) {
  header('Location: index.php?vista=dashboard');
  exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitización básica
  $tipo = isset($_POST['tipo_usuario']) ? trim($_POST['tipo_usuario']) : 'personal';
  $email = isset($_POST['correo']) ? filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL) : '';
  $password = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
  $clienteId = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';



  // Validar tipo de usuario (opcional: según un conjunto permitido)
  $tipos_validos = ['personal', 'cliente']; // por ejemplo
  if (!in_array($tipo, $tipos_validos)) {
    $_SESSION['alerta'] = "Tipo de usuario inválido.";
  }



  // Validaciones básicas
  if ($tipo === 'cliente') {
    // Validar clienteId si el tipo es "cliente"
    if ($tipo === 'cliente' && empty($clienteId)) {
      $_SESSION['alerta'] = "Código de cliente inválido.";
    }

  } else {
    // Validar email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['alerta'] = "Correo electrónico inválido.";
    }

    // Validar contraseña (ejemplo: mínimo 6 caracteres)
    if (empty($password) || strlen($password) < 1) {
      $_SESSION['alerta'] = "La contraseña debe tener al menos 6 caracteres.";
    }
  }

  // Mostrar errores si existen
  if (!empty($errores)) {
    /* foreach ($errores as $error) {
        header('Location: login.php'); 
        exit;
    } */
    $_SESSION['alerta'] = 'hubo errores';
    exit;
  }

  // Si no hay errores, intentamos login según tipo


  if ($tipo === 'cliente') {
    // loginCliente() debe validar cliente por código
    if (loginCliente($pdo, $clienteId)) {
      header('Location: index.php');
      exit;
    } else {

      header('Location: login.php');
      exit;
    }

  } else {
    // Usuario interno (admin, operario, etc.)
    if (login($pdo, $email, $password)) {
      header('Location: index.php');
      exit;
    } else {
      // Mensaje de error ya asignado por login()
      header('Location: login.php');
      exit;
    }
  }

}


?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login – Carpinteria SIXBOKU</title>

  <!-- Bootstrap y Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="./assets/css/public/login.css">

</head>

<body>

  <div
    class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-center p-0 login-wrapper">
    <!-- Card completa en móvil y solo form en escritorio -->
    <div class="login-card d-flex flex-column flex-md-row overflow-hidden">

      <!-- Imagen -->
      <div class="login-image d-block d-md-none"></div> <!-- visible solo en móviles -->
      <div class="col-md-6 d-none d-md-block p-0">
        <div class="login-image"></div>
      </div>

      <!-- Formulario -->
      <div class="col-md-6 p-4 d-flex align-items-center justify-content-center">
        <div class="login-form">
          <h3 class="mb-4 text-center text-success fw-semibold">Bienvenido a la carpinteria <span
              class="fw-bold">SIXBOKU</span>
          </h3>
          <?php include_once('components/alerta.php') ?>


          <form method="POST" id="formLogin" novalidate>
            <div class="mb-3">
              <label for="tipo_usuario" class="form-label">Tipo de usuario</label>
              <select name="tipo_usuario" id="tipo_usuario" class="form-select">
                <option value="personal">Personal interno</option>
                <option value="cliente">Cliente</option>
              </select>
            </div>
            <!-- Campos para personal -->
            <div id="personal_fields">
              <div class="mb-4">
                <label for="correo" class="form-label fw-semibold">Usuario</label>
                <div class="input-group shadow-sm rounded">
                  <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-success"></i></span>
                  <input type="email" id="usuario" name="correo" class="form-control border-start-0"
                    placeholder="Ingrese su usuario" required>
                </div>
              </div>
              <div class="mb-4">
                <label for="contrasena" class="form-label fw-semibold">Contraseña</label>
                <div class="input-group shadow-sm rounded">
                  <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-success"></i></span>
                  <input type="password" id="clave" name="contrasena" class="form-control border-start-0"
                    placeholder="Ingrese su contraseña" required>
                </div>
              </div>
            </div>
            <!-- Campos para cliente -->
            <div id="cliente_fields" style="display:none;">

              <div class="mb-4">
                <label for="codigo" class="form-label fw-semibold">ID de cliente</label>
                <div class="input-group shadow-sm rounded">
                  <span class="input-group-text bg-white border-end-0"><i
                      class="bi bi-person-badge text-success"></i></span>
                  <input type="text" id="usuario" name="codigo" class="form-control border-start-0"
                    placeholder="Ingrese su codigo" required>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Acceder</button>
          </form>
          <div class="text-center mt-3">
            <a href="index.php?vista=inicio">← Volver a Inicio</a>
          </div>
        </div>
      </div>

    </div>
  </div>
  </div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const tipoSelect = document.getElementById('tipo_usuario');
    const personalFields = document.getElementById('personal_fields');
    const clienteFields = document.getElementById('cliente_fields');

    function toggleFields() {
      if (tipoSelect.value === 'cliente') {
        personalFields.style.display = 'none';
        clienteFields.style.display = 'block';
      } else {
        personalFields.style.display = 'block';
        clienteFields.style.display = 'none';
      }
    }

    tipoSelect.addEventListener('change', toggleFields);
    // Mostrar al cargar según selección previa
    window.addEventListener('DOMContentLoaded', toggleFields);
  </script>
</body>

</html>