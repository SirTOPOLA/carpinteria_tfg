<?php
// Obtener imagen para hero aleatoriamente
try {
  
  $stmt = $pdo->prepare("SELECT ruta_imagen FROM imagenes_producto ORDER BY RAND() LIMIT 1");
  $stmt->execute();
  $heroImg = $stmt->fetchColumn();
  $heroRuta = $heroImg ? "api/" . $heroImg : "img/hero-default.jpg";
} catch (PDOException $e) {
  $heroRuta = "img/hero-default.jpg";
}

 
try { 
  $sql = "
    SELECT 
      e.nombre,
      e.apellido,
      e.genero,
      u.perfil,
      r.nombre AS rol
    FROM usuarios u
    INNER JOIN empleados e ON u.empleado_id = e.id
    INNER JOIN roles r ON u.rol_id = r.id
    WHERE u.activo = 1
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $equipo = $stmt->fetchAll(PDO::FETCH_ASSOC);

  
} catch (PDOException $e) {
  echo "<div class='alert alert-danger'>Error al cargar equipo: " . $e->getMessage() . "</div>";
}
 
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Carpintería sixboku</title>
  <!-- Bootstrap 5.3 CSS -->
  <!-- Bootstrap Icons -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


  <!-- para las animaciones  -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">



  <style>
    body { 
      background-color: #f9f7f6;
      color: #444;
      font-family: 'Segoe UI', sans-serif;

    }

    .object-fit-cover {
      object-fit: cover;
    }

    .navbar {
      background-color: #4E342E;
    }

    .navbar-brand,
    .nav-link {
      color: #fff !important;
    }

    .nav-link:hover {
      color: #FFD54F !important;
    }

    .btn-main {
      background-color: #795548;
      color: white;
      border: none;
    }

    .btn-main:hover {
      background-color: #5d4037;
    }

    /* .hero {
      background: url('<?= htmlspecialchars($heroRuta) ?>') no-repeat center center;
      background-size: cover;
      padding: 120px 0;
      color: #fff;
      text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7);
    } */

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
    }

    .hero p {
      font-size: 1.25rem;
      font-weight: 300;
    }

    .object-fit-cover {
      object-fit: cover;
    }
  </style>
</head>

<body>

 <!-- Navbar fija arriba -->
<nav class="navbar navbar-expand-lg navbar-dark  fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="bi bi-house-door-fill me-1"></i> Carpintería
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="index.php?vista=inicio"><i class="bi bi-house-door"></i> Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?vista=nosotro"><i class="bi bi-people"></i> Nosotros</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?vista=producto"><i class="bi bi-box-seam"></i> Catálogo</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?vista=contacto"><i class="bi bi-envelope"></i> Contacto</a></li>
        <li class="nav-item ms-2">
          <a class="btn btn-sm btn-outline-light" href="login.php"><i class="bi bi-person-circle"></i> Acceder</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
