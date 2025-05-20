-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 07:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cos221_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`user_id`) VALUES
(1),
(5),
(10),
(11),
(12);

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `store_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`store_id`, `user_id`) VALUES
(1, 2),
(1, 3),
(2, 4),
(3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `launch_date` date DEFAULT NULL,
  `product_link` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `title`, `thumbnail`, `launch_date`, `product_link`, `description`, `store_id`, `price`) VALUES
(1, 'Smartphone Model A', 'https://example.com/thumb1.jpg', '2025-01-15', 'https://example.com/product1', 'The latest model with a stunning display and fast processor.', 2, 34.67),
(2, 'Laptop Model B', 'https://example.com/thumb2.jpg', '2025-02-20', 'https://example.com/product2', 'High-performance laptop with a sleek design and powerful specs.', 3, 45.78),
(3, 'Wireless Headphones', 'https://example.com/thumb3.jpg', '2025-03-10', 'https://example.com/product3', 'Noise-cancelling headphones with superior sound quality.', 4, 56.89),
(4, 'Gaming Console Z', 'https://example.com/thumb4.jpg', '2025-04-01', 'https://example.com/product4', 'Next-gen gaming console with 4K resolution support.', 5, 45.78),
(5, 'Smartwatch X', 'https://example.com/thumb5.jpg', '2025-05-05', 'https://example.com/product5', 'Track your fitness and stay connected with this smartwatch.', 6, 18990.9),
(6, 'Bluetooth Speaker', 'https://example.com/thumb6.jpg', '2025-06-12', 'https://example.com/product6', 'Portable Bluetooth speaker with deep bass and clear audio.', 7, 101.67),
(7, 'Smart TV Ultra', 'https://example.com/thumb7.jpg', '2025-07-20', 'https://example.com/product7', 'Smart TV with 8K resolution and integrated streaming apps.', 8, 18990.9),
(8, 'Tablet Pro', 'https://example.com/thumb8.jpg', '2025-08-15', 'https://example.com/product8', 'Powerful tablet with a large screen and fast performance.', 9, 101.67),
(9, '4K Camera', 'https://example.com/thumb9.jpg', '2025-09-01', 'https://example.com/product9', 'Capture stunning 4K videos with ease using this camera.', 10, 18990.9),
(10, 'Electric Scooter', 'https://example.com/thumb10.jpg', '2025-10-10', 'https://example.com/product10', 'Eco-friendly electric scooter with long battery life.', 11, 56.89),
(11, 'Portable Charger', 'https://example.com/thumb11.jpg', '2025-11-05', 'https://example.com/product11', 'Compact portable charger for your devices on the go.', 12, 18990.9),
(12, 'VR Headset', 'https://example.com/thumb12.jpg', '2025-12-01', 'https://example.com/product12', 'Experience immersive VR gaming with this advanced headset.', 13, 16789.89),
(13, 'Digital Camera', 'https://example.com/thumb13.jpg', '2025-01-10', 'https://example.com/product13', 'High-quality camera for professional photography and videos.', 14, 45.78),
(14, 'Smart Home Hub', 'https://example.com/thumb14.jpg', '2025-02-01', 'https://example.com/product14', 'Control your smart home devices with this easy-to-use hub.', 15, 56.89),
(15, 'Electric Car Charger', 'https://example.com/thumb15.jpg', '2025-03-25', 'https://example.com/product15', 'Fast-charging station for electric vehicles at home.', 16, 1897),
(16, 'LED Projector', 'https://example.com/thumb16.jpg', '2025-04-05', 'https://example.com/product16', 'Project movies in 4K quality anywhere with this portable projector.', 17, 16789.89),
(17, 'Wireless Keyboard', 'https://example.com/thumb17.jpg', '2025-05-25', 'https://example.com/product17', 'Ergonomic wireless keyboard with long battery life.', 18, 16789.89),
(18, 'Gaming Mouse', 'https://example.com/thumb18.jpg', '2025-06-10', 'https://example.com/product18', 'Precise gaming mouse with customizable buttons and RGB lighting.', 19, 16789.89),
(19, 'Smart Thermostat', 'https://example.com/thumb19.jpg', '2025-07-15', 'https://example.com/product19', 'Smart thermostat that adjusts temperature based on your preferences.', 20, 56.89),
(20, 'Electric Bike', 'https://example.com/thumb20.jpg', '2025-08-20', 'https://example.com/product20', 'Eco-friendly electric bike with fast charging and long range.', 21, 101.67),
(21, 'Smartphone Model A', 'https://example.com/thumb1.jpg', '2025-01-15', 'https://example.com/product1', 'The latest model with a stunning display and fast processor.', 2, 101.67),
(22, 'Laptop Model B', 'https://example.com/thumb2.jpg', '2025-02-20', 'https://example.com/product2', 'High-performance laptop with a sleek design and powerful specs.', 3, 101.67),
(23, 'Wireless Headphones', 'https://example.com/thumb3.jpg', '2025-03-10', 'https://example.com/product3', 'Noise-cancelling headphones with superior sound quality.', 4, 18990.9),
(24, 'Gaming Console Z', 'https://example.com/thumb4.jpg', '2025-04-01', 'https://example.com/product4', 'Next-gen gaming console with 4K resolution support.', 5, 56.89),
(25, 'Smartwatch X', 'https://example.com/thumb5.jpg', '2025-05-05', 'https://example.com/product5', 'Track your fitness and stay connected with this smartwatch.', 6, 101.67),
(26, 'Bluetooth Speaker', 'https://example.com/thumb6.jpg', '2025-06-12', 'https://example.com/product6', 'Portable Bluetooth speaker with deep bass and clear audio.', 7, NULL),
(27, 'Smart TV Ultra', 'https://example.com/thumb7.jpg', '2025-07-20', 'https://example.com/product7', 'Smart TV with 8K resolution and integrated streaming apps.', 8, NULL),
(28, 'Tablet Pro', 'https://example.com/thumb8.jpg', '2025-08-15', 'https://example.com/product8', 'Powerful tablet with a large screen and fast performance.', 9, NULL),
(29, '4K Camera', 'https://example.com/thumb9.jpg', '2025-09-01', 'https://example.com/product9', 'Capture stunning 4K videos with ease using this camera.', 10, NULL),
(30, 'Electric Scooter', 'https://example.com/thumb10.jpg', '2025-10-10', 'https://example.com/product10', 'Eco-friendly electric scooter with long battery life.', 11, NULL),
(31, 'Portable Charger', 'https://example.com/thumb11.jpg', '2025-11-05', 'https://example.com/product11', 'Compact portable charger for your devices on the go.', 12, NULL),
(32, 'VR Headset', 'https://example.com/thumb12.jpg', '2025-12-01', 'https://example.com/product12', 'Experience immersive VR gaming with this advanced headset.', 13, NULL),
(33, 'Digital Camera', 'https://example.com/thumb13.jpg', '2025-01-10', 'https://example.com/product13', 'High-quality camera for professional photography and videos.', 14, NULL),
(34, 'Smart Home Hub', 'https://example.com/thumb14.jpg', '2025-02-01', 'https://example.com/product14', 'Control your smart home devices with this easy-to-use hub.', 15, NULL),
(35, 'Electric Car Charger', 'https://example.com/thumb15.jpg', '2025-03-25', 'https://example.com/product15', 'Fast-charging station for electric vehicles at home.', 16, NULL),
(36, 'LED Projector', 'https://example.com/thumb16.jpg', '2025-04-05', 'https://example.com/product16', 'Project movies in 4K quality anywhere with this portable projector.', 17, NULL),
(37, 'Wireless Keyboard', 'https://example.com/thumb17.jpg', '2025-05-25', 'https://example.com/product17', 'Ergonomic wireless keyboard with long battery life.', 18, NULL),
(38, 'Gaming Mouse', 'https://example.com/thumb18.jpg', '2025-06-10', 'https://example.com/product18', 'Precise gaming mouse with customizable buttons and RGB lighting.', 19, NULL),
(39, 'Smart Thermostat', 'https://example.com/thumb19.jpg', '2025-07-15', 'https://example.com/product19', 'Smart thermostat that adjusts temperature based on your preferences.', 20, NULL),
(40, 'Electric Bike', 'https://example.com/thumb20.jpg', '2025-08-20', 'https://example.com/product20', 'Eco-friendly electric bike with fast charging and long range.', 21, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `user_id`, `product_id`, `rating`, `date`, `comment`) VALUES
(1, 1, 1, 5, '2025-01-01', 'Excellent product, highly recommend!'),
(2, 2, 2, 4, '2025-01-05', 'Great laptop, but a bit heavy.'),
(3, 3, 3, 5, '2025-01-10', 'The sound quality is amazing!'),
(4, 4, 4, 3, '2025-01-12', 'Good console but lacks exclusive games.'),
(5, 5, 5, 4, '2025-01-15', 'Good smartwatch for fitness tracking.'),
(6, 6, 6, 5, '2025-01-20', 'Perfect for outdoor activities.'),
(7, 7, 7, 4, '2025-01-25', 'Great TV, but the setup was a bit tricky.'),
(8, 8, 8, 5, '2025-02-01', 'Amazing tablet with smooth performance.'),
(9, 9, 9, 4, '2025-02-05', 'Good camera, but battery life could be better.'),
(10, 10, 10, 5, '2025-02-10', 'Excellent scooter, easy to ride!'),
(11, 11, 11, 3, '2025-02-15', 'The charger is too slow, needs improvement.'),
(12, 12, 12, 4, '2025-02-20', 'The VR experience is great, but the headset is uncomfortable.'),
(13, 13, 13, 5, '2025-02-25', 'High-quality camera, perfect for professionals.'),
(14, 14, 14, 4, '2025-03-01', 'Great smart home hub, but a bit expensive.'),
(15, 15, 15, 5, '2025-03-05', 'Fast charging, works well for home use.'),
(16, 16, 16, 5, '2025-03-10', 'Fantastic projector for home theater.'),
(17, 17, 17, 4, '2025-03-15', 'The keyboard is comfortable, but the battery life is average.'),
(18, 18, 18, 5, '2025-03-20', 'Perfect mouse for gaming!'),
(19, 19, 19, 3, '2025-03-25', 'The thermostat is good, but setup could be easier.'),
(20, 20, 20, 4, '2025-03-30', 'Nice bike, but the range is not as expected.');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`id`, `name`, `type`, `url`, `owner_id`) VALUES
(1, 'TechHub', 'Online', 'https://techhub.com', 2),
(2, 'FashionMall', 'Physical', 'https://fashionmall.com', 3),
(3, 'GadgetWorld', 'Online', 'https://gadgetworld.com', 4),
(4, 'HomeEssentials', 'Physical', 'https://homeessentials.com', 5),
(5, 'AutoPartsPro', 'Online', 'https://autopartspro.com', 6),
(6, 'BeautyStore', 'Physical', 'https://beautystore.com', 7),
(7, 'SportsCenter', 'Online', 'https://sportscenter.com', 7);

-- --------------------------------------------------------

--
-- Table structure for table `store_owner`
--

CREATE TABLE `store_owner` (
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_owner`
--

