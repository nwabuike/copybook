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

if ($fullname === '' || $phone === '' || $address === '') {
    json_error('Please complete the required fields (name, phone, address).');
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

// Insert order
$insert = "INSERT INTO orders (fullname, email, phone, altphone, address, state, pack, referral_code, created_at) VALUES ('{$fullname_db}','{$email_db}','{$phone_db}','{$altphone_db}','{$address_db}','{$state_db}','{$pack_db}','{$referral_code}','{$created_at}')";
if (!$conn->query($insert)) {
    json_error('Database error: ' . $conn->error);
}
$order_id = $conn->insert_id;

// Send emails (wrapped in try-catch to prevent mail errors from breaking the response)
$adminEmail = 'emeraldonlineecom@gmail.com';
$siteFrom = 'no-reply@smartkidsedu.com.ng';
$adminMailSent = false;
$customerMailSent = false;

try {
    // Admin email
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
} catch (Exception $e) {
    // Log error but don't fail the order
    error_log("Admin email failed: " . $e->getMessage());
}

try {
    // Customer email (if provided)
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
    }
} catch (Exception $e) {
    // Log error but don't fail the order
    error_log("Customer email failed: " . $e->getMessage());
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
    'admin_mail' => $adminMailSent ? 'sent' : 'failed',
    'customer_mail' => $customerMailSent ? 'sent' : 'failed'
]);
ob_end_flush();

exit;
