-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 25 mai 2022 à 13:42
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `autoroom`
--
CREATE DATABASE IF NOT EXISTS `autoroom` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `autoroom`;

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `apartment_delete` (IN `Id` VARCHAR(50))   DELETE FROM apartment WHERE apartment.Id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `apartment_get` (IN `Id` VARCHAR(50))   SELECT * FROM apartment WHERE apartment.id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `apartment_get_all` ()   SELECT * FROM apartment$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `apartment_insert` (IN `Id` VARCHAR(50), IN `Name` VARCHAR(100), IN `Street` VARCHAR(150), IN `ZipCode` VARCHAR(50), IN `City` VARCHAR(100), IN `Longitude` FLOAT, IN `Latitude` FLOAT)   INSERT INTO apartment 
(apartment.id,apartment.name,apartment.street,apartment.zipCode,apartment.city,apartment.longitude,apartment.latitude)
VALUES (Id, Name, Street, ZipCode, City, Longitude, Latitude)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `apartment_update` (IN `Id` VARCHAR(50), IN `Name` VARCHAR(100), IN `Street` VARCHAR(150), IN `ZipCode` VARCHAR(50), IN `City` VARCHAR(100), IN `Longitude` FLOAT, IN `Latitude` FLOAT)   UPDATE apartment 
SET apartment.name=Name, apartment.street=Street, apartment.zipCode=ZipCode, apartment.city=City, apartment.longitude=Longitude, apartment.latitude=Latitude
WHERE apartment.id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `room_delete` (IN `Id` VARCHAR(50))   DELETE FROM room WHERE room.Id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `room_delete_by_apart` (IN `Id` INT)   DELETE FROM room WHERE room.apartment_id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `room_get` (IN `Id` VARCHAR(50))   SELECT * FROM room JOIN apartment ON room.apartmentId=apartment.id WHERE room.Id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `room_get_all` ()   SELECT * FROM room$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `room_get_by_apart` (IN `ApartmentId` VARCHAR(50))   SELECT * FROM room WHERE room.apartment_id=ApartmentId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `room_insert` (IN `Id` VARCHAR(50), IN `Number` INT, IN `Area` FLOAT, IN `Price` INT, IN `Place` INT, IN `ApartmentId` VARCHAR(50))   INSERT INTO 
room (room.Id,room.number,room.area,room.place,room.price,room.apartmentId)
VALUES (Id,Number,Area,Price,Place,ApartmentId)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `room_search` (IN `City` VARCHAR(100))   SELECT * FROM room LEFT JOIN apartment ON room.apartmentId=apartment.id WHERE apartment.city LIKE CONCAT("%",City,"%")$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `room_update` (IN `Id` VARCHAR(50), IN `Number` INT, IN `Area` FLOAT, IN `Price` INT, IN `Place` INT, IN `ApartmentId` VARCHAR(50))   UPDATE room 
SET room.number=Number,room.area=Area,room.price=Price,room.place=Place,room.apartmentId=ApartmentId
WHERE room.Id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_delete` (IN `Id` VARCHAR(50))   DELETE FROM user WHERE user.Id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_get` (IN `Id` VARCHAR(50))   SELECT * FROM user WHERE user.id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_get_all` ()   SELECT * FROM user$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_get_by_mail` (IN `Email` VARCHAR(100))   SELECT * FROM user WHERE user.email=Email$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_insert` (IN `Id` VARCHAR(50), IN `FirstName` VARCHAR(50), IN `LastName` VARCHAR(50), IN `Email` VARCHAR(100), IN `Password` VARCHAR(250), IN `Phone` VARCHAR(20), IN `BirthDate` DATE, IN `Nationality` VARCHAR(100), IN `Admin` BOOLEAN)   INSERT INTO user 
(user.Id,user.firstName,user.lastName,user.email,user.password,user.phone,user.birthDate,user.nationality,user.admin) 
VALUES (Id,FirstName,LastName,Email,Password,Phone,BirthDate,Nationality,Admin)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_is_admin` (IN `Id` VARCHAR(50))   SELECT user.admin FROM user WHERE user.Id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_set_admin` (IN `Id` VARCHAR(50))   UPDATE user
SET user.admin=1
WHERE user.Id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_set_cutomer` (IN `Id` VARCHAR(50))   UPDATE user
SET user.admin=0
WHERE user.Id=Id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_update` (IN `Id` VARCHAR(50), IN `FirstName` VARCHAR(50), IN `LastName` VARCHAR(50), IN `Email` VARCHAR(100), IN `Password` VARCHAR(250), IN `Phone` VARCHAR(20), IN `BirthDate` DATE, IN `Nationality` VARCHAR(100), IN `Admin` BOOLEAN)   UPDATE user 
SET user.first_name=FirstName,
	user.last_name=LastName,
    user.email=Email,
    user.password=Password,
    user.phone=Phone,
    user.birth_date=BirthDate,
    user.nationality=Nationality,
    user.admin=Admin
WHERE user.Id=Id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `apartment`
--

CREATE TABLE `apartment` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipCode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `apartment`
--

INSERT INTO `apartment` (`id`, `name`, `street`, `zipCode`, `city`, `longitude`, `latitude`) VALUES
('id', 'Appartement', '50 rue de la République', '69001', 'Lyon', 1, 1),
('test', 'Appartement spacieux', '12 place Belcoure', '69008', 'Lyon', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `Id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `room`
--

CREATE TABLE `room` (
  `Id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `area` float NOT NULL,
  `price` int(11) NOT NULL,
  `place` int(11) NOT NULL,
  `apartmentId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `room`
--

INSERT INTO `room` (`Id`, `number`, `area`, `price`, `place`, `apartmentId`) VALUES
('a', 1, 45, 75, 21, 'id'),
('bba', 1, 58, 180, 3, 'id'),
('fze', 4, 70, 499, 2, 'test');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `Id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthDate` date NOT NULL,
  `nationality` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`Id`, `firstName`, `lastName`, `email`, `password`, `phone`, `birthDate`, `nationality`, `admin`) VALUES
('0759b6ec-3dc0-4157-817d-f1c352f3a649', 'User', 'User', 'user@user.user', '12dea96fec20593566ab75692c9949596833adc9', '084469749494', '2022-05-25', 'Francais', 0),
('19c67079-7975-447a-aaf9-a82b2180542c', 'Test', 'test', 'test@test.test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test', '1900-01-01', 'test', 1),
('22feaf74-5d32-4587-ab9e-5dad945722e9', 'Admin', 'Admin', 'admin@admin.admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '075459784846', '2022-05-25', 'Francais', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `apartment`
--
ALTER TABLE `apartment`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Index pour la table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `apartment_id` (`apartmentId`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`apartmentId`) REFERENCES `apartment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
