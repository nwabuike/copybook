<?php
/**
 * Quick Invoice Test - Create a sample order for testing
 */

require_once 'php/db.php';

echo "<h2>Creating Test Order for Invoice Preview...</h2>";

// Create a test order
$testData = [
    'fullname' => 'John Doe',
    'email' => 'test@example.com',
    'phone' => '08012345678',
    'altphone' => '08098765432',
    'address' => '123 Test Street, Ikeja',
    'state' => 'Lagos',
    'pack' => 'Bundle',
    'source' => 'facebook',
    'status' => 'delivered',
    'created_at' => date('Y-m-d H:i:s')
];

$sql = "INSERT INTO orders (fullname, email, phone, altphone, address, state, pack, source, status, created_at, delivered_at) 
        VALUES (
            '{$testData['fullname']}',
            '{$testData['email']}',
            '{$testData['phone']}',
            '{$testData['altphone']}',
            '{$testData['address']}',
            '{$testData['state']}',
            '{$testData['pack']}',
            '{$testData['source']}',
            '{$testData['status']}',
            '{$testData['created_at']}',
            NOW()
        )";

if ($conn->query($sql)) {
    $orderId = $conn->insert_id;
    echo "<p style='color: green; font-size: 18px;'>✓ Test order created successfully!</p>";
    echo "<p><strong>Order ID:</strong> {$orderId}</p>";
    echo "<p><strong>Customer:</strong> {$testData['fullname']}</p>";
    echo "<p><strong>Package:</strong> {$testData['pack']}</p>";
    echo "<p><strong>Status:</strong> Delivered</p>";
    echo "<hr>";
    echo "<h3>View Invoice:</h3>";
    echo "<p><a href='invoice_preview.php?order_id={$orderId}' style='display: inline-block; padding: 15px 30px; background: #0a7c42; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>📄 Preview Invoice #{$orderId}</a></p>";
    echo "<p style='margin-top: 20px;'><a href='admin_dashboard_crm.php'>← Back to Dashboard</a></p>";
} else {
    echo "<p style='color: red;'>✗ Error creating test order: " . $conn->error . "</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Order Created</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h2 {
            color: #0a7c42;
        }
    </style>
</head>
<body>
</body>
</html>
