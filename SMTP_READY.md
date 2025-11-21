# ✅ SMTP Email System - Ready to Use!

## 🎉 What's Installed

Your order form now has a professional SMTP email system with automatic fallback!

### ✅ Installed Components:
- **PHPMailer 6.12.0** - Industry-standard email library
- **SMTP Configuration** - Ready to customize
- **Email Functions** - Admin & customer notifications
- **Automatic Fallback** - SMTP → mail() → still works!
- **Test Page** - Easy testing interface

---

## 🚀 Quick Start (2 Steps)

### Step 1: Configure Your Gmail
Edit: `php/smtp_config.php`

Change these 3 lines:
```php
'smtp_username' => 'your-actual-email@gmail.com',
'smtp_password' => 'your-16-char-app-password',
'from_email' => 'your-actual-email@gmail.com',
```

### Step 2: Get Gmail App Password
1. Visit: https://myaccount.google.com/apppasswords
2. Enable 2-Step Verification first (required)
3. Generate App Password for "Mail"
4. Copy the 16-character password
5. Paste into smtp_config.php (remove spaces)

**That's it!** Emails will start working immediately.

---

## 🧪 Testing

### Test Page (Localhost):
```
http://localhost/magicbook/php/test_email.php
```

This page shows:
- ✅ PHPMailer installation status
- ✅ Current SMTP configuration
- ✅ Send test emails
- ✅ Detailed error messages

### Test Order Form:
```
http://localhost/magicbook/test_order_form.html
```

Submit a test order and check response for:
```json
{
  "email_method": "smtp",  // Success!
  "admin_mail": "sent",
  "customer_mail": "sent"
}
```

---

## 📁 Files Created

```
php/
├── composer.json          ✅ Dependency file
├── composer.lock          ✅ Version lock
├── smtp_config.php        ⚙️ EDIT THIS with your Gmail!
├── mailer_smtp.php        ✅ Email functions
├── test_email.php         ✅ Test interface
├── submit_order.php       ✅ Updated with SMTP
└── vendor/                ✅ PHPMailer library
    └── phpmailer/
        └── phpmailer/     ✅ Installed v6.12.0
```

---

## 🌐 Upload to Live Server

### Required Files:
1. **php/smtp_config.php** (with YOUR credentials!)
2. **php/mailer_smtp.php**
3. **php/submit_order.php** (updated)
4. **php/composer.json**
5. **php/vendor/** (entire folder)

### Upload Methods:

**Option A - FTP:**
- Upload entire `php/` folder
- Make sure `vendor/` uploads completely (~2MB)

**Option B - cPanel File Manager:**
- Zip the `php/` folder
- Upload zip
- Extract on server

**Option C - SSH/Terminal:**
```bash
cd /path/to/your/php/directory
composer install
```

---

## 🔧 Configuration Options

### Use Different SMTP Provider?

**SendGrid (100 emails/day free):**
```php
'smtp_host' => 'smtp.sendgrid.net',
'smtp_port' => 587,
'smtp_username' => 'apikey',
'smtp_password' => 'SG.your-api-key',
```

**Mailgun (5,000 emails/month free):**
```php
'smtp_host' => 'smtp.mailgun.org',
'smtp_port' => 587,
'smtp_username' => 'postmaster@sandbox123.mailgun.org',
'smtp_password' => 'your-password',
```

**Outlook:**
```php
'smtp_host' => 'smtp.office365.com',
'smtp_port' => 587,
'smtp_username' => 'your-email@outlook.com',
'smtp_password' => 'your-password',
```

---

## 📊 How It Works

### Email Priority System:

```
Order Submitted
    ↓
1. Try SMTP (PHPMailer)
    ├─ Success? → Emails sent via SMTP ✅
    └─ Failed? → Try fallback
         ↓
2. Try mail()
    ├─ Success? → Emails sent via mail() ✅
    └─ Failed? → Log error
         ↓
3. Order Still Saves! ✅
```

### Response Fields:
- `email_method`: "smtp", "mail", or "none"
- `admin_mail`: "sent", "failed", or "disabled"
- `customer_mail`: "sent", "failed", or "disabled"

---

## 🐛 Troubleshooting

### "PHPMailer not installed"
**Fix:** Check if `vendor/phpmailer/phpmailer/` exists
**Action:** Run `composer install` in php directory

### "SMTP connect() failed"
**Fix:** Check these in smtp_config.php:
- Username = your Gmail address
- Password = 16-char App Password (not regular password!)
- 2-Step Verification enabled on Gmail
- Port 587 is open (check with hosting)

### "Invalid credentials"
**Fix:** 
- Regenerate App Password at myaccount.google.com/apppasswords
- Copy without spaces: `abcdefghijklmnop`
- Make sure username matches from_email

### Emails go to spam
**Fix:**
- Use same domain for from_email and smtp
- Add SPF record to DNS
- Use professional SMTP (SendGrid, Mailgun)

### Debug Mode
Enable detailed errors:
```php
// In smtp_config.php
'debug_mode' => true,  // Shows SMTP communication
```

---

## 📈 Email Limits

| Provider | Free Tier | Cost |
|----------|-----------|------|
| Gmail | 500/day | Free |
| SendGrid | 100/day | Free, then paid |
| Mailgun | 5,000/month | Free, then paid |
| AWS SES | 62,000/month | $0.10/1000 after |

---

## ✅ What's Working Now

### ✅ On Localhost:
- PHPMailer installed
- Configuration ready
- Test page available
- Order form updated

### 🔧 Next Step:
1. Edit `php/smtp_config.php` with your Gmail
2. Test at: `http://localhost/magicbook/php/test_email.php`
3. Upload to live server
4. Enjoy automatic email notifications! 📧

---

## 📞 Support

**Test Email Page:** http://localhost/magicbook/php/test_email.php
**Setup Guide:** SMTP_SETUP_GUIDE.md
**Quick Fix:** Update smtp_config.php with Gmail credentials

**Your order form works immediately - emails are a bonus notification feature!** 🎉
