<?php

// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
  $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
  header("Location: index.php");
  exit;
}


?>
<div id="content" class="container-fluid">
  <div class="card shadow-sm border-0 mb-4">

    <!-- ENCABEZADO con título dinámico, buscador y botones -->
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h4 id="titulo" class="fw-bold text-white mb-0">
        <i class="bi bi-flag-fill me-2"></i> Gestión de Estados
      </h4>

      <div class="input-group" style="max-width: 250px;">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
        <input type="text" id="buscador" class="form-control" placeholder="Buscar...">
      </div>

      <div id="btnGroup" class="btn-group" role="group">
        <button class="btn btn-outline-light active" onclick="mostrarVista('estados')">
          <i class="bi bi-flag-fill me-1"></i>Estados
        </button>
        <button class="btn btn-outline-light" onclick="mostrarVista('roles')">
          <i class="bi bi-person-vcard-fill me-1"></i>Roles
        </button>
      </div>
    </div>

    <!-- CUERPO DE LA TARJETA -->
    <div class="card-body">

      <!-- CONTENEDOR ESTADOS -->
      <div id="vistaEstados" class="table-responsive">
        <div class="card-body">
          <table id="tablaEstados" class="table table-hover table-custom align-middle mb-0">
            <thead>
              <tr>
                <th><i class="bi bi-hash me-1"></i>ID</th>
                <th><i class="bi bi-flag-fill me-1"></i>Nombre</th>
                <th><i class="bi bi-diagram-3-fill me-1"></i>Entidad</th>
                <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
              </tr>
            </thead>
            <tbody id="tEstado">
              <!-- Tus filas PHP aquí -->
              <!-- ... -->
            </tbody>
          </table>
        </div>
        <div class="card-footer row py-2 d-flex justify-content-between">
          <div id="resumenEstados" class="col-12 col-md-4 text-muted small  text-center "></div>
          <!-- Controles de paginación -->
          <div id="paginacionEstados" class="col-12 col-md-7  d-flex justify-content-center "></div>
        </div>
      </div>

      <!-- CONTENEDOR ROLES -->
      <div id="vistaRoles" class="table-responsive d-none">
        <div class="card-body">
          <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
            <thead>
              <tr>
                <th><i class="bi bi-hash me-1"></i>ID</th>
                <th><i class="bi bi-person-fill me-1"></i>Nombre del Rol</th>
                <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
              </tr>
            </thead>
            <tbody id="tRoles">
              <!-- Tus filas PHP aquí -->
              <!-- ... -->
            </tbody>
          </table>
        </div>
        <div class="card-footer row py-2 d-flex justify-content-between">
          <div id="resumenRoles" class="col-12 col-md-4 text-muted small  text-center "></div>
          <!-- Controles de paginación -->
          <div id="paginacionRoles" class="col-12 col-md-7  d-flex justify-content-center "></div>
        </div>
      </div>
    </div>
  </div>
</div>





<!-- Modal para registrar nuevo estado -->
<div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="modalEstadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formEstado" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalEstadoLabel">
            <i class="bi bi-flag"></i> Registrar Nuevo Estado
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <div id="alertEstado" class="alert d-none"></div>

          <div class="mb-3">
            <label for="nombre_estado" class="form-label">Nombre del Estado</label>
            <input type="text" name="nombre" id="nombre_estado" class="form-control" maxlength="100" required>
          </div>

          <div class="mb-3">
            <label for="entidad_estado" class="form-label">Entidad</label>
            <select name="entidad" id="entidad_estado" class="form-select" required>
              <option value="">-- Selecciona una entidad --</option>
              <option value="produccion">Producción</option>
              <option value="proyecto">Proyecto</option>
              <option value="solicitud">Solicitud</option>
              <option value="venta">Venta</option>
              <option value="factura">Factura</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Registrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>




