<?php
// api/expenses.php - Expenses and Pricing API
require_once '../php/db.php';
require_once '../php/auth.php';

requireLogin();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'POST':
            if (isset($_GET['action'])) {
                switch($_GET['action']) {
                    case 'add_expense':
                        addExpense();
                        break;
                    case 'update_pricing':
                        updatePricing();
                        break;
                    default:
                        echo json_encode(['success' => false, 'message' => 'Invalid action']);
                }
            }
            break;
            
        case 'GET':
            if (isset($_GET['action'])) {
                switch($_GET['action']) {
                    case 'get_pricing':
                        getPricing();
                        break;
                    case 'profit_loss_report':
                        getProfitLossReport();
                        break;
                    default:
                        echo json_encode(['success' => false, 'message' => 'Invalid action']);
                }
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function addExpense() {
    global $conn;
    
    // Check permission - subadmins and admins can add expenses
    if (!canPerform('add_expense')) {
        echo json_encode(['success' => false, 'message' => 'You do not have permission to add expenses']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['order_id']) || !isset($input['expenses'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID and expenses amount required']);
        return;
    }
    
    $orderId = $conn->real_escape_string($input['order_id']);
    $expenses = (float)$input['expenses'];
    $notes = isset($input['notes']) ? $conn->real_escape_string($input['notes']) : '';
    $userId = $_SESSION['user_id'];
    
    // Get order details
    $orderSql = "SELECT * FROM orders WHERE id = '$orderId'";
    $orderResult = $conn->query($orderSql);
    
    if ($orderResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        return;
    }
    
    $order = $orderResult->fetch_assoc();
    
    // Calculate revenue based on package type
    $packLower = strtolower($order['pack']);
    $revenue = match($packLower) {
        'starter' => 18000,
        'bundle' => 32000,
        'collection' => 45000,
        default => 0
    };
    
    // Calculate profit (revenue - expenses)
    $profit = $revenue - $expenses;
    
    // Update order with expenses and profit
    $updateSql = "UPDATE orders 
                  SET expenses = $expenses, 
                      profit = $profit,
                      expenses_notes = '$notes',
                      expenses_added_by = $userId,
                      expenses_added_at = NOW()
                  WHERE id = '$orderId'";
    
    if ($conn->query($updateSql)) {
        // Log activity
        logActivity(
            $userId,
            'add_expense',
            'order',
            $orderId,
            "Added expenses ₦" . number_format($expenses) . " to order #{$orderId}",
            null,
            ['expenses' => $expenses, 'profit' => $profit, 'notes' => $notes]
        );
        
        echo json_encode([
            'success' => true,
            'message' => 'Expenses added successfully',
            'data' => [
                'expenses' => $expenses,
                'profit' => $profit,
                'revenue' => $revenue
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add expenses: ' . $conn->error]);
    }
}

function updatePricing() {
    global $conn;
    
    // Only admins can update pricing
    if (!isAdmin()) {
        echo json_encode(['success' => false, 'message' => 'Only administrators can update pricing']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['package_type']) || !isset($input['price'])) {
        echo json_encode(['success' => false, 'message' => 'Package type and price required']);
        return;
    }
    
    $packageType = $conn->real_escape_string($input['package_type']);
    $newPrice = (float)$input['price'];
    $newCost = isset($input['cost']) ? (float)$input['cost'] : null;
    $notes = isset($input['notes']) ? $conn->real_escape_string($input['notes']) : '';
    $userId = $_SESSION['user_id'];
    
    // Get current pricing
    $currentSql = "SELECT * FROM package_pricing WHERE package_type = '$packageType'";
    $currentResult = $conn->query($currentSql);
    
    if ($currentResult && $currentResult->num_rows > 0) {
        $current = $currentResult->fetch_assoc();
        $oldPrice = $current['price'];
        $oldCost = $current['cost_per_unit'];
        
        // Update pricing
        $updateSql = "UPDATE package_pricing 
                      SET price = $newPrice, 
                          cost_per_unit = " . ($newCost !== null ? $newCost : $oldCost) . ",
                          updated_by = $userId 
                      WHERE package_type = '$packageType'";
        
        if ($conn->query($updateSql)) {
            // Log pricing history
            $historySql = "INSERT INTO pricing_history 
                          (package_type, old_price, new_price, old_cost, new_cost, changed_by, notes) 
                          VALUES ('$packageType', $oldPrice, $newPrice, $oldCost, " . 
                          ($newCost !== null ? $newCost : $oldCost) . ", $userId, '$notes')";
            $conn->query($historySql);
            
            // Log activity
            logActivity(
                $userId,
                'update_pricing',
                'pricing',
                null,
                "Updated {$packageType} pricing from ₦" . number_format($oldPrice) . " to ₦" . number_format($newPrice),
                ['price' => $oldPrice],
                ['price' => $newPrice, 'notes' => $notes]
            );
            
            echo json_encode([
                'success' => true,
                'message' => 'Pricing updated successfully',
                'data' => [
                    'package_type' => $packageType,
                    'old_price' => $oldPrice,
                    'new_price' => $newPrice
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update pricing: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Package type not found']);
    }
}

function getPricing() {
    global $conn;
    
    $sql = "SELECT * FROM package_pricing ORDER BY 
            FIELD(package_type, 'starter', 'bundle', 'collection')";
    $result = $conn->query($sql);
    
    $pricing = [];
    while ($row = $result->fetch_assoc()) {
        $pricing[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $pricing]);
}

function getProfitLossReport() {
    global $conn;
    
    // Only admins can view full profit/loss reports
    if (!isAdmin()) {
        echo json_encode(['success' => false, 'message' => 'Only administrators can view profit/loss reports']);
        return;
    }
    
    $period = isset($_GET['period']) ? $_GET['period'] : 'month';
    $startDate = '';
    $endDate = date('Y-m-d');
    
    switch($period) {
        case 'week':
            $startDate = date('Y-m-d', strtotime('-7 days'));
            break;
        case 'month':
            $startDate = date('Y-m-01');
            break;
        case 'year':
            $startDate = date('Y-01-01');
            break;
        default:
            $startDate = date('Y-m-01');
    }
    
    if (isset($_GET['start_date'])) {
        $startDate = $conn->real_escape_string($_GET['start_date']);
    }
    if (isset($_GET['end_date'])) {
        $endDate = $conn->real_escape_string($_GET['end_date']);
    }
    
    // Get delivered orders with expenses in date range
    $sql = "SELECT o.*, 
            CASE 
                WHEN LOWER(o.pack) = 'starter' THEN 18000
                WHEN LOWER(o.pack) = 'bundle' THEN 32000
                WHEN LOWER(o.pack) = 'collection' THEN 45000
                ELSE 0
            END as revenue
            FROM orders o
            WHERE o.status = 'delivered'
            AND o.delivered_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
            ORDER BY o.delivered_at DESC";
    
    $result = $conn->query($sql);
    
    $orders = [];
    $totalRevenue = 0;
    $totalExpenses = 0;
    $totalProfit = 0;
    $ordersWithExpenses = 0;
    $ordersWithoutExpenses = 0;
    
    while ($row = $result->fetch_assoc()) {
        $revenue = (float)$row['revenue'];
        $expenses = (float)$row['expenses'];
        $profit = $revenue - $expenses;
        
        $row['revenue'] = $revenue;
        $row['profit'] = $profit;
        $row['formatted_revenue'] = '₦' . number_format($revenue);
        $row['formatted_expenses'] = '₦' . number_format($expenses);
        $row['formatted_profit'] = '₦' . number_format($profit);
        
        $orders[] = $row;
        
        $totalRevenue += $revenue;
        $totalExpenses += $expenses;
        $totalProfit += $profit;
        
        if ($expenses > 0) {
            $ordersWithExpenses++;
        } else {
            $ordersWithoutExpenses++;
        }
    }
    
    $profitMargin = $totalRevenue > 0 ? (($totalProfit / $totalRevenue) * 100) : 0;
    
    echo json_encode([
        'success' => true,
        'data' => [
            'orders' => $orders,
            'summary' => [
                'period' => $period,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_orders' => count($orders),
                'orders_with_expenses' => $ordersWithExpenses,
                'orders_without_expenses' => $ordersWithoutExpenses,
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
                'total_profit' => $totalProfit,
                'profit_margin' => round($profitMargin, 2),
                'formatted_revenue' => '₦' . number_format($totalRevenue),
                'formatted_expenses' => '₦' . number_format($totalExpenses),
                'formatted_profit' => '₦' . number_format($totalProfit)
            ]
        ]
    ]);
}
?>
