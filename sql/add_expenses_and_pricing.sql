-- Add expenses tracking and dynamic pricing

-- 1. Add expenses column to orders table
ALTER TABLE orders 
ADD COLUMN cost_price DECIMAL(10,2) DEFAULT 0.00 AFTER quantity COMMENT 'Cost price of the product',
ADD COLUMN expenses DECIMAL(10,2) DEFAULT 0.00 AFTER cost_price COMMENT 'Additional expenses (delivery, packaging, etc.)',
ADD COLUMN profit DECIMAL(10,2) DEFAULT NULL AFTER expenses COMMENT 'Profit = Revenue - Cost Price - Expenses',
ADD COLUMN expenses_notes TEXT AFTER profit,
ADD COLUMN expenses_added_by INT AFTER expenses_notes,
ADD COLUMN expenses_added_at TIMESTAMP NULL AFTER expenses_added_by;

-- 2. Create package pricing table for dynamic pricing
CREATE TABLE IF NOT EXISTS package_pricing (
    id INT PRIMARY KEY AUTO_INCREMENT,
    package_type ENUM('starter', 'bundle', 'collection') NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL,
    cost_per_unit DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Base cost for profit calculation',
    updated_by INT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- 3. Insert default pricing
-- Get the first admin user ID or use NULL if no admin exists
SET @admin_user_id = (SELECT id FROM users WHERE role = 'admin' LIMIT 1);

INSERT INTO package_pricing (package_type, price, cost_per_unit, updated_by) VALUES
('starter', 18000.00, 8000.00, COALESCE(@admin_user_id, (SELECT id FROM users LIMIT 1))),
('bundle', 32000.00, 15000.00, COALESCE(@admin_user_id, (SELECT id FROM users LIMIT 1))),
('collection', 45000.00, 22000.00, COALESCE(@admin_user_id, (SELECT id FROM users LIMIT 1)))
ON DUPLICATE KEY UPDATE 
    price = VALUES(price),
    cost_per_unit = VALUES(cost_per_unit);

-- 4. Create pricing history table for audit trail
CREATE TABLE IF NOT EXISTS pricing_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    package_type ENUM('starter', 'bundle', 'collection') NOT NULL,
    old_price DECIMAL(10,2) NOT NULL,
    new_price DECIMAL(10,2) NOT NULL,
    old_cost DECIMAL(10,2),
    new_cost DECIMAL(10,2),
    changed_by INT NOT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (changed_by) REFERENCES users(id)
);

-- 5. Add index for faster queries
CREATE INDEX idx_orders_expenses ON orders(expenses);
CREATE INDEX idx_orders_profit ON orders(profit);
CREATE INDEX idx_orders_delivered_date ON orders(delivered_at);
