SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- CREATE DATABASE IF NOT EXISTS `shopping_portal`
--   DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `shopping_portal`;

START TRANSACTION;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
  UNIQUE KEY `uniq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  UNIQUE KEY `uniq_cat_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `image` VARCHAR(255) NULL,
  `description` TEXT NULL,
  CONSTRAINT `fk_prod_cat` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `uniq_cat_name_in_products` (`category_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_oi_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`name`,`email`,`password`,`role`) VALUES
('Admin','admin@example.com', MD5('admin123'), 'admin')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `role`=VALUES(`role`);

INSERT INTO `users` (`name`,`email`,`password`,`role`) VALUES
('Test User','user@example.com', MD5('user123'), 'user')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `categories` (`id`,`name`,`description`) VALUES
(1,'Electronics','Premium gadgets'),
(2,'Fashion','Modern apparel'),
(3,'Books','Readers'' corner')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `description`=VALUES(`description`);

INSERT INTO `products` (`category_id`,`name`,`price`,`image`,`description`) VALUES
(1,'Wireless Headphones',2999.00,'placeholder.png','Crisp sound 🎧'),
(1,'Smartphone',15999.00,'placeholder.png','Fast and sleek 📱'),
(1,'Bluetooth Speaker',2499.00,'placeholder.png','Party booster 🔊'),
(1,'Smartwatch',4999.00,'placeholder.png','Track your fitness ⌚'),
(2,'T-Shirt',499.00,'placeholder.png','Casual wear 👕'),
(2,'Sneakers',2499.00,'placeholder.png','Comfortable shoes 👟'),
(2,'Jacket',3499.00,'placeholder.png','Stay warm 🧥'),
(2,'Jeans',1999.00,'placeholder.png','Trendy denim 👖'),
(3,'Novel',399.00,'placeholder.png','Bestseller 📖'),
(3,'Comics',199.00,'placeholder.png','Fun stories 🦸'),
(3,'Science Book',699.00,'placeholder.png','Learn more 🔬'),
(3,'Cookbook',599.00,'placeholder.png','Tasty recipes 🍳')
ON DUPLICATE KEY UPDATE `price`=VALUES(`price`), `image`=VALUES(`image`), `description`=VALUES(`description`), `category_id`=VALUES(`category_id`);

-- Sample orders for user@example.com
SET @uid := (SELECT `id` FROM `users` WHERE `email`='user@example.com' LIMIT 1);
INSERT INTO `orders` (`user_id`,`total`) VALUES (@uid, 2999.00);
SET @oid1 := LAST_INSERT_ID();
SET @pid1 := (SELECT `id` FROM `products` WHERE `name`='Wireless Headphones' LIMIT 1);
INSERT INTO `order_items` (`order_id`,`product_id`,`quantity`) VALUES (@oid1, @pid1, 1);

INSERT INTO `orders` (`user_id`,`total`) VALUES (@uid, 4097.00);
SET @oid2 := LAST_INSERT_ID();
SET @pid2 := (SELECT `id` FROM `products` WHERE `name`='Jeans' LIMIT 1);
SET @pid3 := (SELECT `id` FROM `products` WHERE `name`='Novel' LIMIT 1);
INSERT INTO `order_items` (`order_id`,`product_id`,`quantity`) VALUES 
(@oid2, @pid2, 2),
(@oid2, @pid3, 1);

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
