-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 08 2019 г., 17:32
-- Версия сервера: 5.5.58-0+deb8u1
-- Версия PHP: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `pxbipzfo_crm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `pd_VerminGroup`
--

CREATE TABLE IF NOT EXISTS `pd_VerminGroup` (
`id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pd_VerminGroup`
--

INSERT INTO `pd_VerminGroup` (`id`, `name`) VALUES
(0, 'Інші'),
(1, 'Бур''яни'),
(2, 'Хвороби'),
(3, 'Шкідники');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pd_VerminGroup`
--
ALTER TABLE `pd_VerminGroup`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pd_VerminGroup`
--
ALTER TABLE `pd_VerminGroup`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
