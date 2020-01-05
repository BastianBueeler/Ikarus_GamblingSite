-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 05. Jan 2020 um 19:29
-- Server-Version: 10.1.35-MariaDB
-- PHP-Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `ikarusgamblingsite`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `person`
--

CREATE TABLE `person` (
  `Username` varchar(30) NOT NULL,
  `fk_statistic` int(11) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `EMail` varchar(40) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `PreName` varchar(30) NOT NULL,
  `IkarusCoins` int(11) NOT NULL,
  `AboNewsLetter` tinyint(1) DEFAULT NULL,
  `Timestamp` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `person`
--

INSERT INTO `person` (`Username`, `fk_statistic`, `Password`, `EMail`, `Name`, `PreName`, `IkarusCoins`, `AboNewsLetter`, `Timestamp`) VALUES
('abc', 3, '$2y$10$vTYYKzo0y/cpuWoHGGy2SeO2O0AwpVdWyuSVt2K128ezdluTOrpnG', 'abc@gmail.com', 'abc', 'abc', 50, NULL, NULL),
('admin', 1, '$2y$10$gA337tpSpyhslxVdEhHrBOHQt5p8FyAj6k8Mn2okX6luMOpq0Dq/K', '1@gmail.com', 'admin', 'admin', 990000, NULL, NULL),
('BastianBueeler', 2, '$2y$10$hEVsSPAMf6rOfsrKHgT5We913ZomyUKcvAVHU1bRdUPpcPq/j3Gti', '1@gmail.com', 'bastian', 'Bueeler', 0, 1, '2020-01-05'),
('DarioGrob', NULL, '$2y$10$1kVhr0Vop18MBpWsQ9N.E.iLFPkdw3zQRXSM1svlocAJhZneMHvU.', 'dario.grob@gibmit.ch', 'Grob', 'Dario', 50, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `personstatistic`
--

CREATE TABLE `personstatistic` (
  `ID` int(11) NOT NULL,
  `CountedBlackJackGames` int(11) DEFAULT NULL,
  `CountedRouletteGames` int(11) DEFAULT NULL,
  `BlackJackWins` int(11) DEFAULT NULL,
  `RouletteWins` int(11) DEFAULT NULL,
  `MoneyWonBlackJack` int(11) DEFAULT NULL,
  `MoneyWonRoulette` int(11) DEFAULT NULL,
  `MoneySpentBlackJack` int(11) DEFAULT NULL,
  `MoneySpentRoulette` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `personstatistic`
--

INSERT INTO `personstatistic` (`ID`, `CountedBlackJackGames`, `CountedRouletteGames`, `BlackJackWins`, `RouletteWins`, `MoneyWonBlackJack`, `MoneyWonRoulette`, `MoneySpentBlackJack`, `MoneySpentRoulette`) VALUES
(1, NULL, 65, NULL, 19, NULL, 5369000, NULL, 2147483647),
(2, NULL, 12, NULL, 1, NULL, 100, NULL, 615),
(3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`Username`),
  ADD KEY `fk_statistic` (`fk_statistic`);

--
-- Indizes für die Tabelle `personstatistic`
--
ALTER TABLE `personstatistic`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `personstatistic`
--
ALTER TABLE `personstatistic`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_ibfk_1` FOREIGN KEY (`fk_statistic`) REFERENCES `personstatistic` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
