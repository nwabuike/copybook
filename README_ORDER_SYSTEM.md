# MagicBook Order Management System

## Overview
Complete order management system with stock tracking across 36 Nigerian states, delivery agent management, and sales reporting.

## System Components

### 1. Database Schema
Location: `sql/complete_schema.sql`

**Tables:**
- `orders` - Customer orders with status tracking, timestamps, agent assignment
- `delivery_agents` - Agent master records (name, contact, status)
- `agent_states` - Maps agents to states they cover (many-to-many)
- `stock_inventory` - Tracks stock by state and package type (111 records for 36 states × 3 packages)
- `stock_movements` - Audit trail for all stock changes (restock, sale, return, adjustment, transfer)

**Package Types:**
- `starter` - Starter Set (₦18,000)
- `bundle` - Learning Bundle (₦32,000)
- `collection` - Mastery Collection (₦45,000)

**Order Status Flow:**
pending → confirmed → processing → shipped → delivered (or cancelled)

**Automatic Agent Assignment:**
- When a customer places an order, the system automatically assigns a delivery agent based on the customer's state
- The system searches for an active agent covering that state
- If multiple agents cover the same state, the first active agent found is assigned
- Orders in states without agent coverage remain unassigned
- Admins can manually reassign agents via the order edit modal

### 2. API Endpoints

#### Orders API (`api/orders.php`)
- **GET** `?action=list` - Get paginated orders with search/filter
  - Parameters: page, per_page, search, status
- **GET** `?action=single&id={id}` - Get single order details
- **GET** `?action=stats` - Get order counts by status
- **GET** `?action=sales_report` - Date range sales reports
  - Parameters: start_date, end_date, group_by (day/week/month)
- **POST** `?action=update_status` - Update order status (auto stock deduction on delivery)
- **DELETE** - Delete order by ID

#### Agents API (`api/agents.php`)
- **GET** `?action=list` - Get all agents with states and order counts
- **GET** `?action=single&id={id}` - Get agent details with stock summary
- **GET** `?action=states&agent_id={id}` - Get states covered by agent
- **POST** - Create new agent with state assignments
- **PUT** - Update agent details and state assignments
- **DELETE** - Delete agent (prevents deletion if has orders)

#### Stock API (`api/stock.php`)
- **GET** `?action=by_state&state={state}` - Get stock for specific state
- **GET** `?action=summary` - Get overall stock summary and value
- **GET** `?action=movements` - Get stock movement history
  - Parameters: state, package, movement_type, start_date, end_date
- **GET** `?action=low_stock&threshold={n}` - Get items below threshold
- **POST** `?action=update` - Update stock quantity (records movement)
- **POST** - Add stock movement (restock/adjustment/transfer)

### 3. Admin Pages

#### Order Management (`customer_orderlist.php`)
- View all customer orders with pagination (10 per page)
- Real-time statistics (total, confirmed, shipped, delivered)
- Search by ID, customer name, email, or phone
- Filter by order status
- Edit order status and notes
- Delete orders
- Export to CSV with date range
- Automatic timestamp tracking (confirmed_at, delivered_at)

#### Agent Management (`agent_management.php`)
- View all delivery agents
- Statistics (total agents, active agents, states covered)
- Add new agents with state selection (37 states via checkboxes)
- Edit agent details and reassign states
- Delete agents (prevents deletion if has orders)
- View total orders per agent
- One agent can manage multiple states
- Stock tracked separately per state

### 4. Stock Management Features

**Automatic Stock Deduction:**
- When order status changes to "delivered", stock automatically reduces
- Records movement in `stock_movements` table
- Movement type: 'sale', reference_id: order_id

**Stock Initialization:**
- All 37 states × 3 package types = 111 inventory records
- Initial quantity: 0 for all
- Agent assignment via agent management page

**Audit Trail:**
- All stock changes logged in `stock_movements`
- Tracks: quantity_change, movement_type, agent_id, notes, created_by, timestamp

