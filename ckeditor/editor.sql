-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 03:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `editr`
--

-- --------------------------------------------------------

--
-- Table structure for table `editor`
--

CREATE TABLE `editor` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `editor`
--

INSERT INTO `editor` (`id`, `content`) VALUES
(1, '<p><strong>This is some sample content.</strong></p>'),
(2, '<p><strong>Build your custom editor in</strong><br><strong>5 minutes (or less!)</strong></p><p><strong>With CKEditor’s interactive builder, select:</strong></p><p>The features you need</p><p>Your preferred framework (React, Angular, Vue, or Vanilla JS)</p><p>Your preferred distribution method</p>'),
(3, '<p><strong>Build your custom editor in</strong><br><strong>5 minutes (or less!)</strong></p><p><strong>With CKEditor’s interactive builder, select:</strong></p><p>The features you need</p><p>Your preferred framework (React, Angular, Vue, or Vanilla JS)</p><p>Your preferred distribution method</p>'),
(4, '<p>This is some sample content.</p>'),
(5, '<p><strong>This is some sample content.</strong></p>'),
(6, '<p><span style=\"background-color:hsl(30, 75%, 60%);\">This is some sample content.</span></p>'),
(7, '<p><strong>This is some sample content.</strong></p>');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `editor`
--
ALTER TABLE `editor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `editor`
--
ALTER TABLE `editor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
