<?php
/**
 * Email Sending API Endpoint
 * Handles email sending via SMTP for customer orders
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Load SMTP mailer
require_once __DIR__ . '/mailer_smtp.php';

// Get request data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit;
}

// Validate required fields
$requiredFields = ['to', 'subject', 'body'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

// Extract data
$to = $data['to'];
$toName = $data['to_name'] ?? 'Customer';
$subject = $data['subject'];
$body = $data['body'];
$isHtml = $data['is_html'] ?? false;
$replyTo = $data['reply_to'] ?? null;

// Handle bulk emails (comma or semicolon separated)
$recipients = preg_split('/[,;]/', $to);
$recipients = array_map('trim', $recipients);
$recipients = array_filter($recipients);

$results = [];
$successCount = 0;
$failCount = 0;

// Send to each recipient
foreach ($recipients as $recipient) {
    if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
        $results[] = [
            'email' => $recipient,
            'success' => false,
            'message' => 'Invalid email address'
        ];
        $failCount++;
        continue;
    }
    
    $result = sendSMTPEmail($recipient, $toName, $subject, $body, $isHtml, $replyTo);
    
    $results[] = [
        'email' => $recipient,
        'success' => $result['success'],
        'message' => $result['message'],
        'error' => $result['error']
    ];
    
    if ($result['success']) {
        $successCount++;
    } else {
        $failCount++;
    }
    
    // Small delay between emails to avoid rate limiting
    if (count($recipients) > 1) {
        usleep(500000); // 0.5 second delay
    }
}

// Return results
$response = [
    'success' => $successCount > 0,
    'total' => count($recipients),
    'success_count' => $successCount,
    'fail_count' => $failCount,
    'results' => $results,
    'message' => $successCount > 0 
        ? "Successfully sent to $successCount recipient(s)" . ($failCount > 0 ? ", $failCount failed" : "")
        : "Failed to send all emails"
];

http_response_code($response['success'] ? 200 : 500);
echo json_encode($response);