<script>
  const buscador = document.getElementById('buscador');
  function mostrarVista(vista) {
    // Ocultar ambas vistas
    document.getElementById("vistaEstados").classList.add("d-none");
    document.getElementById("vistaRoles").classList.add("d-none");

    // Quitar clase activa de botones
    document.querySelectorAll("#btnGroup .btn").forEach(btn => btn.classList.remove("active"));

    // Eliminar botón nuevo si existe
    const btnExistente = document.getElementById("btnNuevoRegistro");
    if (btnExistente) btnExistente.remove();

    // Referencia al grupo de botones
    const btnGroup = document.getElementById("btnGroup");

    if (vista === "estados") {
      document.getElementById("vistaEstados").classList.remove("d-none");
      document.querySelectorAll("#btnGroup .btn")[0].classList.add("active");
      document.getElementById("titulo").innerHTML = '<i class="bi bi-flag-fill me-2"></i> Gestión de Estados';
      document.getElementById("buscador").placeholder = "Buscar estado...";

      <?php if (in_array($rol, ['administrador'])): ?>
        // Crear botón nuevo estado
        const nuevoBtn = document.createElement('button');
        nuevoBtn.className = 'btn btn-secondary';
        nuevoBtn.id = 'btnNuevoRegistro';
        nuevoBtn.setAttribute('data-bs-toggle', 'modal');
        nuevoBtn.setAttribute('data-bs-target', '#modalEstado');
        nuevoBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Nuevo Estado';
        btnGroup.insertBefore(nuevoBtn, btnGroup.firstChild);
      <?php endif; ?>

    } else if (vista === "roles") {
      document.getElementById("vistaRoles").classList.remove("d-none");
      document.querySelectorAll("#btnGroup .btn")[1].classList.add("active");
      document.getElementById("titulo").innerHTML = '<i class="bi bi-person-vcard-fill me-2"></i> Gestión de Roles';
      document.getElementById("buscador").placeholder = "Buscar rol...";

      <?php if (in_array($rol, ['administrador'])): ?>
        // Crear botón nuevo rol
        const nuevoBtn = document.createElement('button');
        nuevoBtn.className = 'btn btn-secondary';
        nuevoBtn.id = 'btnNuevoRegistro';
        nuevoBtn.setAttribute('data-bs-toggle', 'modal');
        nuevoBtn.setAttribute('data-bs-target', '#modalRol');
        nuevoBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Nuevo Rol';
        btnGroup.insertBefore(nuevoBtn, btnGroup.firstChild);
      <?php endif; ?>
    }
  }

  cargarEstado()
  manejarEventosAjaxTbody()
  function cargarEstado(pagina = 1, termino = '') {
    const formData = new FormData();
    formData.append('pagina', pagina);
    formData.append('termino', termino);

    fetch('api/listar_estado.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('tEstado').innerHTML = data.html;
          document.getElementById('paginacionEstados').innerHTML = data.paginacion;
          document.getElementById('resumenEstados').textContent = data.resumen;
          // inicializarBotonesDetalle(); // 👈 volver a asociar eventos
        }
      });
  }
  cargarRol();

  function manejarEventosAjaxTbody() {
    document.getElementById('tEstado').addEventListener('click', (e) => {
      if (e.target.closest(".btn-eliminar")) {
        const id = e.target.closest(".btn-eliminar").dataset.id;
        eliminar(id);
      }
    });

    document.getElementById('tRoles').addEventListener('click', (e) => {
      if (e.target.closest(".btn-eliminar")) {
        const id = e.target.closest(".btn-eliminar").dataset.id;
        eliminar(id);
      }
    });
  }

  function cargarRol(pagina = 1, termino = '') {
    const formData = new FormData();
    formData.append('pagina', pagina);
    formData.append('termino', termino);

    fetch('api/listar_rol.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('tRoles').innerHTML = data.html;  // corregido de 'tEstado'
          document.getElementById('paginacionRoles').innerHTML = data.paginacion;
          document.getElementById('resumenRoles').textContent = data.resumen;
        }
      });
  }
  document.addEventListener('DOMContentLoaded', () => {
    const tEstado = document.getElementById('tEstado');
    const tRoles = document.getElementById('tRoles');

    if (tEstado) {
      tEstado.addEventListener('click', (e) => {
        if (e.target.closest(".btn-eliminar")) {
          const id = e.target.closest(".btn-eliminar").dataset.id;
          eliminar(id);
        }
      });
    }

    if (tRoles) {
      tRoles.addEventListener('click', (e) => {
        if (e.target.closest(".btn-eliminar")) {
          const id = e.target.closest(".btn-eliminar").dataset.id;
          eliminar(id);
        }
      });
    }

    // El resto de tu código que requiere elementos DOM también dentro de aquí,
    // o que llame a funciones como cargarEstado(), cargarRol() etc.
  });


</script>