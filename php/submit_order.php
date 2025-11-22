<?php
// submit_order.php
// Receives POST from form, inserts order into DB, generates referral code, sends emails, returns JSON

// Start output buffering to prevent "headers already sent" errors
ob_start();

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Try to include db.php and handle if it fails
try {
    if (file_exists('db.php')) {
        require_once 'db.php';
    } elseif (file_exists(__DIR__ . '/db.php')) {
        require_once __DIR__ . '/db.php';
    } else {
        throw new Exception('Database configuration file not found');
    }
} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode(['type'=>'error','text'=>'Configuration error. Please contact support.']);
    exit;
}

date_default_timezone_set('Africa/Lagos');

// Check database connection
if (!isset($conn) || $conn->connect_error) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode(['type'=>'error','text'=>'Database connection failed. Please try again later.']);
    exit;
}

// CORS headers for cross-origin requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    ob_clean();
    http_response_code(200);
    exit;
}

function json_error($msg) {
    ob_clean();
    http_response_code(200); // Keep 200 so fetch doesn't throw
    echo json_encode(['type'=>'error','text'=>$msg]);
    ob_end_flush();
    exit;
}

// Read POST
$fullname = isset($_POST['name']) ? trim($_POST['name']) : '';
$pack = isset($_POST['pack']) ? trim($_POST['pack']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$altphone = isset($_POST['altphone']) ? trim($_POST['altphone']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$state = isset($_POST['state']) ? trim($_POST['state']) : '';

if ($fullname === '' || $phone === '') {
    json_error('Please complete the required fields (name, phone).');
}

// sanitize for DB
$fullname_db = $conn->real_escape_string($fullname);
$pack_db = $conn->real_escape_string($pack);
$email_db = $conn->real_escape_string($email);
$phone_db = $conn->real_escape_string($phone);
$altphone_db = $conn->real_escape_string($altphone);
$address_db = $conn->real_escape_string($address);
$state_db = $conn->real_escape_string($state);

// generate unique referral code
function generate_referral($conn) {
    $try = 0;
    do {
        $code = 'REF' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        $sql = "SELECT id FROM orders WHERE referral_code = '" . $conn->real_escape_string($code) . "' LIMIT 1";
        $res = $conn->query($sql);
        $exists = ($res && $res->num_rows > 0);
        $try++;
    } while ($exists && $try < 5);
    return $code;
}

$referral_code = generate_referral($conn);
$created_at = date('Y-m-d H:i:s');

// Find active agent for this state
$agent_id = null;
$agent_sql = "SELECT da.id FROM delivery_agents da 
              INNER JOIN agent_states ast ON da.id = ast.agent_id 
              WHERE ast.state = '{$state_db}' AND da.status = 'active' 
              LIMIT 1";
$agent_result = $conn->query($agent_sql);
if ($agent_result && $agent_result->num_rows > 0) {
    $agent_row = $agent_result->fetch_assoc();
    $agent_id = $agent_row['id'];
}

// Insert order with auto-assigned agent
$insert = "INSERT INTO orders (fullname, email, phone, altphone, address, state, pack, referral_code, agent_id, quantity, created_at) 
           VALUES ('{$fullname_db}','{$email_db}','{$phone_db}','{$altphone_db}','{$address_db}','{$state_db}','{$pack_db}','{$referral_code}'," . 
           ($agent_id ? $agent_id : "NULL") . ",1,'{$created_at}')";
if (!$conn->query($insert)) {
    json_error('Database error: ' . $conn->error);
}
$order_id = $conn->insert_id;

// Send emails using SMTP (PHPMailer) or fallback to mail()
$adminMailSent = false;
$customerMailSent = false;
$emailMethod = 'none';

// Get agent name if assigned
$agent_name = null;
if ($agent_id) {
    $agent_name_sql = "SELECT name FROM delivery_agents WHERE id = {$agent_id}";
    $agent_name_result = $conn->query($agent_name_sql);
    if ($agent_name_result && $agent_name_result->num_rows > 0) {
        $agent_name_row = $agent_name_result->fetch_assoc();
        $agent_name = $agent_name_row['name'];
    }
}

// Prepare order data for email functions
$orderData = [
    'order_id' => $order_id,
    'fullname' => $fullname,
    'email' => $email,
    'phone' => $phone,
    'altphone' => $altphone,
    'address' => $address,
    'state' => $state,
    'pack' => $pack,
    'referral_code' => $referral_code,
    'agent_id' => $agent_id,
    'agent_name' => $agent_name,
    'created_at' => $created_at
];

// Try SMTP first (if configured)
if (file_exists(__DIR__ . '/mailer_smtp.php') && file_exists(__DIR__ . '/smtp_config.php')) {
    try {
        // Clear any previous config
        if (isset($smtpConfig)) {
            unset($smtpConfig);
        }
        
        require_once __DIR__ . '/mailer_smtp.php';
        
        // Verify config is loaded
        if (!isset($smtpConfig) || !is_array($smtpConfig)) {
            $smtpConfig = require __DIR__ . '/smtp_config.php';
        }
        
        // Send admin notification
        $adminResult = sendAdminOrderNotification($orderData);
        $adminMailSent = isset($adminResult['success']) ? $adminResult['success'] : false;
        
        // Send customer confirmation
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $customerResult = sendCustomerOrderConfirmation($orderData);
            $customerMailSent = isset($customerResult['success']) ? $customerResult['success'] : false;
        }
        
        if ($adminMailSent || $customerMailSent) {
            $emailMethod = 'smtp';
        }
        
        // Log any SMTP errors
        if (!$adminMailSent) {
            $errorMsg = isset($adminResult['error']) ? $adminResult['error'] : 'unknown';
            error_log("SMTP Admin email failed: " . (isset($adminResult['message']) ? $adminResult['message'] : 'No message') . " Error: " . $errorMsg);
        }
        if (!$customerMailSent && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg = isset($customerResult['error']) ? $customerResult['error'] : 'unknown';
            error_log("SMTP Customer email failed: " . (isset($customerResult['message']) ? $customerResult['message'] : 'No message') . " Error: " . $errorMsg);
        }
        
    } catch (Exception $e) {
        error_log("SMTP initialization failed: " . $e->getMessage());
    } catch (Error $e) {
        error_log("SMTP error: " . $e->getMessage());
    }
}

// Fallback to mail() if SMTP failed or not configured
if (!$adminMailSent && function_exists('mail')) {
    try {
        $adminEmail = 'emeraldonlineecom@gmail.com';
        $siteFrom = 'no-reply@smartkidsedu.com.ng';
        
        $subjectAdmin = "New Order #{$order_id} - Smartkids Edu";
        $bodyAdmin = "New order received:\n\n";
        $bodyAdmin .= "Order ID: {$order_id}\n";
        $bodyAdmin .= "Fullname: {$fullname}\n";
        $bodyAdmin .= "Email: {$email}\n";
        $bodyAdmin .= "Phone: {$phone}\n";
        $bodyAdmin .= "Alt Phone: {$altphone}\n";
        $bodyAdmin .= "Address: {$address}\n";
        $bodyAdmin .= "State: {$state}\n";
        $bodyAdmin .= "Package: {$pack}\n";
        $bodyAdmin .= "Referral Code: {$referral_code}\n";
        $bodyAdmin .= "Created At: {$created_at}\n";
        $headersAdmin = "From: Smartkids Edu <{$siteFrom}>\r\nReply-To: {$email}\r\n";
        $adminMailSent = @mail($adminEmail, $subjectAdmin, $bodyAdmin, $headersAdmin);
        
        if ($adminMailSent) {
            $emailMethod = 'mail';
        }
    } catch (Exception $e) {
        error_log("Mail() admin email failed: " . $e->getMessage());
    }
}

// Fallback customer email
if (!$customerMailSent && function_exists('mail') && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    try {
        $siteFrom = 'no-reply@smartkidsedu.com.ng';
        $subjectCustomer = "Your Smartkids Edu Order #{$order_id}";
        $bodyCustomer = "Hi {$fullname},\n\nThank you for your order. Here are your order details:\n\n";
        $bodyCustomer .= "Order ID: {$order_id}\n";
        $bodyCustomer .= "Package: {$pack}\n";
        $bodyCustomer .= "Delivery to: {$state}\n";
        $bodyCustomer .= "Address: {$address}\n";
        $bodyCustomer .= "Referral Code: {$referral_code}\n\n";
        $bodyCustomer .= "We will contact you shortly to confirm delivery details.\n\nRegards,\nSmartkids Edu";
        $headersCustomer = "From: Smartkids Edu <{$siteFrom}>\r\n";
        $customerMailSent = @mail($email, $subjectCustomer, $bodyCustomer, $headersCustomer);
        
        if ($customerMailSent && $emailMethod === 'none') {
            $emailMethod = 'mail';
        }
    } catch (Exception $e) {
        error_log("Mail() customer email failed: " . $e->getMessage());
    }
}

// Log if no email method worked
if ($emailMethod === 'none') {
    error_log("No email method available. Order #{$order_id} created but no emails sent.");
}

// Clean output buffer and return JSON
ob_clean();
http_response_code(200);

echo json_encode([
    'type' => 'message',
    'success' => true,
    'text' => 'Order received successfully!',
    'order_id' => $order_id,
    'referral_code' => $referral_code,
    'agent_id' => $agent_id,
    'agent_name' => $agent_name,
    'agent_assigned' => $agent_id ? true : false,
    'admin_mail' => $adminMailSent ? 'sent' : ($emailMethod === 'none' ? 'disabled' : 'failed'),
    'customer_mail' => $customerMailSent ? 'sent' : ($emailMethod === 'none' ? 'disabled' : 'failed'),
    'email_method' => $emailMethod  // 'smtp', 'mail', or 'none'
]);
ob_end_flush();

exit;
