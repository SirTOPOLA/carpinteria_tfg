<?php
// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}

// Consulta paginada
$sql = "SELECT * FROM proveedores ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div id="content" class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-vcard-fill me-2"></i>Gestión de Proveesores
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar proveedor..." id="buscador">
            </div>
            <a href="index.php?vista=registrar_proveedores" class="btn btn-secondary mb-3"><i class="bi bi-plus"></i>
                Nuevo
                proveedor</a>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-person me-1"></i>Nombre</th>
                            <th><i class="bi bi-person-lines-fill me-1"></i>Contacto</th>
                            <th><i class="bi bi-telephone me-1"></i>Teléfono</th>
                            <th><i class="bi bi-envelope-at me-1"></i>Correo</th>
                            <th><i class="bi bi-geo-alt me-1"></i>Dirección</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>

                    </thead>
                    <tbody id="tbody">
                        <?php if ($proveedores): ?>
                            <?php foreach ($proveedores as $proveedor): ?>
                                <tr>
                                    <td><?= $proveedor['id'] ?></td>
                                    <td><?= htmlspecialchars($proveedor['nombre']) ?></td>
                                    <td><?= htmlspecialchars($proveedor['contacto']) ?></td>
                                    <td><?= htmlspecialchars($proveedor['telefono']) ?></td>
                                    <td><?= htmlspecialchars($proveedor['email']) ?></td>
                                    <td><?= htmlspecialchars($proveedor['direccion']) ?></td>
                                    <td class="text-center">
                                        <a href="index.php?vista=editar_proveedores&id=<?= $proveedor['id'] ?>"
                                            class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No se encontraron proveedores.</td>
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
        if (confirm('¿Seguro que quieres eliminar este proveedore?')) {
            try {
                const response = await fetch(`api/eliminar_proveedores.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el proveedore.');
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
            const res = await fetch('api/listar_proveedores.php', {
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