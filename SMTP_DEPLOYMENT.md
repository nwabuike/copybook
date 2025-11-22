# SMTP Email Configuration - Server Deployment Guide

## Problem
You're seeing this error on your live server:
```
Fatal error: Failed opening required 'smtp_config.php'
```

This is because `smtp_config.php` contains sensitive credentials and is NOT included in git.

## Quick Fix (Manual Setup)

### Option 1: Using FTP/File Manager (Easiest)

1. **On your local machine**, locate the file:
   ```
   c:\laragon\www\magicbook\php\smtp_config.php
   ```

2. **Upload this file** to your server at:
   ```
   /home/kfxulohq/public_html/copybook/php/smtp_config.php
   ```

3. **Set proper permissions** (using File Manager or FTP):
   ```
   chmod 644 php/smtp_config.php
   ```

4. **Test** by visiting:
   ```
   https://your-domain.com/copybook/php/test_email.php
   ```

### Option 2: Create Config File Manually

1. **SSH into your server** or use cPanel File Manager

2. **Navigate to the php directory**:
   ```bash
   cd /home/kfxulohq/public_html/copybook/php
   ```

3. **Copy the example file**:
   ```bash
   cp smtp_config.example.php smtp_config.php
   ```

4. **Edit the file** with your credentials:
   ```bash
   nano smtp_config.php
   ```
   
   Update these values:
   ```php
   'smtp_username' => 'emeraldonlineecom@gmail.com',
   'smtp_password' => 'iwve gqyq sdck rhbl',
   'from_email' => 'emeraldonlineecom@gmail.com',
   'from_name' => 'Smartkids Edu',
   'admin_email' => 'emeraldonlineecom@gmail.com',
   ```

5. **Save and exit** (Ctrl+X, then Y, then Enter)

6. **Set proper permissions**:
   ```bash
   chmod 644 smtp_config.php
   ```

### Option 3: Using Setup Script (Advanced)

If you have SSH access:

```bash
cd /home/kfxulohq/public_html/copybook
chmod +x setup_smtp.sh
./setup_smtp.sh
```

Follow the prompts to enter your SMTP credentials.

## Verify Installation

1. **Check if file exists**:
   ```bash
   ls -la /home/kfxulohq/public_html/copybook/php/smtp_config.php
   ```

2. **Check if PHPMailer is installed**:
   ```bash
   cd /home/kfxulohq/public_html/copybook/php
   composer install
   ```

3. **Test email sending**:
   - Visit: `https://your-domain.com/copybook/php/test_config.php`
   - Then: `https://your-domain.com/copybook/php/test_email.php`

## Important Notes

⚠️ **Security**:
- Never commit `smtp_config.php` to git
- Keep your app password secure
- Use file permissions 644 (readable by owner and web server)

📧 **Gmail App Password**:
- Must enable 2-Step Verification first
- Generate at: https://myaccount.google.com/apppasswords
- Password format: `xxxx xxxx xxxx xxxx` (4 groups of 4 characters)

🔄 **Future Deployments**:
- `smtp_config.php` will NOT be overwritten by git pull
- You only need to set it up once per server
- Keep a backup copy in a secure location

## Troubleshooting

### "Class 'PHPMailer\PHPMailer\PHPMailer' not found"
```bash
cd /home/kfxulohq/public_html/copybook/php
composer install
```

### "SMTP connect() failed"
- Check if port 587 is open on your server
- Try port 465 with SSL instead of TLS
- Contact your hosting provider about SMTP restrictions

### "Authentication failed"
- Verify your app password is correct (no spaces)
- Make sure 2-Step Verification is enabled
- Try generating a new app password

### Emails not sending on server (but work locally)
- Some shared hosting blocks outgoing SMTP on port 587
- Try using your hosting provider's SMTP server instead
- Check with your host about SMTP/email sending policies

## Contact

If you continue having issues, provide:
1. Output from `php/test_config.php`
2. Any error messages from `php/test_email.php`
3. Your hosting provider name
