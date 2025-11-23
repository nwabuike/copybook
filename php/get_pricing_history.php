<?php
// php/get_pricing_history.php - Get pricing change history
require_once 'auth.php';
require_once 'db.php';

requireAdmin();

header('Content-Type: application/json');

try {
    $sql = "SELECT ph.*, u.full_name as changed_by_name 
            FROM pricing_history ph
            LEFT JOIN users u ON ph.changed_by = u.id
            ORDER BY ph.changed_at DESC
            LIMIT 50";
    
    $result = $conn->query($sql);
    
    $history = [];
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $history]);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
