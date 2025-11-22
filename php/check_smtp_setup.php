<?php
// check_smtp_setup.php
// Diagnostic tool to check SMTP configuration

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>SMTP Configuration Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0; }
        .error { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0; }
        .warning { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0; }
        .info { background: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 10px 0; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }
        h2 { color: #333; border-bottom: 2px solid #0a7c42; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h1>🔍 SMTP Configuration Diagnostic</h1>
    
    <?php
    echo "<h2>1. File System Check</h2>";
    
    $configFile = __DIR__ . '/smtp_config.php';
    $exampleFile = __DIR__ . '/smtp_config.example.php';
    
    echo "<div class='info'>";
    echo "<strong>Looking for config at:</strong><br>";
    echo "<code>" . htmlspecialchars($configFile) . "</code>";
    echo "</div>";
    
    if (file_exists($configFile)) {
        echo "<div class='success'>✅ smtp_config.php EXISTS</div>";
        echo "<div class='info'>";
        echo "<strong>File permissions:</strong> " . substr(sprintf('%o', fileperms($configFile)), -4) . "<br>";
        echo "<strong>File size:</strong> " . filesize($configFile) . " bytes<br>";
        echo "<strong>Readable:</strong> " . (is_readable($configFile) ? "Yes" : "No") . "<br>";
        echo "</div>";
    } else {
        echo "<div class='error'>❌ smtp_config.php NOT FOUND</div>";
        
        if (file_exists($exampleFile)) {
            echo "<div class='warning'>⚠️ smtp_config.example.php exists. You need to copy it to smtp_config.php</div>";
            echo "<div class='info'><strong>Quick fix:</strong><br>";
            echo "<code>cp smtp_config.example.php smtp_config.php</code><br>";
            echo "Then edit smtp_config.php with your credentials</div>";
        }
        exit;
    }
    
    echo "<h2>2. Config File Loading</h2>";
    
    try {
        $config = require $configFile;
        echo "<div class='success'>✅ Config file loaded successfully</div>";
        
        if (!is_array($config)) {
            echo "<div class='error'>❌ Config did not return an array. Type: " . gettype($config) . "</div>";
            echo "<div class='warning'>The config file should return an array using 'return [...]'</div>";
            exit;
        }
        
        echo "<div class='success'>✅ Config is an array</div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error loading config: " . htmlspecialchars($e->getMessage()) . "</div>";
        exit;
    }
    
    echo "<h2>3. Configuration Values</h2>";
    
    $requiredKeys = [
        'enable_smtp', 'smtp_host', 'smtp_port', 'smtp_encryption',
        'smtp_username', 'smtp_password', 'from_email', 'from_name',
        'admin_email'
    ];
    
    $allPresent = true;
    foreach ($requiredKeys as $key) {
        if (isset($config[$key])) {
            $value = $config[$key];
            if ($key === 'smtp_password') {
                $display = strlen($value) > 0 ? str_repeat('*', min(16, strlen($value))) . " (length: " . strlen($value) . ")" : "EMPTY!";
            } elseif ($key === 'enable_smtp') {
                $display = $value ? "true ✅" : "false ❌";
            } else {
                $display = $value;
            }
            
            $class = (empty($value) && $value !== false) ? 'warning' : 'success';
            echo "<div class='$class'><strong>$key:</strong> " . htmlspecialchars($display) . "</div>";
            
            if (empty($value) && $value !== false) {
                $allPresent = false;
            }
        } else {
            echo "<div class='error'><strong>$key:</strong> MISSING!</div>";
            $allPresent = false;
        }
    }
    
    if (!$allPresent) {
        echo "<div class='error'><strong>⚠️ Some required configuration values are missing or empty!</strong></div>";
    }
    
    echo "<h2>4. SMTP Status</h2>";
    
    if (!isset($config['enable_smtp'])) {
        echo "<div class='error'>❌ 'enable_smtp' key is not set in config</div>";
    } elseif ($config['enable_smtp'] === true || $config['enable_smtp'] === 1 || $config['enable_smtp'] === 'true') {
        echo "<div class='success'>✅ SMTP is ENABLED</div>";
    } else {
        echo "<div class='error'>❌ SMTP is DISABLED (value: " . var_export($config['enable_smtp'], true) . ")</div>";
        echo "<div class='warning'>Set 'enable_smtp' => true in smtp_config.php</div>";
    }
    
    echo "<h2>5. PHPMailer Check</h2>";
    
    $autoloadPaths = [
        __DIR__ . '/vendor/autoload.php',
        __DIR__ . '/../vendor/autoload.php',
        dirname(__DIR__) . '/vendor/autoload.php'
    ];
    
    $phpmailerFound = false;
    foreach ($autoloadPaths as $path) {
        if (file_exists($path)) {
            echo "<div class='success'>✅ Autoload found: " . htmlspecialchars($path) . "</div>";
            require_once $path;
            $phpmailerFound = true;
            break;
        }
    }
    
    if (!$phpmailerFound) {
        echo "<div class='error'>❌ Composer autoload not found</div>";
        echo "<div class='warning'>Run: <code>cd php && composer install</code></div>";
    } else {
        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            echo "<div class='success'>✅ PHPMailer class is available</div>";
        } else {
            echo "<div class='error'>❌ PHPMailer class not found</div>";
            echo "<div class='warning'>Run: <code>cd php && composer install</code></div>";
        }
    }
    
    echo "<h2>6. Full Configuration (sanitized)</h2>";
    echo "<pre>";
    $sanitized = $config;
    if (isset($sanitized['smtp_password'])) {
        $sanitized['smtp_password'] = '***HIDDEN*** (length: ' . strlen($config['smtp_password']) . ')';
    }
    print_r($sanitized);
    echo "</pre>";
    
    echo "<h2>7. Recommended Actions</h2>";
    
    if ($config['enable_smtp'] !== true) {
        echo "<div class='warning'>🔧 Edit smtp_config.php and set: <code>'enable_smtp' => true,</code></div>";
    }
    
    if (empty($config['smtp_username']) || empty($config['smtp_password'])) {
        echo "<div class='warning'>🔧 Update your SMTP credentials in smtp_config.php</div>";
    }
    
    if ($phpmailerFound && class_exists('PHPMailer\\PHPMailer\\PHPMailer') && $config['enable_smtp'] === true) {
        echo "<div class='success'>✅ Everything looks good! Try sending a test email at <a href='test_email.php'>test_email.php</a></div>";
    }
    
    echo "<hr>";
    echo "<p><strong>Server Info:</strong></p>";
    echo "<ul>";
    echo "<li>PHP Version: " . phpversion() . "</li>";
    echo "<li>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
    echo "<li>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
    echo "<li>Current Script: " . __FILE__ . "</li>";
    echo "</ul>";
    ?>
</body>
</html>
