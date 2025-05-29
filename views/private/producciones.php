<?php
 

// Obtener producciones disponibles
$sql = "SELECT 
                prod.*,
                proy.estado AS estado_proyecto, 
                proy.nombre AS proyecto_nombre, 
                emp.nombre AS empleado_nombre
                FROM producciones prod
                LEFT JOIN proyectos proy ON prod.proyecto_id = proy.id                 
                LEFT JOIN empleados emp ON prod.responsable_id = emp.id
                ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

 
<div id="content" class="container-fluid py-4">
    
    
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-boxes me-2"></i> Gestión de Producciones
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar producción..." id="buscador">
            </div>
            <a href="index.php?vista=registrar_producciones" class="btn btn-secondary">
                <i class="bi bi-plus-circle"></i> Nueva Producción
            </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-hash me-1"></i>ID</th>
                        <th><i class="bi bi-folder2-open me-1"></i>Proyecto</th>
                        <th><i class="bi bi-calendar-event me-1"></i>Inicio</th>
                        <th><i class="bi bi-calendar-check me-1"></i>Fin</th>
                        <th><i class="bi bi-flag-fill me-1"></i>Estado</th>
                        <th><i class="bi bi-cpu me-1"></i>Etapa</th>
                        <th><i class="bi bi-person-fill-gear me-1"></i>Responsable</th>
                        <th><i class="bi bi-clock me-1"></i>Creado</th>
                        <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody" >
                    <?php if (count($producciones) === 0): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">No se encontraron resultados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($producciones as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['proyecto_nombre']) ?></td>
                                <td><?= htmlspecialchars($p['fecha_inicio']) ?></td>
                                <td><?= htmlspecialchars($p['fecha_fin']) ?></td>
                                <td><?= htmlspecialchars($p['estado_proyecto']) ?></td>
                                <td>
                               
                                    <?= htmlspecialchars($p['estado']) ?>

                                </td>
                                <td><?= htmlspecialchars($p['empleado_nombre']) ?></td>
                                <td><?= htmlspecialchars($p['created_at']) ?></td>
                                <td>
                                    <a href="index.php?vista=editar_producciones&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                   <!--  <a href="registrar_proceso_produccionesid=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" title="Procesar">
                                        <i class="bi bi-play-circle"></i>
                                    </a> -->
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer row py-2 d-flex justify-content-between">
            <div id="resumen-paginacion" class="col-12 col-md-4 text-muted small  text-center "></div>
            <!-- Controles de paginación -->
            <div id="paginacion" class="col-12 col-md-7  d-flex justify-content-center "></div>
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
        if (confirm('¿Seguro que quieres eliminar esta produccion?')) {
            try {
                const response = await fetch(`api/eliminar_produccion.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el produccion.');
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
            const res = await fetch('api/listar_producciones.php', {
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
 