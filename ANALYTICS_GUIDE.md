# Analytics & Accounting Dashboard - Complete Guide

## Overview
The Analytics Dashboard provides comprehensive financial reporting and business intelligence for the MagicBook order management system. It includes revenue tracking, sales analysis, state-wise performance, and agent analytics.

---

## Features

### 📊 Summary Statistics
- **Total Revenue**: Real-time revenue calculation across all orders
- **Total Orders**: Count of all orders in the selected period
- **Average Order Value**: Automatic calculation of average transaction size
- **Completed Orders**: Number of successfully delivered orders

### 📈 Visual Analytics
1. **Revenue Over Time Chart** (Line Chart)
   - Track revenue trends by day, week, or month
   - Interactive tooltips showing exact amounts
   - Customizable date ranges

2. **Revenue by Package Type** (Doughnut Chart)
   - Visual breakdown of sales by package (Starter, Bundle, Collection)
   - Percentage distribution
   - Color-coded segments

3. **Order Status Breakdown** (Bar Chart)
   - Count of orders by status (Pending, Confirmed, Processing, Shipped, Delivered, Cancelled)
   - Color-coded by status type
   - Real-time updates

### 🗺️ Geographic Analysis
**Top 10 States by Revenue**
- Ranked list of highest-performing states
- Shows order count and revenue per state
- Percentage of total revenue
- Sortable table format

### 👥 Agent Performance
- Individual agent statistics
- States covered by each agent
- Total orders assigned
- Successfully delivered count
- Revenue generated per agent
- Success rate percentage (delivered/total)

### 📋 Recent Transactions
- Last 20 orders with full details
- Customer information
- Package type and amount
- Order status with color-coded badges
- Date and time stamps

---

## Access & Navigation

### URL
```
http://localhost/magicbook/analytics.php
```

### Navigation Menu
Available from:
- **Customer Orders** page → Analytics link
- **Agent Management** page → Analytics link
- **Sales Notifications** page → Analytics link

---

## Date Filtering

### Quick Filters (One-Click)
- **Today**: Orders from current day only
- **This Week**: Last 7 days (default)
- **This Month**: Last 30 days
- **Last Month**: Previous calendar month
- **This Year**: January 1 to current date
- **Custom Range**: User-defined date range

### Custom Date Range
1. Select **Start Date** from date picker
2. Select **End Date** from date picker
3. Choose **Group By** option:
   - Daily: Day-by-day breakdown
   - Weekly: Week-by-week aggregation
   - Monthly: Month-by-month summary
4. Click **Apply Filter**

### Default Settings
- Start Date: 7 days ago
- End Date: Today
- Group By: Weekly

---

## Export Functionality

### Excel/CSV Export
**Button**: "Export to Excel" (green button with Excel icon)

**File Contents**:
1. **Summary Section**
   - Total Revenue
   - Total Orders
   - Average Order Value

2. **Revenue by Package**
   - Breakdown by package type
   - Individual totals

3. **Transaction Details**
   - All orders in selected date range
   - Columns: Order ID, Customer, Package, State, Amount, Status, Date
   - CSV format for Excel compatibility

**File Naming**:
```
accounting_report_YYYY-MM-DD_to_YYYY-MM-DD.csv
```

---

## Package Pricing

The system uses these fixed prices for calculations:

| Package | Price | Description |
|---------|-------|-------------|
| **Starter Set** | ₦18,000 | Basic package |
| **Learning Bundle** | ₦32,000 | Mid-tier package |
| **Mastery Collection** | ₦45,000 | Premium package |

---

## Technical Details

### API Endpoints Used

#### 1. Sales Report API
```
GET api/orders.php?action=sales_report&start_date=YYYY-MM-DD&end_date=YYYY-MM-DD&group_by=day|week|month
```

**Response**:
```json
{
  "success": true,
  "data": {
    "orders": [...],
    "summary": {
      "total_orders": 50,
      "total_revenue": 1500000,
      "average_order_value": 30000,
      "revenue_by_package": {
        "starter": 360000,
        "bundle": 640000,
        "collection": 500000
      },
      "top_states": [
        {
          "state": "Lagos",
          "order_count": 15,
          "revenue": 450000
        }
      ]
    }
  }
}
```

#### 2. Order Statistics API
```
GET api/orders.php?action=stats
```

**Response**:
```json
{
  "success": true,
  "data": {
    "total_orders": 50,
    "pending": 5,
    "confirmed": 10,
    "processing": 8,
    "shipped": 7,
    "delivered": 18,
    "cancelled": 2
  }
}
```

#### 3. Agents List API
```
GET api/agents.php?action=list
```

#### 4. Orders List API
```
GET api/orders.php?action=list&per_page=1000
```

### JavaScript Libraries

**Chart.js** (v3+)
- Used for all chart rendering
- CDN: `https://cdn.jsdelivr.net/npm/chart.js`
- Charts: Line, Doughnut, Bar

### Database Tables Used
- `orders` - All order records
- `delivery_agents` - Agent information
- `agent_states` - Agent-state relationships

