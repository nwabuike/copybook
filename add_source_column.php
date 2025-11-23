<?php
// Migration script to add 'source' column to orders table
require_once 'php/db.php';

echo "Adding 'source' column to orders table...\n\n";

try {
    // Check if column already exists
    $checkSql = "SHOW COLUMNS FROM orders LIKE 'source'";
    $result = $conn->query($checkSql);
    
    if ($result->num_rows > 0) {
        echo "✓ Column 'source' already exists in orders table.\n";
    } else {
        // Add the source column at the end
        $sql = "ALTER TABLE orders 
                ADD COLUMN source VARCHAR(50) DEFAULT 'facebook'";
        
        if ($conn->query($sql) === TRUE) {
            echo "✓ Successfully added 'source' column to orders table.\n";
            
            // Update existing records to have 'facebook' as source
            $updateSql = "UPDATE orders SET source = 'facebook' WHERE source IS NULL OR source = ''";
            if ($conn->query($updateSql) === TRUE) {
                echo "✓ Updated existing orders with default source 'facebook'.\n";
            }
        } else {
            echo "✗ Error adding column: " . $conn->error . "\n";
        }
    }
    
    echo "\n✓ Migration completed successfully!\n";
    echo "\nYou can now delete this file: add_source_column.php\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

$conn->close();
?>
