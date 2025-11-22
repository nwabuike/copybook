<?php
// php/auth.php - Authentication and Authorization Functions
session_start();

require_once 'db.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
}

// Get current user data
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    $sql = "SELECT id, username, email, full_name, role, status FROM users WHERE id = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Check if user has specific role
function hasRole($role) {
    if (!isLoggedIn()) {
        return false;
    }
    
    if (is_array($role)) {
        return in_array($_SESSION['user_role'], $role);
    }
    
    return $_SESSION['user_role'] === $role;
}

// Check if user is admin
function isAdmin() {
    return hasRole('admin');
}

// Check if user is subadmin
function isSubadmin() {
    return hasRole('subadmin');
}

// Check if user is agent
function isAgent() {
    return hasRole('agent');
}

// Require login - redirect to login page if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        $currentPage = $_SERVER['REQUEST_URI'];
        header("Location: login.php?redirect=" . urlencode($currentPage));
        exit();
    }
}

// Require specific role(s)
function requireRole($role) {
    requireLogin();
    
    if (!hasRole($role)) {
        header("Location: unauthorized.php");
        exit();
    }
}

// Require admin role
function requireAdmin() {
    requireRole('admin');
}

// Check permission for specific action
function canPerform($action) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $role = $_SESSION['user_role'];
    
    // Define permissions matrix
    $permissions = [
        'admin' => [
            'view_orders', 'create_order', 'edit_order', 'delete_order', 'update_order_status',
            'view_agents', 'create_agent', 'edit_agent', 'delete_agent',
            'view_stock', 'update_stock', 'delete_stock_movement',
            'view_analytics', 'export_data',
            'view_users', 'create_user', 'edit_user', 'delete_user',
            'view_logs', 'view_all_activities'
        ],
        'subadmin' => [
            'view_orders', 'create_order', 'edit_order', 'update_order_status',
            'view_agents', 'edit_agent',
            'view_stock', 'update_stock',
            'export_data', 'view_logs'
        ],
        'agent' => [
            'view_orders', 'update_order_status',
            'view_stock'
        ]
    ];
    
    return isset($permissions[$role]) && in_array($action, $permissions[$role]);
}

// Login user
function loginUser($username, $password) {
    global $conn;
    
    $sql = "SELECT id, username, email, full_name, password, role, status FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
    
    $user = $result->fetch_assoc();
    
    // Check if user is active
    if ($user['status'] !== 'active') {
        return ['success' => false, 'message' => 'Your account has been deactivated'];
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    
    // Update last login
    $updateSql = "UPDATE users SET last_login = NOW() WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("i", $user['id']);
    $updateStmt->execute();
    
    // Log activity
    logActivity($user['id'], 'login', 'user', $user['id'], 'User logged in');
    
    return [
        'success' => true, 
        'message' => 'Login successful',
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'role' => $user['role']
        ]
    ];
}

// Logout user
function logoutUser() {
    if (isLoggedIn()) {
        logActivity($_SESSION['user_id'], 'logout', 'user', $_SESSION['user_id'], 'User logged out');
    }
    
    session_unset();
    session_destroy();
}

// Log activity
function logActivity($userId, $action, $entityType, $entityId, $description, $oldValues = null, $newValues = null) {
    global $conn;
    
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    $oldValuesJson = $oldValues ? json_encode($oldValues) : null;
    $newValuesJson = $newValues ? json_encode($newValues) : null;
    
    $sql = "INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, ip_address, user_agent, old_values, new_values) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssss", $userId, $action, $entityType, $entityId, $description, $ipAddress, $userAgent, $oldValuesJson, $newValuesJson);
    $stmt->execute();
}

// Get user's recent activities
function getUserActivities($userId, $limit = 50) {
    global $conn;
    
    $sql = "SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
    
    return $activities;
}

// Get all activities (admin only)
function getAllActivities($filters = [], $limit = 100, $offset = 0) {
    global $conn;
    
    $where = [];
    $params = [];
    $types = "";
    
    if (!empty($filters['user_id'])) {
        $where[] = "al.user_id = ?";
        $params[] = $filters['user_id'];
        $types .= "i";
    }
    
    if (!empty($filters['action'])) {
        $where[] = "al.action = ?";
        $params[] = $filters['action'];
        $types .= "s";
    }
    
    if (!empty($filters['entity_type'])) {
        $where[] = "al.entity_type = ?";
        $params[] = $filters['entity_type'];
        $types .= "s";
    }
    
    if (!empty($filters['start_date'])) {
        $where[] = "DATE(al.created_at) >= ?";
        $params[] = $filters['start_date'];
        $types .= "s";
    }
    
    if (!empty($filters['end_date'])) {
        $where[] = "DATE(al.created_at) <= ?";
        $params[] = $filters['end_date'];
        $types .= "s";
    }
    
    $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
    
    $sql = "SELECT al.*, u.username, u.full_name 
            FROM activity_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            $whereClause 
            ORDER BY al.created_at DESC 
            LIMIT ? OFFSET ?";
    
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
    
    return $activities;
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Generate random password
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

// Get agent ID for current user (if user is an agent)
function getAgentIdForCurrentUser() {
    if (!isLoggedIn() || !isAgent()) {
        return null;
    }
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    $sql = "SELECT id FROM delivery_agents WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    
    return null;
}

// Get states assigned to agent
function getAgentStates($agentId = null) {
    if ($agentId === null && isAgent()) {
        $agentId = getAgentIdForCurrentUser();
    }
    
    if ($agentId === null) {
        return [];
    }
    
    global $conn;
    
    $sql = "SELECT state FROM agent_states WHERE agent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $agentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $states = [];
    while ($row = $result->fetch_assoc()) {
        $states[] = $row['state'];
    }
    
    return $states;
}
?>
