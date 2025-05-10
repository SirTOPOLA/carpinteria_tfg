<?php
require_once("../includes/conexion.php");

 

// ========================
// CONSULTA DE MATERIALES
// ========================
$sql = "SELECT * FROM materiales"; 
$stmt = $pdo->prepare($sql);
$stmt->execute();
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
// ========================
// VISTA HTML
// ========================
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Materiales Registrados</h4>
            <div>
                <a href="registrar_materiales.php" class="btn btn-success" title="Nuevo Material">
                    <i class="bi bi-plus-circle"></i>
                </a>
                
            </div>
        </div>

        

        <!-- TABLA DE MATERIALES -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th> 
                        <th>unidad medida</th> 
                        <th>Stock Actual</th> 
                        <th>Stock Mínimo</th> 
                        <th class="text-center">Acciones</th>
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
                                    <a href="editar_material.php?id=<?= $material['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
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
</main>

<?php include_once("../includes/footer.php"); ?>
