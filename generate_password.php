<?php
// generate_password.php - Generate password hashes for testing
echo "Password Hashes for Demo Accounts:\n\n";

$passwords = [
    'admin123' => password_hash('admin123', PASSWORD_BCRYPT),
    'subadmin123' => password_hash('subadmin123', PASSWORD_BCRYPT),
    'agent123' => password_hash('agent123', PASSWORD_BCRYPT)
];

foreach ($passwords as $plain => $hash) {
    echo "Password: $plain\n";
    echo "Hash: $hash\n";
    echo "Verify: " . (password_verify($plain, $hash) ? 'YES' : 'NO') . "\n\n";
}
?>
