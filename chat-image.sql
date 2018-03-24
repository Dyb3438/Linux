-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 2018-02-10 05:14:12
-- 服务器版本： 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tieba`
--

-- --------------------------------------------------------

--
-- 表的结构 `chat-image`
--

DROP TABLE IF EXISTS `chat-image`;
CREATE TABLE IF NOT EXISTS `chat-image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` bigint(20) NOT NULL,
  `receiver` bigint(20) NOT NULL,
  `image` text COLLATE utf8_bin NOT NULL,
  `time` datetime NOT NULL,
  `readed` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `both` (`sender`,`receiver`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
