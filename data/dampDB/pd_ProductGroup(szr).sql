-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 11 2019 г., 13:26
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
-- Структура таблицы `pd_ProductGroup`
--

CREATE TABLE IF NOT EXISTS `pd_ProductGroup` (
`id` int(11) NOT NULL,
  `guid` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `useInFilter` int(1) DEFAULT '0',
  `ordering` int(11) DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pd_ProductGroup`
--

INSERT INTO `pd_ProductGroup` (`id`, `guid`, `name`, `useInFilter`, `ordering`) VALUES
(1, '8baf0268-b86f-4d9f-ba66-bd3b6b3d8507', 'Гербіцид', 1, 5),
(2, 'ad8b049f-2f4e-474c-88bc-2f71a03aa4f1', 'Фунгіцид', 1, 3),
(3, 'b6d06bfa-97a3-44e4-b38d-04eb6e7bd7bb', 'Біопрепарат', NULL, NULL),
(4, '29129d76-318d-4125-bd96-a2c21d41854a', 'Інсектицид', 1, 4),
(5, '69226131-b845-417e-9c31-fbd4b51e04a2', 'Акарицид', NULL, NULL),
(6, '9cea5378-f620-4839-b6b3-fc7ac5acb174', 'Препарат для боротьби зі шкідниками запасів', NULL, NULL),
(7, '56e90edc-62a4-4058-aa2b-5715a430cb99', 'Фунгіцид для протруювання насіння', NULL, NULL),
(8, '69940610-7395-43da-812e-01a86451dcf7', 'Родентицид', 1, 10),
(9, '28034ec7-13e2-4d00-ac6d-2fd81e2e06f7', 'Препарат для обробки посівного матеріалу', NULL, NULL),
(10, 'a8ea3665-24cb-4fd1-b36d-1f8d9e31feba', 'Десикант', 1, 7),
(11, 'dc3a73db-44fd-43fa-9528-bc2ae0415e66', 'Біодобриво', 0, NULL),
(12, '893e031b-944a-4a9c-8b70-602fff5d9523', 'Препарат для протруювання насіння', NULL, NULL),
(13, 'bee83f75-c1f2-4632-a730-bcad06be903c', 'Фунгіцид для обробки насіння', NULL, NULL),
(14, 'd8ac3647-a3d2-4b15-81e2-3cd5509eaafe', 'Інсектицид для протруювання насіння', NULL, NULL),
(15, '097b3bfa-ada3-45c6-b55e-acd8dd0540f2', 'Десикант (з ПАР Споднам 554)', NULL, NULL),
(16, 'a78feb8f-b9a2-43d8-abf7-2fc9a5bc7560', 'Препарат для обробки насіння', NULL, NULL),
(17, 'ed852a72-7bda-4438-9521-253baa60e241', 'Феромон', NULL, NULL),
(18, '1cd2db01-279f-45a5-886d-4ed9d1e89267', 'Репелент', NULL, NULL),
(19, '23972508-3568-4475-bb9f-2dcba0c15e1d', 'Інсектоакарицид', NULL, NULL),
(20, '98b7a461-9fa0-4f22-b255-2e0bee26286b', 'Препарат для боротьби зі шкідниками', NULL, NULL),
(21, 'a502b7ae-3b55-484e-af59-b08feef84081', 'Інсекто-фунгіцид для протруювання насіння', NULL, NULL),
(22, 'f2cc58eb-2da1-4e74-b344-2845f14b4630', 'Фунгіцид для протруювання посівного матеріала', NULL, NULL),
(23, '24c78349-9929-4483-bb95-c622ea3862cc', 'Інсектицид для протруювання посадкового матеріалу', NULL, NULL),
(24, '52079e81-f259-4dcb-9cd3-452b4c1ef89a', 'Препарат для обробки посадкового матеріалу', NULL, NULL),
(25, 'aea0fee6-bf09-41fb-a261-75e168f40702', 'Фунгіцид для обробки бульб', NULL, NULL),
(26, '6ec18f9c-0578-4491-ad77-0ace174289ff', 'Фумігант', 1, 9),
(27, '99f31102-88a2-4a48-8446-cbe3c24279e1', 'Лісове господарство', NULL, NULL),
(28, '84851b26-8a0f-4961-a328-651825c487db', 'Принада лимацидна', NULL, NULL),
(29, '1794d65f-1d44-4bbd-befb-881511f9c937', 'Біопрепарат інсектицидної дії', NULL, NULL),
(30, 'b357f76c-a211-4c14-a343-6b04fc64211f', 'Біопрепарат фунгіцидної дії', NULL, NULL),
(31, '72023b62-05f1-41f6-97ba-e1f19cbb3778', 'Біологічний благотворний агент', NULL, NULL),
(32, '55a40e9c-b3e2-4277-b08e-0e465efc6c1a', 'Мікродобрива', 1, 1),
(33, '5544782d-f798-4b87-8450-9034fc571016', 'Протруйники', 1, 6),
(34, '46519d07-d27c-47ba-85a5-6787a4beceda', 'Стимулятори', NULL, NULL),
(35, 'c5992599-193f-45c3-9cb1-d38f9e97bd0f', 'Доп. речовини', 1, 8),
(36, 'de7c9144-3ee9-40cb-80fc-51a623e4bf57', 'Регулятори роста', 1, 2),
(37, '115bb76e-d424-4435-9879-7c1e997531f0', 'клеєва пастка', NULL, NULL),
(38, '015cbe3c-571a-4eb0-858e-5c6149a40560', 'клейова пастка-приманка', NULL, NULL),
(40, '09ffbb4d-0081-41b2-8538-3ea9456deb7f', 'Добриво', NULL, NULL),
(41, '2d10f998-20ec-4075-bafb-f6096e772f92', 'Електричний знищувач літаючих комах', NULL, NULL),
(42, 'new', 'Прилипач', NULL, NULL),
(49, 'new1', 'ПАР', NULL, NULL),
(52, '', 'Морфорегулятори', NULL, NULL),
(53, 'new', 'Регулятор росту', NULL, NULL),
(54, 'new', 'Ад’юванти', NULL, NULL),
(55, 'new', 'Ретардант', NULL, NULL),
(56, 'new', 'Органічне добриво', NULL, NULL),
(57, 'new', 'Рідке добриво', NULL, NULL),
(58, 'new', 'Кристалічне водорозчинне добриво', NULL, NULL),
(59, 'new', 'Протруйник насіння ', 0, NULL),
(60, 'new', 'Торф''яні субстрати', 1, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pd_ProductGroup`
--
ALTER TABLE `pd_ProductGroup`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pd_ProductGroup`
--
ALTER TABLE `pd_ProductGroup`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
