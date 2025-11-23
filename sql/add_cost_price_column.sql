-- Add cost_price column to orders table for existing installations
-- This is a migration script for systems that already ran add_expenses_and_pricing.sql

-- Check if cost_price column doesn't exist and add it
ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS cost_price DECIMAL(10,2) DEFAULT 0.00 AFTER quantity COMMENT 'Cost price of the product';

-- Add index for cost_price for better query performance
CREATE INDEX IF NOT EXISTS idx_orders_cost_price ON orders(cost_price);
