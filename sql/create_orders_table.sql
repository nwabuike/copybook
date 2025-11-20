-- SQL: Create `orders` table for copybook site
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `altphone` VARCHAR(50) DEFAULT NULL,
  `address` TEXT,
  `state` VARCHAR(100) DEFAULT NULL,
  `pack` VARCHAR(100) DEFAULT NULL,
  `referral_code` VARCHAR(100) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`referral_code`),
  INDEX (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
