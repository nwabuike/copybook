# 📧 SMTP Email Setup Guide

## ✅ What's Been Created

I've set up a complete SMTP email solution using PHPMailer. Your order form will now automatically:
1. Try to send emails via SMTP (Gmail, SendGrid, etc.)
2. Fall back to PHP mail() if SMTP fails
3. Continue working even if both fail (order still saves!)

---

## 📁 New Files Created

```
php/
├── composer.json          - PHPMailer dependency
├── smtp_config.php        - SMTP settings (EDIT THIS!)
├── mailer_smtp.php        - Email sending functions
└── submit_order.php       - Updated with SMTP support
```

---

## 🚀 Quick Setup (3 Steps)

### Step 1: Install PHPMailer

**Option A - Via Composer (Recommended):**
```bash
cd c:\laragon\www\magicbook\php
composer install
```

**Option B - Manual Download (If no Composer):**
1. Download: https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
2. Extract to: `php/vendor/phpmailer/phpmailer/`
3. Or download and place on your live server

**Option C - On Live Server with cPanel:**
1. SSH or Terminal in cPanel
2. Navigate to your php directory
3. Run: `composer install` or `composer require phpmailer/phpmailer`

### Step 2: Configure SMTP Settings

Edit `php/smtp_config.php` and update these values:

```php
'smtp_username' => 'your-email@gmail.com',    // Your Gmail address
'smtp_password' => 'your-app-password',       // Gmail App Password
'from_email' => 'your-email@gmail.com',       // Must match username
'admin_email' => 'emeraldonlineecom@gmail.com', // Where orders are sent
```

### Step 3: Get Gmail App Password

1. Go to: https://myaccount.google.com/security
2. Enable **2-Step Verification** (required!)
3. Go to: https://myaccount.google.com/apppasswords
4. Select **"Mail"** and **"Other (Custom name)"**
5. Click **Generate**
6. Copy the 16-character password (like: `abcd efgh ijkl mnop`)
7. Paste into `smtp_config.php` → `smtp_password` (remove spaces!)

---

## 📧 SMTP Provider Options

### Gmail (Free, Easy)
```php
'smtp_host' => 'smtp.gmail.com',
'smtp_port' => 587,
'smtp_encryption' => 'tls',
'smtp_username' => 'your-email@gmail.com',
'smtp_password' => 'your-app-password',  // Get from myaccount.google.com/apppasswords
```
**Limit:** 500 emails/day

### SendGrid (Free Tier: 100 emails/day)
```php
'smtp_host' => 'smtp.sendgrid.net',
'smtp_port' => 587,
'smtp_encryption' => 'tls',
'smtp_username' => 'apikey',  // Literally the word "apikey"
'smtp_password' => 'SG.YOUR_API_KEY_HERE',
```
**Setup:** https://sendgrid.com → Create account → Settings → API Keys

### Mailgun (Free Tier: 5,000 emails/month)
```php
'smtp_host' => 'smtp.mailgun.org',
'smtp_port' => 587,
'smtp_encryption' => 'tls',
'smtp_username' => 'postmaster@yoursandbox.mailgun.org',
'smtp_password' => 'your-password',
```
**Setup:** https://mailgun.com → Sign up → Sending → SMTP credentials

### Outlook/Office365
```php
'smtp_host' => 'smtp.office365.com',
'smtp_port' => 587,
'smtp_encryption' => 'tls',
'smtp_username' => 'your-email@outlook.com',
'smtp_password' => 'your-password',
```

---

## 🧪 Testing

### Test 1: Install Composer Locally (Localhost)
```powershell
cd c:\laragon\www\magicbook\php
composer install
```

### Test 2: Configure SMTP
Edit `php/smtp_config.php` with your Gmail credentials

### Test 3: Test Email Function
Create `php/test_email.php`:
```php
<?php
require_once 'mailer_smtp.php';

$testOrder = [
    'order_id' => 'TEST123',
    'fullname' => 'Test User',
    'email' => 'your-test-email@gmail.com',
    'phone' => '08012345678',
    'altphone' => '',
    'address' => '123 Test St',
    'state' => 'Lagos',
    'pack' => 'starter',
    'referral_code' => 'TESTREF',
    'created_at' => date('Y-m-d H:i:s')
];

$result = sendCustomerOrderConfirmation($testOrder);
echo "<pre>";
print_r($result);
echo "</pre>";
?>
```

Visit: `http://localhost/magicbook/php/test_email.php`

### Test 4: Submit Order
1. Go to: `http://localhost/magicbook/test_order_form.html`
2. Submit test order
3. Check response for `"email_method": "smtp"` (success!)

---

## 📤 Upload to Live Server

### Files to Upload:
```
php/
├── composer.json
├── smtp_config.php (with YOUR credentials!)
├── mailer_smtp.php
├── submit_order.php (updated)
└── vendor/ (entire folder from composer install)
```

### Via FTP:
1. Upload all files in `php/` directory
2. Make sure `vendor/` folder is uploaded completely
3. Update `smtp_config.php` with your credentials

### Via cPanel File Manager:
1. Upload php folder
2. Extract if zipped
3. Or use Terminal to run `composer install` on server

---

## 🔍 Troubleshooting

### "PHPMailer not installed" Error
**Solution:** Run `composer install` in the php directory

### "SMTP connect() failed" Error
**Solution:** 
- Check your Gmail App Password (not regular password!)
- Make sure 2-Step Verification is enabled
- Check if SMTP port 587 is open on your hosting

### "Invalid credentials" Error
**Solution:**
- Verify username matches from_email
- Regenerate Gmail App Password
- Remove any spaces from password

### Still Using mail() Instead of SMTP
**Solution:**
- Check if vendor/autoload.php exists
- Verify smtp_config.php exists
- Check error logs for SMTP errors
- Set `'debug_mode' => true` in smtp_config.php

---

## 🎯 How It Works

### Priority System:
1. **First:** Try SMTP (PHPMailer)
2. **Fallback:** Try mail() function
3. **Always:** Order saves regardless!

### Email Flow:
```
Order Submitted
    ↓
Try SMTP Email
    ├── Success → Send emails via SMTP ✅
    └── Failed → Try mail() function
              ├── Success → Send emails via mail() ✅
              └── Failed → Log error, continue ⚠️
                    ↓
Order Saved to Database ✅
Redirect to Thank You Page ✅
```

---

## 📊 Check Email Status

Response will show:
```json
{
  "success": true,
  "email_method": "smtp",  // or "mail" or "none"
  "admin_mail": "sent",    // or "failed" or "disabled"
  "customer_mail": "sent"  // or "failed" or "disabled"
}
```

---

## 🔒 Security Notes

1. **Never commit smtp_config.php to public repos!** (Contains password)
2. Use environment variables in production:
   ```php
   'smtp_password' => getenv('SMTP_PASSWORD'),
   ```
3. Keep vendor/ folder outside public_html if possible
4. Use App Passwords, never your real Gmail password

---

## 💡 Pro Tips

1. **For High Volume:** Use SendGrid or Mailgun (better deliverability)
2. **For Personal Projects:** Gmail works great (500/day limit)
3. **For Production:** Consider dedicated SMTP service
4. **For Testing:** Enable debug mode in smtp_config.php

---

## 📞 Need Help?

If you get errors:
1. Check `error_log` file in php/ directory
2. Enable debug mode: `'debug_mode' => true` in smtp_config.php
3. Test with `php/test_email.php` script
4. Share the error message with me

**Your order form will work immediately after running `composer install` and updating smtp_config.php!** 🎉
