<?php
require_once("db.php");
date_default_timezone_set("Africa/Lagos");

if ((isset($_POST['name']) && $_POST['pack'] != '')) {

    // $i = implode(" ", $_POST['bundle_jamb']);
    $user_name = $conn->real_escape_string($_POST['name']);

    $user_email = $conn->real_escape_string($_POST['email']);
    $user_phone = $conn->real_escape_string($_POST['phone']);
    $user_altphone = $conn->real_escape_string($_POST['altphone']);
    $user_address = $conn->real_escape_string($_POST['address']);
    $user_state = $conn->real_escape_string($_POST['state']);
    $user_pack = $conn->real_escape_string($_POST['pack']);
    $user_source = isset($_POST['source']) ? $conn->real_escape_string($_POST['source']) : 'facebook';
    $user_date = date("M d, Y h:i a");
    $sql = "INSERT INTO orders (fullname, email, phone, altphone, address, state, pack, source, created_at) 
VALUES('" . $user_name . "', '" . $user_email . "', '" . $user_phone . "','" . $user_altphone . "', '" . $user_address . "', '" . $user_state . "','" . $user_pack . "','" . $user_source . "','" . $user_date . "')";
    // echo $sql;
    if (!$result = $conn->query($sql)) {
        $output = json_encode(array('type' => 'error', 'text' => 'There was an error running the query [' . $conn->error . ']'));
        die($output);
        // die('There was an error running the query [' . $conn->error . ']');
    } else {
        // Get the new order ID
        $orderId = $conn->insert_id;
        
        // Send customer confirmation email
        require_once("mailer.php");
        
        // Send automatic notifications to admins/subadmins
        sendNewOrderNotifications($orderId, $user_name, $user_pack, $user_state, $user_phone);
        
        $output = json_encode(array('type' => 'message', 'text' => 'Hi ' . $user_name . ', thank you for the message. We will get back to you shortly.'));
        die($output);
    }
    return !$result;
} else {
    $output = json_encode(array('type' => 'error_emptyfield', 'text' => 'Oops!! There was a problem with your submission. Please complete the form and try again. [' . $conn->error . ']'));
    die($output);
    // echo 'Oops!! There was a problem with your submission. Please complete the form and try again.';
    // die('<h4 class="alert alert-danger">Oops! There was a problem with your submission. Please complete the form and try again.</h4>');
}

/**
 * Send automatic notifications to admins and subadmins when a new order is placed
 */
function sendNewOrderNotifications($orderId, $customerName, $package, $state, $phone) {
    global $conn;
    
    // Check if notification_preferences table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'notification_preferences'");
    $hasNotificationSystem = ($tableCheck->num_rows > 0);
    
    // Get all active admins and subadmins
    $usersSql = "SELECT u.* FROM users u WHERE u.status = 'active' AND (u.role = 'admin' OR u.role = 'subadmin')";
    
    // If notification system exists, filter by preferences
    if ($hasNotificationSystem) {
        $usersSql = "SELECT u.*, 
                     COALESCE(np.notify_new_order, 1) as notify_new_order,
                     COALESCE(np.email_notifications, 1) as email_notifications,
                     np.quiet_hours_start,
                     np.quiet_hours_end
                     FROM users u
                     LEFT JOIN notification_preferences np ON u.id = np.user_id
                     WHERE u.status = 'active' 
                     AND (u.role = 'admin' OR u.role = 'subadmin')
                     AND COALESCE(np.notify_new_order, 1) = 1
                     AND COALESCE(np.email_notifications, 1) = 1";
    }
    
    $usersResult = $conn->query($usersSql);
    
    if (!$usersResult) {
        return; // Silently fail if query fails
    }
    
    $currentTime = date('H:i:s');
    $currentDate = date('M d, Y h:i A');
    
    while ($user = $usersResult->fetch_assoc()) {
        // Check quiet hours if notification system exists
        if ($hasNotificationSystem && 
            !empty($user['quiet_hours_start']) && 
            !empty($user['quiet_hours_end'])) {
            
            if ($currentTime >= $user['quiet_hours_start'] && 
                $currentTime <= $user['quiet_hours_end']) {
                continue; // Skip this user during quiet hours
            }
        }
        
        // Prepare email
        $to = $user['email'];
        $subject = "🔔 New Order #$orderId - $customerName";
        
        $message = "Hello " . $user['full_name'] . ",\n\n";
        $message .= "A new order has been placed on the Sank Magic Copy Book platform!\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "📦 ORDER DETAILS\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "Order ID: #$orderId\n";
        $message .= "Customer: $customerName\n";
        $message .= "Package: $package\n";
        $message .= "State: $state\n";
        $message .= "Phone: $phone\n";
        $message .= "Time: $currentDate\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "👉 Login to view and manage this order:\n";
        $message .= "https://" . $_SERVER['HTTP_HOST'] . "/admin_dashboard_crm.php\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "To manage notification settings, visit:\n";
        $message .= "https://" . $_SERVER['HTTP_HOST'] . "/sales_notifications.php\n\n";
        $message .= "Best regards,\n";
        $message .= "Emerald Tech Hub Order System\n";
        
        // Send email using PHP mail function
        $headers = "From: Emerald Tech Hub <noreply@" . $_SERVER['HTTP_HOST'] . ">\r\n";
        $headers .= "Reply-To: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        $mailSent = @mail($to, $subject, $message, $headers);
        
        // Log notification if notification system exists
        if ($hasNotificationSystem) {
            $status = $mailSent ? 'sent' : 'failed';
            $errorMsg = $mailSent ? null : 'Email sending failed';
            $sentAt = $mailSent ? date('Y-m-d H:i:s') : null;
            
            $logSql = "INSERT INTO notification_logs 
                       (user_id, order_id, notification_type, notification_channel, subject, message, recipient, status, error_message, sent_at)
                       VALUES (?, ?, 'new_order', 'email', ?, ?, ?, ?, ?, ?)";
            
            $logStmt = $conn->prepare($logSql);
            if ($logStmt) {
                $logStmt->bind_param('iissssss', 
                    $user['id'], 
                    $orderId, 
                    $subject, 
                    $message, 
                    $to, 
                    $status, 
                    $errorMsg, 
                    $sentAt
                );
                $logStmt->execute();
            }
        }
    }
}
