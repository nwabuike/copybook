<?php
// api/users.php - User Management API
require_once '../php/auth.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Require admin for all user management operations
requireAdmin();

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $action = $_GET['action'] ?? '';
        
        switch($action) {
            case 'list':
                listUsers();
                break;
            case 'single':
                getSingleUser();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } elseif ($method === 'POST') {
        $action = $_GET['action'] ?? '';
        
        switch($action) {
            case 'create':
                createUser();
                break;
            case 'update':
                updateUser();
                break;
            case 'delete':
                deleteUser();
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

function listUsers() {
    global $conn;
    
    $sql = "SELECT id, username, email, full_name, role, status, last_login, created_at 
            FROM users 
            ORDER BY created_at DESC";
    
    $result = $conn->query($sql);
    $users = [];
    
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $users
    ]);
}

function getSingleUser() {
    global $conn;
    
    if (!isset($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        return;
    }
    
    $userId = (int)$_GET['id'];
    
    $sql = "SELECT id, username, email, full_name, role, status, last_login, created_at 
            FROM users 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => true,
            'data' => $result->fetch_assoc()
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
}

function createUser() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required = ['username', 'email', 'full_name', 'role', 'password'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
            return;
        }
    }
    
    // Validate password length
    if (strlen($input['password']) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
        return;
    }
    
    // Validate role
    $validRoles = ['admin', 'subadmin', 'agent'];
    if (!in_array($input['role'], $validRoles)) {
        echo json_encode(['success' => false, 'message' => 'Invalid role']);
        return;
    }
    
    // Check if username exists
    $checkSql = "SELECT id FROM users WHERE username = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $input['username']);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        return;
    }
    
    // Check if email exists
    $checkEmailSql = "SELECT id FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $input['email']);
    $checkEmailStmt->execute();
    if ($checkEmailStmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        return;
    }
    
    // Hash password
    $hashedPassword = hashPassword($input['password']);
    
    $status = $input['status'] ?? 'active';
    $createdBy = $_SESSION['user_id'];
    
    $sql = "INSERT INTO users (username, email, password, full_name, role, status, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", 
        $input['username'], 
        $input['email'], 
        $hashedPassword, 
        $input['full_name'], 
        $input['role'], 
        $status, 
        $createdBy
    );
    
    if ($stmt->execute()) {
        $newUserId = $conn->insert_id;
        
        // Log activity
        logActivity(
            $_SESSION['user_id'], 
            'create', 
            'user', 
            $newUserId, 
            "Created new user: {$input['username']} ({$input['role']})"
        );
        
        echo json_encode([
            'success' => true, 
            'message' => 'User created successfully',
            'user_id' => $newUserId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create user: ' . $conn->error]);
    }
}

function updateUser() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        return;
    }
    
    $userId = (int)$input['user_id'];
    
    // Get old values for logging
    $oldSql = "SELECT * FROM users WHERE id = ?";
    $oldStmt = $conn->prepare($oldSql);
    $oldStmt->bind_param("i", $userId);
    $oldStmt->execute();
    $oldUser = $oldStmt->get_result()->fetch_assoc();
    
    if (!$oldUser) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        return;
    }
    
    // Build update query dynamically
    $updates = [];
    $params = [];
    $types = "";
    
    if (!empty($input['username']) && $input['username'] !== $oldUser['username']) {
        // Check if new username exists
        $checkSql = "SELECT id FROM users WHERE username = ? AND id != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("si", $input['username'], $userId);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            return;
        }
        $updates[] = "username = ?";
        $params[] = $input['username'];
        $types .= "s";
    }
    
    if (!empty($input['email']) && $input['email'] !== $oldUser['email']) {
        // Check if new email exists
        $checkEmailSql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $checkEmailStmt = $conn->prepare($checkEmailSql);
        $checkEmailStmt->bind_param("si", $input['email'], $userId);
        $checkEmailStmt->execute();
        if ($checkEmailStmt->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }
        $updates[] = "email = ?";
        $params[] = $input['email'];
        $types .= "s";
    }
    
    if (!empty($input['full_name'])) {
        $updates[] = "full_name = ?";
        $params[] = $input['full_name'];
        $types .= "s";
    }
    
    if (!empty($input['role'])) {
        $validRoles = ['admin', 'subadmin', 'agent'];
        if (!in_array($input['role'], $validRoles)) {
            echo json_encode(['success' => false, 'message' => 'Invalid role']);
            return;
        }
        $updates[] = "role = ?";
        $params[] = $input['role'];
        $types .= "s";
    }
    
    if (!empty($input['status'])) {
        $updates[] = "status = ?";
        $params[] = $input['status'];
        $types .= "s";
    }
    
    if (!empty($input['password'])) {
        if (strlen($input['password']) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
            return;
        }
        $updates[] = "password = ?";
        $params[] = hashPassword($input['password']);
        $types .= "s";
    }
    
    if (empty($updates)) {
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        return;
    }
    
    $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
    $params[] = $userId;
    $types .= "i";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        // Log activity
        logActivity(
            $_SESSION['user_id'], 
            'update', 
            'user', 
            $userId, 
            "Updated user: {$oldUser['username']}",
            $oldUser,
            $input
        );
        
        echo json_encode([
            'success' => true, 
            'message' => 'User updated successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user: ' . $conn->error]);
    }
}

function deleteUser() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        return;
    }
    
    $userId = (int)$input['user_id'];
    
    // Prevent deleting yourself
    if ($userId === $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'You cannot delete your own account']);
        return;
    }
    
    // Get user info for logging
    $userSql = "SELECT username, role FROM users WHERE id = ?";
    $userStmt = $conn->prepare($userSql);
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $user = $userStmt->get_result()->fetch_assoc();
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        return;
    }
    
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        // Log activity
        logActivity(
            $_SESSION['user_id'], 
            'delete', 
            'user', 
            $userId, 
            "Deleted user: {$user['username']} ({$user['role']})"
        );
        
        echo json_encode([
            'success' => true, 
            'message' => 'User deleted successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user: ' . $conn->error]);
    }
}
?>
