<?php
// smtp_config.example.php
// SMTP Configuration Template - Copy this to smtp_config.php and update with your credentials

return [
    // SMTP Server Settings
    'smtp_host' => 'smtp.gmail.com',  // Gmail SMTP server (change for other providers)
    'smtp_port' => 587,                // 587 for TLS, 465 for SSL
    'smtp_encryption' => 'tls',        // 'tls' or 'ssl'
    
    // SMTP Authentication
    'smtp_username' => 'your-email@gmail.com',  // Your Gmail address
    'smtp_password' => 'your-app-password',     // Gmail App Password (NOT regular password)
    
    // From Email Settings
    'from_email' => 'your-email@gmail.com',     // Must match smtp_username for Gmail
    'from_name' => 'Your Company Name',         // Display name
    
    // Admin Notification Email
    'admin_email' => 'admin@yourcompany.com',
    
    // Reply-To Email (optional)
    'reply_to_email' => 'support@yourcompany.com',
    'reply_to_name' => 'Customer Support',
    
    // Email Settings
    'enable_smtp' => true,  // Set to false to disable SMTP emails
    'debug_mode' => false,  // Set to true for debugging (shows SMTP errors)
];

/*
===========================================
SETUP INSTRUCTIONS:
===========================================
1. Copy this file to smtp_config.php
2. Update the credentials with your actual values
3. Never commit smtp_config.php to git (it's in .gitignore)

===========================================
HOW TO GET GMAIL APP PASSWORD:
===========================================
1. Go to: https://myaccount.google.com/security
2. Enable 2-Step Verification (required)
3. Go to: https://myaccount.google.com/apppasswords
4. Select "Mail" and "Other (Custom name)"
5. Generate password
6. Copy the 16-character password (e.g., "abcd efgh ijkl mnop")
7. Paste it in 'smtp_password' above

===========================================
OTHER SMTP PROVIDERS:
===========================================

// SendGrid
'smtp_host' => 'smtp.sendgrid.net',
'smtp_port' => 587,
'smtp_encryption' => 'tls',
'smtp_username' => 'apikey',
'smtp_password' => 'your-sendgrid-api-key',

// Mailgun
'smtp_host' => 'smtp.mailgun.org',
'smtp_port' => 587,
'smtp_encryption' => 'tls',
'smtp_username' => 'postmaster@yourdomain.com',
'smtp_password' => 'your-mailgun-password',

// Outlook/Office365
'smtp_host' => 'smtp.office365.com',
'smtp_port' => 587,
'smtp_encryption' => 'tls',
'smtp_username' => 'your-email@outlook.com',
'smtp_password' => 'your-password',

// Yahoo
'smtp_host' => 'smtp.mail.yahoo.com',
'smtp_port' => 587,
'smtp_encryption' => 'tls',
'smtp_username' => 'your-email@yahoo.com',
'smtp_password' => 'your-app-password',
*/
