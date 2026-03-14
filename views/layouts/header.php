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
    <link rel="stylesheet" href="<?= urlsite ?>public/css/dashboard.css">
    <link rel="stylesheet" href="<?= urlsite ?>public/css/footer.css">
    <!--  -->

    <?php if (isset($page) && $page == "home"): ?>
        <link rel="stylesheet" href="<?= urlsite ?>public/css/home.css">
    <?php endif; ?>

    <?php if (isset($page) && $page == "login"): ?>
        <link rel="stylesheet" href="<?= urlsite ?>public/css/auth.css">
    <?php endif; ?>


</head>

<body>