**Movement Types:**
- `restock` - Adding new stock
- `sale` - Stock sold (automatic on order delivery)
- `return` - Customer returns
- `adjustment` - Manual corrections
- `transfer` - Inter-state transfers

## Setup Instructions

### 1. Database Setup
```sql
-- Run the complete schema
mysql -u root copybook < sql/complete_schema.sql

-- Fix created_at default (if not already done)
ALTER TABLE orders MODIFY created_at DATETIME DEFAULT CURRENT_TIMESTAMP;
```

### 2. File Structure
```
magicbook/
├── api/
│   ├── orders.php      (Orders CRUD & reporting)
│   ├── agents.php      (Agent management)
│   └── stock.php       (Inventory management)
├── sql/
│   └── complete_schema.sql
├── php/
│   ├── db.php          (Database connection)
│   └── smtp_config.php (Email configuration)
├── customer_orderlist.php  (Orders admin page)
├── agent_management.php    (Agents admin page)
└── index.php               (Sales landing page)
```

### 3. Configuration

**Database Connection** (`php/db.php`):
```php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'copybook';
```

### 4. Nigerian States Covered
Abia, Adamawa, Akwa Ibom, Anambra, Bauchi, Bayelsa, Benue, Borno, Cross River, Delta, Ebonyi, Edo, Ekiti, Enugu, FCT (Abuja), Gombe, Imo, Jigawa, Kaduna, Kano, Katsina, Kebbi, Kogi, Kwara, Lagos, Nasarawa, Niger, Ogun, Ondo, Osun, Oyo, Plateau, Rivers, Sokoto, Taraba, Yobe, Zamfara

Total: 37 states/territories

## Usage Examples

### Creating a Delivery Agent
1. Navigate to `agent_management.php`
2. Click "Add New Agent"
3. Fill in agent details (name, phone required)
4. Select states from checkboxes (at least 1 required)
5. Set status (active/inactive)
6. Click "Save Agent"
7. Stock inventory automatically links to agent for selected states
8. All future orders from selected states will automatically assign to this agent

### How Automatic Agent Assignment Works
1. **When Customer Places Order:**
   - Customer fills out order form including their state
   - System searches for active agent covering that state
   - Agent automatically assigned to order
   - Order appears in agent's workload immediately

2. **Auto-Assign Existing Orders:**
   ```sql
   -- Run this SQL to assign agents to old orders
   mysql -u root copybook < sql/auto_assign_agents.sql
   ```

3. **Manual Reassignment:**
   - Open order in `customer_orderlist.php`
   - Click Edit button
   - Change agent_id (future feature: dropdown of agents)
   - Save changes

### Processing an Order
1. Navigate to `customer_orderlist.php`
2. Click Edit icon on order
3. Change status:
   - `pending` → `confirmed` (sets confirmed_at timestamp)
   - `confirmed` → `processing`
   - `processing` → `shipped`
   - `shipped` → `delivered` (sets delivered_at, reduces stock automatically)
4. Add admin notes if needed
5. Click "Save"
6. Stock automatically reduced from customer's state

### Checking Stock Levels
Use API: `GET api/stock.php?action=summary`

Response includes:
- Stock by package type
- Total inventory value (₦)
- Low stock alerts (< 5 items)
- Out of stock count

### Sales Reports
Use API: `GET api/orders.php?action=sales_report&start_date=2025-01-01&end_date=2025-01-31&group_by=day`

Returns:
- Orders grouped by day/week/month
- Revenue by package type
- Top 10 states by revenue
- Total orders and revenue summary

### Exporting Orders
1. Click "Export" button on orders page
2. Enter start date (YYYY-MM-DD)
3. Enter end date (YYYY-MM-DD)
4. CSV file downloads with all order details

## Key Features

