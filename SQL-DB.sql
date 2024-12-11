-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 08:39 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gp_karde`
--

-- --------------------------------------------------------

--
-- Table structure for table `maindata`
--

CREATE TABLE `maindata` (
  `userId` varchar(255) NOT NULL,
  `name` varchar(255)  NOT NULL,
  `section` varchar(20)  NOT NULL,
  `houseTax` int DEFAULT '0',
  `electricityTax` int DEFAULT '0',
  `waterTax` int DEFAULT '0',
  `BusinessTax` int DEFAULT '0'
);

--
-- Dumping data for table `maindata`
--

INSERT INTO `maindata` (`userId`, `name`, `section`, `houseTax`, `electricityTax`, `waterTax`, `BusinessTax`) VALUES
('1', 'Nilima Nilkanth Nagvekar', 'A', 29, 0, 0, 0),
('1/A', 'Nitin Yashwant Mane', 'F', 234, 0, 1100, 0),
('2003', 'Narendra Vasudev Naravanekar etc. - 2', 'A', 23, 0, 0, 0),
('9 Gotha', 'Sharad Narayanrao Jagtap', 'H', 11719, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `maindata_structure`
--

CREATE TABLE `maindata_structure` (
  `userId` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `section` varchar(20) NOT NULL,
  `houseTax` int DEFAULT '0',
  `electricityTax` int DEFAULT '0',
  `waterTax` int DEFAULT '0',
  `BusinessTax` int DEFAULT '0'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
);

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`username`, `password`) VALUES
('admin\r\n', 'admin'),
('database', 'database');

-- --------------------------------------------------------

--
-- Table structure for table `year2024`
--

CREATE TABLE `year2024` (
  `userId` varchar(255) NOT NULL,
  `hTaxPaid` int DEFAULT '0',
  `hTaxDate` datetime DEFAULT NULL,
  `eleTaxPaid` int DEFAULT '0',
  `eleTaxDate` date DEFAULT NULL,
  `wTaxPaid` int DEFAULT '0',
  `wTaxDate` date DEFAULT NULL,
  `bTaxPaid` int DEFAULT '0',
  `bTaxDate` date DEFAULT NULL,
  `totalTax` int DEFAULT '0'
);

--
-- Dumping data for table `year2024`
--

INSERT INTO `year2024` (`userId`, `hTaxPaid`, `hTaxDate`, `eleTaxPaid`, `eleTaxDate`, `wTaxPaid`, `wTaxDate`, `bTaxPaid`, `bTaxDate`, `totalTax`) VALUES
('1', 0, '2024-12-07 12:17:00', 0, NULL, 0, NULL, 0, NULL, 0),
('1/A', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0),
('2003', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0),
('9 Gotha', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `yearsdata`
--

CREATE TABLE `yearsdata` (
  `year` bigint NOT NULL
);

--
-- Dumping data for table `yearsdata`
--

INSERT INTO `yearsdata` (`year`) VALUES
(2024);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `maindata`
--
ALTER TABLE `maindata`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `maindata_structure`
--
ALTER TABLE `maindata_structure`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `year2024`
--
ALTER TABLE `year2024`
  ADD PRIMARY KEY (`userId`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `year2024`
--
ALTER TABLE `year2024`
  ADD CONSTRAINT `fk_userId_2024` FOREIGN KEY (`userId`) REFERENCES `maindata` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
