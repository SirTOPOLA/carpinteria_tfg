<?php
// config/database.php


// dashboard.php

// Consultas para el dashboard
try {
    // Total de producciones
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM producciones");
    $stmt->execute();
    $total_producciones = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Producciones activas
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as activas 
        FROM producciones p 
        INNER JOIN estados e ON p.estado_id = e.id 
        WHERE e.nombre IN ('En Proceso', 'Iniciado', 'Activo')
    ");
    $stmt->execute();
    $producciones_activas = $stmt->fetch(PDO::FETCH_ASSOC)['activas'];

    // Total empleados
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM empleados");
    $stmt->execute();
    $total_empleados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Materiales con stock bajo
    $stmt = $pdo->prepare("SELECT COUNT(*) as bajo_stock FROM materiales WHERE stock_actual <= stock_minimo");
    $stmt->execute();
    $materiales_bajo_stock = $stmt->fetch(PDO::FETCH_ASSOC)['bajo_stock'];

    // Producciones recientes
    $stmt = $pdo->prepare("
        SELECT p.id, dp.descripcion as producto, e1.nombre as estado, 
               emp.nombre as responsable, p.fecha_inicio, p.fecha_fin
        FROM producciones p
        LEFT JOIN detalles_produccion dp ON p.id = dp.produccion_id
        LEFT JOIN estados e1 ON p.estado_id = e1.id
        LEFT JOIN empleados emp ON p.responsable_id = emp.id
        ORDER BY p.created_at DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $producciones_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Materiales críticos
    $stmt = $pdo->prepare("
        SELECT nombre, stock_actual, stock_minimo, unidad_medida
        FROM materiales 
        WHERE stock_actual <= stock_minimo 
        ORDER BY (stock_actual - stock_minimo) ASC
        LIMIT 5
    ");
    $stmt->execute();
    $materiales_criticos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tareas pendientes
    $stmt = $pdo->prepare("
        SELECT t.descripcion, emp.nombre as responsable, p.id as produccion_id,
               e.nombre as estado, t.fecha_fin
        FROM tareas_produccion t
        LEFT JOIN empleados emp ON t.responsable_id = emp.id
        LEFT JOIN producciones p ON t.produccion_id = p.id
        LEFT JOIN estados e ON t.estado_id = e.id
        WHERE e.nombre IN ('Pendiente', 'En Proceso')
        ORDER BY t.fecha_fin ASC
        LIMIT 5
    ");
    $stmt->execute();
    $tareas_pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $exception) {
    echo "Error en consulta: " . $exception->getMessage();
}
?>


<!-- Navbar -->
<!--  <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-hammer me-2"></i>
                CarpinteríaPro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            Usuario
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav> -->

<div id="content" class="container-fluid">

    <div class="p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 text-dark fw-bold">Dashboard</h2>
            <div class="text-muted">
                <i class="fas fa-calendar-alt me-1"></i>
                <?php echo date('d/m/Y H:i'); ?>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(45deg, #28a745, #20c997);">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3 class="stat-number"><?php echo $total_producciones; ?></h3>
                    <p class="stat-label">Total Producciones</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(45deg, #17a2b8, #6f42c1);">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <h3 class="stat-number"><?php echo $producciones_activas; ?></h3>
                    <p class="stat-label">En Proceso</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(45deg, #fd7e14, #e83e8c);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="stat-number"><?php echo $total_empleados; ?></h3>
                    <p class="stat-label">Empleados</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(45deg, #dc3545, #fd7e14);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="stat-number"><?php echo $materiales_bajo_stock; ?></h3>
                    <p class="stat-label">Stock Crítico</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Producciones Recientes -->
            <div class="col-lg-8">
                <div class="table-container">
                    <h5 class="table-title">
                        <i class="fas fa-industry text-primary"></i>
                        Producciones Recientes
                    </h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Responsable</th>
                                    <th>Estado</th>
                                    <th>Fecha Fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($producciones_recientes)): ?>
                                    <?php foreach ($producciones_recientes as $produccion): ?>
                                        <tr>
                                            <td><strong>#<?php echo $produccion['id']; ?></strong></td>
                                            <td><?php echo $produccion['producto'] ?: 'Sin especificar'; ?></td>
                                            <td><?php echo $produccion['responsable'] ?: 'Sin asignar'; ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?php echo $produccion['estado'] ?: 'Sin estado'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                if ($produccion['fecha_fin']) {
                                                    echo date('d/m/Y', strtotime($produccion['fecha_fin']));
                                                } else {
                                                    echo 'Sin fecha';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No hay producciones registradas
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <!-- Materiales Críticos -->
                <div class="table-container">
                    <h5 class="table-title">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        Materiales Críticos
                    </h5>
                    <?php if (!empty($materiales_criticos)): ?>
                        <?php foreach ($materiales_criticos as $material): ?>
                            <div
                                class="d-flex justify-content-between align-items-center mb-3 p-2 border-start border-warning border-3">
                                <div>
                                    <strong><?php echo $material['nombre']; ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        Stock: <?php echo $material['stock_actual']; ?>
                                        <?php echo $material['unidad_medida']; ?>
                                    </small>
                                </div>
                                <span class="badge bg-warning">Crítico</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Todos los materiales tienen stock suficiente
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tareas Pendientes -->
                <div class="table-container">
                    <h5 class="table-title">
                        <i class="fas fa-tasks text-info"></i>
                        Tareas Pendientes
                    </h5>
                    <?php if (!empty($tareas_pendientes)): ?>
                        <?php foreach ($tareas_pendientes as $tarea): ?>
                            <div class="mb-3 p-2 border-start border-info border-3">
                                <strong><?php echo substr($tarea['descripcion'], 0, 50) . '...'; ?></strong>
                                <br>
                                <small class="text-muted">
                                    Responsable: <?php echo $tarea['responsable'] ?: 'Sin asignar'; ?>
                                </small>
                                <br>
                                <small class="text-muted">
                                    Vence:
                                    <?php echo $tarea['fecha_fin'] ? date('d/m/Y', strtotime($tarea['fecha_fin'])) : 'Sin fecha'; ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            No hay tareas pendientes
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Actualizar fecha y hora cada minuto
    setInterval(function () {
        location.reload();
    }, 60000);

    // Animación de entrada para las tarjetas
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.stat-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
<style>
        :root {
            --primary-color: #8B4513;
            --secondary-color: #D2691E;
            --accent-color: #CD853F;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --dark-color: #2c3e50;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
        }

         
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-color);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .table-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: var(--dark-color);
            padding: 15px 12px;
        }

        .table td {
            padding: 12px;
            vertical-align: middle;
        }

        .badge {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .progress {
            height: 8px;
            border-radius: 10px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .stat-card {
                margin-bottom: 20px;
            }
        }
    </style>