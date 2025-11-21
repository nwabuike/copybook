<?php
// api/stock.php - Stock Management API
require_once '../php/db.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            if (isset($_GET['action'])) {
                switch($_GET['action']) {
                    case 'by_state':
                        getStockByState();
                        break;
                    case 'summary':
                        getStockSummary();
                        break;
                    case 'movements':
                        getStockMovements();
                        break;
                    case 'low_stock':
                        getLowStock();
                        break;
                    default:
                        getAllStock();
                }
            } else {
                getAllStock();
            }
            break;
            
        case 'POST':
            if (isset($_GET['action']) && $_GET['action'] === 'update') {
                updateStock();
            } else {
                addStockMovement();
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function getAllStock() {
    global $conn;
    
    $state = isset($_GET['state']) ? $conn->real_escape_string($_GET['state']) : '';
    $packageType = isset($_GET['package']) ? $conn->real_escape_string($_GET['package']) : '';
    
    $where = [];
    if (!empty($state)) {
        $where[] = "si.state = '$state'";
    }
    if (!empty($packageType)) {
        $where[] = "si.package_type = '$packageType'";
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    $sql = "SELECT si.*, da.name as agent_name, da.phone as agent_phone
            FROM stock_inventory si
            LEFT JOIN delivery_agents da ON si.agent_id = da.id
            $whereClause
            ORDER BY si.state, si.package_type";
    
    $result = $conn->query($sql);
    $stock = [];
    
    while ($row = $result->fetch_assoc()) {
        $stock[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $stock]);
}

function getStockByState() {
    global $conn;
    
    if (!isset($_GET['state'])) {
        echo json_encode(['success' => false, 'message' => 'State required']);
        return;
    }
    
    $state = $conn->real_escape_string($_GET['state']);
    
    $sql = "SELECT si.*, da.name as agent_name
            FROM stock_inventory si
            LEFT JOIN delivery_agents da ON si.agent_id = da.id
            WHERE si.state = '$state'
            ORDER BY si.package_type";
    
    $result = $conn->query($sql);
    $stock = [];
    
    while ($row = $result->fetch_assoc()) {
        $stock[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $stock]);
}

function getStockSummary() {
    global $conn;
    
    // Overall stock summary
    $sql = "SELECT 
                package_type,
                SUM(quantity) as total_quantity,
                COUNT(DISTINCT state) as states_with_stock,
                COUNT(DISTINCT agent_id) as agents_managing
            FROM stock_inventory
            GROUP BY package_type";
    
    $result = $conn->query($sql);
    $summary = [];
    
    while ($row = $result->fetch_assoc()) {
        $summary[] = $row;
    }
    
    // Total value calculation
    $valueSql = "SELECT 
                    SUM(CASE WHEN package_type = 'starter' THEN quantity * 18000 ELSE 0 END) as starter_value,
                    SUM(CASE WHEN package_type = 'bundle' THEN quantity * 32000 ELSE 0 END) as bundle_value,
                    SUM(CASE WHEN package_type = 'collection' THEN quantity * 45000 ELSE 0 END) as collection_value
                FROM stock_inventory";
    
    $valueResult = $conn->query($valueSql);
    $valueData = $valueResult->fetch_assoc();
    $totalValue = $valueData['starter_value'] + $valueData['bundle_value'] + $valueData['collection_value'];
    
    // States with low stock (less than 5)
    $lowStockSql = "SELECT COUNT(*) as low_stock_count 
                    FROM stock_inventory 
                    WHERE quantity < 5 AND quantity > 0";
    $lowStockResult = $conn->query($lowStockSql);
    $lowStockData = $lowStockResult->fetch_assoc();
    
    // Out of stock
    $outOfStockSql = "SELECT COUNT(*) as out_of_stock_count 
                      FROM stock_inventory 
                      WHERE quantity = 0";
    $outOfStockResult = $conn->query($outOfStockSql);
    $outOfStockData = $outOfStockResult->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'by_package' => $summary,
            'total_inventory_value' => $totalValue,
            'formatted_value' => '₦' . number_format($totalValue),
            'low_stock_alerts' => $lowStockData['low_stock_count'],
            'out_of_stock' => $outOfStockData['out_of_stock_count']
        ]
    ]);
}

function getLowStock() {
    global $conn;
    
    $threshold = isset($_GET['threshold']) ? (int)$_GET['threshold'] : 5;
    
    $sql = "SELECT si.*, da.name as agent_name
            FROM stock_inventory si
            LEFT JOIN delivery_agents da ON si.agent_id = da.id
            WHERE si.quantity <= $threshold
            ORDER BY si.quantity ASC, si.state";
    
    $result = $conn->query($sql);
    $lowStock = [];
    
    while ($row = $result->fetch_assoc()) {
        $lowStock[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $lowStock]);
}

