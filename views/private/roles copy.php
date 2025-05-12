<?php
// --- conexión y carga inicial (solo para contar)—
// Si quieres mostrar la primera página del lado del servidor
require_once("../includes/conexion.php");
$porPagina = 5;
$total      = $pdo->query("SELECT COUNT(*) FROM roles")->fetchColumn();
$paginasTot = ceil($total / $porPagina);
?>
<?php 
include_once ('../includes/header.php');
include_once ('../includes/sidebar.php');
include_once ('../includes/nav.php');
?>

<!-- ---------- CONTENIDO ---------- -->
<div class="container-fluid py-4">
  <!-- BARRA DE ACCIONES -->
  <div class="d-flex justify-content-between align-items-center p-2 mb-3">
    <h4 class="mb-0">Listado de Roles</h4>
    <div>
      <a href="registrar_rol.php" class="btn btn-success me-2">
        <i class="bi bi-shield-plus"></i> Nuevo Rol
      </a>
      <a href="usuarios.php" class="btn btn-primary">
        <i class="bi bi-person-lines-fill"></i> Lista de Usuarios
      </a>
    </div>
  </div>

  <!-- BUSCADOR -->
  <div class="mb-3">
    <input type="search"
           id="buscador"
           class="form-control bg-dark text-white border-secondary"
           placeholder="Buscar rol…">
  </div>

  <!-- TABLA -->
  <div class="table-responsive">
    <table id="tablaRoles" class="table table-dark table-hover align-middle mb-0">
      <thead class="table-dark text-white">
        <tr>
          <th><i class="bi bi-hash"></i> ID</th>
          <th><i class="bi bi-shield-lock-fill"></i> Rol</th>
          <th><i class="bi bi-gear-fill"></i> Acciones</th>
        </tr>
      </thead>
      <tbody><!-- Se llena dinámicamente --></tbody>
    </table>
  </div>

  <!-- PAGINACIÓN -->
  <div id="paginacion" class="mt-3 d-flex flex-wrap"></div>
</div>



<!-- ---------- SCRIPTS ---------- -->
<script>
document.addEventListener("DOMContentLoaded",()=>{

  const buscador  = document.getElementById("buscador");
  const tbody     = document.querySelector("#tablaRoles tbody");
  const paginador = document.getElementById("paginacion");
  const POR_PAGINA = 5;
  let paginaActual = 1;
  let queryActual  = "";

  // --- carga inicial
  cargarRoles();

  // --- búsqueda en tiempo real
  buscador.addEventListener("input",()=> {
    queryActual  = buscador.value.trim();
    paginaActual = 1;
    cargarRoles();
  });

  // --- función principal
  function cargarRoles(){
    const params = new URLSearchParams({
      page: paginaActual,
      q   : queryActual
    });

    fetch("../ajax/get_roles.php?" + params.toString())
      .then(r => r.json())
      .then(({rows,totalPages})=>{
          tbody.innerHTML = rows;
          renderPaginacion(totalPages);
      })
      .catch(err=>{
          console.error(err);
          tbody.innerHTML =
            '<tr><td colspan="3" class="text-danger text-center">Error al cargar datos.</td></tr>';
          paginador.innerHTML = "";
      });
  }

  // --- crea botones de paginación
  function renderPaginacion(total){
    paginador.innerHTML = "";
    if (total <= 1) return;

    for(let i=1;i<=total;i++){
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.className   =
        "btn btn-sm " + (i===paginaActual ? "btn-primary" : "btn-outline-light") + " me-1 mb-1";
      btn.addEventListener("click",()=>{
        paginaActual = i;
        cargarRoles();
      });
      paginador.appendChild(btn);
    }
  }
});
</script>

<?php include_once('../includes/footer.php'); ?>
