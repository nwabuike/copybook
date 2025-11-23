<?php
// Ensure all test users exist with correct passwords
require_once 'php/auth.php';
requireAdmin();
require_once 'php/db.php';

$users = [
    [
        'username' => 'admin',
        'email' => 'admin@magicbook.com',
        'password' => '$2y$10$4pBISldZ9DSKz6TnpNHbU.XhIw0tQmTSTXEPUNskQtUa9mbCGyE8a',
        'full_name' => 'System Administrator',
        'role' => 'admin'
    ],
    [
        'username' => 'subadmin',
        'email' => 'subadmin@magicbook.com',
        'password' => '$2y$10$LK04KsQQjgs12JVRUXPwLu9LPEFNNqWkvN86YOU9EkTLyA36PNF0q',
        'full_name' => 'Sales Manager',
        'role' => 'subadmin'
    ],
    [
        'username' => 'agent001',
        'email' => 'agent@magicbook.com',
        'password' => '$2y$10$fh48IRs4RNJjSS72ghZ9L.j2w517twlISNdY6sbei/p5nkIRSBGPG',
        'full_name' => 'Delivery Agent',
        'role' => 'agent'
    ]
];

echo "Setting up test users...\n\n";

foreach ($users as $user) {
    // Check if user exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $user['username']);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing user
        $stmt = $conn->prepare("UPDATE users SET password = ?, email = ?, full_name = ?, role = ?, status = 'active' WHERE username = ?");
        $stmt->bind_param("sssss", $user['password'], $user['email'], $user['full_name'], $user['role'], $user['username']);
        $stmt->execute();
        echo "✓ Updated existing user: {$user['username']}\n";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->bind_param("sssss", $user['username'], $user['email'], $user['password'], $user['full_name'], $user['role']);
        $stmt->execute();
        echo "✓ Created new user: {$user['username']}\n";
    }
    
    // Verify password
    $test_password = $user['username'] === 'admin' ? 'admin123' : 
                    ($user['username'] === 'subadmin' ? 'subadmin123' : 'agent123');
    
    if (password_verify($test_password, $user['password'])) {
        echo "  ✓ Password verified for {$user['username']}\n\n";
    } else {
        echo "  ✗ Password verification failed for {$user['username']}\n\n";
    }
}

echo "✅ All test users ready!\n\n";
echo "Login credentials:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Admin (Full Access):\n";
echo "  Username: admin\n";
echo "  Password: admin123\n\n";
echo "Subadmin (No Delete/Analytics):\n";
echo "  Username: subadmin\n";
echo "  Password: subadmin123\n\n";
echo "Agent (View Only):\n";
echo "  Username: agent001\n";
echo "  Password: agent123\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
