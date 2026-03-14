<nav class="sidebar shadow">


    <div class="nav flex-column pb-5 mt-2">

        <a href="<?= urlsite ?>?page=dashboard" class="nav-link <?= ($_GET['page'] == 'dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> <span>Panel General</span>
        </a>

        <div class="nav-category">Producción y Proyectos</div>
        <a href="<?= urlsite ?>?page=proyectos" class="nav-link <?= ($_GET['page'] == 'proyectos') ? 'active' : '' ?>">
            <i class="bi bi-kanban"></i> <span>Proyectos</span>
        </a>
        <a href="<?= urlsite ?>?page=ordenes_servicio"
            class="nav-link <?= ($_GET['page'] == 'ordenes_servicio') ? 'active' : '' ?>">
            <i class="bi bi-hammer"></i> <span>Órdenes Servicio</span>
        </a>
        <a href="<?= urlsite ?>?page=cotizaciones"
            class="nav-link <?= ($_GET['page'] == 'cotizaciones') ? 'active' : '' ?>">
            <i class="bi bi-pencil-square"></i> <span>Cotizaciones</span>
        </a>

        <div class="nav-category">Activos e Inventario</div>
        <a href="<?= urlsite ?>?page=materiales"
            class="nav-link <?= ($_GET['page'] == 'materiales') ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i> <span>Materiales</span>
        </a>
        <a href="<?= urlsite ?>?page=productos" class="nav-link <?= ($_GET['page'] == 'productos') ? 'active' : '' ?>">
            <i class="bi bi-stack"></i> <span>Productos</span>
        </a>

        <div class="nav-category">Mente Maestra (Finanzas)</div>
        <a href="<?= urlsite ?>?page=finanzas" class="nav-link <?= ($_GET['page'] == 'finanzas') ? 'active' : '' ?>">
            <i class="bi bi-cash-stack"></i> <span>Flujo de Caja</span>
        </a>
        <a href="<?= urlsite ?>?page=gastos" class="nav-link <?= ($_GET['page'] == 'gastos') ? 'active' : '' ?>">
            <i class="bi bi-piggy-bank"></i> <span>Gastos Ops.</span>
        </a>
        <a href="<?= urlsite ?>?page=proveedores"
            class="nav-link <?= ($_GET['page'] == 'proveedores') ? 'active' : '' ?>">
            <i class="bi bi-truck"></i> <span>Proveedores</span>
        </a>

        <div class="nav-category">Capital Humano</div>
        <a href="<?= urlsite ?>?page=clientes" class="nav-link <?= ($_GET['page'] == 'clientes') ? 'active' : '' ?>">
            <i class="bi bi-people"></i> <span>Clientes</span>
        </a>
        <a href="<?= urlsite ?>?page=empleados" class="nav-link <?= ($_GET['page'] == 'empleados') ? 'active' : '' ?>">
            <i class="bi bi-person-badge"></i> <span>Empleados</span>
        </a>

        <div class="nav-category">Administración</div>
        <a href="<?= urlsite ?>?page=usuarios" class="nav-link <?= ($_GET['page'] == 'usuarios') ? 'active' : '' ?>">
            <i class="bi bi-person-gear"></i> <span>Usuarios</span>
        </a>
        <a href="<?= urlsite ?>?page=roles" class="nav-link <?= ($_GET['page'] == 'roles') ? 'active' : '' ?>">
            <i class="bi bi-shield-lock"></i> <span>Roles</span>
        </a>
        <a href="<?= urlsite ?>?page=configuracion"
            class="nav-link <?= ($_GET['page'] == 'configuracion') ? 'active' : '' ?>">
            <i class="bi bi-gear"></i> <span>Configuración</span>
        </a>
    </div>
</nav>


<div class="main-content">
    <header class="navbar bg-white border-bottom px-4 py-3 sticky-top shadow-sm">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item text-muted small">admin</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="<?= urlsite ?>?page=logout" class="btn btn-logout rounded-pill text-decoration-none">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </div>

            </div>
        </div>
    </header>