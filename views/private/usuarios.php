<?php
// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}

$sql = "SELECT 
            u.*,
            r.nombre AS rol,
            e.nombre AS empleado_nombre,
            e.apellido AS empleado_apellido
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            LEFT JOIN empleados e ON u.empleado_id = e.id
            ";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<div id="content" class="container-fliud">
    <!-- Card con tabla de roles -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-lock me-2"></i> Gestión de Roles de Usuario
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar usuario..." id="buscador">
            </div>
            <a href="index.php?vista=registrar_usuarios" class="btn btn-secondary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-image me-1"></i>Perfil</th>

                            <th><i class="bi bi-person-circle me-1"></i>Usuario</th>
                            <th><i class="bi bi-person-badge me-1"></i>Empleado</th>
                            <th><i class="bi bi-person-gear me-1"></i>Rol</th>
                            <th class="text-center"><i class="bi bi-check-circle me-1"></i>Activo</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php if (count($usuarios) > 0): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= $usuario['id'] ?></td>
                                    <td>
                                        <img src="api/<?= htmlspecialchars($usuario['perfil']) ?>" alt="Perfil" width="60"
                                            class="img-thumbnail">
                                    </td>

                                    <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                                    <td><?= htmlspecialchars($usuario['empleado_nombre'] . ' ' . $usuario['empleado_apellido']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($usuario['rol']) ?></td>
                                    <td class="text-center">
                                        <a href="#"
                                            class="btn btn-sm <?= $usuario['activo'] ? 'btn-success' : 'btn-danger' ?> activar-btn"
                                            data-id="<?= $usuario['id'] ?>" data-estado="<?= $usuario['activo'] ? '1' : '0' ?>">
                                            <i class="bi <?= $usuario['activo'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                            <?= $usuario['activo'] ? 'Activado' : 'Desactivado' ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="index.php?vista=editar_usuarios&id=<?= $usuario['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-muted text-center py-3">No se encontraron usuarios.</td>
                            </tr>
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
            //cambiar estado 
            if (e.target.closest(".activar-btn")) {
                const btn = e.target.closest(".activar-btn");
                const id = btn.dataset.id;
                const estado = parseInt(btn.dataset.estado);
                cambiarEstado(id, estado);
            }


        });

    }

    async function eliminar(id) {
        if (confirm('¿Seguro que quieres eliminar este usuario?')) {
            try {
                const response = await fetch(`api/eliminar_usuarios.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el usuario.');
                }
            } catch (error) {
                alert('Error en la petición.');
            }
        }
    }


    async function cambiarEstado(id, estado) {
        if (confirm(`¿Está seguro de ${estado === '1' ? 'desactivar' : 'activar'} este usuario?`)) {
            try {
                const res = await fetch("api/activar_desactivar_usuario.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id: id })
                })

                const data = await res.json();
                if (data.success) {
                    location.reload(); // refresca para ver el cambio
                } else {
                    alert(data.message);
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
            const res = await fetch('api/listar_usuarios.php', {
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
        document.getElementById('paginacion').addEventListener('click', async ( e )=> {
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