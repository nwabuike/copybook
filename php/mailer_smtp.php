<?php
// mailer_smtp.php
// PHPMailer SMTP Email Handler

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load SMTP configuration
if (!isset($smtpConfig)) {
    $configFile = __DIR__ . '/smtp_config.php';
    
    // Check if config file exists
    if (!file_exists($configFile)) {
        // Try to copy from example if it doesn't exist
        $exampleFile = __DIR__ . '/smtp_config.example.php';
        if (file_exists($exampleFile)) {
            error_log("Warning: smtp_config.php not found. Please copy smtp_config.example.php to smtp_config.php and configure your SMTP settings.");
        } else {
            error_log("Error: Neither smtp_config.php nor smtp_config.example.php found.");
        }
        
        // Return a default disabled config
        $smtpConfig = [
            'enable_smtp' => false,
            'smtp_host' => '',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'smtp_username' => '',
            'smtp_password' => '',
            'from_email' => '',
            'from_name' => '',
            'admin_email' => '',
            'reply_to_email' => '',
            'reply_to_name' => '',
            'debug_mode' => false
        ];
    } else {
        $smtpConfig = require $configFile;
    }
}

/**
 * Send email using SMTP via PHPMailer
 * 
 * @param string $to Recipient email address
 * @param string $toName Recipient name
 * @param string $subject Email subject
 * @param string $body Email body (HTML or plain text)
 * @param bool $isHtml Whether body is HTML (default: false)
 * @param string|null $replyTo Reply-to email address (optional)
 * @return array ['success' => bool, 'message' => string, 'error' => string|null]
 */
function sendSMTPEmail($to, $toName, $subject, $body, $isHtml = false, $replyTo = null) {
    global $smtpConfig;
    
    // Reload config if not set or not an array
    if (!isset($smtpConfig) || !is_array($smtpConfig)) {
        $smtpConfig = require __DIR__ . '/smtp_config.php';
    }
    
    // Check if SMTP is enabled
    if (!isset($smtpConfig['enable_smtp']) || !$smtpConfig['enable_smtp']) {
        return [
            'success' => false,
            'message' => 'SMTP is disabled in configuration',
            'error' => 'SMTP_DISABLED'
        ];
    }
    
    // Check if PHPMailer is available
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        // Try to load via composer autoload
        $autoloadPaths = [
            __DIR__ . '/vendor/autoload.php',
            __DIR__ . '/../vendor/autoload.php',
            dirname(__DIR__) . '/vendor/autoload.php'
        ];
        
        $loaded = false;
        foreach ($autoloadPaths as $path) {
            if (file_exists($path)) {
                require_once $path;
                $loaded = true;
                break;
            }
        }
        
        if (!$loaded || !class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return [
                'success' => false,
                'message' => 'PHPMailer not installed. Run: composer require phpmailer/phpmailer',
                'error' => 'PHPMAILER_NOT_FOUND'
            ];
        }
    }
    
    try {
        $mail = new PHPMailer(true);
        
        // Server settings
        if ($smtpConfig['debug_mode']) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Enable verbose debug output
        } else {
            $mail->SMTPDebug = 0;  // Disable debug output
        }
        
        $mail->isSMTP();
        $mail->Host       = $smtpConfig['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpConfig['smtp_username'];
        $mail->Password   = $smtpConfig['smtp_password'];
        $mail->SMTPSecure = $smtpConfig['smtp_encryption'];
        $mail->Port       = $smtpConfig['smtp_port'];
        $mail->CharSet    = 'UTF-8';
        
        // Recipients
        $mail->setFrom($smtpConfig['from_email'], $smtpConfig['from_name']);
        $mail->addAddress($to, $toName);
        
        // Reply-To
        if ($replyTo) {
            $mail->addReplyTo($replyTo, $toName);
        } elseif (isset($smtpConfig['reply_to_email'])) {
            $mail->addReplyTo($smtpConfig['reply_to_email'], $smtpConfig['reply_to_name']);
        }
        
        // Content
        $mail->isHTML($isHtml);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        // Plain text alternative for HTML emails
        if ($isHtml) {
            $mail->AltBody = strip_tags($body);
        }
        
        // Send email
        $mail->send();
        
        return [
            'success' => true,
            'message' => 'Email sent successfully',
            'error' => null
        ];
        
    } catch (Exception $e) {
        error_log("PHPMailer Error: {$mail->ErrorInfo}");
        
        return [
            'success' => false,
            'message' => 'Email could not be sent',
            'error' => $mail->ErrorInfo
        ];
    }
}

/**
 * Send order notification to admin
 */
function sendAdminOrderNotification($orderData) {
    global $smtpConfig;
    
    // Reload config if not set or not an array
    if (!isset($smtpConfig) || !is_array($smtpConfig)) {
        $smtpConfig = require __DIR__ . '/smtp_config.php';
    }
    
    $subject = "New Order #{$orderData['order_id']} - Smartkids Edu";
    
    $body = "New order received:\n\n";
    $body .= "Order ID: {$orderData['order_id']}\n";
    $body .= "Fullname: {$orderData['fullname']}\n";
    $body .= "Email: {$orderData['email']}\n";
    $body .= "Phone: {$orderData['phone']}\n";
    $body .= "Alt Phone: {$orderData['altphone']}\n";
    $body .= "Address: {$orderData['address']}\n";
    $body .= "State: {$orderData['state']}\n";
    $body .= "Package: {$orderData['pack']}\n";
    $body .= "Referral Code: {$orderData['referral_code']}\n";
    $body .= "Created At: {$orderData['created_at']}\n";
    
    return sendSMTPEmail(
        $smtpConfig['admin_email'],
        'Admin',
        $subject,
        $body,
        false,
        $orderData['email']
    );
}

/**
 * Send order confirmation to customer
 */
function sendCustomerOrderConfirmation($orderData) {
    global $smtpConfig;
    
    // Reload config if not set or not an array
    if (!isset($smtpConfig) || !is_array($smtpConfig)) {
        $smtpConfig = require __DIR__ . '/smtp_config.php';
    }
    
    if (!filter_var($orderData['email'], FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Invalid customer email',
            'error' => 'INVALID_EMAIL'
        ];
    }
    
    $subject = "Your Smartkids Edu Order #{$orderData['order_id']}";
    
    $body = "Hi {$orderData['fullname']},\n\n";
    $body .= "Thank you for your order. Here are your order details:\n\n";
    $body .= "Order ID: {$orderData['order_id']}\n";
    $body .= "Package: {$orderData['pack']}\n";
    $body .= "Delivery to: {$orderData['state']}\n";
    $body .= "Address: {$orderData['address']}\n";
    $body .= "Referral Code: {$orderData['referral_code']}\n\n";
    $body .= "We will contact you shortly to confirm delivery details.\n\n";
    $body .= "Share your referral code with friends to earn cashback!\n\n";
    $body .= "Regards,\nSmartkids Edu Team";
    
    return sendSMTPEmail(
        $orderData['email'],
        $orderData['fullname'],
        $subject,
        $body,
        false
    );
}
