<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: row;
    }

    .sidebar {
      width: 250px;
      background-color: #343a40;
      color: white;
      padding-top: 1rem;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 0.75rem 1rem;
      display: block;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .main {
      flex-grow: 1;
      padding: 2rem;
    }

    .card {
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>





  <div id="sidebarContainer" class="d-none d-md-block">
    <?php include_once("sidebar.php"); ?>
  </div>
  <div class="container ">

    <!-- Botón para mostrar/ocultar sidebar en dispositivos pequeños -->
    <button class="btn btn-dark d-md-none mt-3" id="toggleSidebar">☰</button>

  
 