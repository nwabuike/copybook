# Stock Management Implementation TODO

## Feature Requirements
Implement stock management system organized by delivery agents and states.

## Current Status
⚠️ **NOT IMPLEMENTED** - This feature is planned but not yet developed.

## Planned Features

### 1. Stock Inventory by State
- Track inventory for each state separately
- Each state should have stock counts for:
  - Starter Set (1 set)
  - Learning Bundle (2 sets)
  - Mastery Collection (3 sets)

### 2. Agent-Based Stock Management
- Assign stock to delivery agents based on their coverage states
- Track stock movement per agent
- Monitor agent inventory levels

### 3. Database Schema (Planned)
```sql
-- Stock Inventory Table
CREATE TABLE stock_inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    state VARCHAR(50) NOT NULL,
    package_type ENUM('starter', 'bundle', 'collection') NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY state_package (state, package_type)
);

-- Stock Movements/History Table
CREATE TABLE stock_movements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    state VARCHAR(50) NOT NULL,
    package_type ENUM('starter', 'bundle', 'collection') NOT NULL,
    quantity_change INT NOT NULL,
    movement_type ENUM('stock_in', 'sale', 'adjustment', 'transfer') NOT NULL,
    reference_id INT,
    agent_id INT,
    notes TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES delivery_agents(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Agent Stock Allocation Table
CREATE TABLE agent_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    agent_id INT NOT NULL,
    state VARCHAR(50) NOT NULL,
    package_type ENUM('starter', 'bundle', 'collection') NOT NULL,
    allocated_quantity INT NOT NULL DEFAULT 0,
    available_quantity INT NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES delivery_agents(id),
    UNIQUE KEY agent_state_package (agent_id, state, package_type)
);
```

### 4. Required Functionality

#### Stock Management Page (`stock_management.php`)
- View stock levels by state
- Add new stock to states
- Transfer stock between states
- Adjust stock (corrections, damaged goods, etc.)
- View stock movement history
- Generate low stock alerts

#### Agent Stock Assignment
- Allocate stock to delivery agents
- View agent's current inventory
- Track deliveries and update stock automatically
- Return stock from agent to central inventory

#### Integration with Order System
- Automatically reduce stock when order status changes to "delivered"
- Check stock availability before confirming orders
- Alert when stock is low in specific states
- Prevent order confirmation if insufficient stock

#### Reports & Analytics
- Stock levels by state
- Stock movement history
- Agent performance (deliveries vs stock allocated)
- Low stock alerts by state
- Forecast stock needs based on order trends

### 5. UI Components Needed
- Stock dashboard with state-wise breakdown
- Stock addition/adjustment forms
- Stock transfer interface
- Agent stock allocation interface
- Stock movement history table
- Low stock warning badges
- Real-time stock counters

### 6. API Endpoints Required
```
GET  /api/stock.php?action=list&state={state}
POST /api/stock.php?action=add
POST /api/stock.php?action=adjust
POST /api/stock.php?action=transfer
GET  /api/stock.php?action=history&state={state}
GET  /api/stock.php?action=agent_stock&agent_id={id}
POST /api/stock.php?action=allocate_agent
GET  /api/stock.php?action=low_stock_alerts
```

### 7. Implementation Priority
1. **High Priority:**
   - Basic stock tracking by state
   - Automatic stock reduction on delivery
   - View current stock levels
   
2. **Medium Priority:**
   - Agent stock allocation
   - Stock transfer between states
   - Movement history tracking
   
3. **Low Priority:**
   - Advanced forecasting
   - Detailed analytics
   - Automated reorder alerts

## Notes
- Stock management should respect user roles (admin full access, agents view only their stock)
- Consider adding barcode/SKU tracking for physical inventory management
- May need mobile-friendly interface for agents to update stock on-the-go
- Integration with existing delivery agent management system is essential

## Estimated Development Time
- Database schema setup: 2-3 hours
- Basic stock management UI: 8-10 hours
- Agent integration: 4-6 hours
- Order system integration: 3-4 hours
- Reports and analytics: 4-6 hours
- Testing and refinement: 4-6 hours

**Total: ~25-35 hours**

## Contact
For implementation questions or to start development, contact the development team.
