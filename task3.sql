-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 06 2015 г., 22:49
-- Версия сервера: 5.5.45
-- Версия PHP: 5.4.44

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `task3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `bid` int(11) NOT NULL,
  `au_fname` varchar(16) NOT NULL,
  `au_lname` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `authors`
--

INSERT INTO `authors` (`bid`, `au_fname`, `au_lname`) VALUES
(3, 'Cristian', 'Darie'),
(3, 'Bogdan', 'Brinzarea'),
(3, 'Filip', 'Chereches-Toza'),
(5, 'Josh', 'Lockhart'),
(6, 'Робин', 'Никсон');

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `abstract` text NOT NULL,
  `cover` varchar(64) NOT NULL,
  `year` int(11) NOT NULL,
  `genre` varchar(32) NOT NULL,
  `subgenre` varchar(32) NOT NULL,
  `price` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `have` tinyint(1) NOT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`bid`, `title`, `abstract`, `cover`, `year`, `genre`, `subgenre`, `price`, `date`, `have`) VALUES
(3, 'AJAX and PHP: building responsive web applications', 'Authorized translation of the English edition &Acirc;&copy; 2006 Packt Publishing. This translation is published and sold by permission of Packt Publishing Ltd., the owner of all rights to publish and sell the same.', 'bid_1.jpg', 2006, 'Web development', '', 12.03, '2015-11-22 21:15:24', 1),
(5, 'Modern PHP', 'PHP is experiencing a renaissance, though it may be dicult to tell with all of the outdated PHP tutorials online. With this practical guide, you&acirc;', 'bid_2.jpg', 2015, 'Web development', '', 14.55, '2015-11-28 20:28:10', 1),
(6, 'PHP, MySQL, JavaScript, CSS и HTML5', 'Научитесь создавать интерактивные сайты, активно работающие с данными, воплощая в них мощные комбинации свободно распространяемых технологий и веб-стандартов. Для этого достаточно обладать базовыми знаниями языка HTML. Это популярное и доступное пособие поможет вам уверенно освоить динамическое веб-программирование с применением самых современных языков и технологий: PHP, MySQL, JavaScript, CSS и HTML5.', 'bid_3.jpg', 2015, 'Web development', '', 14.55, '2015-11-28 21:56:47', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `purchases`
--

CREATE TABLE IF NOT EXISTS `purchases` (
  `uid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(32) NOT NULL,
  `hpassword` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `ava` varchar(64) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(64) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`uid`, `uname`, `hpassword`, `email`, `admin`, `ava`, `date`, `status`) VALUES
(1, 'admin', 'ed4060702b42311eb4f6c707b11f1999', 'admin@a.com', 1, 'defaultava.jpg', '2015-11-17 22:06:28', ''),
(2, 'user1', '781f357c35df1fef3138f6d29670365a', 'user1@a.com', 0, 'defaultava.jpg', '2015-11-17 22:07:07', ''),
(3, 'user2', '781f357c35df1fef3138f6d29670365a', 'user2@b.com', 0, 'defaultava.jpg', '2015-11-17 22:07:32', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
