<?php
// test_email.php
// Test SMTP email configuration

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMTP Email Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #0a7c42; }
        .result {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        button {
            background: #0a7c42;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        button:hover { background: #066633; }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 SMTP Email Configuration Test</h1>
        
        <?php
        // Check if PHPMailer is available
        $phpmailerInstalled = false;
        $autoloadPaths = [
            __DIR__ . '/vendor/autoload.php',
            __DIR__ . '/../vendor/autoload.php',
            dirname(__DIR__) . '/vendor/autoload.php'
        ];
        
        foreach ($autoloadPaths as $path) {
            if (file_exists($path)) {
                require_once $path;
                $phpmailerInstalled = class_exists('PHPMailer\PHPMailer\PHPMailer');
                break;
            }
        }
        
        // Check if config exists
        $configExists = file_exists(__DIR__ . '/smtp_config.php');
        $mailerExists = file_exists(__DIR__ . '/mailer_smtp.php');
        
        echo '<div class="info">';
        echo '<h3>System Check:</h3>';
        echo '<ul>';
        echo '<li>PHPMailer: ' . ($phpmailerInstalled ? '✅ Installed' : '❌ Not installed - Run: composer install') . '</li>';
        echo '<li>smtp_config.php: ' . ($configExists ? '✅ Found' : '❌ Not found') . '</li>';
        echo '<li>mailer_smtp.php: ' . ($mailerExists ? '✅ Found' : '❌ Not found') . '</li>';
        echo '<li>PHP mail(): ' . (function_exists('mail') ? '✅ Available' : '⚠️ Disabled') . '</li>';
        echo '</ul>';
        echo '</div>';
        
        if ($configExists) {
            $config = require __DIR__ . '/smtp_config.php';
            
            echo '<div class="info" style="margin-top: 20px;">';
            echo '<h3>Current SMTP Configuration:</h3>';
            echo '<pre>';
            echo "Host: " . $config['smtp_host'] . "\n";
            echo "Port: " . $config['smtp_port'] . "\n";
            echo "Encryption: " . $config['smtp_encryption'] . "\n";
            echo "Username: " . $config['smtp_username'] . "\n";
            echo "Password: " . (strlen($config['smtp_password']) > 0 ? str_repeat('*', 16) . ' (configured)' : '❌ NOT SET') . "\n";
            echo "From Email: " . $config['from_email'] . "\n";
            echo "Enabled: " . ($config['enable_smtp'] ? 'Yes' : 'No') . "\n";
            echo '</pre>';
            
            if ($config['smtp_username'] === 'your-email@gmail.com' || $config['smtp_password'] === 'your-app-password') {
                echo '<div class="warning" style="margin-top: 10px;">';
                echo '<strong>⚠️ Warning:</strong> You need to update smtp_config.php with your actual Gmail credentials!<br>';
                echo 'Get App Password from: <a href="https://myaccount.google.com/apppasswords" target="_blank">myaccount.google.com/apppasswords</a>';
                echo '</div>';
            }
            echo '</div>';
        }
        
        // Test form
        if (isset($_POST['send_test'])) {
            echo '<hr style="margin: 30px 0;">';
            echo '<h2>Test Results:</h2>';
            
            if ($mailerExists && $phpmailerInstalled) {
                require_once __DIR__ . '/mailer_smtp.php';
                
                $testEmail = $_POST['test_email'];
                $testName = $_POST['test_name'];
                
                // Test order data
                $testOrder = [
                    'order_id' => 'TEST-' . time(),
                    'fullname' => $testName,
                    'email' => $testEmail,
                    'phone' => '08012345678',
                    'altphone' => '08098765432',
                    'address' => '123 Test Street, Test Area',
                    'state' => 'Lagos',
                    'pack' => 'Starter Set',
                    'referral_code' => 'TEST' . strtoupper(substr(md5(time()), 0, 8)),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                if ($_POST['test_type'] === 'customer') {
                    echo '<div class="info"><strong>Sending customer confirmation email...</strong></div>';
                    $result = sendCustomerOrderConfirmation($testOrder);
                } else {
                    echo '<div class="info"><strong>Sending admin notification email...</strong></div>';
                    $result = sendAdminOrderNotification($testOrder);
                }
                
                if ($result['success']) {
                    echo '<div class="result success">';
                    echo '<h3>✅ Email Sent Successfully!</h3>';
                    echo '<p>Email was sent to: <strong>' . htmlspecialchars($testEmail) . '</strong></p>';
                    echo '<p>Check your inbox (and spam folder).</p>';
                    echo '</div>';
                } else {
                    echo '<div class="result error">';
                    echo '<h3>❌ Email Failed</h3>';
                    echo '<p><strong>Error:</strong> ' . htmlspecialchars($result['message']) . '</p>';
                    if ($result['error']) {
                        echo '<p><strong>Details:</strong> ' . htmlspecialchars($result['error']) . '</p>';
                    }
                    echo '</div>';
                }
                
                echo '<div class="info">';
                echo '<h4>Full Response:</h4>';
                echo '<pre>' . print_r($result, true) . '</pre>';
                echo '</div>';
                
            } else {
                echo '<div class="result error">';
                echo '<h3>❌ Cannot Send Test Email</h3>';
                echo '<p>PHPMailer is not installed. Run: <code>composer install</code></p>';
                echo '</div>';
            }
        }
        ?>
        
        <hr style="margin: 30px 0;">
        
        <h2>Send Test Email</h2>
        <form method="POST">
            <label>Your Email Address:</label>
            <input type="email" name="test_email" required value="<?= htmlspecialchars($_POST['test_email'] ?? 'test@example.com') ?>">
            
            <label>Your Name:</label>
            <input type="text" name="test_name" required value="<?= htmlspecialchars($_POST['test_name'] ?? 'Test User') ?>">
            
            <label>Email Type:</label>
            <select name="test_type">
                <option value="customer">Customer Confirmation</option>
                <option value="admin">Admin Notification</option>
            </select>
            
            <button type="submit" name="send_test">Send Test Email</button>
        </form>
        
        <hr style="margin: 30px 0;">
        
        <h2>Setup Instructions</h2>
        <div class="info">
            <ol>
                <li><strong>Install PHPMailer:</strong> Run <code>composer install</code> in the php directory</li>
                <li><strong>Configure SMTP:</strong> Edit <code>smtp_config.php</code> with your Gmail credentials</li>
                <li><strong>Get App Password:</strong> Visit <a href="https://myaccount.google.com/apppasswords" target="_blank">myaccount.google.com/apppasswords</a></li>
                <li><strong>Test:</strong> Use the form above to send a test email</li>
            </ol>
            <p><strong>Note:</strong> Make sure 2-Step Verification is enabled on your Gmail account before generating an App Password.</p>
        </div>
    </div>
</body>
</html>
