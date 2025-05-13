<?php


$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
$stmt->execute([$id]);
$servicio = $stmt->fetch();

if (!$servicio) {
    echo "Servicio no encontrado.";
    exit;
}
?>

<div id="content" class="container-fluid ">


    <h2>Editar Servicio</h2>
    <form id="formEditarServicio">
        <input type="hidden" id="id" value="<?= $servicio['id'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" class="form-control" value="<?= htmlspecialchars($servicio['nombre']) ?>"
                required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea id="descripcion" class="form-control"><?= htmlspecialchars($servicio['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="precio_base" class="form-label">Precio base</label>
            <input type="number" step="0.01" id="precio_base" class="form-control"
                value="<?= $servicio['precio_base'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="unidad" class="form-label">Unidad</label>
            <input type="text" id="unidad" class="form-control" value="<?= htmlspecialchars($servicio['unidad']) ?>"
                required>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="activo" <?= $servicio['activo'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="activo">Servicio activo</label>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php?vista=servicios" class="btn btn-secondary">Cancelar</a>
    </form>

    <div id="mensaje" class="mt-3"></div>
</div>

<script>
    document.getElementById('formEditarServicio').addEventListener('submit', async function (e) {
        e.preventDefault();

        const data = {
            id: document.getElementById('id').value,
            nombre: document.getElementById('nombre').value,
            descripcion: document.getElementById('descripcion').value,
            precio_base: document.getElementById('precio_base').value,
            unidad: document.getElementById('unidad').value,
            activo: document.getElementById('activo').checked ? 1 : 0
        };

        const res = await fetch('api/actualizar_servicios.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const resultado = await res.json();
        const mensaje = document.getElementById('mensaje');

        if (resultado.success) {
            mensaje.innerHTML = `<div class="alert alert-success">${resultado.message}</div>`;
        } else {
            mensaje.innerHTML = `<div class="alert alert-danger">${resultado.message}</div>`;
        }
    });
</script>