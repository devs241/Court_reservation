-- Disable foreign key checks to avoid constraint errors during table drops
SET foreign_key_checks = 0;

-- -------------------------------------
-- Drop Tables if they Exist
-- -------------------------------------
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `courts`;
DROP TABLE IF EXISTS `users`;

-- Enable foreign key checks again
SET foreign_key_checks = 1;

-- -------------------------------------
-- Create Users Table
-- -------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    id INT(11) NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) DEFAULT NULL,
    last_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(15) DEFAULT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('admin', 'user') DEFAULT 'user',  
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample user data including admin
INSERT INTO `users` (`first_name`, `middle_name`, `last_name`, `contact_number`, `username`, `password`, `user_type`, `status`) 
VALUES 
('John', 'Nambatac', 'Casinillo', '09350336101', 'devs', '$2y$10$sCJb6f2hsl0E/EDEqlixn.Ol3ilsKMyVO1V9yThcQqR.', 'user', 'Approved'),
('Admin', '', 'User', '09123456789', 'admin', '$2y$10$wQH9UhCp/BVuoIkn2Ml.M.FMXvb2hDKEtUsZgctOL6C4lRiM.VWz2', 'admin', 'Approved');  

-- -------------------------------------
-- Create Courts Table
-- -------------------------------------
CREATE TABLE IF NOT EXISTS `courts` (
    id INT(11) NOT NULL AUTO_INCREMENT,
    court_name VARCHAR(100) NOT NULL UNIQUE,
    status ENUM('Available', 'Under Maintenance') DEFAULT 'Available',
    maintenance_day VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample courts
INSERT INTO `courts` (`court_name`, `status`, `maintenance_day`) VALUES
('Court 1', 'Available', 'Monday'),
('Court 2', 'Available', 'Tuesday'),
('Court 3', 'Under Maintenance', 'Wednesday'),
('Court 4', 'Available', 'Thursday');

-- -------------------------------------
-- Create Bookings Table
-- -------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    court_id INT(11) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    hours INT NOT NULL,
    people INT NOT NULL,
    payment_method ENUM('GCash', 'Onsite') NOT NULL,
    total_payment DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`court_id`) REFERENCES `courts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample booking data
INSERT INTO `bookings` (`user_id`, `court_id`, `fullname`, `contact_number`, `date`, `start_time`, `end_time`, `hours`, `people`, `payment_method`, `total_payment`, `status`) 
VALUES 
(1, 1, 'John Casinillo', '09350336101', '2025-03-15', '14:00:00', '16:00:00', 2, 4, 'GCash', 300.00, 'Pending');

-- -------------------------------------
-- Create Notifications Table
-- -------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('Read', 'Unread') DEFAULT 'Unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample notification data
INSERT INTO `notifications` (`user_id`, `message`, `status`) 
VALUES 
(1, 'Your reservation for Court 1 on 2025-03-15 has been received and is pending confirmation.', 'Unread');