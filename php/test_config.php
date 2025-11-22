<?php
// Simple test to check config loading
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing SMTP Configuration</h2>";

// Test 1: Check if config file exists
echo "<h3>Test 1: Config File Exists</h3>";
if (file_exists(__DIR__ . '/smtp_config.php')) {
    echo "✅ smtp_config.php exists<br>";
} else {
    echo "❌ smtp_config.php NOT found<br>";
    exit;
}

// Test 2: Load config
echo "<h3>Test 2: Load Configuration</h3>";
try {
    $config = require __DIR__ . '/smtp_config.php';
    echo "✅ Config loaded successfully<br>";
    echo "Type: " . gettype($config) . "<br>";
    echo "<pre>";
    print_r($config);
    echo "</pre>";
} catch (Exception $e) {
    echo "❌ Error loading config: " . $e->getMessage() . "<br>";
    exit;
}

// Test 3: Check config values
echo "<h3>Test 3: Configuration Values</h3>";
if (is_array($config)) {
    echo "✅ Config is an array<br>";
    echo "enable_smtp: " . (isset($config['enable_smtp']) ? ($config['enable_smtp'] ? 'true' : 'false') : 'NOT SET') . "<br>";
    echo "smtp_host: " . (isset($config['smtp_host']) ? $config['smtp_host'] : 'NOT SET') . "<br>";
    echo "smtp_username: " . (isset($config['smtp_username']) ? $config['smtp_username'] : 'NOT SET') . "<br>";
    echo "smtp_password: " . (isset($config['smtp_password']) && strlen($config['smtp_password']) > 0 ? '*** (SET)' : 'NOT SET') . "<br>";
} else {
    echo "❌ Config is NOT an array<br>";
}

// Test 4: Load mailer_smtp.php
echo "<h3>Test 4: Load Mailer Functions</h3>";
if (file_exists(__DIR__ . '/mailer_smtp.php')) {
    echo "✅ mailer_smtp.php exists<br>";
    try {
        require_once __DIR__ . '/mailer_smtp.php';
        echo "✅ mailer_smtp.php loaded<br>";
        
        if (function_exists('sendSMTPEmail')) {
            echo "✅ sendSMTPEmail function available<br>";
        } else {
            echo "❌ sendSMTPEmail function NOT found<br>";
        }
        
        if (function_exists('sendAdminOrderNotification')) {
            echo "✅ sendAdminOrderNotification function available<br>";
        } else {
            echo "❌ sendAdminOrderNotification function NOT found<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Error loading mailer: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ mailer_smtp.php NOT found<br>";
}

// Test 5: Check PHPMailer
echo "<h3>Test 5: PHPMailer Installation</h3>";
$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    dirname(__DIR__) . '/vendor/autoload.php'
];

$found = false;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        echo "✅ Autoload found: $path<br>";
        require_once $path;
        $found = true;
        break;
    }
}

if (!$found) {
    echo "❌ No autoload.php found<br>";
} else {
    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        echo "✅ PHPMailer class available<br>";
    } else {
        echo "❌ PHPMailer class NOT available<br>";
    }
}

echo "<h3>All Tests Complete</h3>";
echo "<p><a href='test_email.php'>Go to Email Test Page</a></p>";
