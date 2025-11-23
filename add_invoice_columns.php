<?php
/**
 * Add invoice tracking columns to orders table
 * Run this once to add invoice_sent and invoice_sent_at columns
 */

require_once 'php/db.php';

echo "Adding invoice tracking columns to orders table...\n";

// Check if invoice_sent column exists
$checkSql1 = "SHOW COLUMNS FROM orders LIKE 'invoice_sent'";
$result1 = $conn->query($checkSql1);

if ($result1->num_rows == 0) {
    // Add invoice_sent column (boolean)
    $sql1 = "ALTER TABLE orders 
             ADD COLUMN invoice_sent TINYINT(1) DEFAULT 0 COMMENT 'Whether invoice has been sent'";

    if ($conn->query($sql1)) {
        echo "✓ Added invoice_sent column\n";
    } else {
        echo "✗ Error adding invoice_sent column: " . $conn->error . "\n";
    }
} else {
    echo "- invoice_sent column already exists\n";
}

// Check if invoice_sent_at column exists
$checkSql2 = "SHOW COLUMNS FROM orders LIKE 'invoice_sent_at'";
$result2 = $conn->query($checkSql2);

if ($result2->num_rows == 0) {
    // Add invoice_sent_at column (datetime)
    $sql2 = "ALTER TABLE orders 
             ADD COLUMN invoice_sent_at DATETIME NULL COMMENT 'When invoice was sent'";

    if ($conn->query($sql2)) {
        echo "✓ Added invoice_sent_at column\n";
    } else {
        echo "✗ Error adding invoice_sent_at column: " . $conn->error . "\n";
    }
} else {
    echo "- invoice_sent_at column already exists\n";
}

echo "\nMigration complete!\n";
echo "You can now automatically send invoices when orders are marked as delivered.\n";

$conn->close();
