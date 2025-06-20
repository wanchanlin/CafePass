-- Drop existing tables if they exist
DROP TABLE IF EXISTS `event_registrations`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `wishlist`;
DROP TABLE IF EXISTS `user_visits`;
DROP TABLE IF EXISTS `cafes`;
DROP TABLE IF EXISTS `cafe_owners`;
DROP TABLE IF EXISTS `users`;

-- Create users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first` varchar(255) NOT NULL,
  `last` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `qr_code` varchar(255) DEFAULT NULL,
  `dateAdded` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create cafe_owners table
CREATE TABLE `cafe_owners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `cafe_owners_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create cafes table
CREATE TABLE `cafes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `opening_hours` time DEFAULT NULL,
  `closing_hours` time DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  CONSTRAINT `cafes_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `cafe_owners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create user_visits table
CREATE TABLE `user_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cafe_id` int(11) NOT NULL,
  `visit_date` datetime NOT NULL DEFAULT current_timestamp(),
  `points_earned` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `cafe_id` (`cafe_id`),
  CONSTRAINT `user_visits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_visits_ibfk_2` FOREIGN KEY (`cafe_id`) REFERENCES `cafes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create wishlist table
CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cafe_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_cafe` (`user_id`,`cafe_id`),
  KEY `cafe_id` (`cafe_id`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`cafe_id`) REFERENCES `cafes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create reviews table
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cafe_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` text DEFAULT NULL,
  `review_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_cafe` (`user_id`,`cafe_id`),
  KEY `cafe_id` (`cafe_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`cafe_id`) REFERENCES `cafes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create events table
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cafe_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cafe_id` (`cafe_id`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`cafe_id`) REFERENCES `cafes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create event_registrations table
CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_event` (`user_id`,`event_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample users
INSERT INTO `users` (`first`, `last`, `email`, `password`, `is_admin`, `dateAdded`) VALUES
('John', 'Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2024-01-01 00:00:00'),
('Jane', 'Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, '2024-01-02 00:00:00'),
('Admin', 'User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2024-01-03 00:00:00');

-- Insert sample cafe owners
INSERT INTO `cafe_owners` (`user_id`, `name`, `description`) VALUES
(1, 'Coffee Haven Owner', 'Owner of Coffee Haven'),
(2, 'Brew & Bean Owner', 'Owner of Brew & Bean'),
(3, 'Cafe Delight Owner', 'Owner of Cafe Delight');

-- Insert sample cafes
INSERT INTO `cafes` (`owner_id`, `name`, `address`, `description`, `phone`, `email`, `website`, `opening_hours`, `closing_hours`, `image_path`, `rating`) VALUES
(1, 'Coffee Haven', '123 Main St, City', 'A cozy coffee shop with a great atmosphere', '+1 555-0123', 'info@coffeehaven.com', 'www.coffeehaven.com', '08:00:00', '22:00:00', 'images/cafes/coffee-haven.jpg', 4.5),
(2, 'Brew & Bean', '456 Oak Ave, Town', 'Specialty coffee and fresh pastries', '+1 555-0124', 'info@brewandbean.com', 'www.brewandbean.com', '07:00:00', '21:00:00', 'images/cafes/brew-bean.jpg', 4.2),
(3, 'Cafe Delight', '789 Pine Rd, Village', 'Modern cafe with outdoor seating', '+1 555-0125', 'info@cafedelight.com', 'www.cafedelight.com', '09:00:00', '23:00:00', 'images/cafes/cafe-delight.jpg', 4.0);

-- Insert sample user visits
INSERT INTO `user_visits` (`user_id`, `cafe_id`, `points_earned`) VALUES
(1, 1, 10),
(1, 2, 10),
(2, 1, 10),
(2, 3, 10);

-- Insert sample reviews
INSERT INTO `reviews` (`user_id`, `cafe_id`, `rating`, `comment`) VALUES
(1, 1, 5, 'Great coffee and friendly staff!'),
(1, 2, 4, 'Nice atmosphere, good pastries.'),
(2, 1, 5, 'My favorite coffee shop in town!'),
(2, 3, 4, 'Good coffee, comfortable seating.');

-- Insert sample events
INSERT INTO `events` (`cafe_id`, `title`, `description`, `event_date`) VALUES
(1, 'Coffee Tasting', 'Join us for a special coffee tasting event', '2024-04-01 14:00:00'),
(2, 'Barista Workshop', 'Learn the art of coffee making', '2024-04-15 15:00:00'),
(3, 'Live Music Night', 'Enjoy live music with your coffee', '2024-04-20 19:00:00');

-- Insert sample event registrations
INSERT INTO `event_registrations` (`user_id`, `event_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 3); 