<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Carpintería - CEO Ready</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bs-primary: #d97706; /* Ámbar Madera (Kiyosaki: Activo) */
            --bs-dark: #0f172a;    /* Slate 900 (Hill: Enfoque) */
            --sidebar-width: 280px;
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }

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

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(217, 119, 6, 0.1);
        }

        .nav-link.active {
            color: var(--bs-primary);
            border-right: 4px solid var(--bs-primary);
            font-weight: 600;
        }

        .nav-link i { margin-right: 12px; font-size: 1.1rem; }

        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }

        /* Estilos de Tabla y Cards */
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <nav class="sidebar shadow">
        <div class="p-4 border-bottom border-secondary">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button class="btn btn-logout rounded-pill">
                    <i class="bi bi-power"></i> Salir
                </button>
                <span class="badge bg-success-subtle text-success small">Online</span>
            </div>
            <h5 class="text-white fw-bold mb-0">WoodMaster <span class="text-primary">ERP</span></h5>
        </div>
        
        <div class="nav flex-column pb-5">
            <div class="nav-category">Producción y Proyectos</div>
            <a href="#" class="nav-link active"><i class="bi bi-kanban"></i> Proyectos</a>
            <a href="#" class="nav-link"><i class="bi bi-hammer"></i> Ordenes de Servicio</a>
            <a href="#" class="nav-link"><i class="bi bi-pencil-square"></i> Cotizaciones</a>
            <a href="#" class="nav-link"><i class="bi bi-palette"></i> Diseños / Archivos</a>

            <div class="nav-category">Activos e Inventario</div>
            <a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Materiales</a>
            <a href="#" class="nav-link"><i class="bi bi-arrow-left-right"></i> Movimientos Stock</a>
            <a href="#" class="nav-link"><i class="bi bi-stack"></i> Productos Terminados</a>
            <a href="#" class="nav-link"><i class="bi bi-tags"></i> Categorías</a>

            <div class="nav-category">Mente Maestra (Finanzas)</div>
            <a href="#" class="nav-link"><i class="bi bi-cash-coin"></i> Caja Diaria</a>
            <a href="#" class="nav-link"><i class="bi bi-receipt"></i> Ventas / Facturas</a>
            <a href="#" class="nav-link"><i class="bi bi-cart-check"></i> Ordenes de Compra</a>
            <a href="#" class="nav-link"><i class="bi bi-piggy-bank"></i> Gastos Operativos</a>
            <a href="#" class="nav-link"><i class="bi bi-truck"></i> Proveedores</a>

            <div class="nav-category">Capital Humano</div>
            <a href="#" class="nav-link"><i class="bi bi-people"></i> Clientes</a>
            <a href="#" class="nav-link"><i class="bi bi-person-badge"></i> Empleados</a>
            <a href="#" class="nav-link"><i class="bi bi-stopwatch"></i> Mano de Obra</a>

            <div class="nav-category">Administración</div>
            <a href="#" class="nav-link"><i class="bi bi-shield-lock"></i> Usuarios y Roles</a>
            <a href="#" class="nav-link"><i class="bi bi-gear"></i> Configuración</a>
        </div>
    </nav>

    <div class="main-content">
        <header class="navbar bg-white border-bottom px-4 py-3 sticky-top shadow-sm">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item text-muted small">Gestión Operativa</li>
                        <li class="breadcrumb-item active small" aria-current="page font-bold">Proyectos</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center">
                    <div class="input-group input-group-sm me-3">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-light border-0" placeholder="Buscar proyecto...">
                    </div>
                    <img src="https://ui-avatars.com/api/?name=CEO+Carpinteria&background=d97706&color=fff" class="rounded-circle shadow-sm" width="38">
                </div>
            </div>
        </header>

        <div class="container-fluid p-4">
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 bg-white border-start border-primary border-4">
                        <small class="text-muted fw-bold">PROYECTOS ACTIVOS</small>
                        <h2 class="fw-bold mb-0">24</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-white border-start border-success border-4">
                        <small class="text-muted fw-bold">CAJA DEL DÍA</small>
                        <h2 class="fw-bold mb-0">€1,280.50</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-white border-start border-warning border-4">
                        <small class="text-muted fw-bold">STOCK BAJO</small>
                        <h2 class="fw-bold mb-0">7 Items</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-white border-start border-info border-4">
                        <small class="text-muted fw-bold">PENDIENTE COBRO</small>
                        <h2 class="fw-bold mb-0">€4,120.00</h2>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Control de Proyectos (Selectividad de Activos)</h5>
                    <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">Nuevo Proyecto</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th class="ps-4">CÓDIGO</th>
                                <th>CLIENTE</th>
                                <th>ESTADO</th>
                                <th>TIPO</th>
                                <th>TOTAL ESTIMADO</th>
                                <th class="pe-4 text-end">GESTIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4 fw-bold text-primary">PRJ-2026-001</td>
                                <td>Diseños Malabo S.L.</td>
                                <td><span class="badge bg-info-subtle text-info px-3">En Proceso</span></td>
                                <td>Mueble Medida</td>
                                <td class="fw-bold">€2,450.00</td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group shadow-sm">
                                        <button class="btn btn-white btn-sm border"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-white btn-sm border"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-white btn-sm border text-danger"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>