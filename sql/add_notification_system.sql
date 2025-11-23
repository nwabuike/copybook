-- Add notification system for automatic order alerts
-- Run this SQL to add notification preferences and logs

-- Create notification preferences table
-- Drop table if exists to ensure clean installation
DROP TABLE IF EXISTS notification_logs;
DROP TABLE IF EXISTS notification_preferences;

CREATE TABLE notification_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    notify_new_order TINYINT(1) DEFAULT 1,
    notify_status_change TINYINT(1) DEFAULT 1,
    email_notifications TINYINT(1) DEFAULT 1,
    whatsapp_notifications TINYINT(1) DEFAULT 0,
    notification_frequency ENUM('instant', 'hourly', 'daily') DEFAULT 'instant',
    quiet_hours_start TIME DEFAULT NULL,
    quiet_hours_end TIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_prefs (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create notification logs table
-- Note: We'll add foreign keys after ensuring proper data types match
CREATE TABLE notification_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_id INT DEFAULT NULL,
    notification_type ENUM('new_order', 'status_change', 'system') NOT NULL,
    notification_channel ENUM('email', 'whatsapp', 'system') NOT NULL,
    subject VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    recipient VARCHAR(255) NOT NULL,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    error_message TEXT DEFAULT NULL,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_order_id (order_id),
    INDEX idx_user_status (user_id, status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key constraints separately to handle potential type mismatches
-- If these fail, it means the orders or users table structure needs verification
ALTER TABLE notification_logs 
    ADD CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE notification_logs 
    ADD CONSTRAINT fk_notif_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL;

-- Insert default notification preferences for existing users
INSERT INTO notification_preferences (user_id, notify_new_order, notify_status_change, email_notifications, notification_frequency)
SELECT id, 1, 1, 1, 'instant'
FROM users
WHERE NOT EXISTS (
    SELECT 1 FROM notification_preferences WHERE notification_preferences.user_id = users.id
);

-- Add notification settings to the sales_notifications page
-- (This is just for reference, actual UI changes are in the PHP file)
