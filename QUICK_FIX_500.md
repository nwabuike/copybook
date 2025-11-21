# 🚀 QUICK FIX GUIDE - 500 Error on Live Server

## Step 1: Upload Files to Live Server
Upload these updated files via FTP/cPanel:
```
php/submit_order.php (UPDATED - better error handling)
php/test_submit.php (NEW - diagnostic tool)
php/test_db.php (NEW - database test)
test_order_form.html (NEW - test page)
```

## Step 2: Run Diagnostic Test
**Option A: Browser**
1. Visit: `https://yourdomain.com/magicbook/test_order_form.html`
2. Click "1. Run Diagnostic Test"
3. Review the results

**Option B: Direct**
1. Visit: `https://yourdomain.com/magicbook/php/test_submit.php`
2. Look at the JSON output

## Step 3: Fix Based on Results

### ❌ If "db_connection" shows error:
**Problem:** Wrong database credentials
**Fix:** Update `php/db.php` on live server with correct info:
```php
$host = "localhost"; // Ask your host if different
$userName = "your_cpanel_username_dbname";
$password = "your_database_password";
$dbName = "your_cpanel_username_dbname";
```

### ❌ If "orders_table_exists" is false:
**Problem:** Table doesn't exist
**Fix:** Run this SQL in phpMyAdmin:
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

### ❌ If "missing_columns" shows any:
**Problem:** Table structure is wrong
**Fix:** Add missing columns in phpMyAdmin:
```sql
ALTER TABLE orders ADD COLUMN referral_code VARCHAR(20) NULL;
```

### ❌ If "php_version_ok" is false:
**Problem:** Old PHP version
**Fix:** 
- cPanel: Software → Select PHP Version → Choose 7.4 or 8.0
- Or contact your hosting support

## Step 4: Test Order Submission
1. On `test_order_form.html`, scroll to "3. Test Order Submission"
2. Click "Submit Test Order"
3. Should show green success message

## Step 5: Check Your Main Site
1. Visit your main page: `https://yourdomain.com/magicbook/index.php`
2. Fill order form and submit
3. Should redirect to thank you page

---

## 🔍 Still Getting 500 Error?

### Check Error Logs:
**cPanel:**
1. Login to cPanel
2. Click "Errors" under "Metrics"
3. Look for recent PHP errors

**File Manager:**
Look for these files:
- `error_log` in your root folder
- `error_log` in magicbook folder

### Common Error Messages & Fixes:

**"Call to undefined function mysqli_connect"**
→ Contact host to enable mysqli extension

**"headers already sent"**
→ Already fixed in updated code

**"Maximum execution time exceeded"**
→ Already fixed with timeout handling

**"Cannot modify header information"**
→ Already fixed with output buffering

---

## 📞 Need More Help?

Run diagnostic and send me:
1. Screenshot of test_order_form.html results
2. Last 10 lines from error log
3. Your hosting provider name
4. Output from php/test_submit.php

---

## Files Overview:

| File | Purpose |
|------|---------|
| `php/submit_order.php` | Main order processing (UPDATED) |
| `php/test_submit.php` | Diagnostic tool to find issues |
| `php/test_db.php` | Test database connection |
| `test_order_form.html` | Complete test interface |
| `TROUBLESHOOTING_500_ERROR.md` | Detailed guide |

---

## Most Common Fix (90% of cases):
**Wrong database credentials in php/db.php**

Check with your hosting provider:
- Database name (usually: cpanelusername_dbname)
- Database username (usually same as db name)
- Database password (you set this when creating DB)
- Database host (usually: localhost)

Update php/db.php on your live server with correct info!