---

## Key Metrics Explained

### Total Revenue
Sum of all order amounts based on package type, regardless of status. Includes:
- Pending orders
- Confirmed orders
- Processing orders
- Shipped orders
- Delivered orders

Excludes:
- Cancelled orders

### Average Order Value
```
Average Order Value = Total Revenue / Total Orders
```

### Success Rate (Agent Performance)
```
Success Rate = (Delivered Orders / Total Orders) × 100
```

### State Revenue
Revenue attributed to a state based on customer's delivery address, not agent location.

---

## Common Use Cases

### 1. Monthly Accounting
```
1. Select "Last Month" quick filter
2. Click "Export to Excel"
3. Use CSV file for accounting records
```

### 2. Agent Performance Review
```
1. Set date range for review period
2. Scroll to "Agent Performance" section
3. Sort by revenue or success rate
4. Identify top performers and areas for improvement
```

### 3. State Expansion Planning
```
1. Set date range to 3-6 months
2. Check "Top 10 States by Revenue"
3. Identify high-performing states
4. Use data to plan agent deployment
```

### 4. Weekly Sales Report
```
1. Select "This Week" (default)
2. Review summary stats cards
3. Check revenue trend chart
4. Export for management review
```

### 5. Package Performance Analysis
```
1. View "Revenue by Package Type" chart
2. Compare sales distribution
3. Adjust marketing focus accordingly
```

---

## Mobile Responsiveness

The analytics dashboard is fully responsive:

### Desktop (>768px)
- Multi-column grid layouts
- Full-width charts
- Side-by-side comparisons

### Tablet (768px)
- Adjusted grid columns
- Stacked layouts for readability

### Mobile (480px)
- Single-column layout
- Reduced chart heights
- Touch-friendly buttons
- Scrollable tables

---

## Performance Considerations

### Data Volume
- Default query: Last 7 days
- For large datasets (>1000 orders), consider:
  - Shorter date ranges
  - Weekly/monthly grouping instead of daily

### Chart Rendering
- Charts update automatically on filter changes
- Old chart instances are destroyed before creating new ones
- Prevents memory leaks

### API Response Time
- Orders endpoint: ~100-500ms (typical)
- Sales report endpoint: ~200-800ms (with calculations)
- Agent performance: Multiple API calls (can take 1-2 seconds)

---

## Troubleshooting

### Issue: "No data available"
**Causes**:
- No orders in selected date range
- Database connection error
- Incorrect date format

**Solution**:
1. Check date range selection
2. Verify orders exist in database
3. Check browser console for errors

### Issue: Charts not rendering
**Causes**:
- Chart.js library not loaded
- JavaScript error
- Invalid data format

**Solution**:
1. Check browser console for errors
2. Verify Chart.js CDN is accessible
3. Hard refresh browser (Ctrl+F5)

### Issue: Export not working
**Causes**:
- No data to export
- Browser blocking download
- JavaScript error

**Solution**:
1. Ensure data is loaded first
2. Check browser download settings
3. Try different browser

### Issue: Agent performance showing 0
**Causes**:
- No orders assigned to agents
- Agent assignment not configured
- Orders pending assignment

**Solution**:
1. Run agent assignment script
2. Check `agent_id` field in orders table
3. Verify agents are active in system

---

## Future Enhancements

### Planned Features
- 📊 Profit margin tracking
- 📧 Scheduled email reports
- 🎯 Goal setting and tracking
- 📱 WhatsApp/SMS report delivery
- 🔄 Auto-refresh dashboard
- 💹 Trend predictions
- 📅 Year-over-year comparisons
- 🎨 Custom report builder
- 🔐 Role-based access control
- 📤 PDF export option

### Advanced Analytics (Future)
- Customer lifetime value
- Churn rate analysis
- Geographic heat maps
- Seasonal trend analysis
- Package conversion funnels
- Agent efficiency scores

---

## Security Notes

### Current State
- No authentication required
- Open access to all data
- Client-side only (no sensitive data exposed)

### Production Recommendations
1. Add login system
2. Implement role-based access:
   - Admin: Full access
   - Manager: View all, limited edit
   - Agent: View own data only
3. Secure API endpoints with authentication
4. Log all data exports
5. Add HTTPS

---

## Support & Maintenance

### Regular Tasks
- [ ] Weekly: Review revenue trends
- [ ] Monthly: Export accounting records
- [ ] Quarterly: Analyze agent performance
- [ ] Yearly: Archive old data

### Data Integrity
- Revenue calculations are automatic
- No manual data entry required
- Based on package type from orders
- Timestamps from database

### Backup Recommendations
- Export monthly reports
- Keep CSV files for records
- Database backup includes all analytics data

---

## Contact & Updates

For issues or feature requests:
1. Check browser console for errors
2. Verify database connectivity
3. Ensure all dependencies loaded
4. Review this documentation

**Last Updated**: November 21, 2025
**Version**: 1.0
**Status**: Production Ready ✅
