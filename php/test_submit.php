<?php
// test_submit.php
// Debug file to test what's causing the 500 error

// Start output buffering
ob_start();

// Enable error display for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

header('Content-Type: application/json; charset=utf-8');

$results = [];

// Test 1: Check PHP version
$results['php_version'] = PHP_VERSION;
$results['php_version_ok'] = version_compare(PHP_VERSION, '5.6.0', '>=');

// Test 2: Check if db.php exists
$results['db_file_exists'] = file_exists('db.php') || file_exists(__DIR__ . '/db.php');

// Test 3: Try to include db.php
try {
    if (file_exists('db.php')) {
        require_once 'db.php';
    } elseif (file_exists(__DIR__ . '/db.php')) {
        require_once __DIR__ . '/db.php';
    }
    $results['db_include'] = 'success';
} catch (Exception $e) {
    $results['db_include'] = 'failed: ' . $e->getMessage();
}

// Test 4: Check database connection
if (isset($conn)) {
    if ($conn->connect_error) {
        $results['db_connection'] = 'failed: ' . $conn->connect_error;
    } else {
        $results['db_connection'] = 'success';
        
        // Test 5: Check if orders table exists
        $tableCheck = $conn->query("SHOW TABLES LIKE 'orders'");
        $results['orders_table_exists'] = ($tableCheck && $tableCheck->num_rows > 0);
        
        // Test 6: Check orders table structure
        if ($results['orders_table_exists']) {
            $columnsCheck = $conn->query("DESCRIBE orders");
            $columns = [];
            while ($row = $columnsCheck->fetch_assoc()) {
                $columns[] = $row['Field'];
            }
            $results['orders_columns'] = $columns;
            
            // Check for required columns
            $requiredColumns = ['id', 'fullname', 'phone', 'address', 'state', 'pack', 'referral_code', 'created_at'];
            $missingColumns = array_diff($requiredColumns, $columns);
            $results['missing_columns'] = $missingColumns;
        }
    }
} else {
    $results['db_connection'] = 'no $conn variable';
}

// Test 7: Check if mail function is available
$results['mail_function_exists'] = function_exists('mail');

// Test 8: Check write permissions (for error logs)
$results['can_write_logs'] = is_writable(__DIR__) || is_writable(ini_get('error_log'));

// Test 9: Check POST data
$results['post_data_received'] = !empty($_POST);
if (!empty($_POST)) {
    $results['post_keys'] = array_keys($_POST);
}

// Test 10: Check server software
$results['server_software'] = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'unknown';

ob_clean();
echo json_encode($results, JSON_PRETTY_PRINT);
ob_end_flush();
?>
