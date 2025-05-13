<?php


// ========================
// CONSULTA DE MATERIALES
// ========================
$sql = "SELECT * FROM materiales";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<div id="content" class="container-fluid py-4">

    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-vcard-fill me-2"></i> Gestión de Materiales
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar material..." id="buscador-materiales">
            </div>
            <a href="index.php?vista=registrar_materiales" class="btn btn-secondary" title="Nuevo Material">
                <i class="bi bi-plus-circle"></i> Nuevo Material
            </a>
        </div>

        <div class="card-body">
            <table id="tablaMateriales" class="table table-hover table-custom align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="bi bi-hash me-1"></i>ID</th>

                        <th><i class="bi bi-person"></i> Nombre</th>
                        <th>Descripción</th>
                        <th>unidad medida</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($materiales) > 0): ?>
                        <?php foreach ($materiales as $material): ?>
                            <tr>
                                <td><?= $material['id'] ?></td>
                                <td><?= htmlspecialchars($material['nombre']) ?></td>
                                <td><?= htmlspecialchars($material['descripcion']) ?></td>
                                <td><?= htmlspecialchars($material['unidad_medida']) ?></td>
                                <td><?= number_format($material['stock_actual'], 0) ?></td>
                                <td><?= number_format($material['stock_minimo'], 0) ?></td>

                                <td class="text-center">
                                    <a href="index.php?vista=editar_materiales&id=<?= $material['id'] ?>"
                                        class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron materiales.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>


        </div>
    </div>
















</div>