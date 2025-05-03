<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Carpintería</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      overflow: hidden;
    }

    .sidebar {
      width: 200px;
      background-color: #343a40;
    }

    .sidebar a {
      color: #ffffff;
    }

    .sidebar .nav-link.active {
      background-color: #495057;
    }

    @media (max-width: 768px) {
      .sidebar-overlay {
        position: fixed;
        z-index: 1050;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: none;
      }

      .sidebar.mobile-show {
        display: block !important;
        z-index: 1060;
        height: 100vh;
        overflow-y: auto;
      }

      main {
        margin-left: 0px;
        "

      }
    }

    @media (min-width: 768px) {
      main {
        margin-left: 200px;
        "

      }

    }

    @media print {
      body * {
        visibility: hidden;
      }

      .container,
      .container * {
        visibility: visible;
      }

      .container {
        margin: 0;
        padding: 0;
      }

      .btn,
      nav,
      .no-print {
        display: none !important;
      }
    }
  </style>

</head>

<body class="d-flex flex-column vh-100">