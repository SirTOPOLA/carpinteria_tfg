<?php
// api/NotificationManager.php
class NotificationManager {
    private $logFile;
    private $processedFile;
    
    public function __construct($logFile = 'logs.txt', $processedFile = 'processed_logs.txt') {
        $this->logFile = $logFile;
        $this->processedFile = $processedFile;
    }
    
    /**
     * Obtiene todas las notificaciones con estado
     */
    public function getAllNotifications($limit = 50) {
        $notifications = [];
        
        if (!file_exists($this->logFile)) {
            return ['success' => true, 'notifications' => [], 'total' => 0];
        }
        
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $processedIds = $this->getProcessedIds();
        
        foreach (array_reverse($lines) as $index => $line) {
            if (count($notifications) >= $limit) break;
            
            $notification = $this->parseLogLine($line, $index);
            if ($notification) {
                $notification['id'] = md5($line . $index);
                $notification['processed'] = in_array($notification['id'], $processedIds);
                $notification['index'] = $index;
                $notifications[] = $notification;
            }
        }
        
        return [
            'success' => true,
            'notifications' => $notifications,
            'total' => count($lines),
            'unread' => count(array_filter($notifications, fn($n) => !$n['processed']))
        ];
    }
    
    /**
     * Parsea una línea del log y extrae los datos
     */
    private function parseLogLine($line, $index) {
        // Extraer fecha si existe
        $datePattern = '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s*\|\s*/';
        $hasDate = preg_match($datePattern, $line, $dateMatches);
        
        $date = $hasDate ? $dateMatches[1] : date('Y-m-d H:i:s');
        $content = $hasDate ? preg_replace($datePattern, '', $line) : $line;
        
        // Parsear campos
        $fields = [];
        $parts = explode(' | ', $content);
        
        foreach ($parts as $part) {
            if (strpos($part, ': ') !== false) {
                list($key, $value) = explode(': ', $part, 2);
                $fields[strtolower(trim($key))] = trim($value);
            }
        }
        
        if (empty($fields)) return null;
        
        return [
            'date' => $date,
            'name' => $fields['nombre'] ?? 'Sin nombre',
            'code' => $fields['código'] ?? $fields['codigo'] ?? '',
            'phone' => $fields['teléfono'] ?? $fields['telefono'] ?? '',
            'address' => $fields['dirección'] ?? $fields['direccion'] ?? '',
            'email' => $fields['email'] ?? '',
            'description' => $fields['descripción'] ?? $fields['descripcion'] ?? '',
            'raw_line' => $line,
            'priority' => $this->calculatePriority($fields)
        ];
    }
    
    /**
     * Calcula la prioridad basada en el contenido
     */
    private function calculatePriority($fields) {
        $description = strtolower($fields['descripción'] ?? $fields['descripcion'] ?? '');
        
        // Palabras clave que indican urgencia
        $urgentKeywords = ['urgente', 'rápido', 'pronto', 'inmediato'];
        $serviceKeywords = ['instalación', 'reparación', 'barnizado', 'servicio'];
        
        foreach ($urgentKeywords as $keyword) {
            if (strpos($description, $keyword) !== false) {
                return 'high';
            }
        }
        
        foreach ($serviceKeywords as $keyword) {
            if (strpos($description, $keyword) !== false) {
                return 'medium';
            }
        }
        
        return 'normal';
    }
    
    /**
     * Marca una notificación como procesada
     */
    public function markAsProcessed($notificationId) {
        $processedIds = $this->getProcessedIds();
        
        if (!in_array($notificationId, $processedIds)) {
            $processedIds[] = $notificationId;
            file_put_contents($this->processedFile, implode("\n", $processedIds));
        }
        
        return ['success' => true, 'message' => 'Notificación marcada como procesada'];
    }
    
    /**
     * Elimina una notificación del log
     */
    public function deleteNotification($index) {
        if (!file_exists($this->logFile)) {
            return ['success' => false, 'message' => 'Archivo de log no encontrado'];
        }
        
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES);
        