✅ **Real-time Database Integration** - All pages fetch live data from MySQL
✅ **Pagination** - Orders list with configurable per-page (default 10)
✅ **Search & Filter** - Find orders by ID, name, email, phone, or status
✅ **Automatic Stock Tracking** - Deducts on delivery, prevents negative stock
✅ **Timestamp Tracking** - created_at, confirmed_at, delivered_at
✅ **Agent-State Mapping** - One agent, multiple states, separate stock per state
✅ **Audit Trail** - All stock movements logged with who/when/why
✅ **Status Badge UI** - Color-coded order statuses
✅ **CSV Export** - Date range filtered export
✅ **Responsive Design** - Works on mobile, tablet, desktop

## API Response Format

All APIs return JSON:
```json
{
  "success": true/false,
  "message": "Optional message",
  "data": { ... },
  "pagination": { 
    "total": 100,
    "page": 1,
    "per_page": 10,
    "total_pages": 10
  }
}
```

## Security Considerations

🔒 **Input Validation** - All user inputs escaped with `real_escape_string()`
🔒 **SQL Injection Prevention** - Parameterized queries where possible
🔒 **CORS Headers** - Configured for localhost (update for production)
🔒 **Delete Protection** - Prevents agent deletion if has orders
🔒 **Stock Protection** - WHERE clause prevents negative inventory

## Browser Notifications System

### Sales Rep/Customer Care Alerts (`sales_notifications.php`)

**Real-time notifications for:**
- ⏰ Pending orders requiring follow-up
- 📦 Low stock alerts (configurable threshold)
- 🚨 Out of stock items
- 📞 Follow-up reminders for confirmed orders

**Features:**
- **Browser Notifications**: Native push notifications on desktop and mobile
- **Customizable Thresholds**: Set custom time limits for each alert type
- **Auto-Check Interval**: Automatic monitoring every 1-60 minutes
- **Visual Alerts**: On-page alert list with color-coded urgency
- **Sound Alerts**: Audio notification for urgent issues
- **Persistent Settings**: Saves preferences in localStorage
- **Service Worker**: Background notification support

**How to Use:**
1. Navigate to `sales_notifications.php`
2. Click "Enable Notifications" (browser will request permission)
3. Configure alert thresholds:
   - Pending Orders: Alert after X minutes (default: 30)
   - Low Stock: Alert when below X units (default: 5)
   - Follow-up: Remind after X minutes (default: 60)
   - Check Interval: Auto-check every X minutes (default: 5)
4. Click "Save Settings"
5. Notifications will automatically check and alert

**API Endpoint** (`api/notifications.php`):
- `GET ?action=check_all` - Check all alerts at once
- `GET ?action=pending_orders&threshold=30` - Check pending orders
- `GET ?action=low_stock&threshold=5` - Check stock levels
- `GET ?action=follow_ups&threshold=60` - Check follow-up reminders

**Cron Job Setup** (Optional):
```bash
# Check alerts every 5 minutes
*/5 * * * * curl http://your-domain.com/magicbook/api/notifications.php?action=check_all
```

## Future Enhancements

- 📊 Sales dashboard with charts (Chart.js)
- 📱 Mobile app for agents
- 📧 Order confirmation emails to customers
- 🔐 Admin login/authentication
- 📦 Batch order processing
- 🚚 Shipment tracking integration
- 💰 Revenue analytics by agent/state/period
- 📝 Printable invoices/receipts
- 🔍 Advanced reporting (pivot tables)
- 📲 SMS/WhatsApp notifications integration

## Troubleshooting

**Orders not loading:**
- Check browser console for errors
- Verify API endpoint: `http://localhost/magicbook/api/orders.php?action=list`
- Check database connection in `php/db.php`

**Stock not deducting:**
- Ensure status changes to exactly "delivered"
- Check stock_inventory has records for that state
- Verify quantity > 0 before order
- Check stock_movements table for sale record

**Agent creation fails:**
- At least 1 state must be selected
- Name and phone are required fields
- Check for duplicate phone numbers
- Verify states match exact spelling

## Support
For issues or questions, contact: Emerald Tech Hub

---

**Version:** 1.0.0  
**Last Updated:** November 2025  
**Database:** MySQL 8.4.3  
**PHP Version:** 8.3.26
