-- Complete Database Schema for Order Management System with Stock Tracking
-- Run this SQL file to create all necessary tables

-- 1. Update orders table with status and tracking fields
ALTER TABLE `orders` 
ADD COLUMN `status` ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending' AFTER `referral_code`,
ADD COLUMN `confirmed_at` DATETIME NULL AFTER `created_at`,
ADD COLUMN `delivered_at` DATETIME NULL AFTER `confirmed_at`,
ADD COLUMN `admin_notes` TEXT NULL AFTER `delivered_at`,
ADD COLUMN `agent_id` INT UNSIGNED NULL AFTER `admin_notes`,
ADD COLUMN `quantity` INT DEFAULT 1 AFTER `pack`,
ADD INDEX (`status`),
ADD INDEX (`agent_id`),
ADD INDEX (`created_at`);

-- 2. Create delivery_agents table
CREATE TABLE IF NOT EXISTS `delivery_agents` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(50) NOT NULL,
  `alt_phone` VARCHAR(50) NULL,
  `address` TEXT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create agent_states table (maps agents to states they cover)
CREATE TABLE IF NOT EXISTS `agent_states` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` INT UNSIGNED NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`agent_id`) REFERENCES `delivery_agents`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `agent_state_unique` (`agent_id`, `state`),
  INDEX (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create stock_inventory table (tracks stock by state)
CREATE TABLE IF NOT EXISTS `stock_inventory` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `state` VARCHAR(100) NOT NULL,
  `package_type` VARCHAR(100) NOT NULL COMMENT 'starter, bundle, collection',
  `quantity` INT NOT NULL DEFAULT 0,
  `agent_id` INT UNSIGNED NULL,
  `last_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` VARCHAR(100) NULL COMMENT 'admin username or agent name',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`agent_id`) REFERENCES `delivery_agents`(`id`) ON DELETE SET NULL,
  UNIQUE KEY `state_package_unique` (`state`, `package_type`),
  INDEX (`package_type`),
  INDEX (`state`),
  INDEX (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Create stock_movements table (audit trail for stock changes)
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `state` VARCHAR(100) NOT NULL,
  `package_type` VARCHAR(100) NOT NULL,
  `quantity_change` INT NOT NULL COMMENT 'Positive for additions, negative for sales',
  `movement_type` ENUM('restock', 'sale', 'return', 'adjustment', 'transfer') NOT NULL,
  `reference_id` INT UNSIGNED NULL COMMENT 'order_id if sale',
  `agent_id` INT UNSIGNED NULL,
  `notes` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`agent_id`) REFERENCES `delivery_agents`(`id`) ON DELETE SET NULL,
  INDEX (`state`),
  INDEX (`package_type`),
  INDEX (`movement_type`),
  INDEX (`created_at`),
  INDEX (`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Insert Nigerian states into stock_inventory (initialize with 0 stock)
INSERT INTO `stock_inventory` (`state`, `package_type`, `quantity`) VALUES
-- Starter package
('Abia', 'starter', 0), ('Adamawa', 'starter', 0), ('Akwa Ibom', 'starter', 0), 
('Anambra', 'starter', 0), ('Bauchi', 'starter', 0), ('Bayelsa', 'starter', 0),
('Benue', 'starter', 0), ('Borno', 'starter', 0), ('Cross River', 'starter', 0),
('Delta', 'starter', 0), ('Ebonyi', 'starter', 0), ('Edo', 'starter', 0),
('Ekiti', 'starter', 0), ('Enugu', 'starter', 0), ('FCT', 'starter', 0),
('Gombe', 'starter', 0), ('Imo', 'starter', 0), ('Jigawa', 'starter', 0),
('Kaduna', 'starter', 0), ('Kano', 'starter', 0), ('Katsina', 'starter', 0),
('Kebbi', 'starter', 0), ('Kogi', 'starter', 0), ('Kwara', 'starter', 0),
('Lagos', 'starter', 0), ('Nasarawa', 'starter', 0), ('Niger', 'starter', 0),
('Ogun', 'starter', 0), ('Ondo', 'starter', 0), ('Osun', 'starter', 0),
('Oyo', 'starter', 0), ('Plateau', 'starter', 0), ('Rivers', 'starter', 0),
('Sokoto', 'starter', 0), ('Taraba', 'starter', 0), ('Yobe', 'starter', 0),
('Zamfara', 'starter', 0),
-- Bundle package
('Abia', 'bundle', 0), ('Adamawa', 'bundle', 0), ('Akwa Ibom', 'bundle', 0),
('Anambra', 'bundle', 0), ('Bauchi', 'bundle', 0), ('Bayelsa', 'bundle', 0),
('Benue', 'bundle', 0), ('Borno', 'bundle', 0), ('Cross River', 'bundle', 0),
('Delta', 'bundle', 0), ('Ebonyi', 'bundle', 0), ('Edo', 'bundle', 0),
('Ekiti', 'bundle', 0), ('Enugu', 'bundle', 0), ('FCT', 'bundle', 0),
('Gombe', 'bundle', 0), ('Imo', 'bundle', 0), ('Jigawa', 'bundle', 0),
('Kaduna', 'bundle', 0), ('Kano', 'bundle', 0), ('Katsina', 'bundle', 0),
('Kebbi', 'bundle', 0), ('Kogi', 'bundle', 0), ('Kwara', 'bundle', 0),
('Lagos', 'bundle', 0), ('Nasarawa', 'bundle', 0), ('Niger', 'bundle', 0),
('Ogun', 'bundle', 0), ('Ondo', 'bundle', 0), ('Osun', 'bundle', 0),
('Oyo', 'bundle', 0), ('Plateau', 'bundle', 0), ('Rivers', 'bundle', 0),
('Sokoto', 'bundle', 0), ('Taraba', 'bundle', 0), ('Yobe', 'bundle', 0),
('Zamfara', 'bundle', 0),
-- Collection package
('Abia', 'collection', 0), ('Adamawa', 'collection', 0), ('Akwa Ibom', 'collection', 0),
('Anambra', 'collection', 0), ('Bauchi', 'collection', 0), ('Bayelsa', 'collection', 0),
('Benue', 'collection', 0), ('Borno', 'collection', 0), ('Cross River', 'collection', 0),
('Delta', 'collection', 0), ('Ebonyi', 'collection', 0), ('Edo', 'collection', 0),
('Ekiti', 'collection', 0), ('Enugu', 'collection', 0), ('FCT', 'collection', 0),
('Gombe', 'collection', 0), ('Imo', 'collection', 0), ('Jigawa', 'collection', 0),
('Kaduna', 'collection', 0), ('Kano', 'collection', 0), ('Katsina', 'collection', 0),
('Kebbi', 'collection', 0), ('Kogi', 'collection', 0), ('Kwara', 'collection', 0),
('Lagos', 'collection', 0), ('Nasarawa', 'collection', 0), ('Niger', 'collection', 0),
('Ogun', 'collection', 0), ('Ondo', 'collection', 0), ('Osun', 'collection', 0),
('Oyo', 'collection', 0), ('Plateau', 'collection', 0), ('Rivers', 'collection', 0),
('Sokoto', 'collection', 0), ('Taraba', 'collection', 0), ('Yobe', 'collection', 0),
('Zamfara', 'collection', 0)
ON DUPLICATE KEY UPDATE quantity = quantity;
