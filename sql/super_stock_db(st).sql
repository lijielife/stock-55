-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2015 at 12:04 AM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `super_stock_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_log`
--

CREATE TABLE IF NOT EXISTS `data_log` (
  `ID` int(11) NOT NULL,
  `SIDE_ID` int(11) NOT NULL,
  `SYMBOL_ID` varchar(45) NOT NULL,
  `VOLUME` int(11) NOT NULL,
  `PRICE` decimal(10,4) NOT NULL,
  `AMOUNT` decimal(10,4) NOT NULL,
  `VAT` decimal(10,4) DEFAULT NULL,
  `NET_AMOUNT` decimal(10,4) NOT NULL,
  `DATE` date NOT NULL,
  `BROKER_ID` varchar(45) NOT NULL,
  `IS_DW` tinyint(1) NOT NULL,
  `CREATED_AT` date DEFAULT NULL,
  `CREATED_BY` varchar(45) DEFAULT NULL,
  `UPDATED_AT` date DEFAULT NULL,
  `UPDATED_BY` varchar(45) DEFAULT NULL,
  `USER_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `ID` bigint(20) NOT NULL,
  `SYMBOL` varchar(11) NOT NULL,
  `RESOLUTION` varchar(2) NOT NULL,
  `ORIGIN` varchar(45) NOT NULL,
  `millisec` bigint(20) NOT NULL,
  `TIME` varchar(20) NOT NULL,
  `OPEN` decimal(10,2) NOT NULL,
  `CLOSE` decimal(10,2) NOT NULL,
  `HIGH` decimal(10,2) NOT NULL,
  `LOW` decimal(10,2) NOT NULL,
  `VOLUME` bigint(20) NOT NULL,
  `UPDATED_AT` date NOT NULL,
  `CREATED_AT` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_map`
--

CREATE TABLE IF NOT EXISTS `log_map` (
  `ID` int(11) NOT NULL,
  `MAP_SRC` int(11) DEFAULT NULL,
  `MAP_DESC` int(11) DEFAULT NULL,
  `MAP_VOL` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mas_broker`
--

CREATE TABLE IF NOT EXISTS `mas_broker` (
  `ID` int(11) NOT NULL,
  `BROKER_CODE` varchar(45) DEFAULT NULL,
  `BROKER_NAME` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mas_side`
--

CREATE TABLE IF NOT EXISTS `mas_side` (
  `ID` int(11) NOT NULL,
  `SIDE_CODE` int(11) DEFAULT NULL,
  `SIDE_NAME` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `symbol_name`
--

CREATE TABLE IF NOT EXISTS `symbol_name` (
  `ID` int(11) NOT NULL,
  `SYMBOL` varchar(11) NOT NULL,
  `IS_USE` tinyint(1) NOT NULL DEFAULT '1',
  `CREATED_AT` date NOT NULL,
  `UPDATED_AT` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `table_name`
--

CREATE TABLE IF NOT EXISTS `table_name` (
  `ID` int(11) NOT NULL,
  `TABLE_NAME` varchar(45) DEFAULT NULL,
  `SYMBOL` varchar(45) DEFAULT NULL,
  `ORIGIN` varchar(45) DEFAULT NULL,
  `CREATED_AT` date DEFAULT NULL,
  `UPDATED_AT` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `active` varchar(1) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_log`
--
ALTER TABLE `data_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `NAME` (`SYMBOL`,`TIME`);

--
-- Indexes for table `log_map`
--
ALTER TABLE `log_map`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `MAP_SRC_UNIQUE` (`MAP_SRC`),
  ADD UNIQUE KEY `MAP_DESC_UNIQUE` (`MAP_DESC`),
  ADD UNIQUE KEY `MAP_VOL_UNIQUE` (`MAP_VOL`);

--
-- Indexes for table `mas_broker`
--
ALTER TABLE `mas_broker`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `BROKER_CODE_UNIQUE` (`BROKER_CODE`);

--
-- Indexes for table `mas_side`
--
ALTER TABLE `mas_side`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `SIDE_CODE_UNIQUE` (`SIDE_CODE`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `symbol_name`
--
ALTER TABLE `symbol_name`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `SYMBOL` (`SYMBOL`);

--
-- Indexes for table `table_name`
--
ALTER TABLE `table_name`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `TABLE_NAME_UNIQUE` (`TABLE_NAME`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_log`
--
ALTER TABLE `data_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_map`
--
ALTER TABLE `log_map`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mas_broker`
--
ALTER TABLE `mas_broker`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mas_side`
--
ALTER TABLE `mas_side`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `symbol_name`
--
ALTER TABLE `symbol_name`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `table_name`
--
ALTER TABLE `table_name`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_map`
--
ALTER TABLE `log_map`
  ADD CONSTRAINT `MAP_DESC` FOREIGN KEY (`MAP_DESC`) REFERENCES `data_log` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `MAP_SRC` FOREIGN KEY (`MAP_SRC`) REFERENCES `data_log` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
