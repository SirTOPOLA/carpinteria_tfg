<?php
 // Si no hay sesión → redirige a login
 if (!isset($_SESSION['usuario']) || isset($_SESSION['usuario'])) {
    header("Location: ../index.php?vista=inicio");
    exit;
  }
require '../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? ''; // 'pedido', 'proyecto', 'produccion'
    $id = intval($_POST['id'] ?? 0);
    $nuevoEstado = $_POST['estado'] ?? '';

    if ($id <= 0 || !in_array($tipo, ['pedido', 'proyecto', 'produccion'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // ------------------- PEDIDO (SOLICITUD) -------------------
        if ($tipo === 'pedido') {
            $stmt = $pdo->prepare("SELECT proyecto_id FROM solicitudes_proyecto WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) throw new Exception('Pedido no encontrado.');
            $proyectoId = $row['proyecto_id'];

            $pdo->prepare("UPDATE solicitudes_proyecto SET estado = ? WHERE id = ?")
                ->execute([$nuevoEstado, $id]);

            if ($nuevoEstado === 'cotizado') {
                $pdo->prepare("UPDATE proyectos SET estado = 'pendiente' WHERE id = ?")
                    ->execute([$proyectoId]);
            } elseif ($nuevoEstado === 'aprobado') {
                // Verificar producción existente
                $stmt = $pdo->prepare("SELECT id FROM producciones WHERE proyecto_id = ?");
                $stmt->execute([$proyectoId]);
                $produccion = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$produccion) {
                    $pdo->prepare("
                        INSERT INTO producciones (proyecto_id, estado, fecha_inicio)
                        VALUES (?, 'pendiente', NOW())
                    ")->execute([$proyectoId]);
                } else {
                    $pdo->prepare("UPDATE producciones SET estado = 'pendiente' WHERE proyecto_id = ?")
                        ->execute([$proyectoId]);
                }

                $pdo->prepare("UPDATE proyectos SET estado = 'en diseño' WHERE id = ?")
                    ->execute([$proyectoId]);
            }
        }

        // ------------------- PROYECTO -------------------
        elseif ($tipo === 'proyecto') {
            $stmt = $pdo->prepare("SELECT id FROM solicitudes_proyecto WHERE proyecto_id = ?");
            $stmt->execute([$id]);
            $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
            $solicitudId = $solicitud['id'] ?? null;

            $pdo->prepare("UPDATE proyectos SET estado = ? WHERE id = ?")
                ->execute([$nuevoEstado, $id]);

            if ($nuevoEstado === 'en producción') {
                if ($solicitudId) {
                    $pdo->prepare("UPDATE solicitudes_proyecto SET estado = 'en_produccion' WHERE id = ?")
                        ->execute([$solicitudId]);
                }

                $stmt = $pdo->prepare("SELECT id FROM producciones WHERE proyecto_id = ?");
                $stmt->execute([$id]);
                $produccion = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$produccion) {
                    $pdo->prepare("INSERT INTO producciones (proyecto_id, estado, fecha_inicio) VALUES (?, 'en proceso', NOW())")
                        ->execute([$id]);
                } else {
                    $pdo->prepare("UPDATE producciones SET estado = 'en proceso' WHERE proyecto_id = ?")
                        ->execute([$id]);
                }
            }
        }

        // ------------------- PRODUCCIÓN -------------------
        elseif ($tipo === 'produccion') {
            $stmt = $pdo->prepare("SELECT proyecto_id FROM producciones WHERE id = ?");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$prod) throw new Exception('Producción no encontrada.');

            $proyectoId = $prod['proyecto_id'];

            $stmt = $pdo->prepare("SELECT id, descripcion, estimacion_total FROM solicitudes_proyecto WHERE proyecto_id = ?");
            $stmt->execute([$proyectoId]);
            $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$solicitud) throw new Exception('Solicitud relacionada no encontrada.');
            $solicitudId = $solicitud['id'];
            $descripcion = $solicitud['descripcion'];
            $estimacionTotal = (float) $solicitud['estimacion_total'];

            $stmt = $pdo->prepare("SELECT nombre FROM proyectos WHERE id = ?");
            $stmt->execute([$proyectoId]);
            $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);
            $nombreProyecto = $proyecto['nombre'] ?? 'Producto final';

            $stmt = $pdo->query("SELECT iva FROM configuracion LIMIT 1");
            $config = $stmt->fetch(PDO::FETCH_ASSOC);
            $iva = $config['iva'] ?? 0.16;

            $pdo->prepare("UPDATE producciones SET estado = ? WHERE id = ?")
                ->execute([$nuevoEstado, $id]);

            if ($nuevoEstado === 'terminado') {
                $pdo->prepare("UPDATE proyectos SET estado = 'finalizado' WHERE id = ?")
                    ->execute([$proyectoId]);
                $pdo->prepare("UPDATE solicitudes_proyecto SET estado = 'finalizado' WHERE id = ?")
                    ->execute([$solicitudId]);

                $precioFinal = $estimacionTotal + ($estimacionTotal * $iva);

                $stmt = $pdo->prepare("SELECT id FROM productos WHERE nombre = ?");
                $stmt->execute([$nombreProyecto]);
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($producto) {
                    $pdo->prepare("UPDATE productos SET stock = stock + 1, precio_unitario = ?, descripcion = ? WHERE id = ?")
                        ->execute([$precioFinal, $descripcion, $producto['id']]);
                } else {
                    $pdo->prepare("
                        INSERT INTO productos (nombre, descripcion, precio_unitario, stock, fecha_creacion)
                        VALUES (?, ?, ?, 1, NOW())
                    ")->execute([$nombreProyecto, $descripcion, $precioFinal]);
                }
            }
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente.']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
