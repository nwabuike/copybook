-- Create Users Table with Roles
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'subadmin', 'agent') NOT NULL DEFAULT 'subadmin',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Activity Logs Table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id VARCHAR(50) NULL,
    description TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
-- Password: admin123 (you should change this after first login)
INSERT INTO users (username, email, password, full_name, role, status) 
VALUES ('admin', 'admin@magicbook.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 'active');

-- Add user_id column to orders table
ALTER TABLE orders 
ADD COLUMN updated_by INT NULL AFTER delivered_at;

ALTER TABLE orders 
ADD FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL;

-- Add user_id column to delivery_agents table
ALTER TABLE delivery_agents 
ADD COLUMN updated_by INT NULL AFTER updated_at;

ALTER TABLE delivery_agents 
ADD FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL;

-- Add user_id column to stock_movements table (already has created_by)
-- Just ensure the column exists
ALTER TABLE stock_movements 
MODIFY COLUMN created_by VARCHAR(100) NOT NULL;

-- Create sessions table for session management (optional but recommended)
CREATE TABLE IF NOT EXISTS user_sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data: Create a subadmin user
-- Password: subadmin123
INSERT INTO users (username, email, password, full_name, role, status, created_by) 
VALUES ('subadmin', 'subadmin@magicbook.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sales Manager', 'subadmin', 'active', 1);

-- Sample data: Create an agent user
-- Password: agent123
INSERT INTO users (username, email, password, full_name, role, status, created_by) 
VALUES ('agent001', 'agent@magicbook.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Delivery Agent', 'agent', 'active', 1);

-- View all users
SELECT id, username, email, full_name, role, status, last_login, created_at FROM users;
