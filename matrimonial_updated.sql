-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2018 at 12:22 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--
ALTER TABLE `member` ADD `belongs_to` VARCHAR(255) NULL AFTER `pic_privacy`;

ALTER TABLE `member` ADD `latitude` VARCHAR(255) NULL AFTER `belongs_to`;
ALTER TABLE `member` ADD `longitude` VARCHAR(255) NULL AFTER `belongs_to`;
ALTER TABLE `member` ADD `last_visit` datetime NULL AFTER `belongs_to`;
ALTER TABLE `member` ADD `is_completed` int(11) NULL AFTER `belongs_to`;
ALTER TABLE `plan` ADD `duration_type` int(11) NULL AFTER `image`;
ALTER TABLE `plan` ADD `duration` int(11) NULL AFTER `image`;
ALTER TABLE `member` ADD `membership_valid_till` datetime NULL AFTER `belongs_to`;
ALTER TABLE `member` ADD `timezone` varchar(191) NULL AFTER `belongs_to`;



INSERT INTO `business_settings` (`type`, `value`) VALUES
('paytm_set', 'ok'),
('paytm_mid', ''),
('paytm_mkey', ''),
('paytm_account_type', 'sandbox');


INSERT INTO `business_settings` (`type`, `value`) VALUES
('ccavenue_mid', 'ok'),
('ccavenue_working_key', ''),
('ccavenue_account_type', 'sandbox'),
('ccavenue_access_code', '');


DROP TABLE IF EXISTS `partner_personal_preferances`;
CREATE TABLE `partner_personal_preferances` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL, 
  `partner_age_min` int(11) DEFAULT NULL,
  `partner_age_max` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `partner_height_min` decimal(11,2) DEFAULT NULL,
  `partner_height_max` decimal(11,2) DEFAULT NULL,
  `height` 	decimal(10,2) DEFAULT NULL,
  `partner_weight_min` int(11) DEFAULT NULL,
  `partner_weight_max` int(11) DEFAULT NULL,
  `partner_weight_units` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `weight_units` int(11) DEFAULT NULL,
  `partner_marital_status` int(11) DEFAULT NULL, 
  `marital_status` int(11) DEFAULT NULL,
  `partner_accepted_with_children` int(11) DEFAULT NULL, 
  `number_of_children` int(11) DEFAULT NULL, 
  `partner_religion` int(11) DEFAULT NULL,
  `religion` int(11) DEFAULT NULL,
  `partner_caste` varchar(255) DEFAULT NULL,
  `caste` int(11) DEFAULT NULL, 
  `partner_sub_caste` varchar(255) DEFAULT NULL, 
  `sub_caste` int(11) DEFAULT NULL,
  `partner_family_value` varchar(255) DEFAULT NULL, 
  `family_value` int(11) DEFAULT NULL,
  `partner_education` varchar(255) DEFAULT NULL, 
  `education` int(11) DEFAULT NULL,
  `partner_profession` varchar(255) DEFAULT NULL,
  `profession` int(11) DEFAULT NULL,
  `partner_drinking_habits` int(11) DEFAULT NULL,
  `drinking_habits` int(11) DEFAULT NULL,
  `partner_smoking_habits` int(11) DEFAULT NULL,
  `smoking_habits` int(11) DEFAULT NULL,
  `partner_diet` int(11) DEFAULT NULL,
  `diet` int(11) DEFAULT NULL,
  `partner_body_type` int(11) DEFAULT NULL,
  `body_type` int(11) DEFAULT NULL,
  `partner_manglik` int(11) DEFAULT NULL,
  `manglik` int(11) DEFAULT NULL,
  `partner_disability` int(11) DEFAULT NULL,
  `disability` int(11) DEFAULT NULL,
  `partner_mother_tongue` int(11) DEFAULT NULL,
  `mother_tongue` int(11) DEFAULT NULL,
  `partner_family_status` int(11) DEFAULT NULL,
  `family_status` int(11) DEFAULT NULL,
  `partner_complexion` int(11) DEFAULT NULL,
  `complexion` int(11) DEFAULT NULL,
  `partner_address_country` int(11) DEFAULT NULL,
  `partner_address_state` varchar(255) DEFAULT NULL,
  `partner_address_city` varchar(255) DEFAULT NULL,
  `address_country` int(11) DEFAULT NULL,
  `address_state` int(11) DEFAULT NULL,
  `address_city` int(11) DEFAULT NULL,
  `latitude` varchar(30) DEFAULT NULL,
  `longitude` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `partner_personal_preferances`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `partner_personal_preferances`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `reset_tokens`;
CREATE TABLE `reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL, 
  `token` varchar(100) DEFAULT NULL,
  `expire_at` varchar(25) NOT NULL DEFAULT NULL,
  `used` int(11) NOT NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `reset_tokens`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reset_tokens`
  MODIFY `id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
