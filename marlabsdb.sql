-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 10, 2016 at 11:26 PM
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
  `folder_name` varchar(50) NOT NULL,
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
(56, 'Assignment 1 .NET', 8),
(57, 'java_assignment 1', 10),
(58, 'Big Data Assignment ', 5);

-- --------------------------------------------------------

--
-- Table structure for table `Followups`
--

CREATE TABLE `Followups` (
  `followup_id` int(20) NOT NULL,
  `followup_details` varchar(255) NOT NULL,
  `followup_date` datetime NOT NULL,
  `followup_privacy` enum('0','1','2') NOT NULL DEFAULT '0',
  `post_following` int(15) NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Followups`
--

INSERT INTO `Followups` (`followup_id`, `followup_details`, `followup_date`, `followup_privacy`, `post_following`, `added_by`) VALUES
(1, 'This is my first followup. hhhhhh', '2016-03-10 09:54:03', '0', 16, 6),
(12, 'Chies', '2016-03-10 17:18:51', '0', 16, 3),
(13, 'Thx, very helpful!!', '2016-03-10 17:20:38', '1', 42, 3);

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
(41, 56),
(42, 36),
(43, 37),
(44, 2),
(45, 53),
(47, 57),
(48, 53),
(49, 32);

-- --------------------------------------------------------

--
-- Table structure for table `Posts`
--

CREATE TABLE `Posts` (
  `post_id` int(15) NOT NULL,
  `post_summary` varchar(100) NOT NULL,
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
(9, 'Only to the Trainer by KLD', 'How to traverse an array in jQuery?', '2016-03-07 03:51:59', '2', '1', '2', '2', 6),
(10, 'Hi I am Binoy', 'I am your trainer in Java', '2016-03-07 03:55:30', '0', '0', '1', '2', 5),
(11, 'This a post with out folder', 'This a post with out folder by min hu', '2016-03-07 04:05:35', '0', '0', '2', '2', 3),
(12, 'Assignment 2 Jiaming', 'This is my assignment 2 submission.\nThanks.\nJiaming', '2016-03-07 19:21:55', '0', '0', '1', '0', 1),
(13, 'Assignment 2 Jiaming', 'This is my assignment 2 submission.\nThanks.\nJiaming', '2016-03-07 19:21:58', '0', '0', '1', '0', 1),
(14, 'PHP Class John', 'I am your trainer', '2016-03-07 19:24:51', '0', '0', '1', '0', 2),
(15, 'JJJJJ', 'cdsc s\ndcsdc', '2016-03-07 19:27:13', '0', '0', '1', '0', 2),
(16, 'Minhu', 'newfolder33333333 3', '2016-03-07 19:40:09', '0', '0', '0', '2', 3),
(41, 'Assignment 1 Note', 'Kaiyuan Duan note .NET Assignment 1 submission.', '2016-03-07 20:42:25', '0', '0', '1', '3', 7),
(42, 'Java Note Min Hu', 'JavaScript is one of the 3 languages all web developers must learn:\n\n   1. HTML to define the content of web pages\n\n   2. CSS to specify the layout of web pages\n\n   3. JavaScript to program the behavior of web pages\n\nThis tutorial is about JavaScript, and', '2016-03-08 06:01:06', '0', '1', '1', '2', 3),
(43, 'This is a note', 'for - loops through a block of code a number of times\nfor/in - loops through the properties of an object\nwhile - loops through a block of code while a specified condition is true', '2016-03-08 17:31:34', '0', '0', '1', '2', 5),
(44, 'Code Debug', '.p {\n  font-family: Arial, sans-erif;\n  font-size: 14px;\n  margin-top: 80px;\n}', '2016-03-08 17:35:55', '1', '0', '0', '0', 1),
(45, 'Final project', 'Hi I have a question about our final project, do we have a template?', '2016-03-08 22:03:17', '2', '0', '0', '2', 3),
(46, 'This is the very first post in', 'My name is Aishwarya from India.', '2016-03-10 04:39:29', '0', '0', '0', '1', 9),
(47, 'Notes for assignment 1', 'Please submit your assignment to my email no later than this weekend.', '2016-03-10 04:47:29', '0', '0', '1', '1', 10),
(48, 'blur vs focusout — any real differences? [duplicat', 'Can you please answer this question?', '2016-03-10 16:28:18', '2', '1', '0', '2', 6),
(49, 'How to install Virtual Machine?', 'I have a question about how to install Virtual Machine?\nCould you please help me?', '2016-03-10 22:25:54', '0', '1', '0', '2', 6);

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
(8, 'Sebastian', 'Dries', 'sebastian@uchicago.edu', '1234567', '1', '3'),
(9, 'Aishwarya', 'Chopra', 'aishwarya@gmail.com', '123456', '0', '1'),
(10, 'Rahul', 'Bishnois', 'rahul123@yahoo.com', '1234567', '1', '1');

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
-- Indexes for table `Followups`
--
ALTER TABLE `Followups`
  ADD PRIMARY KEY (`followup_id`),
  ADD KEY `post_following` (`post_following`),
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
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `Followups`
--
ALTER TABLE `Followups`
  MODIFY `followup_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `Posts`
--
ALTER TABLE `Posts`
  MODIFY `post_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Folders`
--
ALTER TABLE `Folders`
  ADD CONSTRAINT `added_by_user_id` FOREIGN KEY (`added_by`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Followups`
--
ALTER TABLE `Followups`
  ADD CONSTRAINT `added_by_constraint` FOREIGN KEY (`added_by`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `post_following_constraint` FOREIGN KEY (`post_following`) REFERENCES `Posts` (`post_id`);

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
