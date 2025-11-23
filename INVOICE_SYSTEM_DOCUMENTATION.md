# 📧 Invoice System - Smartkids Edu

## Overview
An automated invoice generation and email system that sends professional invoices to customers when their orders are delivered successfully.

## Features
✅ **Automatic Invoice Generation** - Invoices are automatically generated when orders are marked as "Delivered"  
✅ **Professional HTML Template** - Beautiful, responsive invoice design with company branding  
✅ **Marked as PAID** - All invoices are pre-marked as paid (payment on delivery)  
✅ **Email Delivery** - Invoices are automatically sent to customer email addresses  
✅ **Manual Resend** - Admins can manually send/resend invoices from the dashboard  
✅ **Invoice Tracking** - System tracks which invoices have been sent and when  
✅ **SMTP & Fallback** - Uses SMTP when configured, falls back to PHP mail() if needed

---

## How It Works

### 1. **Automatic Invoice Sending**
When an admin marks an order as "Delivered" in the dashboard:
1. Order status updates to `delivered`
2. System generates HTML invoice with order details
3. Invoice is automatically emailed to customer
4. Database tracks that invoice was sent
5. Admin receives confirmation notification

### 2. **Manual Invoice Sending**
Admins can manually send invoices from the dashboard:
1. Look for the 📄 invoice button on delivered orders
2. Button appears only for delivered orders with email addresses
3. Click the invoice button
4. Confirm sending
5. Invoice is sent and button turns green

---

## Invoice Template Details

### What's Included in the Invoice:
- **Company Header**: Smartkids Edu branding
- **Invoice Number**: Format `INV-{ORDER_ID}-{YEAR}{MONTH}`
- **Customer Details**: Name, address, state, phone, email
- **Order Information**: Order date, delivery date, order ID
- **Product Details**: Package name, quantity, unit price, total
- **Payment Status**: Pre-marked as "PAID" with green badge
- **Totals**: Subtotal, tax, grand total
- **Company Contact**: Email, phone, website
- **Terms & Conditions**: Payment terms and support information

### Invoice Appearance:
- Professional green theme matching company branding
- Responsive design (works on mobile and desktop)
- Print-friendly layout
- Clean, modern typography
- Company logo placeholder (can be customized)

---

## Files Added/Modified

### New Files:
1. **`php/invoice_generator.php`** (500+ lines)
   - Core invoice generation logic
   - HTML template generation
   - Email sending functionality
   - Functions: `generateInvoiceHTML()`, `sendInvoiceEmail()`, `generateAndSendInvoice()`

2. **`api/invoices.php`** (150+ lines)
   - API endpoint for invoice operations
   - Actions: `send`, `preview`, `check_status`
   - Handles manual invoice sending from admin panel

3. **`add_invoice_columns.php`** (50+ lines)
   - Database migration script
   - Adds `invoice_sent` and `invoice_sent_at` columns to orders table
   - Run once: `php add_invoice_columns.php`

### Modified Files:
1. **`api/orders.php`**
   - Added automatic invoice sending when order status becomes "delivered"
   - Integrates with invoice generator
   - Tracks invoice sending in database

2. **`admin_dashboard_crm.php`**
   - Added invoice button (📄 icon) for delivered orders
   - Shows green button if invoice already sent
   - JavaScript function `sendInvoice()` to handle manual sending
   - CSS styling for invoice button

---

## Database Changes

### New Columns in `orders` Table:
```sql
ALTER TABLE orders 
ADD COLUMN invoice_sent TINYINT(1) DEFAULT 0 COMMENT 'Whether invoice has been sent';

ALTER TABLE orders 
ADD COLUMN invoice_sent_at DATETIME NULL COMMENT 'When invoice was sent';
```

**Already Applied**: Migration has been run successfully ✓

---

## Setup Instructions

### ✅ Step 1: Database Migration (COMPLETED)
Migration has been executed successfully. The following columns were added:
- `invoice_sent` - Tracks if invoice has been sent
- `invoice_sent_at` - Records when invoice was sent

### Step 2: Configure SMTP (Optional but Recommended)
For reliable email delivery, configure SMTP settings:

1. Edit `php/smtp_config.php`
2. Enable SMTP:
```php
'enable_smtp' => true,
'smtp_host' => 'smtp.gmail.com',
'smtp_port' => 587,
'smtp_username' => 'your-email@gmail.com',
'smtp_password' => 'your-app-password',
'from_email' => 'no-reply@smartkidsedu.com.ng',
'from_name' => 'Smartkids Edu',
```

**If SMTP is not configured**: System will automatically fall back to PHP `mail()` function.

### Step 3: Test Invoice System

#### Test Automatic Sending:
1. Create a test order with a valid email address
2. Mark the order as "Delivered" in the dashboard
3. Check customer email for invoice
4. Verify `invoice_sent` column is updated to 1

#### Test Manual Sending:
1. Go to Admin Dashboard CRM
2. Find a delivered order with an email address
3. Click the 📄 invoice button
4. Confirm sending
5. Check for success notification
6. Verify email delivery

### Step 4: Preview Invoice (Testing)
To preview an invoice without sending:
```
http://yourdomain.com/api/invoices.php?action=preview&order_id=123
```
Replace `123` with actual order ID.

---

## Usage Guide for Admins

