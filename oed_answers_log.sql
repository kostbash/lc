-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 18, 2014 at 04:12 PM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `education`
--

-- --------------------------------------------------------

--
-- Table structure for table `oed_answers_log`
--

CREATE TABLE IF NOT EXISTS `oed_answers_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `id_course` int(11) NOT NULL,
  `id_lesson_group` int(11) NOT NULL,
  `id_lesson` int(11) NOT NULL,
  `id_exercise_group` int(11) NOT NULL,
  `id_exercise` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `oed_answers_log`
--

INSERT INTO `oed_answers_log` (`id`, `ip`, `date`, `id_course`, `id_lesson_group`, `id_lesson`, `id_exercise_group`, `id_exercise`, `answer`) VALUES
(1, '127.0.0.1', '2014-04-18', 11, 58, 59, 33, 17, 'AS');
