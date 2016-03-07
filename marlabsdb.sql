-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 07, 2016 at 08:55 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marlabsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `Folders`
--

CREATE TABLE `Folders` (
  `folder_id` int(11) NOT NULL,
  `folder_name` varchar(20) NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Folders`
--

INSERT INTO `Folders` (`folder_id`, `folder_name`, `added_by`) VALUES
(1, 'assignment 1', 2),
(2, 'assignment2', 2),
(26, 'exercise 1', 2),
(29, 'exercise 2', 2),
(30, 'note', 2),
(31, 'note 2', 2),
(32, 'Java Assignment 1', 5),
(33, 'Java assignment 2', 5),
(34, 'test1', 2),
(36, 'java_note', 5),
(37, 'note', 5),
(50, 'newfolder33', 5),
(53, 'newfolder34', 5),
(54, 'newfolder33', 2),
(55, 'newfolder4444', 2),
(56, 'Assignment 1 .NET', 8);

-- --------------------------------------------------------

--
-- Table structure for table `PostFolderMap`
--

CREATE TABLE `PostFolderMap` (
  `post_id` int(11) NOT NULL,
  `folder_id` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PostFolderMap`
--

INSERT INTO `PostFolderMap` (`post_id`, `folder_id`) VALUES
(5, 2),
(5, 26),
(5, 29),
(5, 30),
(7, 33),
(8, 33),
(9, 32),
(10, 32),
(12, 2),
(13, 2),
(16, 50),
(41, 56);

-- --------------------------------------------------------

--
-- Table structure for table `Posts`
--

CREATE TABLE `Posts` (
  `post_id` int(15) NOT NULL,
  `post_summary` varchar(30) NOT NULL,
  `post_details` varchar(255) NOT NULL,
  `post_date` datetime NOT NULL,
  `post_privacy` enum('0','1','2') NOT NULL DEFAULT '0',
  `post_to` enum('0','1') NOT NULL DEFAULT '0',
  `post_type` enum('0','1','2') NOT NULL DEFAULT '0',
  `class_type` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Posts`
--

INSERT INTO `Posts` (`post_id`, `post_summary`, `post_details`, `post_date`, `post_privacy`, `post_to`, `post_type`, `class_type`, `posted_by`) VALUES
(5, 'some title', 'some content', '2016-03-07 01:31:53', '2', '1', '1', '0', 1),
(6, 'Kai', 'I am Kaiyuan Duuan.', '2016-03-07 01:48:53', '0', '1', '0', '3', 7),
(7, 'csd', 'csd sdcsdcwev ewdewcsdc', '2016-03-07 03:48:52', '1', '0', '0', '2', 3),
(8, 'KLD', 'I am KLD', '2016-03-07 03:49:48', '1', '1', '1', '2', 6),
(9, 'Only to the Trainer by KLD', 'How to traverse an array in jQuery?', '2016-03-07 03:51:59', '2', '1', '0', '2', 6),
(10, 'Hi I am Binoy', 'I am your trainer in Java', '2016-03-07 03:55:30', '0', '0', '1', '2', 5),
(11, 'This a post with out folder', 'This a post with out folder by min hu', '2016-03-07 04:05:35', '0', '0', '0', '2', 3),
(12, 'Assignment 2 Jiaming', 'This is my assignment 2 submission.\nThanks.\nJiaming', '2016-03-07 19:21:55', '0', '0', '1', '0', 1),
(13, 'Assignment 2 Jiaming', 'This is my assignment 2 submission.\nThanks.\nJiaming', '2016-03-07 19:21:58', '0', '0', '1', '0', 1),
(14, 'PHP Class John', 'I am your trainer', '2016-03-07 19:24:51', '0', '0', '1', '0', 2),
(15, 'JJJJJ', 'cdsc s\ndcsdc', '2016-03-07 19:27:13', '0', '0', '1', '0', 2),
(16, 'Minhu', 'newfolder33333333 3', '2016-03-07 19:40:09', '0', '0', '0', '2', 3),
(41, 'Assignment 1 Note', 'Kaiyuan Duan note .NET Assignment 1 submission.', '2016-03-07 20:42:25', '0', '0', '1', '3', 7);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('0','1') NOT NULL DEFAULT '0',
  `class_type` enum('0','1','2','3') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `user_type`, `class_type`) VALUES
(1, 'Jiaming', 'Dong', 'jiamingd@uchicago.edu', '123456', '0', '0'),
(2, 'John', 'Emmanuel', 'johne@gmail.com', '1234567', '1', '0'),
(3, 'Min', 'Hu', 'minhu@gmail.com', '123456', '0', '2'),
(5, 'Binoy', 'Taylor', 'binoy@gmail.com', 'binoy123', '1', '2'),
(6, 'Lingduo', 'Kong', 'kkklllddd@uchicago.edu', 'kkklllddd', '0', '2'),
(7, 'Kaiyuan', 'Duan', 'kaiyuanduan@uchicago.edu', '123456', '0', '3'),
(8, 'Sebastian', 'Dries', 'sebastian@uchicago.edu', '1234567', '1', '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Folders`
--
ALTER TABLE `Folders`
  ADD PRIMARY KEY (`folder_id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `PostFolderMap`
--
ALTER TABLE `PostFolderMap`
  ADD KEY `post_id` (`post_id`),
  ADD KEY `folder_id` (`folder_id`);

--
-- Indexes for table `Posts`
--
ALTER TABLE `Posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `posted_by` (`posted_by`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Folders`
--
ALTER TABLE `Folders`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `Posts`
--
ALTER TABLE `Posts`
  MODIFY `post_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Folders`
--
ALTER TABLE `Folders`
  ADD CONSTRAINT `added_by_user_id` FOREIGN KEY (`added_by`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `PostFolderMap`
--
ALTER TABLE `PostFolderMap`
  ADD CONSTRAINT `postfoldermap_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `Posts` (`post_id`),
  ADD CONSTRAINT `postfoldermap_ibfk_2` FOREIGN KEY (`folder_id`) REFERENCES `Folders` (`folder_id`);

--
-- Constraints for table `Posts`
--
ALTER TABLE `Posts`
  ADD CONSTRAINT `posted_by_user_id` FOREIGN KEY (`posted_by`) REFERENCES `Users` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
