<?php
/**
 * Invoice Preview Page
 * Use this to preview invoices for any order
 */

require_once 'php/auth.php';
require_once 'php/db.php';
require_once 'php/invoice_generator.php';

// Require login to view
requireLogin();

// Get order ID from URL
$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;

// If no order ID, show form to enter one
if (!$orderId) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice Preview</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background: #f5f5f5;
                padding: 40px 20px;
                margin: 0;
            }
            .container {
                max-width: 500px;
                margin: 0 auto;
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            }
            h1 {
                color: #0a7c42;
                margin-top: 0;
                text-align: center;
            }
            .form-group {
                margin-bottom: 20px;
            }
            label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: #333;
            }
            input {
                width: 100%;
                padding: 12px;
                border: 2px solid #e0e0e0;
                border-radius: 5px;
                font-size: 16px;
                box-sizing: border-box;
            }
            input:focus {
                outline: none;
                border-color: #0a7c42;
            }
            button {
                width: 100%;
                padding: 14px;
                background: #0a7c42;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
            }
            button:hover {
                background: #066633;
            }
            .hint {
                margin-top: 10px;
                font-size: 14px;
                color: #666;
            }
            .back-link {
                display: block;
                text-align: center;
                margin-top: 20px;
                color: #0a7c42;
                text-decoration: none;
            }
            .back-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>📄 Invoice Preview</h1>
            <p style="text-align: center; color: #666; margin-bottom: 30px;">
                Enter an order ID to preview its invoice
            </p>
            
            <form method="GET" action="">
                <div class="form-group">
                    <label for="order_id">Order ID</label>
                    <input type="number" id="order_id" name="order_id" 
                           placeholder="e.g., 123" required min="1">
                    <div class="hint">
                        💡 Tip: You can find order IDs in the Admin Dashboard CRM
                    </div>
                </div>
                
                <button type="submit">Preview Invoice</button>
            </form>
            
            <a href="admin_dashboard_crm.php" class="back-link">
                ← Back to Dashboard
            </a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Get order details
$orderId = $conn->real_escape_string($orderId);
$sql = "SELECT * FROM orders WHERE id = '$orderId'";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Not Found</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background: #f5f5f5;
                padding: 40px 20px;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }
            .error-box {
                max-width: 500px;
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 2px 20px rgba(0,0,0,0.1);
                text-align: center;
            }
            .error-icon {
                font-size: 60px;
                margin-bottom: 20px;
            }
            h1 {
                color: #dc3545;
                margin: 0 0 10px 0;
            }
            p {
                color: #666;
                margin-bottom: 30px;
            }
            .btn {
                display: inline-block;
                padding: 12px 30px;
                background: #0a7c42;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: background 0.3s;
            }
            .btn:hover {
                background: #066633;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <div class="error-icon">❌</div>
            <h1>Order Not Found</h1>
            <p>No order found with ID: <strong><?php echo htmlspecialchars($orderId); ?></strong></p>
            <a href="invoice_preview.php" class="btn">Try Another Order</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$order = $result->fetch_assoc();

// Generate and display the invoice
echo generateInvoiceHTML($order);

// Add a print button and back button at the bottom
?>
<style>
    .action-buttons {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: flex;
        gap: 10px;
        z-index: 1000;
    }
    .action-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        transition: all 0.3s;
    }
    .print-btn {
        background: #0a7c42;
        color: white;
    }
    .print-btn:hover {
        background: #066633;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .back-btn {
        background: white;
        color: #333;
        border: 2px solid #ddd;
    }
    .back-btn:hover {
        background: #f5f5f5;
        border-color: #0a7c42;
        transform: translateY(-2px);
    }
    @media print {
        .action-buttons {
            display: none !important;
        }
    }
</style>

<div class="action-buttons">
    <a href="invoice_preview.php" class="action-btn back-btn">
        ← Back
    </a>
    <button onclick="window.print()" class="action-btn print-btn">
        🖨️ Print Invoice
    </button>
</div>
