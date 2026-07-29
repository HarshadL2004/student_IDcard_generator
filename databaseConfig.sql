-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 10, 2022 at 12:58 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ca3`
--

-- --------------------------------------------------------

--
-- Table structure for table `studentsDetails`
--

CREATE TABLE `studentsDetails` (
  `id` int(11) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `regNo` varchar(8) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `courseStart` date NOT NULL,
  `courseEnd` date NOT NULL,
  `image` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `studentsDetails`
--

INSERT INTO `studentsDetails` (`id`, `firstName`, `lastName`, `regNo`, `email`, `mobile`, `dob`, `courseStart`, `courseEnd`, `image`, `address`) VALUES
(18, 'Nitesh', 'khatri', '12102335', 'niteshkhatri545@gmail.com', '9873848154', '2000-04-22', '2022-08-28', '2023-05-31', '', 'New delhi'),
(19, 'Deepak', 'Kumar', '12102231', 'deepakkumar6154@gmail.com', '8789700832', '1999-08-18', '2022-12-01', '2023-02-01', 'userDp.png', 'Patna'),
(20, 'Abhishek ', 'Ranjan', '12102243', 'abhishekranjan45@gmail.com', '7542332109', '1999-08-23', '2023-02-02', '2024-02-02', 'userDp.png', 'Kankarbagh, Patna');

-- --------------------------------------------------------

--
-- Table structure for table `userTable`
--

CREATE TABLE `userTable` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(16) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userTable`
--

INSERT INTO `userTable` (`id`, `username`, `password`, `created`) VALUES
(1, 'Harsh', 'test123', '2022-10-07 17:21:03'),
(2, 'venkata', 'test123', '2022-10-07 17:21:56');

-- --------------------------------------------------------

--
-- New Tables
--

CREATE TABLE `Department` (
  `DeptID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `DeptName` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Student` (
  `s_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `s_firstName` VARCHAR(20) NOT NULL,
  `s_lastName` VARCHAR(20) NOT NULL,
  `s_regNo` VARCHAR(8) NOT NULL UNIQUE,
  `s_email` VARCHAR(50) NOT NULL,
  `s_mobile` VARCHAR(10) NOT NULL,
  `s_dob` DATE NOT NULL,
  `s_courseStart` DATE NOT NULL,
  `s_courseEnd` DATE NOT NULL,
  `s_image` VARCHAR(100) NOT NULL,
  `s_address` VARCHAR(100) NOT NULL,
  `s_DeptID` INT(11),
  FOREIGN KEY (`s_DeptID`) REFERENCES `Department`(`DeptID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Staff` (
  `st_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `st_firstName` VARCHAR(20) NOT NULL,
  `st_lastName` VARCHAR(20) NOT NULL,
  `st_regNo` VARCHAR(8) NOT NULL UNIQUE,
  `st_email` VARCHAR(50) NOT NULL,
  `st_mobile` VARCHAR(10) NOT NULL,
  `st_dob` DATE NOT NULL,
  `st_joiningDate` DATE NOT NULL,
  `st_image` VARCHAR(100) NOT NULL,
  `st_address` VARCHAR(100) NOT NULL,
  `st_DeptID` INT(11),
  FOREIGN KEY (`st_DeptID`) REFERENCES `Department`(`DeptID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Teacher` (
  `t_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `t_firstName` VARCHAR(20) NOT NULL,
  `t_lastName` VARCHAR(20) NOT NULL,
  `t_regNo` VARCHAR(8) NOT NULL UNIQUE,
  `t_email` VARCHAR(50) NOT NULL,
  `t_mobile` VARCHAR(10) NOT NULL,
  `t_dob` DATE NOT NULL,
  `t_joiningDate` DATE NOT NULL,
  `t_image` VARCHAR(100) NOT NULL,
  `t_address` VARCHAR(100) NOT NULL,
  `t_DeptID` INT(11),
  FOREIGN KEY (`t_DeptID`) REFERENCES `Department`(`DeptID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

ALTER TABLE `studentsDetails`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `userTable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `studentsDetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

ALTER TABLE `userTable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
