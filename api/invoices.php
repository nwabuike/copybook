<?php
/**
 * Invoice API - Send/Resend invoices
 */

require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/invoice_generator.php';

// Require admin or agent login
requireLogin();

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'send':
        sendInvoiceManually();
        break;
    
    case 'preview':
        previewInvoice();
        break;
    
    case 'check_status':
        checkInvoiceStatus();
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

/**
 * Send or resend invoice manually
 */
function sendInvoiceManually() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $orderId = $conn->real_escape_string($input['order_id']);
    
    // Get order details
    $sql = "SELECT * FROM orders WHERE id = '$orderId'";
    $result = $conn->query($sql);
    
    if (!$result || $result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        return;
    }
    
    $order = $result->fetch_assoc();
    
    // Check if order is delivered
    if (strtolower($order['status']) !== 'delivered') {
        echo json_encode([
            'success' => false,
            'message' => 'Invoice can only be sent for delivered orders',
            'error' => 'ORDER_NOT_DELIVERED'
        ]);
        return;
    }
    
    // Check if customer has email
    if (empty($order['email'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Customer email not provided',
            'error' => 'NO_EMAIL'
        ]);
        return;
    }
    
    // Send invoice
    $result = sendInvoiceEmail($order);
    
    // Update invoice tracking if successful
    if ($result['success']) {
        $conn->query("UPDATE orders SET invoice_sent = 1, invoice_sent_at = NOW() WHERE id = '$orderId'");
        
        // Log activity
        if (function_exists('logActivity') && isset($_SESSION['user_id'])) {
            logActivity(
                $_SESSION['user_id'],
                'send_invoice',
                'order',
                $orderId,
                "Invoice manually sent to {$order['email']}",
                [],
                ['invoice_sent' => true, 'method' => 'manual']
            );
        }
    }
    
    echo json_encode($result);
}

/**
 * Preview invoice HTML (for testing/debugging)
 */
function previewInvoice() {
    global $conn;
    
    if (!isset($_GET['order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $orderId = $conn->real_escape_string($_GET['order_id']);
    
    // Get order details
    $sql = "SELECT * FROM orders WHERE id = '$orderId'";
    $result = $conn->query($sql);
    
    if (!$result || $result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        return;
    }
    
    $order = $result->fetch_assoc();
    
    // Generate and return HTML directly (not JSON)
    header('Content-Type: text/html');
    echo generateInvoiceHTML($order);
}

/**
 * Check invoice status for an order
 */
function checkInvoiceStatus() {
    global $conn;
    
    if (!isset($_GET['order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $orderId = $conn->real_escape_string($_GET['order_id']);
    
    // Get order details
    $sql = "SELECT id, status, email, invoice_sent, invoice_sent_at FROM orders WHERE id = '$orderId'";
    $result = $conn->query($sql);
    
    if (!$result || $result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        return;
    }
    
    $order = $result->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'order_id' => $order['id'],
            'status' => $order['status'],
            'has_email' => !empty($order['email']),
            'email' => $order['email'],
            'invoice_sent' => (bool)$order['invoice_sent'],
            'invoice_sent_at' => $order['invoice_sent_at'],
            'can_send_invoice' => strtolower($order['status']) === 'delivered' && !empty($order['email'])
        ]
    ]);
}
