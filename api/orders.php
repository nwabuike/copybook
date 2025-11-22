<?php
// api/orders.php - Order Management API
require_once '../php/db.php';
require_once '../php/auth.php';

// Require authentication for all order operations
requireLogin();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            if (isset($_GET['action'])) {
                switch($_GET['action']) {
                    case 'stats':
                        getOrderStats();
                        break;
                    case 'list':
                        listOrders();
                        break;
                    case 'single':
                        getSingleOrder();
                        break;
                    case 'sales_report':
                        getSalesReport();
                        break;
                    default:
                        listOrders();
                }
            } else {
                listOrders();
            }
            break;
            
        case 'POST':
            if (isset($_GET['action'])) {
                switch($_GET['action']) {
                    case 'update_status':
                        updateOrderStatus();
                        break;
                    default:
                        echo json_encode(['success' => false, 'message' => 'Invalid action']);
                }
            }
            break;
            
        case 'DELETE':
            deleteOrder();
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function listOrders() {
    global $conn;
    
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $offset = ($page - 1) * $perPage;
    
    // Build WHERE clause
    $where = [];
    
    // If user is an agent, filter by their assigned states
    if (isAgent()) {
        $agentStates = getAgentStates();
        if (!empty($agentStates)) {
            $statesQuoted = array_map(function($state) use ($conn) {
                return "'" . $conn->real_escape_string($state) . "'";
            }, $agentStates);
            $where[] = "o.state IN (" . implode(',', $statesQuoted) . ")";
        } else {
            // Agent has no states assigned, show no orders
            $where[] = "1 = 0";
        }
    }
    
    if (!empty($search)) {
        $where[] = "(o.id LIKE '%$search%' OR o.fullname LIKE '%$search%' OR o.email LIKE '%$search%' OR o.phone LIKE '%$search%')";
    }
    if (!empty($status)) {
        $where[] = "o.status = '$status'";
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM orders o $whereClause";
    $countResult = $conn->query($countSql);
    $total = $countResult->fetch_assoc()['total'];
    
    // Get orders with agent info
    $sql = "SELECT o.*, da.name as agent_name, da.phone as agent_phone
            FROM orders o
            LEFT JOIN delivery_agents da ON o.agent_id = da.id
            $whereClause
            ORDER BY o.created_at DESC
            LIMIT $offset, $perPage";
    
    $result = $conn->query($sql);
    $orders = [];
    
    while ($row = $result->fetch_assoc()) {
        // Calculate amount based on package
        $amount = 0;
        switch(strtolower($row['pack'])) {
            case 'starter': $amount = 18000; break;
            case 'bundle': $amount = 32000; break;
            case 'collection': $amount = 45000; break;
            default: $amount = 0;
        }
        
        $row['amount'] = $amount;
        $row['formatted_amount'] = '₦' . number_format($amount);
        $orders[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $orders,
        'pagination' => [
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ]
    ]);
}

function getSingleOrder() {
    global $conn;
    
    if (!isset($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $orderId = $conn->real_escape_string($_GET['id']);
    
    // Build WHERE clause
    $where = ["o.id = '$orderId'"];
    
    // If user is an agent, ensure order is in their assigned states
    if (isAgent()) {
        $agentStates = getAgentStates();
        if (!empty($agentStates)) {
            $statesQuoted = array_map(function($state) use ($conn) {
                return "'" . $conn->real_escape_string($state) . "'";
            }, $agentStates);
            $where[] = "o.state IN (" . implode(',', $statesQuoted) . ")";
        } else {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }
    }
    
    $whereClause = 'WHERE ' . implode(' AND ', $where);
    
    $sql = "SELECT o.*, da.name as agent_name, da.phone as agent_phone, da.email as agent_email
            FROM orders o
            LEFT JOIN delivery_agents da ON o.agent_id = da.id
            $whereClause";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        
        // Calculate amount
        $amount = 0;
        switch(strtolower($order['pack'])) {
            case 'starter': $amount = 18000; break;
            case 'bundle': $amount = 32000; break;
            case 'collection': $amount = 45000; break;
        }
        
        $order['amount'] = $amount;
        $order['formatted_amount'] = '₦' . number_format($amount);
        
        echo json_encode(['success' => true, 'data' => $order]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
    }
}

function getOrderStats() {
    global $conn;
    
    // Build WHERE clause for agents
    $whereClause = '';
    if (isAgent()) {
        $agentStates = getAgentStates();
        if (!empty($agentStates)) {
            $statesQuoted = array_map(function($state) use ($conn) {
                return "'" . $conn->real_escape_string($state) . "'";
            }, $agentStates);
            $whereClause = "WHERE state IN (" . implode(',', $statesQuoted) . ")";
        } else {
            $whereClause = "WHERE 1 = 0";
        }
    }
    
    $sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END) as shipped,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
            FROM orders $whereClause";
    
    $result = $conn->query($sql);
    $stats = $result->fetch_assoc();
    
    echo json_encode(['success' => true, 'data' => $stats]);
}

function getSalesReport() {
    global $conn;
    
    $startDate = isset($_GET['start_date']) ? $conn->real_escape_string($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
    $endDate = isset($_GET['end_date']) ? $conn->real_escape_string($_GET['end_date']) : date('Y-m-d');
    $groupBy = isset($_GET['group_by']) ? $conn->real_escape_string($_GET['group_by']) : 'day';
    
    // Build WHERE clause for date range and agent states
    $where = [];
    $where[] = "o.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
    
    // If user is an agent, filter by their assigned states
    if (isAgent()) {
        $agentStates = getAgentStates();
        if (!empty($agentStates)) {
            $statesQuoted = array_map(function($state) use ($conn) {
                return "'" . $conn->real_escape_string($state) . "'";
            }, $agentStates);
            $where[] = "o.state IN (" . implode(',', $statesQuoted) . ")";
        } else {
            $where[] = "1 = 0";
        }
    }
    
    $whereClause = 'WHERE ' . implode(' AND ', $where);
    
    // Get all orders in date range
    $ordersSql = "SELECT o.*, da.name as agent_name
                  FROM orders o
                  LEFT JOIN delivery_agents da ON o.agent_id = da.id
                  $whereClause
                  ORDER BY o.created_at DESC";
    
    $ordersResult = $conn->query($ordersSql);
    $orders = [];
    $totalRevenue = 0;
    $totalOrders = 0;
    $starterRevenue = 0;
    $bundleRevenue = 0;
    $collectionRevenue = 0;
    $orderCounts = [];
    
    $deliveredOrders = 0;
    $failedOrders = 0;
    
    while ($row = $ordersResult->fetch_assoc()) {
        $orders[] = $row;
        $totalOrders++;
        
        // Count delivered and failed orders
        if (strtolower($row['status']) === 'delivered') {
            $deliveredOrders++;
        } elseif (strtolower($row['status']) === 'cancelled') {
            $failedOrders++;
        }
        
        // Calculate revenue ONLY from delivered orders
        if (strtolower($row['status']) === 'delivered') {
            switch(strtolower($row['pack'])) {
                case 'starter':
                    $totalRevenue += 18000;
                    $starterRevenue += 18000;
                    break;
                case 'bundle':
                    $totalRevenue += 32000;
                    $bundleRevenue += 32000;
                    break;
                case 'collection':
                    $totalRevenue += 45000;
                    $collectionRevenue += 45000;
                    break;
            }
        }
        
        // Count by state
        $state = $row['state'];
        if (!isset($orderCounts[$state])) {
            $orderCounts[$state] = ['order_count' => 0, 'revenue' => 0];
        }
        $orderCounts[$state]['order_count']++;
        
        // Add revenue only from delivered orders
        if (strtolower($row['status']) === 'delivered') {
            $orderCounts[$state]['revenue'] += match(strtolower($row['pack'])) {
                'starter' => 18000,
                'bundle' => 32000,
                'collection' => 45000,
                default => 0
            };
        }
    }
    
    // Format top states
    $topStates = [];
    foreach ($orderCounts as $state => $data) {
        $topStates[] = [
            'state' => $state,
            'order_count' => $data['order_count'],
            'revenue' => $data['revenue']
        ];
    }
    
    // Sort by revenue descending
    usort($topStates, function($a, $b) {
        return $b['revenue'] - $a['revenue'];
    });
    
    $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;
    
    echo json_encode([
        'success' => true,
        'data' => [
            'orders' => $orders,
            'summary' => [
                'total_orders' => $totalOrders,
                'delivered_orders' => $deliveredOrders,
                'failed_orders' => $failedOrders,
                'total_revenue' => $totalRevenue,
                'formatted_revenue' => '₦' . number_format($totalRevenue),
                'average_order_value' => $avgOrderValue,
                'revenue_by_package' => [
                    'starter' => $starterRevenue,
                    'bundle' => $bundleRevenue,
                    'collection' => $collectionRevenue
                ],
                'top_states' => $topStates
            ]
        ]
    ]);
}

function updateOrderStatus() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['order_id']) || !isset($input['status'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID and status required']);
        return;
    }
    
    $orderId = $conn->real_escape_string($input['order_id']);
    $newStatus = $conn->real_escape_string($input['status']);
    $agentId = isset($input['agent_id']) ? (int)$input['agent_id'] : null;
    $notes = isset($input['notes']) ? $conn->real_escape_string($input['notes']) : '';
    
    // Build WHERE clause to check agent access
    $orderWhere = ["id = '$orderId'"];
    
    // If user is an agent, ensure they can only update orders in their states
    if (isAgent()) {
        $agentStates = getAgentStates();
        if (!empty($agentStates)) {
            $statesQuoted = array_map(function($state) use ($conn) {
                return "'" . $conn->real_escape_string($state) . "'";
            }, $agentStates);
            $orderWhere[] = "state IN (" . implode(',', $statesQuoted) . ")";
        } else {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }
    }
    
    $orderWhereClause = 'WHERE ' . implode(' AND ', $orderWhere);
    
    // Get current order details
    $orderSql = "SELECT * FROM orders $orderWhereClause";
    $orderResult = $conn->query($orderSql);
    
    if ($orderResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found or access denied']);
        return;
    }
    
    $order = $orderResult->fetch_assoc();
    $oldStatus = $order['status'];
    
    // Build update query
    $updateParts = ["status = '$newStatus'", "updated_by = " . $_SESSION['user_id']];
    
    if (!empty($notes)) {
        $updateParts[] = "admin_notes = '$notes'";
    }
    
    if ($agentId !== null) {
        $updateParts[] = "agent_id = $agentId";
    }
    
    // Set timestamp for confirmed
    if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed' && empty($order['confirmed_at'])) {
        $updateParts[] = "confirmed_at = NOW()";
    }
    
    // Set timestamp for delivered
    if ($newStatus === 'delivered' && $oldStatus !== 'delivered' && empty($order['delivered_at'])) {
        $updateParts[] = "delivered_at = NOW()";
        
        // Update stock - reduce inventory
        $packageType = strtolower($order['pack']);
        $state = $order['state'];
        $quantity = isset($order['quantity']) ? (int)$order['quantity'] : 1;
        
        $stockSql = "UPDATE stock_inventory 
                    SET quantity = quantity - $quantity 
                    WHERE state = '$state' AND package_type = '$packageType' AND quantity >= $quantity";
        $conn->query($stockSql);
        
        // Record stock movement
        $userId = $_SESSION['user_id'];
        $movementSql = "INSERT INTO stock_movements 
                        (state, package_type, quantity_change, movement_type, reference_id, agent_id, notes, created_by)
                        VALUES ('$state', '$packageType', -$quantity, 'sale', $orderId, " . 
                        ($agentId ? $agentId : "NULL") . ", 'Order delivered', '$userId')";
        $conn->query($movementSql);
    }
    
    $updateSql = "UPDATE orders SET " . implode(', ', $updateParts) . " WHERE id = '$orderId'";
    
    if ($conn->query($updateSql)) {
        // Log activity
        logActivity(
            $_SESSION['user_id'],
            'update_status',
            'order',
            $orderId,
            "Updated order #{$orderId} status from {$oldStatus} to {$newStatus}",
            ['status' => $oldStatus],
            ['status' => $newStatus, 'notes' => $notes]
        );
        
        echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order: ' . $conn->error]);
    }
}

function deleteOrder() {
    global $conn;
    
    // Check permission
    if (!canPerform('delete_order')) {
        echo json_encode(['success' => false, 'message' => 'You do not have permission to delete orders']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $orderId = $conn->real_escape_string($input['order_id']);
    
    // Get order info for logging
    $orderSql = "SELECT * FROM orders WHERE id = '$orderId'";
    $orderResult = $conn->query($orderSql);
    $order = $orderResult->fetch_assoc();
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        return;
    }
    
    $sql = "DELETE FROM orders WHERE id = '$orderId'";
    
    if ($conn->query($sql)) {
        // Log activity
        logActivity(
            $_SESSION['user_id'],
            'delete',
            'order',
            $orderId,
            "Deleted order #{$orderId} - Customer: {$order['fullname']}",
            $order,
            null
        );
        
        echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete order: ' . $conn->error]);
    }
}
?>
