<?php
require_once("../includes/conexion.php");

try { 

    // Verificar si los roles ya están cargadas
    $consulta = $pdo->prepare("SELECT COUNT(*) FROM roles");
    $consulta->execute();
    $total = $consulta->fetchColumn();

    if ($total > 0) {
        // Si las categorías ya están cargadas, no es necesario insertarlas de nuevo
        
    } else {
        // Si no hay roles, insertamos los predeterminados
        $roles = [
            ['Administrador'], 
            ['Vendedor'], 
            ['Diseñador'], 
            ['Operario'], 
        ];

        // Preparamos la consulta para insertar roles
        $stmt = $pdo->prepare("INSERT INTO roles (nombre) VALUES ( ?)");

        // Ejecutamos la inserción de cada rol
        foreach ($roles as $rol) {
            $stmt->execute([$rol[0]]);
        }

    }
} catch (PDOException $e) {
    error_log("Error al insertar categorías: " . $e->getMessage());
    
}


 
// ========================
// CONSULTA DE ROLES
// ========================
$query = "SELECT id, nombre FROM roles ";
$stmt = $pdo->prepare($query);
 $stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
    <!-- BARRA DE ACCIONES -->
    <div class="d-flex justify-content-between align-items-center  p-2 mb-3">
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

     
    <!-- TABLA -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($roles) > 0): ?>
                    <?php foreach ($roles as $rol): ?>
                        <tr>
                            <td><?= htmlspecialchars($rol['id']) ?></td>
                            <td><?= htmlspecialchars($rol['nombre']) ?></td>
                            <td>
                                <a href="editar_rol.php?id=<?= $rol['id'] ?>" class="btn btn-sm btn-primary me-1" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                 
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No se encontraron roles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

 
</div>
</main>
<?php include_once("../includes/footer.php"); ?>
