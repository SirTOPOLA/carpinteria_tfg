<?php
// Conexión
require_once "config/conexion.php";

// Verifica si ya hay usuarios
$sql = "SELECT COUNT(*) FROM usuarios";
$stmt = $pdo->query($sql);
$hay_usuarios = $stmt->fetchColumn() > 0;

/* if ($hay_usuarios) {
    // Redirigir al login si ya existe al menos un usuario
    header("Location: login.php");
    exit;
} */
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Inicial</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-label i {
      color: #0d6efd;
      margin-right: 6px;
    }
    .form-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #0d6efd;
    }
    .form-control {
      border-radius: 0.5rem;
    }
    .btn-primary {
      border-radius: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card p-4">
          <h2 class="form-title mb-4 text-center"><i class="bi bi-person-plus-fill"></i> Registro Inicial de Usuario</h2>
          <form action="guardar_usuario_inicial.php" method="POST" novalidate>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label"><i class="bi bi-person-fill"></i> Nombre completo</label>
                <input type="text" name="nombre" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="bi bi-envelope-fill"></i> Correo electrónico</label>
                <input type="email" name="correo" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="bi bi-lock-fill"></i> Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="bi bi-lock-fill"></i> Confirmar contraseña</label>
                <input type="password" name="confirmar_contrasena" class="form-control" required>
              </div>
              <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary w-100">
                  <i class="bi bi-check-circle-fill"></i> Registrar usuario inicial
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