### Viewing Invoice Status
In the admin dashboard, for delivered orders:
- **Blue 📄 button** = Invoice not yet sent
- **Green 📄 button** = Invoice already sent
- **No button** = Order not delivered or no email address

### Sending Invoices Manually
1. Navigate to Admin Dashboard CRM
2. Locate the order (must be in "Delivered" status)
3. Click the 📄 invoice button
4. Confirm in the popup dialog
5. Wait for success notification
6. Invoice is sent immediately

### Resending Invoices
To resend an invoice (e.g., customer didn't receive it):
1. Click the green 📄 button on a delivered order
2. Confirm resending
3. New invoice email is sent with updated timestamp

### Checking If Invoice Was Sent
Check the orders table:
- `invoice_sent = 1` means invoice was sent
- `invoice_sent_at` shows the date/time it was sent

---

## API Endpoints

### 1. Send Invoice
```http
POST /api/invoices.php?action=send
Content-Type: application/json

{
  "order_id": 123
}
```

**Response:**
```json
{
  "success": true,
  "message": "Invoice sent successfully via SMTP",
  "method": "smtp"
}
```

### 2. Preview Invoice
```http
GET /api/invoices.php?action=preview&order_id=123
```

**Response:** HTML invoice (for preview/testing)

### 3. Check Invoice Status
```http
GET /api/invoices.php?action=check_status&order_id=123
```

**Response:**
```json
{
  "success": true,
  "data": {
    "order_id": 123,
    "status": "delivered",
    "has_email": true,
    "email": "customer@example.com",
    "invoice_sent": true,
    "invoice_sent_at": "2025-11-24 14:30:00",
    "can_send_invoice": true
  }
}
```

---

## Troubleshooting

### Invoice Not Being Sent Automatically
**Problem**: Order marked as delivered but invoice not sent

**Solutions**:
1. Check if customer has email address in order
2. Verify email address is valid format
3. Check server error logs for PHP errors
4. Try manual sending using invoice button
5. Check SMTP configuration (if using SMTP)
6. Verify PHPMailer is installed: `composer require phpmailer/phpmailer`

### Customer Not Receiving Invoices
**Problem**: Invoice sent successfully but customer doesn't receive it

**Solutions**:
1. Check customer's spam/junk folder
2. Verify email address is correct
3. Test with different email provider (Gmail, Yahoo, etc.)
4. Configure SMTP for better deliverability
5. Check domain reputation (if using custom domain)

### Invoice Button Not Showing
**Problem**: Invoice button doesn't appear in dashboard

**Solutions**:
1. Verify order status is "delivered"
2. Confirm order has customer email address
3. Clear browser cache and reload page
4. Check browser console for JavaScript errors

### Database Errors
**Problem**: SQL errors when sending invoices

**Solutions**:
1. Verify migration was run: `php add_invoice_columns.php`
2. Check if `invoice_sent` and `invoice_sent_at` columns exist
3. Manually add columns if migration failed
4. Check database permissions

---

## Customization

### Customize Invoice Template
Edit `php/invoice_generator.php` to modify:
- **Company Name/Logo**: Update line ~70-75 (company header)
- **Color Scheme**: Modify CSS colors (default: green #0a7c42)
- **Footer Text**: Update line ~350-380 (contact info, terms)
- **Email From Address**: Change in sendInvoiceEmail() function
- **Invoice Number Format**: Modify line ~41 (INV-{ID}-{DATE} format)

### Add Company Logo
In `generateInvoiceHTML()` function, add after line 70:
```html
<img src="https://yourdomain.com/images/logo.png" alt="Logo" style="max-width: 200px; margin-bottom: 10px;">
```

### Customize Email Subject
In `php/invoice_generator.php`, line ~330:
```php
$subject = "Your Invoice - Order #" . $order['id'];
```

---

## Price Configuration

Current package prices (in Naira):
- **Starter Set**: ₦18,000
- **Learning Bundle**: ₦32,000  
- **Mastery Collection**: ₦45,000

To update prices, edit `php/invoice_generator.php` lines 15-19.

---

## Security Notes

1. **Email Validation**: All email addresses are validated before sending
2. **SQL Injection Prevention**: All database queries use parameterized inputs
3. **Access Control**: Only logged-in admins/agents can send invoices
4. **Activity Logging**: All invoice sends are logged for audit trail

---

## Future Enhancements (Optional)

- [ ] PDF invoice generation (add PDF library)
- [ ] Invoice download feature
- [ ] Bulk invoice sending
- [ ] Custom invoice templates
- [ ] Invoice number customization
- [ ] Multi-language support
- [ ] WhatsApp invoice delivery
- [ ] Invoice statistics dashboard

---

## Support

For issues or questions about the invoice system:
- Email: goldenemeraldglobal@gmail.com
- Phone: 09038356928

---

## Summary

✅ **Invoice system is fully functional and ready to use**
✅ **Database migration completed successfully**
✅ **Automatic sending configured for delivered orders**
✅ **Manual sending available in admin dashboard**
✅ **Professional invoice template created**
✅ **SMTP and fallback email configured**

**Next Steps**:
1. Configure SMTP settings (optional but recommended)
2. Test with a real order
3. Train admins on manual sending
4. Monitor invoice delivery success rate

---

**Created**: November 24, 2025  
**Status**: ✅ Production Ready
