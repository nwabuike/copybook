<?php
/**
 * Invoice Generator for Smartkids Edu
 * Generates professional invoices for delivered orders
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailer_smtp.php';

/**
 * Generate HTML invoice for an order
 * 
 * @param array $order Order data from database
 * @return string HTML invoice
 */
function generateInvoiceHTML($order) {
    // Calculate amounts
    $amounts = [
        'starter' => 18000,
        'bundle' => 32000,
        'collection' => 45000
    ];
    
    $packageLower = strtolower($order['pack']);
    $unitPrice = $amounts[$packageLower] ?? 18000;
    $quantity = isset($order['quantity']) ? (int)$order['quantity'] : 1;
    $subtotal = $unitPrice * $quantity;
    $tax = 0; // No tax for now
    $total = $subtotal + $tax;
    
    // Format package name
    $packageNames = [
        'starter' => 'Starter Set (1 Set - 4 in 1 Book)',
        'bundle' => 'Learning Bundle (2 Sets - 4 in 1 Book)',
        'collection' => 'Mastery Collection (3 Sets - 4 in 1 Book)'
    ];
    $packageName = $packageNames[$packageLower] ?? ucfirst($order['pack']);
    
    // Format dates
    $invoiceDate = date('F d, Y');
    $orderDate = date('F d, Y', strtotime($order['created_at']));
    $deliveryDate = isset($order['delivered_at']) ? date('F d, Y', strtotime($order['delivered_at'])) : $invoiceDate;
    
    // Invoice number (ORDER-ID-YEAR-MONTH)
    $invoiceNumber = 'INV-' . $order['id'] . '-' . date('Ym');
    
    $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #' . $invoiceNumber . '</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #0a7c42 0%, #066633 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .company-name {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .invoice-title {
            background: #f8f9fa;
            padding: 20px 40px;
            border-bottom: 3px solid #0a7c42;
        }
        
        .invoice-title h2 {
            font-size: 24px;
            color: #0a7c42;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            color: #666;
            font-size: 14px;
        }
        
        .invoice-body {
            padding: 40px;
        }
        
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 20px;
        }
        
        .info-section {
            flex: 1;
        }
        
        .info-section h3 {
            font-size: 14px;
            color: #0a7c42;
            text-transform: uppercase;
            margin-bottom: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .info-section p {
            margin: 5px 0;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .info-label {
            color: #666;
            font-weight: 500;
        }
        
        .paid-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        .items-table thead {
            background: #f8f9fa;
        }
        
        .items-table th {
            padding: 15px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
        }
        
        .items-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }
        
        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }
        
        .item-description {
            color: #666;
            font-size: 13px;
            margin-top: 5px;
        }
        
        .totals {
            margin-left: auto;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 14px;
        }
        
        .total-row.subtotal {
            border-top: 2px solid #f0f0f0;
        }
        
        .total-row.grand-total {
            border-top: 3px solid #0a7c42;
            font-size: 18px;
            font-weight: bold;
            color: #0a7c42;
            padding-top: 15px;
            margin-top: 10px;
        }
        
        .invoice-footer {
            background: #f8f9fa;
            padding: 30px 40px;
            margin-top: 40px;
            border-top: 3px solid #0a7c42;
        }
        
        .footer-section {
            margin-bottom: 20px;
        }
        
        .footer-section h4 {
            font-size: 14px;
            color: #0a7c42;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .footer-section p {
            font-size: 13px;
            color: #666;
            line-height: 1.8;
        }
        
        .contact-info {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #666;
        }
        
        .thank-you {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #0a7c42 0%, #066633 100%);
            color: white;
            font-size: 16px;
            font-weight: 500;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                max-width: 100%;
            }
        }
        
        @media (max-width: 600px) {
            .invoice-header {
                padding: 30px 20px;
            }
            
            .company-name {
                font-size: 24px;
            }
            
            .invoice-body {
                padding: 20px;
            }
            
            .invoice-info {
                flex-direction: column;
            }
            
            .items-table {
                font-size: 12px;
            }
            
            .items-table th,
            .items-table td {
                padding: 10px;
            }
            
            .totals {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-name">Smartkids Edu</div>
            <div class="company-tagline">Premium Educational Materials for Young Learners</div>
        </div>
        
        <!-- Invoice Title -->
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <div class="invoice-number">' . htmlspecialchars($invoiceNumber) . '</div>
        </div>
        
        <!-- Invoice Body -->
        <div class="invoice-body">
            <!-- Invoice Info -->
            <div class="invoice-info">
                <div class="info-section">
                    <h3>Bill To</h3>
                    <p><strong>' . htmlspecialchars($order['fullname']) . '</strong></p>
                    <p>' . htmlspecialchars($order['address']) . '</p>
                    <p>' . htmlspecialchars($order['state']) . ', Nigeria</p>
                    <p class="info-label">Phone: ' . htmlspecialchars($order['phone']) . '</p>
                    ' . (!empty($order['email']) ? '<p class="info-label">Email: ' . htmlspecialchars($order['email']) . '</p>' : '') . '
                </div>
                
                <div class="info-section" style="text-align: right;">
                    <h3>Invoice Details</h3>
                    <p><span class="info-label">Invoice Date:</span> ' . $invoiceDate . '</p>
                    <p><span class="info-label">Order Date:</span> ' . $orderDate . '</p>
                    <p><span class="info-label">Delivery Date:</span> ' . $deliveryDate . '</p>
                    <p><span class="info-label">Order ID:</span> #' . $order['id'] . '</p>
                    <div class="paid-badge">✓ PAID</div>
                </div>
            </div>
            
            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Description</th>
                        <th style="width: 15%; text-align: center;">Quantity</th>
                        <th style="width: 20%;">Unit Price</th>
                        <th style="width: 15%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>' . htmlspecialchars($packageName) . '</strong>
                            <div class="item-description">
                                Sank Magic Copy Books - Reusable educational workbooks with magic pen
                            </div>
                        </td>
                        <td style="text-align: center;">' . $quantity . '</td>
                        <td>₦' . number_format($unitPrice, 2) . '</td>
                        <td style="text-align: right;">₦' . number_format($subtotal, 2) . '</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Totals -->
            <div class="totals">
                <div class="total-row subtotal">
                    <span>Subtotal:</span>
                    <span>₦' . number_format($subtotal, 2) . '</span>
                </div>
                <div class="total-row">
                    <span>Tax (VAT):</span>
                    <span>₦' . number_format($tax, 2) . '</span>
                </div>
                <div class="total-row grand-total">
                    <span>Total Amount:</span>
                    <span>₦' . number_format($total, 2) . '</span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-section">
                <h4>Payment Information</h4>
                <p>This invoice has been marked as <strong>PAID</strong>. Payment received on delivery.</p>
            </div>
            
            <div class="footer-section">
                <h4>Terms & Conditions</h4>
                <p>All sales are final. Products are guaranteed against manufacturing defects. For support or questions, please contact us using the information below.</p>
            </div>
            
            <div class="footer-section">
                <h4>Contact Us</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <strong>Email:</strong> goldenemeraldglobal@gmail.com
                    </div>
                    <div class="contact-item">
                        <strong>Phone:</strong> 09038356928
                    </div>
                    <div class="contact-item">
                        <strong>Website:</strong> smartkidsedu.com.ng
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thank You -->
        <div class="thank-you">
            Thank you for your business! We appreciate your trust in Smartkids Edu.
        </div>
    </div>
</body>
</html>';
    
    return $html;
}

/**
 * Send invoice email to customer
 * 
 * @param array $order Order data from database
 * @return array Result array with success status and message
 */
function sendInvoiceEmail($order) {
    global $smtpConfig;
    
    // Check if customer has email
    if (empty($order['email'])) {
        return [
            'success' => false,
            'message' => 'Customer email not provided',
            'error' => 'NO_EMAIL'
        ];
    }
    
    // Validate email
    if (!filter_var($order['email'], FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Invalid customer email address',
            'error' => 'INVALID_EMAIL'
        ];
    }
    
    // Generate invoice HTML
    $invoiceHTML = generateInvoiceHTML($order);
    
    // Invoice number
    $invoiceNumber = 'INV-' . $order['id'] . '-' . date('Ym');
    
    // Email subject
    $subject = "Invoice #$invoiceNumber - Your Smartkids Edu Order";
    
    // Try SMTP first
    $result = sendSMTPEmail(
        $order['email'],
        $order['fullname'],
        $subject,
        $invoiceHTML,
        true, // HTML email
        'goldenemeraldglobal@gmail.com'
    );
    
    // If SMTP fails or is disabled, try PHP mail()
    if (!$result['success'] && function_exists('mail')) {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Smartkids Edu <no-reply@smartkidsedu.com.ng>\r\n";
        $headers .= "Reply-To: goldenemeraldglobal@gmail.com\r\n";
        
        if (mail($order['email'], $subject, $invoiceHTML, $headers)) {
            $result = [
                'success' => true,
                'message' => 'Invoice sent successfully via PHP mail()',
                'method' => 'php_mail'
            ];
        } else {
            $result = [
                'success' => false,
                'message' => 'Failed to send invoice via both SMTP and PHP mail()',
                'error' => 'MAIL_FAILED'
            ];
        }
    }
    
    return $result;
}

/**
 * Generate and send invoice when order is delivered
 * This function should be called from the order status update
 * 
 * @param int $orderId Order ID
 * @return array Result array
 */
function generateAndSendInvoice($orderId) {
    global $conn;
    
    // Get order details
    $orderId = $conn->real_escape_string($orderId);
    $sql = "SELECT * FROM orders WHERE id = '$orderId'";
    $result = $conn->query($sql);
    
    if (!$result || $result->num_rows === 0) {
        return [
            'success' => false,
            'message' => 'Order not found',
            'error' => 'ORDER_NOT_FOUND'
        ];
    }
    
    $order = $result->fetch_assoc();
    
    // Check if order is delivered
    if (strtolower($order['status']) !== 'delivered') {
        return [
            'success' => false,
            'message' => 'Invoice can only be sent for delivered orders',
            'error' => 'ORDER_NOT_DELIVERED'
        ];
    }
    
    // Send invoice email
    $emailResult = sendInvoiceEmail($order);
    
    // Log invoice sending attempt
    if ($emailResult['success']) {
        // Update order to mark invoice sent
        $updateSql = "UPDATE orders SET invoice_sent = 1, invoice_sent_at = NOW() WHERE id = '$orderId'";
        $conn->query($updateSql);
        
        // Log activity if logging function exists
        if (function_exists('logActivity') && isset($_SESSION['user_id'])) {
            logActivity(
                $_SESSION['user_id'],
                'send_invoice',
                'order',
                $orderId,
                "Invoice sent to customer: {$order['fullname']} ({$order['email']})",
                [],
                ['invoice_sent' => true]
            );
        }
    }
    
    return $emailResult;
}

/**
 * Manually send/resend invoice for an order
 * Can be called from admin panel
 * 
 * @param int $orderId Order ID
 * @return array Result array
 */
function resendInvoice($orderId) {
    return generateAndSendInvoice($orderId);
}
