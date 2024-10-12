-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2024 at 10:18 AM
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
-- Database: `catwiki_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `article_thumbnail` blob NOT NULL,
  `article_title` varchar(255) NOT NULL,
  `article_content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`article_id`, `article_thumbnail`, `article_title`, `article_content`, `created_at`, `updated_at`, `category_id`) VALUES
(3, 0x2e2e2f6173736574732f696d616765732f61727469636c655f7468756d626e61696c732f696d6167655f363730383866626233333932625f44414c4cc2b74520323032342d31302d31312030392e34382e3330202d20412076697375616c6c792061707065616c696e6720616e6420696e666f726d61746976652077656273697465206c61796f757420666f6375736564206f6e20737472617920616e696d616c732e2054686520686f6d657061676520666561747572657320612068656164657220776974682061206c6f676f206f66206120706177207072696e7420616e642061206e617669676174692e77656270, 'Meme', 'Meme', '2024-10-10 20:37:34', '2024-10-10 20:37:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `breeds`
--

CREATE TABLE `breeds` (
  `breed_id` int(11) NOT NULL,
  `breed_name` varchar(50) NOT NULL,
  `breed_description` text NOT NULL,
  `average_lifespan` varchar(50) NOT NULL,
  `origin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breeds`
--

INSERT INTO `breeds` (`breed_id`, `breed_name`, `breed_description`, `average_lifespan`, `origin`) VALUES
(2, 'Siamese', 'The Siamese cat is one of the first distinctly recognised breeds of Asian cat. The Siamese Cat derived from the Wichianmat landrace. They are one of several varieties of cats native to Thailand, the original Siamese became one of the most popular breeds in Europe and North America in the 19th century.', '15', 'Pwetey'),
(5, 'British Shorthair', 'The British Shorthair is the pedigreed version of the traditional British domestic cat, with a distinctively stocky body, thick coat, and broad face. The most familiar colour variant is the \"British Blue\", with a solid grey-blue coat, pineapple eyes, and a medium-sized tail', '9', 'Great Britain'),
(9, 'pwetey', 'ASDJASJ', '544545', 'ASDASD');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_date_created` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_date_created`) VALUES
(3, 'pwetey', '2024-10-11');

-- --------------------------------------------------------

--
-- Table structure for table `cats`
--

CREATE TABLE `cats` (
  `cat_id` int(11) NOT NULL,
  `cat_profile` blob NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `breed_id` int(11) DEFAULT NULL,
  `cat_description` text DEFAULT NULL,
  `cat_image_url` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cat_image_url`)),
  `created_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cats`
--

INSERT INTO `cats` (`cat_id`, `cat_profile`, `cat_name`, `breed_id`, `cat_description`, `cat_image_url`, `created_by`) VALUES
(6, '', 'Julia', 5, 'asdasdasd', NULL, 'jm'),
(7, '', 'Juju', 2, 'asdasd\r\n', NULL, 'aj');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `profile` blob NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `role` varchar(50) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `profile`, `username`, `email`, `password`, `role`, `date_created`) VALUES
(27, '', 'jm', 'jm@gmail.com', '$2y$10$W/2l/rIAe3FnJCKHZ6EIa.UeS1q3mbw0fQ24BA8HLgrtcHcOQ9tuy', 'Admin', '2024-10-04'),
(30, '', 'aj11', 'aj@gmail.com', '$2y$10$Vf.QDT6rKzeXrsvwX8K/9uvt0ctaDqft2V8q0tvddOktV1d/JlXvu', 'Admin', '2024-10-07'),
(32, '', 'denver', 'denver.delamasa@gmail.com', '$2y$10$M39h0D3ISPaxQyCfsDH25.5G5Pt084p49rXUbVOjmxm.ub1i0JFhK', 'Admin', '2024-10-08'),
(33, '', 'aj12', 'aj12@gmail.com', '$2y$10$LH5NK9Pk075NVKBdRYAw6.YbNKByCDmPsTdLXar9eP23BnRTucm/m', 'Admin', '2024-10-08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `breeds`
--
ALTER TABLE `breeds`
  ADD PRIMARY KEY (`breed_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`category_name`);

--
-- Indexes for table `cats`
--
ALTER TABLE `cats`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `breed_id` (`breed_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `breed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cats`
--
ALTER TABLE `cats`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `cats`
--
ALTER TABLE `cats`
  ADD CONSTRAINT `cats_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breeds` (`breed_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
