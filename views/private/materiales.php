<?php

 
$sql = "SELECT * FROM materiales";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<div id="content" class="container-fluid py-4">

<div class="card shadow-sm border-0 rounded-4 mb-4">
  <div class="card-header bg-success text-white rounded-top-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3 px-4">
    <h5 class="fw-bold mb-0">
      <i class="bi bi-box-seam-fill me-2"></i>Gestión de Materiales
    </h5>
    <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
      <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control" id="buscador" placeholder="Buscar material...">
    </div>
    <a href="index.php?vista=registrar_materiales" class="btn btn-secondary shadow-sm" title="Nuevo Material">
      <i class="bi bi-plus-circle me-1"></i>Nuevo Material
    </a>
  </div>

  <div class="card-body p-4">
    <div class="table-responsive">
      <table id="tablaMateriales" class="table table-hover align-middle text-center mb-0">
        <thead class="table-light text-nowrap">
          <tr>
            <th><i class="bi bi-hash me-1"></i>ID</th>
            <th><i class="bi bi-box-fill me-1"></i>Nombre</th>
            <th><i class="bi bi-text-left me-1"></i>Descripción</th>
            <th><i class="bi bi-rulers me-1"></i>Unidad</th>
            <th><i class="bi bi-archive-fill me-1"></i>Stock Actual</th>
            <th><i class="bi bi-exclamation-triangle-fill me-1"></i>Stock Mínimo</th>
            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbody">
          <?php if (count($materiales) > 0): ?>
            <?php foreach ($materiales as $material): ?>
              <tr>
                <td><?= $material['id'] ?></td>
                <td><?= htmlspecialchars($material['nombre']) ?></td>
                <td><?= htmlspecialchars($material['descripcion']) ?></td>
                <td><?= htmlspecialchars($material['unidad_medida']) ?></td>
                <td><?= number_format($material['stock_actual'], 0) ?></td>
                <td><?= number_format($material['stock_minimo'], 0) ?></td>
                <td>
                  <a href="index.php?vista=editar_materiales&id=<?= $material['id'] ?>"
                     class="btn btn-sm btn-outline-warning  " title="Editar">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-muted text-center py-3">
                <i class="bi bi-info-circle-fill me-1"></i>No se encontraron materiales registrados.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer row py-2 d-flex justify-content-between">
            <div id="resumen-paginacion" class="col-12 col-md-4 text-muted small  text-center "></div>
            <!-- Controles de paginación -->
            <div id="paginacion" class="col-12 col-md-7  d-flex justify-content-center "></div>
        </div>
  </div>
</div>

 
</div>


<script>

    const buscador = document.getElementById('buscador');
    let paginaActual = 1;

    //cargar las funciones al cargarse la pagina completamente
    document.addEventListener('DOMContentLoaded', () => {
        cargarDatos();
        clickPaginacion()
        manejarEventosAjaxTbody(); // Necesario cuando cargamos html por ajax
       // buscar()

    });

    function manejarEventosAjaxTbody() {
        document.getElementById("tbody").addEventListener("click", function (e) {
            //eliminar un registro de la fila por ID            
            if (e.target.closest(".btn-eliminar")) {
                const id = e.target.closest(".btn-eliminar").dataset.id;
                eliminar(id);
            }
         
           


        });

    }

    async function eliminar(id) {
        if (confirm('¿Seguro que quieres eliminar este material?')) {
            try {
                const response = await fetch(`api/eliminar_materiales.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el material.');
                }
            } catch (error) {
                alert('Error en la petición.');
            }
        }
    }

 
    async function cargarDatos(pagina = 1, termino = '') {
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('termino', termino);
        try {
            const res = await fetch('api/listar_materiales.php', {
                method: 'POST',
                body: formData
            })

            const data = await res.json();
            if (data.success) {
                document.getElementById('tbody').innerHTML = data.html;
                document.getElementById('paginacion').innerHTML = data.paginacion;
                document.getElementById('resumen-paginacion').textContent = data.resumen;
                paginaActual = pagina; // actualizar página actual
            } else {
                alert(data.message);
                console.log()
            }

        } catch (error) {
            alert('Error al cargar datos:', error);
            console.log(error)
        }

    }

    // Buscar
    function buscar() {
        buscador.addEventListener('input', async () => {
            paginaActual = 1;
            await cargarDatos(paginaActual, buscador.value.trim());
        });

    }
    // Manejar clics en paginación
    function clickPaginacion() {
        document.getElementById('paginacion').addEventListener('click', async e => {
            const btn = e.target.closest('.pagina-link');
            if (btn) {
                e.preventDefault();
                const nuevaPagina = parseInt(btn.dataset.pagina);
                if (!isNaN(nuevaPagina)) {
                    paginaActual = nuevaPagina;
                    await cargarDatos(paginaActual, buscador.value.trim());
                }
            }
        });

    }


</script>




