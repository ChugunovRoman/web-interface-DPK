-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 11 2016 г., 20:19
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `dpk`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Должности`
--

CREATE TABLE IF NOT EXISTS `Должности` (
  `Код` int(11) NOT NULL AUTO_INCREMENT,
  `Должность` varchar(40) DEFAULT NULL,
  `Принадлежность` tinyint(5) NOT NULL,
  PRIMARY KEY (`Код`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Дамп данных таблицы `Должности`
--

INSERT INTO `Должности` (`Код`, `Должность`, `Принадлежность`) VALUES
(1, 'библиотекарь', 1),
(2, 'бухгалтер', 1),
(3, 'вахтер', 1),
(4, 'водитель', 1),
(5, 'гардеробщица', 1),
(6, ' Глав.бухгалтер', 1),
(7, 'дворник', 1),
(8, 'директор', 1),
(9, 'зав.библиотекой', 1),
(10, 'зав.методкабине', 1),
(11, 'зав.отделением', 5),
(12, 'зав.практикой', 5),
(13, 'зав.уч.частью', 5),
(14, 'инженер по ОТ', 0),
(15, 'зам.дир.по АХЧ', 1),
(16, 'спец.по кадрам', 0),
(17, 'комендант', 1),
(18, 'лаборант', 3),
(19, 'педагог-психолог', 0),
(20, 'преподаватель', 2),
(21, 'рабочий', 0),
(22, 'рук.физвоспит.', 0),
(23, 'секретарь', 0),
(24, 'уборщица', 0),
(25, 'зам.директора', 1),
(31, 'программист', 0),
(32, 'соц.педагог', 0),
(33, 'экономист', 0),
(34, 'методист', 0),
(36, 'методист', 0),
(37, 'секр.учеб.части', 0),
(41, 'гл. бухгалтер', 1),
(46, 'сторож', 0),
(47, 'зав.канцелярией', 1),
(48, 'инженер по ОТ и', 0),
(49, 'инженер по ОТ и', 0),
(52, 'администрация', 1),
(53, 'дежурный по общ.', 1),
(54, 'зам.дир.по безопасн.', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `Категории`
--

CREATE TABLE IF NOT EXISTS `Категории` (
  `Код` int(11) NOT NULL AUTO_INCREMENT,
  `Категория` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`Код`),
  KEY `norm_orderbyindex` (`Категория`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `Категории`
--

INSERT INTO `Категории` (`Код`, `Категория`) VALUES
(3, 'вторая'),
(1, 'высшая'),
(4, 'нет'),
(2, 'первая');

-- --------------------------------------------------------

--
-- Структура таблицы `Основная_таблица`
--

CREATE TABLE IF NOT EXISTS `Основная_таблица` (
  `Код` int(11) NOT NULL AUTO_INCREMENT,
  `Фамилия` varchar(30) DEFAULT NULL,
  `Имя` varchar(30) DEFAULT NULL,
  `Отчество` varchar(30) DEFAULT NULL,
  `Серия_и_номер_паспорта` varchar(50) DEFAULT NULL,
  `кем_выдан_паспорт` varchar(255) DEFAULT NULL,
  `Дата_выдачи` DATE DEFAULT NULL,
  `Номер_пенс_св_ва` varchar(50) DEFAULT NULL,
  `ИНН` varchar(50) DEFAULT NULL,
  `Дата_приема_на_работу` DATE DEFAULT NULL,
  `№_приказа__дата` varchar(50) DEFAULT NULL,
  `Индекс` varchar(50) DEFAULT NULL,
  `Адрес` varchar(80) DEFAULT NULL,
  `ДомашнийТелефон` varchar(15) DEFAULT NULL,
  `ДатаРождения` DATE DEFAULT NULL,
  `Должность` int(11) DEFAULT NULL,
  `Подразделение` int(11) DEFAULT NULL,
  `Категория` int(11) DEFAULT NULL,
  `Разряд` varchar(50) DEFAULT NULL,
  `Звание` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Код`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=170 ;


-- --------------------------------------------------------

--
-- Структура таблицы `Основная_таблица_копия`
--

CREATE TABLE IF NOT EXISTS `Основная_таблица_копия` (
  `Код` int(11) NOT NULL AUTO_INCREMENT,
  `Фамилия` varchar(30) DEFAULT NULL,
  `Имя` varchar(30) DEFAULT NULL,
  `Отчество` varchar(30) DEFAULT NULL,
  `Серия_и_номер_паспорта` varchar(50) DEFAULT NULL,
  `кем_выдан_паспорт` varchar(255) DEFAULT NULL,
  `Дата_выдачи` DATE DEFAULT NULL,
  `Номер_пенс_св_ва` varchar(50) DEFAULT NULL,
  `ИНН` varchar(50) DEFAULT NULL,
  `Дата_приема_на_работу` DATE DEFAULT NULL,
  `№_приказа__дата` varchar(50) DEFAULT NULL,
  `Индекс` varchar(50) DEFAULT NULL,
  `Адрес` varchar(80) DEFAULT NULL,
  `ДомашнийТелефон` varchar(15) DEFAULT NULL,
  `ДатаРождения` DATE DEFAULT NULL,
  `Должность` int(11) DEFAULT NULL,
  `Подразделение` int(11) DEFAULT NULL,
  `Категория` int(11) DEFAULT NULL,
  `Разряд` varchar(50) DEFAULT NULL,
  `Звание` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Код`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=170 ;


-- --------------------------------------------------------

--
-- Структура таблицы `Подразделения`
--

CREATE TABLE IF NOT EXISTS `Подразделения` (
  `Код` int(11) NOT NULL AUTO_INCREMENT,
  `Подразделение` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`Код`),
  KEY `norm_orderbyindex` (`Подразделение`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `Подразделения`
--

INSERT INTO `Подразделения` (`Код`, `Подразделение`) VALUES
(1, 'администрация'),
(4, 'Не назначено'),
(2, 'преподаватель'),
(3, 'сотрудник'),
(5, 'Сотрудник и преподаватель');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
