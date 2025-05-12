<div id="content" class="container-fliud">
    <div id="navContent" class="row">
        <!-- BARRA DE ACCIONES -->
        <h4 class="mb-0">Usuarios Registrados</h4>

        <div class="text-end mb-4 p-2">
            <a href="index.php?vista=registrar_usuarios" class="btn btn-success me-2">

                <i class="bi bi-plus-circle"></i> Nuevo Usuario
            </a>

        </div>


        <!-- BUSCADOR -->
        <div class="mb-3">
            <input type="search" id="buscador" class="form-control bg-white text-white border-secondary"
                placeholder="Buscar rol…">
        </div>

    </div>

    <!-- TABLA -->
    <div class="card p-2">

        <div class="table-responsive">
            <table id="tablaRoles" class="table table-light table-hover align-middle mb-0">
                <thead class="text-dark table-success p-2">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Empleado</th>
                            <th>Rol</th>
                            <th>Activo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= $usuario['id'] ?></td>
                                <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                                <td><?= htmlspecialchars($usuario['empleado_nombre'] . ' ' . $usuario['empleado_apellido']) ?>
                                </td>
                                <td><?= htmlspecialchars($usuario['rol']) ?></td>
                                <td class="text-center">
                                    <!-- Botón de Activar/Desactivar -->
                                    <a href="#"
                                        class="btn btn-sm <?= $usuario['activo'] ? 'btn-success' : 'btn-danger' ?> activar-btn"
                                        data-id="<?= $usuario['id'] ?>" data-estado="<?= $usuario['activo'] ? '1' : '0' ?>">
                                        <i class="bi <?= $usuario['activo'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                        <?= $usuario['activo'] ? 'Activado' : 'Desactivado' ?>
                                    </a>


                                    <!--   -->
                                </td>

                                <td class="text-center">
                                    <a href="index.php?vista=editar_usuarios&id=<?= $usuario['id'] ?>"
                                        class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron usuarios.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const botones = document.querySelectorAll(".activar-btn");

            botones.forEach(boton => {
                boton.addEventListener("click", function (e) {
                    e.preventDefault();

                    const id = this.dataset.id;
                    const estadoActual = this.dataset.estado;

                    if (!confirm(`¿Está seguro de ${estadoActual === '1' ? 'desactivar' : 'activar'} este usuario?`)) {
                        return;
                    }

                    fetch("api/activar_desactivar_usuario.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ id: id })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.ok) {
                                // Puedes recargar la tabla, cambiar íconos, texto, etc.
                                alert("Estado actualizado correctamente");
                                location.reload(); // o actualiza solo la fila si prefieres
                            } else {
                                alert("Error al actualizar estado");
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Error en la petición");
                        });
                });
            });
        });
    </script>