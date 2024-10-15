-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2024 at 10:34 AM
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
(11, 0x2e2e2f6173736574732f696d616765732f61727469636c655f7468756d626e61696c732f696d6167655f363730613438306365656331395f53637265656e73686f7420323032342d30362d3137203132353530342e706e67, 'asdasd', 'asdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasdaasdasda', '2024-10-12 09:57:32', '2024-10-14 19:41:38', 4),
(14, '', 'Article 1', 'Article 1', '2024-10-15 01:41:38', '2024-10-15 01:41:38', 4),
(15, '', 'Article 2', 'Article 2', '2024-10-15 01:54:45', '2024-10-15 01:54:45', 4);

-- --------------------------------------------------------

--
-- Table structure for table `breeds`
--

CREATE TABLE `breeds` (
  `breed_id` int(11) NOT NULL,
  `breed_name` varchar(50) NOT NULL,
  `breed_description` text NOT NULL,
  `average_lifespan` varchar(50) NOT NULL,
  `origin` varchar(50) NOT NULL,
  `species_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breeds`
--

INSERT INTO `breeds` (`breed_id`, `breed_name`, `breed_description`, `average_lifespan`, `origin`, `species_id`) VALUES
(2, 'Siamese', 'The Siamese cat is one of the first distinctly recognised breeds of Asian cat. The Siamese Cat derived from the Wichianmat landrace. They are one of several varieties of cats native to Thailand, the original Siamese became one of the most popular breeds in Europe and North America in the 19th century.', '15', 'Pwetey', NULL),
(5, 'British Shorthair', 'The British Shorthair is the pedigreed version of the traditional British domestic cat, with a distinctively stocky body, thick coat, and broad face. The most familiar colour variant is the \"British Blue\", with a solid grey-blue coat, pineapple eyes, and a medium-sized tail', '1', 'Great Britain', NULL),
(9, 'pwetey', 'ASDJASJ', '1213', 'ASDASD', NULL);

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
(3, 'Dogs', '2024-10-11'),
(4, 'Cats', '2024-10-15');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `pet_id` int(11) NOT NULL,
  `pet_profile` blob DEFAULT NULL,
  `pet_name` varchar(100) NOT NULL,
  `breed_id` int(11) DEFAULT NULL,
  `pet_description` text DEFAULT NULL,
  `pet_image_url` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pet_image_url`)),
  `species_id` int(11) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`pet_id`, `pet_profile`, `pet_name`, `breed_id`, `pet_description`, `pet_image_url`, `species_id`, `created_by`) VALUES
(8, NULL, 'sadas', 5, 'asdasd', NULL, 1, 'aj');

-- --------------------------------------------------------

--
-- Table structure for table `species`
--

CREATE TABLE `species` (
  `species_id` int(11) NOT NULL,
  `species_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `species`
--

INSERT INTO `species` (`species_id`, `species_name`) VALUES
(1, 'Cat'),
(2, 'Dog');

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
  `date_created` date NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_online` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `profile`, `username`, `email`, `password`, `role`, `date_created`, `last_active`, `is_online`) VALUES
(30, '', '123asdasd', 'jm18@gmail.com', '$2y$10$4hSLSMPZAJPylOdWQLvdBei8oGchK3IYc78eiV3VKy8RSCzbGTfRG', 'Admin', '2024-10-07', '2024-10-13 10:22:15', 0),
(33, '', 'aj12', 'aj12@gmail.com', '$2y$10$X210XOzqBbJVxsf57./L..o/I7BEmWOXhAiYhNzguBYiw.VjILRRq', 'Admin', '2024-10-08', '2024-10-13 10:22:15', 1),
(58, '', 'asdasdas', 'test@gmail.com', '$2y$10$MCQbTMUPdHj8lkPaWXSjCud94BzBGwDIm5vXCjZkYcB7kfOcjIMdO', 'Admin', '2024-10-14', '2024-10-14 08:31:44', 0),
(61, 0x2e2e2f6173736574732f696d616765732f75736572735f70726f66696c652f70726f66696c655f363730646362316131336662385f5369616d6573655f6361745f31313130783734302e6a7067, 'aj', 'aj@gmail.com', '$2y$10$NFugno7kyczRr6H8vJkk6ucP5fRxk37I4RC4YBymcoUPjLWdqS9I.', 'Admin', '2024-10-15', '2024-10-15 00:22:32', 1);

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
  ADD PRIMARY KEY (`breed_id`),
  ADD KEY `species_id` (`species_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`category_name`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`pet_id`),
  ADD KEY `breed_id` (`breed_id`),
  ADD KEY `species` (`species_id`);

--
-- Indexes for table `species`
--
ALTER TABLE `species`
  ADD PRIMARY KEY (`species_id`);

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
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `breed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `pet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `species`
--
ALTER TABLE `species`
  MODIFY `species_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `breeds`
--
ALTER TABLE `breeds`
  ADD CONSTRAINT `breeds_ibfk_1` FOREIGN KEY (`species_id`) REFERENCES `species` (`species_id`);

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breeds` (`breed_id`),
  ADD CONSTRAINT `pets_ibfk_2` FOREIGN KEY (`species_id`) REFERENCES `species` (`species_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
