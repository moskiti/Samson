-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Фев 14 2021 г., 14:18
-- Версия сервера: 5.7.33-log
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_samson`
--

-- --------------------------------------------------------

--
-- Структура таблицы `a_category`
--

CREATE TABLE `a_category` (
  `category_id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_category`
--

INSERT INTO `a_category` (`category_id`, `code`, `name`, `parent_id`) VALUES
(23, '100', 'Бумага', 0),
(24, '102', 'Принтеры', 0),
(25, '103', 'МФУ', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `a_price`
--

CREATE TABLE `a_price` (
  `product_id` int(11) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 NOT NULL,
  `price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `a_price`
--

INSERT INTO `a_price` (`product_id`, `type`, `price`) VALUES
(216, 'Базовая', '11.50'),
(216, 'Москва', '12.50'),
(217, 'Базовая', '18.50'),
(217, 'Москва', '22.50'),
(218, 'Базовая', '3010.00'),
(218, 'Москва', '3500.00'),
(219, 'Базовая', '3310.00'),
(219, 'Москва', '2999.00');

-- --------------------------------------------------------

--
-- Структура таблицы `a_product`
--

CREATE TABLE `a_product` (
  `product_id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_product`
--

INSERT INTO `a_product` (`product_id`, `code`, `name`) VALUES
(216, '201', 'Бумага А4'),
(217, '202', 'Бумага А3'),
(218, '302', 'Принтер Canon'),
(219, '305', 'Принтер HP');

-- --------------------------------------------------------

--
-- Структура таблицы `a_product_category`
--

CREATE TABLE `a_product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_product_category`
--

INSERT INTO `a_product_category` (`product_id`, `category_id`) VALUES
(216, 23),
(217, 23),
(218, 24),
(218, 25),
(219, 24),
(219, 25);

-- --------------------------------------------------------

--
-- Структура таблицы `a_property`
--

CREATE TABLE `a_property` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `property` varchar(255) NOT NULL,
  `unit` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `a_property`
--

INSERT INTO `a_property` (`product_id`, `name`, `property`, `unit`) VALUES
(216, 'Плотность', '100', ''),
(216, 'Белизна', '150', '%'),
(217, 'Плотность', '90', ''),
(217, 'Белизна', '100', '%'),
(218, 'Формат', 'A4', ''),
(218, 'Формат', 'A3', ''),
(218, 'Тип', 'Лазерный', ''),
(219, 'Формат', 'A3', ''),
(219, 'Тип', 'Лазерный', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `a_category`
--
ALTER TABLE `a_category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Индексы таблицы `a_product`
--
ALTER TABLE `a_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Индексы таблицы `a_product_category`
--
ALTER TABLE `a_product_category`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `a_property`
--
ALTER TABLE `a_property`
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `a_category`
--
ALTER TABLE `a_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `a_product`
--
ALTER TABLE `a_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `a_product_category`
--
ALTER TABLE `a_product_category`
  ADD CONSTRAINT `a_product_category_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `a_category` (`category_id`),
  ADD CONSTRAINT `a_product_category_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `a_product` (`product_id`);

--
-- Ограничения внешнего ключа таблицы `a_property`
--
ALTER TABLE `a_property`
  ADD CONSTRAINT `a_property_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `a_product` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
