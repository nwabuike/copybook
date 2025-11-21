<?php
// api/auth.php - Authentication API
require_once '../php/auth.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $action = $_GET['action'] ?? '';
        
        switch($action) {
            case 'login':
                handleLogin();
                break;
            case 'logout':
                handleLogout();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } elseif ($method === 'GET') {
        $action = $_GET['action'] ?? '';
        
        switch($action) {
            case 'check':
                checkAuth();
                break;
            case 'user':
                getCurrentUserInfo();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function handleLogin() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['username']) || !isset($input['password'])) {
        echo json_encode(['success' => false, 'message' => 'Username and password required']);
        return;
    }
    
    $result = loginUser($input['username'], $input['password']);
    echo json_encode($result);
}

function handleLogout() {
    logoutUser();
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

function checkAuth() {
    if (isLoggedIn()) {
        echo json_encode([
            'success' => true,
            'logged_in' => true,
            'user' => getCurrentUser()
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'logged_in' => false
        ]);
    }
}

function getCurrentUserInfo() {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        return;
    }
    
    $user = getCurrentUser();
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
}
?>
