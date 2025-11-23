-- Add cost_price column to orders table for existing installations
-- This is a migration script for systems that already ran add_expenses_and_pricing.sql

-- Add cost_price column (ignore error if it already exists)
ALTER TABLE orders 
ADD COLUMN cost_price DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Cost price of the product' AFTER quantity;

-- Add index for cost_price for better query performance (ignore error if it already exists)
CREATE INDEX idx_orders_cost_price ON orders(cost_price);
