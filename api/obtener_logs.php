<?php
// api/obtener_logs_mejorado.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
include_once 'notificationManager.php';
try {
    $manager = new NotificationManager();
    
    $action = $_GET['action'] ?? 'get_all';
    $limit = intval($_GET['limit'] ?? 20);
    
    switch ($action) {
        case 'get_all':
            echo json_encode($manager->getAllNotifications($limit));
            break;
            
        case 'get_stats':
            echo json_encode($manager->getStatistics());
            break;
            
        case 'search':
            $query = $_GET['q'] ?? '';
            $filters = [
                'status' => $_GET['status'] ?? '',
                'priority' => $_GET['priority'] ?? '',
                'date_from' => $_GET['date_from'] ?? '',
                'date_to' => $_GET['date_to'] ?? ''
            ];
            echo json_encode($manager->searchNotifications($query, $filters));
            break;
            
        case 'mark_processed':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $notificationId = $input['id'] ?? '';
                echo json_encode($manager->markAsProcessed($notificationId));
            } else {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            break;
            
        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $index = intval($input['index'] ?? -1);
                echo json_encode($manager->deleteNotification($index));
            } else {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            break;
            
        case 'archive_old':
            $days = intval($_GET['days'] ?? 30);
            echo json_encode($manager->archiveOldNotifications($days));
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'error' => $e->getMessage()
    ]);
}