        if (!isset($lines[$index])) {
            return ['success' => false, 'message' => 'Notificación no encontrada'];
        }
        
        // Eliminar la línea
        array_splice($lines, $index, 1);
        
        // Guardar archivo actualizado
        file_put_contents($this->logFile, implode("\n", $lines) . "\n");
        
        return ['success' => true, 'message' => 'Notificación eliminada correctamente'];
    }
    
    /**
     * Archiva notificaciones antiguas
     */
    public function archiveOldNotifications($days = 30) {
        if (!file_exists($this->logFile)) {
            return ['success' => false, 'message' => 'Archivo de log no encontrado'];
        }
        
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES);
        $cutoffDate = date('Y-m-d', strtotime("-$days days"));
        $archivedCount = 0;
        $remainingLines = [];
        
        foreach ($lines as $line) {
            $notification = $this->parseLogLine($line, 0);
            
            if ($notification && $notification['date'] < $cutoffDate) {
                // Archivar notificación
                file_put_contents('archived_logs.txt', $line . "\n", FILE_APPEND);
                $archivedCount++;
            } else {
                $remainingLines[] = $line;
            }
        }
        
        // Actualizar archivo principal
        file_put_contents($this->logFile, implode("\n", $remainingLines) . "\n");
        
        return [
            'success' => true,
            'message' => "$archivedCount notificaciones archivadas",
            'archived_count' => $archivedCount
        ];
    }
    
    /**
     * Obtiene estadísticas de las notificaciones
     */
    public function getStatistics() {
        $result = $this->getAllNotifications(1000);
        $notifications = $result['notifications'];
        
        $stats = [
            'total' => count($notifications),
            'unread' => count(array_filter($notifications, fn($n) => !$n['processed'])),
            'today' => 0,
            'this_week' => 0,
            'priority_high' => 0,
            'priority_medium' => 0,
            'priority_normal' => 0
        ];
        
        $today = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        
        foreach ($notifications as $notification) {
            $notificationDate = date('Y-m-d', strtotime($notification['date']));
            
            if ($notificationDate === $today) {
                $stats['today']++;
            }
            
            if ($notificationDate >= $weekStart) {
                $stats['this_week']++;
            }
            
            $stats['priority_' . $notification['priority']]++;
        }
        
        return ['success' => true, 'stats' => $stats];
    }
    
    /**
     * Busca notificaciones por criterios
     */
    public function searchNotifications($query, $filters = []) {
        $result = $this->getAllNotifications(1000);
        $notifications = $result['notifications'];
        
        $filtered = array_filter($notifications, function($notification) use ($query, $filters) {
            // Búsqueda por texto
            if (!empty($query)) {
                $searchText = strtolower($query);
                $notificationText = strtolower(implode(' ', [
                    $notification['name'],
                    $notification['description'],
                    $notification['email'],
                    $notification['phone']
                ]));
                
                if (strpos($notificationText, $searchText) === false) {
                    return false;
                }
            }
            
            // Filtros adicionales
            if (!empty($filters['status'])) {
                if ($filters['status'] === 'unread' && $notification['processed']) {
                    return false;
                }
                if ($filters['status'] === 'read' && !$notification['processed']) {
                    return false;
                }
            }
            
            if (!empty($filters['priority']) && $notification['priority'] !== $filters['priority']) {
                return false;
            }
            
            if (!empty($filters['date_from'])) {
                if ($notification['date'] < $filters['date_from']) {
                    return false;
                }
            }
            
            if (!empty($filters['date_to'])) {
                if ($notification['date'] > $filters['date_to']) {
                    return false;
                }
            }
            
            return true;
        });
        
        return [
            'success' => true,
            'notifications' => array_values($filtered),
            'total' => count($filtered)
        ];
    }
    
    /**
     * Obtiene IDs de notificaciones procesadas
     */
    private function getProcessedIds() {
        if (!file_exists($this->processedFile)) {
            return [];
        }
        
        return array_filter(file($this->processedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
    }
}

