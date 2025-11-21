<?php
// api/activity_logs.php - Activity Logs API
require_once '../php/auth.php';

// Require admin for all activity log operations
requireAdmin();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $action = $_GET['action'] ?? 'list';
    
    switch($action) {
        case 'list':
            listActivityLogs();
            break;
        default:
            listActivityLogs();
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function listActivityLogs() {
    $filters = [];
    
    if (!empty($_GET['user_id'])) {
        $filters['user_id'] = (int)$_GET['user_id'];
    }
    
    if (!empty($_GET['action'])) {
        $filters['action'] = $_GET['action'];
    }
    
    if (!empty($_GET['entity_type'])) {
        $filters['entity_type'] = $_GET['entity_type'];
    }
    
    if (!empty($_GET['start_date'])) {
        $filters['start_date'] = $_GET['start_date'];
    }
    
    if (!empty($_GET['end_date'])) {
        $filters['end_date'] = $_GET['end_date'];
    }
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
    $offset = ($page - 1) * $perPage;
    
    $logs = getAllActivities($filters, $perPage, $offset);
    
    // Get total count
    global $conn;
    $where = [];
    $params = [];
    $types = "";
    
    if (!empty($filters['user_id'])) {
        $where[] = "al.user_id = ?";
        $params[] = $filters['user_id'];
        $types .= "i";
    }
    
    if (!empty($filters['action'])) {
        $where[] = "al.action = ?";
        $params[] = $filters['action'];
        $types .= "s";
    }
    
    if (!empty($filters['entity_type'])) {
        $where[] = "al.entity_type = ?";
        $params[] = $filters['entity_type'];
        $types .= "s";
    }
    
    if (!empty($filters['start_date'])) {
        $where[] = "DATE(al.created_at) >= ?";
        $params[] = $filters['start_date'];
        $types .= "s";
    }
    
    if (!empty($filters['end_date'])) {
        $where[] = "DATE(al.created_at) <= ?";
        $params[] = $filters['end_date'];
        $types .= "s";
    }
    
    $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
    
    $countSql = "SELECT COUNT(*) as total FROM activity_logs al $whereClause";
    $countStmt = $conn->prepare($countSql);
    
    if (!empty($params)) {
        $countStmt->bind_param($types, ...$params);
    }
    
    $countStmt->execute();
    $total = $countStmt->get_result()->fetch_assoc()['total'];
    
    echo json_encode([
        'success' => true,
        'data' => $logs,
        'pagination' => [
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ]
    ]);
}
?>
