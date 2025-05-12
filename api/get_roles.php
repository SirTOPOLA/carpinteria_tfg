<?php
require_once("../includes/conexion.php");

// --- parámetros ---
$pagina   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$busqueda = isset($_GET['q'])    ? trim($_GET['q']) : "";
$porPagina = 5;
$offset    = ($pagina - 1) * $porPagina;

// --- total de registros (con filtro) ---
$sqlTotal  = "SELECT COUNT(*) FROM roles WHERE nombre LIKE :q";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute([':q'=>"%$busqueda%"]);
$totalFiltrado = (int)$stmtTotal->fetchColumn();
$totalPaginas  = max(1, ceil($totalFiltrado / $porPagina));

// --- consulta paginada ---
$sql  = "SELECT id, nombre
         FROM roles
         WHERE nombre LIKE :q
         ORDER BY id DESC
         LIMIT :lim OFFSET :off";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':q'  , "%$busqueda%", PDO::PARAM_STR);
$stmt->bindValue(':lim', $porPagina   , PDO::PARAM_INT);
$stmt->bindValue(':off', $offset      , PDO::PARAM_INT);
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- construir filas HTML ---
if ($roles){
  $rows = "";
  foreach ($roles as $rol){
    $rows .= '<tr>
      <td data-label="ID"><i class="bi bi-hash"></i> '.htmlspecialchars($rol["id"]).'</td>
      <td data-label="Rol"><i class="bi bi-person-badge-fill"></i> '.htmlspecialchars($rol["nombre"]).'</td>
      <td data-label="Acciones">
        <a href="editar_rol.php?id='.$rol["id"].'"
           class="btn btn-sm btn-outline-info"
           title="Editar">
          <i class="bi bi-pencil-square"></i>
        </a>
      </td>
    </tr>';
  }
} else {
  $rows = '<tr><td colspan="3" class="text-white text-center">No se encontraron resultados.</td></tr>';
}

// --- respuesta JSON ---
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'rows'       => $rows,
  'totalPages' => $totalPaginas
]);
