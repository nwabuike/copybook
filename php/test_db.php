<?php
// test_db.php
// Simple database connection test

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    if (file_exists('db.php')) {
        require_once 'db.php';
    } elseif (file_exists(__DIR__ . '/db.php')) {
        require_once __DIR__ . '/db.php';
    } else {
        echo json_encode(['status' => 'error', 'message' => 'db.php file not found']);
        exit;
    }
    
    if (!isset($conn)) {
        echo json_encode(['status' => 'error', 'message' => '$conn variable not defined in db.php']);
        exit;
    }
    
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => $conn->connect_error]);
        exit;
    }
    
    // Try a simple query
    $result = $conn->query("SELECT 1");
    if ($result) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Connected successfully to database',
            'server_info' => $conn->server_info
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
    }
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
