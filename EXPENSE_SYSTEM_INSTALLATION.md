# Expense Tracking & Pricing System - Installation Guide

## Overview
This update adds comprehensive expense tracking and dynamic pricing management to the CopyBook order system.

## Features Added

### 1. Expense Tracking
- Add expenses to delivered orders
- Auto-calculate profit (revenue - expenses)
- Track who added expenses and when
- Add notes about expense details
- Role-based access: Subadmins can add expenses, Admins see full analytics

### 2. Dynamic Pricing Management
- Admin-only price management dashboard
- Change package prices dynamically
- Track price change history with audit trail
- Notes for each price change
- Current prices: Starter ₦18,000, Bundle ₦32,000, Collection ₦45,000

### 3. Profit/Loss Reports
- Weekly, monthly, yearly reports
- Custom date range selection
- Visual charts showing revenue, expenses, and profit
- Profit margin calculations
- Detailed order-by-order breakdown
- Warnings for orders without expenses

## Installation Steps

### Step 1: Database Setup
Execute the SQL schema file to add new tables and columns:

```bash
# Navigate to your project directory
cd c:\laragon\www\magicbook

# Run the SQL file
php -r "require 'php/db.php'; $sql = file_get_contents('sql/add_expenses_and_pricing.sql'); \$conn->multi_query(\$sql); echo 'Database updated successfully!';"
```

Or manually import in phpMyAdmin:
1. Open phpMyAdmin
2. Select your database
3. Go to "Import" tab
4. Choose file: `sql/add_expenses_and_pricing.sql`
5. Click "Go"

### Step 2: Verify Database Changes
Check that the following were created:
- ✅ Orders table has new columns: expenses, profit, expenses_notes, expenses_added_by, expenses_added_at
- ✅ New table: package_pricing (with default prices)
- ✅ New table: pricing_history (for audit trail)
- ✅ Indexes created for performance

### Step 3: Test the Features

#### Test Expense Tracking:
1. Go to customer_orderlist.php
2. Edit any delivered order
3. Add expenses (e.g., 5000)
4. Add expense notes (e.g., "Delivery: ₦3000, Packaging: ₦2000")
5. Save - should show calculated profit

#### Test Pricing Management (Admin Only):
1. Go to pricing_management.php
2. Update any package price
3. Add reason for change
4. Save and verify price updated
5. Check history table shows the change

#### Test Profit/Loss Report (Admin Only):
1. Go to profit_loss_report.php
2. Select period (week/month/year)
3. View summary cards showing totals
4. Check chart visualization
5. Review order-by-order details

## File Changes

### New Files Created:
1. `api/expenses.php` - API for expense and pricing operations
2. `php/get_pricing_history.php` - Fetch pricing history
3. `pricing_management.php` - Admin pricing dashboard
4. `profit_loss_report.php` - Profit/loss analytics page
5. `sql/add_expenses_and_pricing.sql` - Database schema
6. `EXPENSE_SYSTEM_INSTALLATION.md` - This file

### Modified Files:
1. `php/auth.php` - Added expense permissions
2. `customer_orderlist.php` - Added expense input to order edit modal

## API Endpoints

### Expense Management
- `POST api/expenses.php?action=add_expense`
  - Body: `{order_id, expenses, notes}`
  - Auto-calculates profit
  - Logs activity

### Pricing Management
- `GET api/expenses.php?action=get_pricing`
  - Returns all package prices
  
- `POST api/expenses.php?action=update_pricing` (Admin only)
  - Body: `{package_type, price, cost, notes}`
  - Logs price history
  
### Reports
- `GET api/expenses.php?action=profit_loss_report` (Admin only)
  - Params: `period=week|month|year`, optional `start_date`, `end_date`
  - Returns comprehensive P&L data

## Permissions

### Admin:
- ✅ Add/edit expenses
- ✅ View profit/loss reports
- ✅ Manage pricing
- ✅ View pricing history
- ✅ Full analytics access

### Subadmin:
- ✅ Add/edit expenses to their orders
- ❌ Cannot view full profit/loss reports
- ❌ Cannot change pricing
- ✅ View current prices

### Agent:
- ❌ No expense or pricing access
- ✅ View orders only

## Usage Guide

### For Admins:

#### Managing Prices:
1. Navigate to "Pricing Management" (from footer or direct URL)
2. See current prices for all packages
3. Enter new price in the input field
4. Optionally enter cost per unit
5. Add a note explaining the change
6. Click "Update Price"
7. Confirm the change
8. View history at bottom of page

#### Viewing Profit/Loss:
1. Navigate to "Profit & Loss Report"
2. Select time period (or custom date range)
3. Click "Update Report"
4. Review:
   - Total revenue, expenses, profit
   - Profit margin percentage
   - Visual chart breakdown
   - Detailed order list
5. Check for orders without expenses (warning shown)

#### Adding Expenses to Orders:
1. Go to Orders list
2. Click "Edit" on a delivered order
3. Expense section appears automatically
4. Enter total expenses
5. Add expense breakdown notes
6. Save order
7. Profit calculated and shown

### For Subadmins:
- Can add expenses when marking orders as delivered
- Cannot access pricing management or full P&L reports
- Can see expenses on individual orders they manage

## Troubleshooting

### Problem: Expense section not showing in order edit
**Solution:** Ensure order status is set to "delivered" - expense fields only show for delivered orders

### Problem: "Permission denied" when accessing pricing
**Solution:** Only admins can access pricing_management.php - verify user role

### Problem: Profit not calculating
**Solution:** 
1. Check that revenue is populated (based on package type)
2. Ensure expenses is a valid number
3. Profit = Revenue - Expenses (auto-calculated)

### Problem: Orders not showing in P&L report
**Solution:** 
1. Only delivered orders show in report
2. Check date range includes order delivery dates
3. Verify orders have delivered_at timestamp

## Future Enhancements (Not Implemented Yet)

- [ ] Expense categories breakdown
- [ ] Recurring expenses (rent, utilities)
- [ ] Expense approval workflow
- [ ] Export P&L to Excel/PDF
- [ ] Comparative reports (month-over-month)
- [ ] Budget vs actual analysis
- [ ] Agent-specific profit tracking
- [ ] Cost of goods sold (COGS) tracking

## Support

For issues or questions:
- Check database connection in `php/db.php`
- Verify user permissions in `php/auth.php`
- Check browser console for JavaScript errors
- Review PHP error logs

## Rollback (If Needed)

To remove this update:

```sql
-- Remove columns from orders table
ALTER TABLE orders 
DROP COLUMN expenses,
DROP COLUMN profit,
DROP COLUMN expenses_notes,
DROP COLUMN expenses_added_by,
DROP COLUMN expenses_added_at;

-- Drop new tables
DROP TABLE IF EXISTS pricing_history;
DROP TABLE IF EXISTS package_pricing;
```

Then delete the new files listed above.
