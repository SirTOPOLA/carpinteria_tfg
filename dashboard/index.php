<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      transition: all 0.3s ease;
      width: 250px;
      background-color: #343a40;
      color: #fff;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
    .sidebar.collapsed {
      width: 80px;
      background-color: #1d2124;
    }
    .sidebar .nav-link {
      color: #adb5bd;
      transition: all 0.2s;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: rgba(255, 255, 255, 0.1);
      color: #fff;
    }
    .sidebar .nav-link i {
      font-size: 1.2rem;
    }
    .sidebar.collapsed .nav-link span,
    .sidebar.collapsed h5 {
      display: none;
    }
    #content {
      transition: margin-left 0.3s ease;
      margin-left: 250px;
    }
    #content.collapsed {
      margin-left: 80px;
    }
    .card {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .navbar {
      background-color: #495057 !important;
      color: #fff;
    }
    .navbar .navbar-brand,
    .navbar .bi {
      color: #fff;
    }
    .collapse-icon {
      transition: transform 0.3s ease;
    }
    .collapsed .collapse-icon {
      transform: rotate(-90deg);
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #fff;
    }
    .user-info img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar position-fixed h-100 p-3">
      <h5 class="mb-4"><i class="bi bi-hammer me-2"></i>Menú</h5>
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a href="#" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i><span>Dashboard</span></a>
        </li>
        <li class="nav-item">
          <a href="#usuariosSubmenu" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-people me-2"></i><span>Usuarios</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="usuariosSubmenu">
            <li><a href="#" class="nav-link"><i class="bi bi-person me-2"></i><span>Usuarios</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-person-badge me-2"></i><span>Empleados</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-shield-lock me-2"></i><span>Roles</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#clientesSubmenu" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-people-fill me-2"></i><span>Clientes</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="clientesSubmenu">
            <li><a href="#" class="nav-link"><i class="bi bi-person-check me-2"></i><span>Clientes</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-person-workspace me-2"></i><span>Proveedores</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#materialesSubmenu" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-box-seam me-2"></i><span>Materiales</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="materialesSubmenu">
            <li><a href="#" class="nav-link"><i class="bi bi-archive me-2"></i><span>Inventario</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-arrow-left-right me-2"></i><span>Movimientos</span></a></li>
          </ul>
        </li>
      </ul>
    </div>

    <!-- Contenido principal -->
    <div id="content" class="flex-grow-1">
      <!-- Topbar -->
      <nav class="navbar navbar-expand navbar-dark shadow-sm px-3">
        <button id="toggleSidebar" class="btn btn-outline-light me-3">
          <i class="bi bi-list"></i>
        </button>
        <span class="navbar-brand mb-0 h5"><i class="bi bi-hammer me-2"></i>Panel Carpintería</span>
        <div class="ms-auto user-info">
          <img src="https://via.placeholder.com/32" alt="Avatar usuario">
          <div>
            <div class="fw-bold">Juan Pérez</div>
            <small>Administrador</small>
          </div>
          <button class="btn btn-sm btn-outline-light ms-2"><i class="bi bi-box-arrow-right">Salir</i></button>
        </div>
      </nav>

      <!-- Contenido -->
      <div class="container-fluid py-4">
        <div class="row g-4">
          <div class="col-md-6 col-xl-3">
            <div class="card border-start border-primary border-4">
              <div class="card-body">
                <h6 class="text-muted">Usuarios activos</h6>
                <h4 class="fw-bold">1,245</h4>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-3">
            <div class="card border-start border-success border-4">
              <div class="card-body">
                <h6 class="text-muted">Ventas</h6>
                <h4 class="fw-bold">$9,870</h4>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-3">
            <div class="card border-start border-warning border-4">
              <div class="card-body">
                <h6 class="text-muted">Tickets</h6>
                <h4 class="fw-bold">36</h4>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-3">
            <div class="card border-start border-danger border-4">
              <div class="card-body">
                <h6 class="text-muted">Pendientes</h6>
                <h4 class="fw-bold">7</h4>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById("toggleSidebar").addEventListener("click", function () {
      document.getElementById("sidebar").classList.toggle("collapsed");
      document.getElementById("content").classList.toggle("collapsed");
    });
  </script>
</body>
</html>