function getStockMovements() {
    global $conn;
    
    $state = isset($_GET['state']) ? $conn->real_escape_string($_GET['state']) : '';
    $packageType = isset($_GET['package']) ? $conn->real_escape_string($_GET['package']) : '';
    $movementType = isset($_GET['movement_type']) ? $conn->real_escape_string($_GET['movement_type']) : '';
    $startDate = isset($_GET['start_date']) ? $conn->real_escape_string($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
    $endDate = isset($_GET['end_date']) ? $conn->real_escape_string($_GET['end_date']) : date('Y-m-d');
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    
    $where = ["sm.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'"];
    
    if (!empty($state)) {
        $where[] = "sm.state = '$state'";
    }
    if (!empty($packageType)) {
        $where[] = "sm.package_type = '$packageType'";
    }
    if (!empty($movementType)) {
        $where[] = "sm.movement_type = '$movementType'";
    }
    
    $whereClause = 'WHERE ' . implode(' AND ', $where);
    
    $sql = "SELECT sm.*, da.name as agent_name, o.fullname as customer_name
            FROM stock_movements sm
            LEFT JOIN delivery_agents da ON sm.agent_id = da.id
            LEFT JOIN orders o ON sm.reference_id = o.id AND sm.movement_type = 'sale'
            $whereClause
            ORDER BY sm.created_at DESC
            LIMIT $limit";
    
    $result = $conn->query($sql);
    $movements = [];
    
    while ($row = $result->fetch_assoc()) {
        $movements[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $movements]);
}

function updateStock() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['state']) || !isset($input['package_type']) || !isset($input['quantity'])) {
        echo json_encode(['success' => false, 'message' => 'State, package type, and quantity required']);
        return;
    }
    
    $state = $conn->real_escape_string($input['state']);
    $packageType = $conn->real_escape_string($input['package_type']);
    $newQuantity = (int)$input['quantity'];
    $updatedBy = isset($input['updated_by']) ? $conn->real_escape_string($input['updated_by']) : 'admin';
    
    // Get current quantity
    $currentSql = "SELECT quantity FROM stock_inventory WHERE state = '$state' AND package_type = '$packageType'";
    $currentResult = $conn->query($currentSql);
    
    if ($currentResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Stock record not found']);
        return;
    }
    
    $currentData = $currentResult->fetch_assoc();
    $oldQuantity = $currentData['quantity'];
    $quantityChange = $newQuantity - $oldQuantity;
    
    // Update stock
    $updateSql = "UPDATE stock_inventory 
                  SET quantity = $newQuantity, updated_by = '$updatedBy'
                  WHERE state = '$state' AND package_type = '$packageType'";
    
    if ($conn->query($updateSql)) {
        // Record movement
        $movementSql = "INSERT INTO stock_movements 
                        (state, package_type, quantity_change, movement_type, notes, created_by)
                        VALUES ('$state', '$packageType', $quantityChange, 'adjustment', 
                                'Manual stock update from $oldQuantity to $newQuantity', '$updatedBy')";
        $conn->query($movementSql);
        
        echo json_encode(['success' => true, 'message' => 'Stock updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update stock: ' . $conn->error]);
    }
}

function addStockMovement() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['state']) || !isset($input['package_type']) || 
        !isset($input['quantity_change']) || !isset($input['movement_type'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        return;
    }
    
    $state = $conn->real_escape_string($input['state']);
    $packageType = $conn->real_escape_string($input['package_type']);
    $quantityChange = (int)$input['quantity_change'];
    $movementType = $conn->real_escape_string($input['movement_type']);
    $agentId = isset($input['agent_id']) ? (int)$input['agent_id'] : null;
    $notes = isset($input['notes']) ? $conn->real_escape_string($input['notes']) : '';
    $createdBy = isset($input['created_by']) ? $conn->real_escape_string($input['created_by']) : 'admin';
    
    // Update stock inventory
    $updateSql = "UPDATE stock_inventory 
                  SET quantity = quantity + $quantityChange 
                  WHERE state = '$state' AND package_type = '$packageType'";
    
    if ($conn->query($updateSql)) {
        // Record movement
        $movementSql = "INSERT INTO stock_movements 
                        (state, package_type, quantity_change, movement_type, agent_id, notes, created_by)
                        VALUES ('$state', '$packageType', $quantityChange, '$movementType', " .
                        ($agentId ? $agentId : "NULL") . ", '$notes', '$createdBy')";
        
        if ($conn->query($movementSql)) {
            echo json_encode(['success' => true, 'message' => 'Stock movement recorded successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to record movement: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update stock: ' . $conn->error]);
    }
}
?>