INSERT INTO `store_owner` (`user_id`) VALUES
(1),
(1),
(2),
(3),
(4),
(5),
(6),
(7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `salt` char(32) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `salt`, `email`) VALUES
(1, 'John Doe', 'd41d8cd98f00b204e9800998ecf8427e', '5baa61e4c9b93f3f0682250b6cf8331b', 'john.doe@example.com'),
(2, 'Jane Smith', 'e99a18c428cb38d5f260853678922e03', 'e7cf44d16847da5999284a73c63c3c2b', 'jane.smith@example.com'),
(3, 'Alice Johnson', 'c4ca4238a0b923820dcc509a6f75849b', 'ab98c6fdb8c357d99ab67d8fcb1f9ea5', 'alice.johnson@example.com'),
(4, 'Bob Brown', '098f6bcd4621d373cade4e832627b4f6', 'b054c07e08a98b643fc11683b334fa6d', 'bob.brown@example.com'),
(5, 'Charlie Davis', 'c81e728d9d4c2f636f067f89cc14862c', '68a5790381b74efeeff0c9e3196aada1', 'charlie.davis@example.com'),
(6, 'David Evans', 'a87ff679a2f3e71d9181a67b7542122c', '7db1b1a9070de564de7758f7e9d858f1', 'david.evans@example.com'),
(7, 'Emma Foster', 'e4d909c290d0fb1ca068ffaddf22cbd0', 'ae3870f728fd8ffdbbe7d47d774d9d3a', 'emma.foster@example.com'),
(8, 'Frank Green', '1679091c5a880faf6fb5e6087eb1b2dc', 'd8d9f27850a7ac6b46cce10e035b7ecf', 'frank.green@example.com'),
(9, 'Grace Harris', '8f14e45fceea167a5a36dedd4bea2543', 'cb299c2f18c21df37e5f7589b255c1f5', 'grace.harris@example.com'),
(10, 'Henry Irvine', 'c9f0f895fb98ab9159f1d1c51d3bdb78', 'b685d36d4d8b8e1deaf51f3d5f08f29c', 'henry.irvine@example.com'),
(11, 'Isla Jones', '45c48cce2e2d7fbdea1afc51c7c6ad26', '758b39b98b72a8b257ee73e4bbfdc1b7', 'isla.jones@example.com'),
(12, 'Jack King', '6512bd43d9caa6e02c990b0a82652dca', 'b3b128f0155fd8ca8f13b2d90bc8ef7b', 'jack.king@example.com'),
(13, 'Kathy Lee', 'c20ad4d76fe97759aa27a0c99bff6710', '4b93a1912a177fe65ca7bc57ad6e5c9d', 'kathy.lee@example.com'),
(14, 'Liam Moore', 'e4eaa4d9c735e05a8da0e0dfe7271f92', '0339d990d0d6f0413d0f9b9d6fa6e6d9', 'liam.moore@example.com'),
(15, 'Megan Nelson', '1dcca23355272056f04fe8bf20edfce0', '092b96ebdcb6c11160257b5f8ed915f9', 'megan.nelson@example.com'),
(16, 'Noah Owens', '9bf31c7ff062936a96d3c8bd1f8f2ff3', 'ae0d3e4e156edc08393e8e4fd4be8553', 'noah.owens@example.com'),
(17, 'Olivia Perez', '7c6a180b36896a0a8c02787eeafb0e4c', '8e42ed7e6e96f8f543bc7fc1b57e7b12', 'olivia.perez@example.com'),
(18, 'Paul Quinn', '5f4dcc3b5aa765d61d8327deb882cf99', 'b0a04291d0577edb0a0b98ea3d69ed95', 'paul.quinn@example.com'),
(19, 'Quincy Roberts', '3c59dc048e885024e746f06b99a03f45', '413d6a59cdb9779b28e9a30f6fe6ed1c', 'quincy.roberts@example.com'),
(20, 'Rachel Smith', 'e99a18c428cb38d5f260853678922e03', 'e3e0e833cc49798a708dffdb3c5e32a6', 'rachel.smith@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `usersettings`
--

CREATE TABLE `usersettings` (
  `user_id` int(11) DEFAULT NULL,
  `theme` tinyint(4) DEFAULT NULL,
  `min_price` int(11) DEFAULT NULL,
  `max_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usersettings`
--

INSERT INTO `usersettings` (`user_id`, `theme`, `min_price`, `max_price`) VALUES
(1, 1, 50, 500),
(2, 0, 100, 1000),
(3, 1, 150, 1500),
(4, 0, 200, 2000),
(5, 1, 250, 2500),
(6, 0, 300, 3000),
(7, 1, 350, 3500),
(8, 0, 400, 4000),
(9, 1, 450, 4500),
(10, 0, 500, 5000),
(11, 1, 550, 5500),
(12, 0, 600, 6000),
(13, 1, 650, 6500),
(14, 0, 700, 7000),
(15, 1, 750, 7500),
(16, 0, 800, 8000),
(17, 1, 850, 8500),
(18, 0, 900, 9000),
(19, 1, 950, 9500),
(20, 0, 1000, 10000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD KEY `store_id` (`store_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD KEY `id` (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `store_owner`
--
ALTER TABLE `store_owner`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usersettings`
--
ALTER TABLE `usersettings`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `store_ibfk_1` FOREIGN KEY (`id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `store_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `store_owner` (`user_id`);

--
-- Constraints for table `store_owner`
--
ALTER TABLE `store_owner`
  ADD CONSTRAINT `store_owner_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `usersettings`
--
ALTER TABLE `usersettings`
  ADD CONSTRAINT `usersettings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

--adding role to user just to simplify managing 

ALTER TABLE `user` 
  ADD COLUMN `role` ENUM('customer', 'store_owner', 'admin') DEFAULT 'customer';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
