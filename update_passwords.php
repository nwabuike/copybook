<?php
// Update user passwords with correct hashes
require_once 'php/db.php';

$users = [
    ['username' => 'admin', 'password' => '$2y$10$4pBISldZ9DSKz6TnpNHbU.XhIw0tQmTSTXEPUNskQtUa9mbCGyE8a'],
    ['username' => 'subadmin', 'password' => '$2y$10$LK04KsQQjgs12JVRUXPwLu9LPEFNNqWkvN86YOU9EkTLyA36PNF0q'],
    ['username' => 'agent001', 'password' => '$2y$10$fh48IRs4RNJjSS72ghZ9L.j2w517twlISNdY6sbei/p5nkIRSBGPG']
];

echo "Updating user passwords...\n\n";

foreach ($users as $user) {
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $user['password'], $user['username']);
    
    if ($stmt->execute()) {
        echo "✓ Updated {$user['username']} password\n";
        
        // Verify the password works
        $verify = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $verify->bind_param("s", $user['username']);
        $verify->execute();
        $result = $verify->get_result()->fetch_assoc();
        
        $test_password = $user['username'] === 'admin' ? 'admin123' : 
                        ($user['username'] === 'subadmin' ? 'subadmin123' : 'agent123');
        
        if (password_verify($test_password, $result['password'])) {
            echo "  ✓ Password verified successfully\n\n";
        } else {
            echo "  ✗ Password verification failed\n\n";
        }
    } else {
        echo "✗ Failed to update {$user['username']}: " . $stmt->error . "\n\n";
    }
}

echo "All done! You can now login with:\n";
echo "- admin / admin123\n";
echo "- subadmin / subadmin123\n";
echo "- agent001 / agent123\n";
