-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2019 年 07 月 05 日 11:08
-- 伺服器版本： 5.7.26
-- PHP 版本： 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `projectdb`
--
drop database IF EXISTS projectDB;
create database projectDB character set utf8;
use projectDB; 
-- --------------------------------------------------------

--
-- 資料表結構 `administrator`
--

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `email` varchar(100) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 傾印資料表的資料 `administrator`
--

INSERT INTO `administrator` (`email`, `firstName`, `lastName`, `password`) VALUES
('123123@gmail.com', 'Lai', 'Dai Ming', '123123123'),
('abc123@gmail.com', 'Wong', 'Tsz Ching', 'abc123');

-- --------------------------------------------------------

--
-- 資料表結構 `dealer`
--

DROP TABLE IF EXISTS `dealer`;
CREATE TABLE IF NOT EXISTS `dealer` (
  `dealerID` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phoneNumber` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`dealerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 傾印資料表的資料 `dealer`
--

INSERT INTO `dealer` (`dealerID`, `password`, `name`, `phoneNumber`, `address`) VALUES
('12', '123', '12', '12309845', 'Room Y,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.'),
('7770003', '222ying', 'root', '12302332', 'Room B,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.'),
('laisiuwai123', '123', 'laisiuwai', '23123123', 'Room B,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.'),
('ngdaiming4567', 'ndm123123', 'Ng Dai Ming', '45678901', 'Flat 1A, Yee 6 House, Yee Ming Estate, 6 Chi Shin Street, Tseung Kwan O, N.T. Hong Kong'),
('root', '123', 'root', '12309845', 'Room A,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.');

-- --------------------------------------------------------

--
-- 資料表結構 `orderpart`
--

DROP TABLE IF EXISTS `orderpart`;
CREATE TABLE IF NOT EXISTS `orderpart` (
  `orderID` int(11) NOT NULL,
  `partNumber` int(11) NOT NULL,
  `quantity` int(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  KEY `FKOrderPart106296` (`orderID`),
  KEY `FKOrderPart737123` (`partNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 傾印資料表的資料 `orderpart`
--

INSERT INTO `orderpart` (`orderID`, `partNumber`, `quantity`, `price`) VALUES
(1, 1, 4, '3200.00'),
(1, 3, 3, '600.00'),
(1, 4, 11, '3080.00'),
(2, 1, 3, '2400.00'),
(2, 3, 2, '400.00'),
(2, 4, 7, '1960.00'),
(3, 1, 10, '8000.00'),
(4, 3, 0, '0.00'),
(4, 4, 0, '0.00'),
(5, 4, 0, '0.00');

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT,
  `dealerID` varchar(50) NOT NULL,
  `orderDate` date NOT NULL,
  `deliveryAddress` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`orderID`),
  KEY `FKOrders795865` (`dealerID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`orderID`, `dealerID`, `orderDate`, `deliveryAddress`, `status`) VALUES
(1, 'laisiuwai123', '2019-07-05', 'Room Y,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.', 2),
(2, 'laisiuwai123', '2019-07-05', 'Room Y,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.', 3),
(3, 'laisiuwai123', '2019-07-05', 'Room A,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.', 2),
(4, 'laisiuwai123', '2019-07-05', 'Room A,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.', 3),
(5, 'laisiuwai123', '2019-07-05', 'Room B,FL 12,BLK 11,Yee Yuet House, Yee Ming Estate, Tseung Kwan O, N.T.', 3);

-- --------------------------------------------------------

--
-- 資料表結構 `part`
--

DROP TABLE IF EXISTS `part`;
CREATE TABLE IF NOT EXISTS `part` (
  `partNumber` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `partName` varchar(100) NOT NULL,
  `stockQuantity` int(11) NOT NULL,
  `stockPrice` decimal(10,2) NOT NULL,
  `stockStatus` int(1) NOT NULL,
  `partImage` text NOT NULL,
  `partDescription` text NOT NULL,
  PRIMARY KEY (`partNumber`),
  UNIQUE KEY `partName` (`partName`),
  KEY `FKPart451022` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- 傾印資料表的資料 `part`
--

INSERT INTO `part` (`partNumber`, `email`, `partName`, `stockQuantity`, `stockPrice`, `stockStatus`, `partImage`, `partDescription`) VALUES
(1, '123123@gmail.com', 'Exhaust Pipe', 500, '800.00', 0, 'image/753-275_1.jpg', 'An exhaust pipe must be carefully designed to carry toxic and/or noxious gases away from the users of the machine. Indoor generators and furnaces can quickly fill an enclosed space with poisonous exhaust gases such as hydrocarbons, carbon monoxide and nitrogen oxides, if they are not properly vented to the outdoors. Also, the gases from most types of machines are very hot; the pipe must be heat-resistant, and it must not pass through or near anything that can burn or can be damaged by heat. A chimney serves as an exhaust pipe in a stationary structure. For the internal combustion engine it is important to have the exhaust system \"tuned\" (refer to tuned exhaust) for optimal efficiency.'),
(2, '123123@gmail.com', 'Engine', 0, '1200.00', 1, 'image/p_171102_05588.png', 'An engine or motor is a machine designed to convert one form of energy into mechanical energy. Heat engines, like the internal combustion engine, burn a fuel to create heat which is then used to do work. Electric motors convert electrical energy into mechanical motion, pneumatic motors use compressed air, and clockwork motors in wind-up toys use elastic energy. In biological systems, molecular motors, like myosins in muscles, use chemical energy to create forces and eventually motion.'),
(3, '123123@gmail.com', 'Radiator', 134, '200.00', 0, 'image/Car-radiator-engines-protector.jpg', 'Radiators are heat exchangers used to transfer thermal energy from one medium to another for the purpose of cooling and heating. The majority of radiators are constructed to function in automobiles, buildings, and electronics. The radiator is always a source of heat to its environment, although this may be for either the purpose of heating this environment, or for cooling the fluid or coolant supplied to it, as for engine cooling. Despite the name, most radiators transfer the bulk of their heat via convection instead of thermal radiation.'),
(4, '123123@gmail.com', 'Stabilizer Bar', 100, '280.00', 0, 'image/moog-car-suspension-parts-k750043-64_1000.jpg', 'An anti-roll bar (roll bar, anti-sway bar, sway bar, stabilizer bar) is a part of many automobile suspensions that helps reduce the body roll of a vehicle during fast cornering or over road irregularities. It connects opposite (left/right) wheels together through short lever arms linked by a torsion spring. A sway bar increases the suspension\'s roll stiffness—its resistance to roll in turns, independent of its spring rate in the vertical direction. The first stabilizer bar patent was awarded to Canadian inventor Stephen Coleman of Fredericton, New Brunswick on April 22, 1919.');

--
-- 已傾印資料表的限制(constraint)
--

--
-- 資料表的限制(constraint) `orderpart`
--
ALTER TABLE `orderpart`
  ADD CONSTRAINT `FKOrderPart106296` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`),
  ADD CONSTRAINT `FKOrderPart737123` FOREIGN KEY (`partNumber`) REFERENCES `part` (`partNumber`);

--
-- 資料表的限制(constraint) `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FKOrders795865` FOREIGN KEY (`dealerID`) REFERENCES `dealer` (`dealerID`);

--
-- 資料表的限制(constraint) `part`
--
ALTER TABLE `part`
  ADD CONSTRAINT `FKPart451022` FOREIGN KEY (`email`) REFERENCES `administrator` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
