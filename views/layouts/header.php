<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        <?= $title ?? "SIXBOKU - Sistema de Gestión de Carpintería" ?>
    </title>

    <meta name="description"
        content="Sistema de gestión profesional para carpinterías. Control de clientes, proyectos, materiales y finanzas.">

    <meta name="author" content="SIXBOKU">

    <meta name="robots" content="index, follow">


    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= urlsite ?>public/img/favicon.png">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= urlsite ?>public/css/bootstrap.min.css">


    <!-- Bootstrap Icons -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="<?= urlsite ?>public/css/main.css">

    
    <!-- CSS VISTAS -->
    
    <link rel="stylesheet" href="<?= urlsite ?>public/css/footer.css">
    <!--  -->

    <?php if (isset($page) && $page == "home"): ?>
        <link rel="stylesheet" href="<?= urlsite ?>public/css/home.css">
    <?php endif; ?>

    <?php if (isset($page) && $page == "login"): ?>
        <link rel="stylesheet" href="<?= urlsite ?>public/css/auth.css">
    <?php endif; ?>
    <style>
       :root {
  --bs-primary: #d97706;
  /* Ámbar Madera (Kiyosaki: Activo) */
  --bs-dark: #0f172a;
  /* Slate 900 (Hill: Enfoque) */
  --sidebar-width: 280px;
}

body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background-color: #f1f5f9;
}

/* Sidebar Styling */
.sidebar {
  width: var(--sidebar-width);
  height: 100vh;
  position: fixed;
  background: var(--bs-dark);
  z-index: 1000;
  overflow-y: auto;
}

/* Botón Cerrar Sesión (Esq. Superior Izquierda) */
.btn-logout {
  background: rgba(220, 38, 38, 0.1);
  color: #f87171;
  border: 1px solid rgba(220, 38, 38, 0.2);
  font-size: 0.75rem;
  padding: 5px 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: 0.3s;
}

.btn-logout:hover {
  background: #dc2626;
  color: white;
}

.nav-category {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #64748b;
  padding: 20px 25px 5px;
  font-weight: 800;
}

.nav-link {
  color: #94a3b8;
  padding: 10px 25px;
  display: flex;
  align-items: center;
  transition: 0.2s;
  font-size: 0.9rem;
}

.nav-link:hover,
.nav-link.active {
  color: #fff;
  background: rgba(217, 119, 6, 0.1);
}

.nav-link.active {
  color: var(--bs-primary);
  border-right: 4px solid var(--bs-primary);
  font-weight: 600;
}

.nav-link i {
  margin-right: 12px;
  font-size: 1.1rem;
}

.main-content {
  margin-left: var(--sidebar-width);
  min-height: 100vh;
}

/* Estilos de Tabla y Cards */
.card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
    </style>

</head>

<body>


<!-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Carpintería - CEO Ready</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
       :root {
  --bs-primary: #d97706;
  /* Ámbar Madera (Kiyosaki: Activo) */
  --bs-dark: #0f172a;
  /* Slate 900 (Hill: Enfoque) */
  --sidebar-width: 280px;
}

body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background-color: #f1f5f9;
}

/* Sidebar Styling */
.sidebar {
  width: var(--sidebar-width);
  height: 100vh;
  position: fixed;
  background: var(--bs-dark);
  z-index: 1000;
  overflow-y: auto;
}

/* Botón Cerrar Sesión (Esq. Superior Izquierda) */
.btn-logout {
  background: rgba(220, 38, 38, 0.1);
  color: #f87171;
  border: 1px solid rgba(220, 38, 38, 0.2);
  font-size: 0.75rem;
  padding: 5px 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: 0.3s;
}

.btn-logout:hover {
  background: #dc2626;
  color: white;
}

.nav-category {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #64748b;
  padding: 20px 25px 5px;
  font-weight: 800;
}

.nav-link {
  color: #94a3b8;
  padding: 10px 25px;
  display: flex;
  align-items: center;
  transition: 0.2s;
  font-size: 0.9rem;
}

.nav-link:hover,
.nav-link.active {
  color: #fff;
  background: rgba(217, 119, 6, 0.1);
}

.nav-link.active {
  color: var(--bs-primary);
  border-right: 4px solid var(--bs-primary);
  font-weight: 600;
}

.nav-link i {
  margin-right: 12px;
  font-size: 1.1rem;
}

.main-content {
  margin-left: var(--sidebar-width);
  min-height: 100vh;
}

/* Estilos de Tabla y Cards */
.card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
    </style>
</head>

<body>
 -->