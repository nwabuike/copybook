<?php
// api/notifications.php - Notification Helper API
require_once '../php/db.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$action = isset($_GET['action']) ? $_GET['action'] : 'check_all';

try {
    switch($action) {
        case 'pending_orders':
            checkPendingOrders();
            break;
        case 'low_stock':
            checkLowStock();
            break;
        case 'follow_ups':
            checkFollowUps();
            break;
        case 'check_all':
            checkAll();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function checkPendingOrders() {
    global $conn;
    
    $threshold = isset($_GET['threshold']) ? (int)$_GET['threshold'] : 30; // minutes
    $thresholdDate = date('Y-m-d H:i:s', strtotime("-{$threshold} minutes"));
    
    $sql = "SELECT COUNT(*) as count, 
            GROUP_CONCAT(CONCAT(id, ': ', fullname) SEPARATOR ', ') as orders
            FROM orders 
            WHERE status = 'pending' 
            AND created_at < '$thresholdDate'";
    
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'type' => 'pending_orders',
        'count' => $data['count'],
        'threshold_minutes' => $threshold,
        'alert' => $data['count'] > 0,
        'message' => $data['count'] > 0 ? 
            "{$data['count']} order(s) pending for over {$threshold} minutes" : 
            "No pending orders requiring attention",
        'details' => $data['orders']
    ]);
}

function checkLowStock() {
    global $conn;
    
    $threshold = isset($_GET['threshold']) ? (int)$_GET['threshold'] : 5;
    
    $sql = "SELECT 
            SUM(CASE WHEN quantity <= $threshold AND quantity > 0 THEN 1 ELSE 0 END) as low_stock,
            SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) as out_of_stock,
            GROUP_CONCAT(
                CASE WHEN quantity = 0 
                THEN CONCAT(state, ' - ', package_type, ' (OUT)')
                ELSE NULL END
                SEPARATOR ', '
            ) as out_items,
            GROUP_CONCAT(
                CASE WHEN quantity <= $threshold AND quantity > 0 
                THEN CONCAT(state, ' - ', package_type, ' (', quantity, ')')
                ELSE NULL END
                SEPARATOR ', '
            ) as low_items
            FROM stock_inventory";
    
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    
    $lowStock = (int)$data['low_stock'];
    $outOfStock = (int)$data['out_of_stock'];
    
    echo json_encode([
        'success' => true,
        'type' => 'stock_alert',
        'low_stock_count' => $lowStock,
        'out_of_stock_count' => $outOfStock,
        'threshold' => $threshold,
        'alert' => ($lowStock > 0 || $outOfStock > 0),
        'urgent' => $outOfStock > 0,
        'message' => $outOfStock > 0 ? 
            "{$outOfStock} item(s) out of stock, {$lowStock} running low" :
            ($lowStock > 0 ? "{$lowStock} item(s) running low" : "Stock levels normal"),
        'out_of_stock_items' => $data['out_items'],
        'low_stock_items' => $data['low_items']
    ]);
}

function checkFollowUps() {
    global $conn;
    
    $threshold = isset($_GET['threshold']) ? (int)$_GET['threshold'] : 60; // minutes
    $thresholdDate = date('Y-m-d H:i:s', strtotime("-{$threshold} minutes"));
    
    $sql = "SELECT COUNT(*) as count,
            GROUP_CONCAT(CONCAT(id, ': ', fullname, ' (', state, ')') SEPARATOR ', ') as orders
            FROM orders 
            WHERE status = 'confirmed' 
            AND (
                (confirmed_at IS NOT NULL AND confirmed_at < '$thresholdDate')
                OR (confirmed_at IS NULL AND created_at < '$thresholdDate')
            )";
    
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'type' => 'follow_up',
        'count' => $data['count'],
        'threshold_minutes' => $threshold,
        'alert' => $data['count'] > 0,
        'message' => $data['count'] > 0 ? 
            "{$data['count']} confirmed order(s) need follow-up" : 
            "All confirmed orders have been followed up",
        'details' => $data['orders']
    ]);
}

function checkAll() {
    global $conn;
    
    $pendingThreshold = isset($_GET['pending_threshold']) ? (int)$_GET['pending_threshold'] : 30;
    $stockThreshold = isset($_GET['stock_threshold']) ? (int)$_GET['stock_threshold'] : 5;
    $followupThreshold = isset($_GET['followup_threshold']) ? (int)$_GET['followup_threshold'] : 60;
    
    $alerts = [];
    
    // Check pending orders
    $pendingDate = date('Y-m-d H:i:s', strtotime("-{$pendingThreshold} minutes"));
    $pendingResult = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending' AND created_at < '$pendingDate'");
    $pendingCount = $pendingResult->fetch_assoc()['count'];
    
    if ($pendingCount > 0) {
        $alerts[] = [
            'type' => 'pending_orders',
            'severity' => 'urgent',
            'count' => $pendingCount,
            'message' => "{$pendingCount} order(s) pending for over {$pendingThreshold} minutes"
        ];
    }
    
    // Check stock
    $stockResult = $conn->query("SELECT 
        SUM(CASE WHEN quantity <= $stockThreshold AND quantity > 0 THEN 1 ELSE 0 END) as low_stock,
        SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) as out_of_stock
        FROM stock_inventory");
    $stockData = $stockResult->fetch_assoc();
    
    if ((int)$stockData['out_of_stock'] > 0) {
        $alerts[] = [
            'type' => 'out_of_stock',
            'severity' => 'urgent',
            'count' => $stockData['out_of_stock'],
            'message' => "{$stockData['out_of_stock']} item(s) out of stock"
        ];
    }
    
    if ((int)$stockData['low_stock'] > 0) {
        $alerts[] = [
            'type' => 'low_stock',
            'severity' => 'warning',
            'count' => $stockData['low_stock'],
            'message' => "{$stockData['low_stock']} item(s) running low"
        ];
    }
    
    // Check follow-ups
    $followupDate = date('Y-m-d H:i:s', strtotime("-{$followupThreshold} minutes"));
    $followupResult = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'confirmed' AND 
        ((confirmed_at IS NOT NULL AND confirmed_at < '$followupDate') OR (confirmed_at IS NULL AND created_at < '$followupDate'))");
    $followupCount = $followupResult->fetch_assoc()['count'];
    
    if ($followupCount > 0) {
        $alerts[] = [
            'type' => 'follow_up',
            'severity' => 'warning',
            'count' => $followupCount,
            'message' => "{$followupCount} confirmed order(s) need follow-up"
        ];
    }
    
    echo json_encode([
        'success' => true,
        'timestamp' => date('Y-m-d H:i:s'),
        'alerts_count' => count($alerts),
        'has_alerts' => count($alerts) > 0,
        'has_urgent' => count(array_filter($alerts, function($a) { return $a['severity'] === 'urgent'; })) > 0,
        'alerts' => $alerts,
        'summary' => count($alerts) > 0 ? 
            count($alerts) . " alert(s) require attention" : 
            "All systems normal"
    ]);
}
?>
