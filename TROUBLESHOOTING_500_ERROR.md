# 500 Error Troubleshooting Guide for Live Server

## Problem
Getting "Error: Server returned error: 500" when submitting order form on live/production server, but it works perfectly on localhost.

## Common Causes & Solutions

### 1. **Database Connection Issues**
**Symptoms:** Order not saving, 500 error immediately on submit

**Fix:**
- Check your live server's `php/db.php` file
- Update credentials for your live database:
```php
<?php
$host = "localhost"; // or your DB host
$userName = "your_live_db_username";
$password = "your_live_db_password";
$dbName = "your_live_db_name";
$conn = new mysqli($host, $userName, $password, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
```

### 2. **Missing Database Table or Columns**
**Symptoms:** Connection works but insert fails

**Fix:**
- Ensure your `orders` table exists on live database
- Required columns: `id`, `fullname`, `email`, `phone`, `altphone`, `address`, `state`, `pack`, `referral_code`, `created_at`

**SQL to create table:**
```sql
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) NOT NULL,
  `altphone` varchar(50) DEFAULT NULL,
  `address` text NOT NULL,
  `state` varchar(100) NOT NULL,
  `pack` varchar(50) NOT NULL,
  `referral_code` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3. **PHP Version Issues**
**Symptoms:** Code works on localhost but not live server

**Fix:**
- Check your hosting PHP version (needs PHP 5.6+)
- Contact your hosting provider to upgrade if needed
- Most shared hosting allows PHP version selection in cPanel

### 4. **File Permissions**
**Symptoms:** Intermittent 500 errors, error logs not created

**Fix:**
```bash
# On your live server via SSH or file manager:
chmod 755 php/
chmod 644 php/submit_order.php
chmod 644 php/db.php
```

### 5. **Headers Already Sent Error**
**Symptoms:** 500 error with "headers already sent" in error log

**Fix:** Already implemented in the updated code with output buffering

### 6. **Mail Function Disabled**
**Symptoms:** Order saves but 500 error occurs

**Fix:** Already handled - emails wrapped in try-catch blocks

### 7. **Path Issues (require_once)**
**Symptoms:** File not found errors

**Fix:** Already implemented - uses both relative and __DIR__ paths

---

## Step-by-Step Diagnostic Process

### Step 1: Run the Test Script
1. Upload `php/test_submit.php` to your live server
2. Access it in browser: `https://yourdomain.com/magicbook/php/test_submit.php`
3. Review the JSON output for issues

### Step 2: Check Error Logs
**cPanel:**
1. Login to cPanel
2. Go to "Errors" or "Error Log"
3. Look for recent PHP errors

**Via FTP:**
Check these locations:
- `/public_html/error_log`
- `/public_html/magicbook/error_log`
- `/home/username/logs/`

### Step 3: Enable Error Display (Temporarily)
In `php/submit_order.php`, temporarily change:
```php
ini_set('display_errors', 1); // Change 0 to 1
```
Then test form submission and check browser console for actual PHP error.

**IMPORTANT:** Set back to 0 after debugging!

### Step 4: Test Database Connection Manually
Create `php/test_db.php`:
```php
<?php
require_once 'db.php';
header('Content-Type: application/json');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => $conn->connect_error]);
} else {
    echo json_encode(['status' => 'success', 'message' => 'Connected successfully']);
}
```

### Step 5: Check Server Requirements
Minimum requirements:
- PHP 5.6 or higher (7.4+ recommended)
- MySQL 5.5 or higher
- `mysqli` extension enabled
- `mail()` function available (optional)

---

## Quick Fixes for Common Hosting Providers

### **Shared Hosting (cPanel, Plesk)**
1. Update `php/db.php` with correct credentials
2. PHP version: cPanel → Software → Select PHP Version (choose 7.4+)
3. Check error logs: cPanel → Metrics → Errors

### **Hostinger**
1. Database host is usually `localhost`
2. Error logs: hPanel → Files → File Manager → Show Hidden Files
3. Look for `.htaccess` conflicts

### **Namecheap**
1. Database host might be different (check email from them)
2. Use cPanel → MySQL Databases to verify DB name
3. Remote MySQL might be disabled by default

### **000webhost / Free Hosting**
1. Often blocks `mail()` function - emails will fail (but order will save)
2. Limited execution time - already handled with timeout
3. May need to add `error_reporting(0);` at top of file

---

## Testing Checklist

After making changes, test:
- [ ] Visit test_submit.php - all checks should pass
- [ ] Submit form with valid data
- [ ] Check if order appears in database
- [ ] Verify redirect to thank you page
- [ ] Check error logs are clear
- [ ] Test on different browsers

---

## If Still Not Working

**Send me this information:**
1. Output from `php/test_submit.php`
2. Last 10 lines from error log
3. Hosting provider name
4. PHP version (from test script)

---

## Files Updated to Fix Issue
1. `php/submit_order.php` - Added output buffering, better error handling
2. `php/test_submit.php` - New diagnostic tool
3. `index.php` - Added timeout handling, better error messages

All changes are backward compatible and work on both localhost and live servers.
