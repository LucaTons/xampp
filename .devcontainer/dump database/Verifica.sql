-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Creato il: Nov 22, 2025 alle 11:30
-- Versione del server: 11.3.2-MariaDB-1:11.3.2+maria~ubu2204
-- Versione PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Verifica`
--
CREATE DATABASE IF NOT EXISTS `Verifica` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Verifica`;

-- --------------------------------------------------------

--
-- Struttura della tabella `Dipendenti`
--

CREATE TABLE `Dipendenti` (
  `Matricola` int(11) NOT NULL,
  `CF` varchar(16) DEFAULT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  `Cognome` varchar(50) DEFAULT NULL,
  `Indirizzo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Dipendenti`
--

INSERT INTO `Dipendenti` (`Matricola`, `CF`, `Nome`, `Cognome`, `Indirizzo`) VALUES
(5, '45gff11d5', 'Pippo', 'Franco', 'via roma, 1'),
(101, 'RSSMRA80A01H501Z', 'Mario', 'Rossi', 'Via Verdi 3'),
(102, 'BNCLRA85B12H501L', 'Laura', 'Bianchi', 'Via Dante 9'),
(103, 'FRNGPP90C11H501Q', 'Giuseppe', 'Ferrari', 'Via Leopardi 5'),
(104, 'CNTPLA95D22H501S', 'Paola', 'Conti', 'Via Manzoni 8'),
(105, 'LMBCHR88E10H501Y', 'Chiara', 'Lombardi', 'Via Carducci 14');

-- --------------------------------------------------------

--
-- Struttura della tabella `Magazzini`
--

CREATE TABLE `Magazzini` (
  `Codice` int(11) NOT NULL,
  `Capienza` int(11) NOT NULL,
  `Indirizzo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Magazzini`
--

INSERT INTO `Magazzini` (`Codice`, `Capienza`, `Indirizzo`) VALUES
(1, 500, 'Via Roma 10'),
(2, 800, 'Via Milano 25'),
(3, 700, 'Via Torino 18'),
(4, 1000, 'Via Napoli 33'),
(5, 650, 'Via Firenze 50'),
(6, 1200, 'Via Bologna 7'),
(7, 900, 'Via Venezia 12'),
(8, 1100, 'Via Genova 22'),
(9, 750, 'Via Bari 6'),
(10, 600, 'Via Palermo 4');

-- --------------------------------------------------------

--
-- Struttura della tabella `MateriePrime`
--

CREATE TABLE `MateriePrime` (
  `Tipologia` varchar(50) NOT NULL,
  `CostoUnitario` decimal(10,2) DEFAULT NULL,
  `PesoUnitario` decimal(10,2) DEFAULT NULL,
  `Codice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `MateriePrime`
--

INSERT INTO `MateriePrime` (`Tipologia`, `CostoUnitario`, `PesoUnitario`, `Codice`) VALUES
('Biscotti Savoiardi', 0.70, 0.15, 5),
('Burro', 1.00, 0.50, 3),
('Cacao', 1.50, 0.20, 6),
('Cioccolato', 2.50, 0.50, 2),
('Farina', 0.50, 1.00, 1),
('Frutti di Bosco', 3.00, 0.30, 3),
('Latte', 0.60, 1.00, 5),
('Lievito', 0.40, 0.05, 7),
('Mascarpone', 1.80, 0.25, 4),
('Miele', 2.00, 0.25, 8),
('Panna Fresca', 1.20, 0.50, 2),
('Sale', 0.10, 0.10, 10),
('Uova', 0.30, 0.06, 4),
('Vanillina', 0.90, 0.02, 9),
('Zucchero', 0.80, 1.00, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotti`
--

CREATE TABLE `Prodotti` (
  `Id` int(11) NOT NULL,
  `Codice` int(11) DEFAULT NULL,
  `Matricola` int(11) DEFAULT NULL,
  `Descrizione` varchar(100) DEFAULT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Prodotti`
--

INSERT INTO `Prodotti` (`Id`, `Codice`, `Matricola`, `Descrizione`, `Nome`) VALUES
(10, 1, 101, 'Torta al cioccolato', 'TortaCiocco'),
(11, 2, 101, 'Tiramisù classico', 'Tiramisù'),
(12, 3, 102, 'Pan di Spagna', 'PanDiSpagna'),
(13, 4, 103, 'Muffin ai mirtilli', 'MuffinMirtilli'),
(14, 5, 104, 'Croissant al burro', 'Croissant'),
(15, 6, 105, 'Torta alla panna', 'TortaPanna'),
(16, 7, 103, 'Biscotti al miele', 'BiscottiMiele'),
(17, 8, 102, 'Torta vaniglia e cacao', 'TortaVanCac'),
(18, 9, 105, 'Cheesecake ai frutti di bosco', 'Cheesecake'),
(19, 10, 104, 'Torta al mascarpone', 'TortaMascarpone');

-- --------------------------------------------------------

--
-- Struttura della tabella `Ricette`
--

CREATE TABLE `Ricette` (
  `Tipologia` varchar(50) NOT NULL,
  `Id` int(11) NOT NULL,
  `Qta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Ricette`
--

INSERT INTO `Ricette` (`Tipologia`, `Id`, `Qta`) VALUES
('Biscotti Savoiardi', 11, 0.50),
('Burro', 10, 0.10),
('Burro', 13, 0.15),
('Burro', 14, 0.25),
('Burro', 16, 0.10),
('Cacao', 10, 0.15),
('Cacao', 17, 0.15),
('Farina', 10, 0.30),
('Farina', 12, 0.50),
('Farina', 13, 0.30),
('Farina', 14, 0.30),
('Farina', 16, 0.40),
('Farina', 17, 0.40),
('Frutti di Bosco', 13, 0.20),
('Frutti di Bosco', 18, 0.25),
('Latte', 14, 0.20),
('Lievito', 12, 0.05),
('Mascarpone', 11, 0.40),
('Mascarpone', 18, 0.30),
('Mascarpone', 19, 0.40),
('Miele', 16, 0.20),
('Panna Fresca', 11, 0.30),
('Panna Fresca', 15, 0.40),
('Panna Fresca', 18, 0.30),
('Panna Fresca', 19, 0.20),
('Uova', 10, 0.30),
('Uova', 12, 0.40),
('Uova', 14, 0.10),
('Vanillina', 15, 0.05),
('Vanillina', 17, 0.05),
('Vanillina', 19, 0.05),
('Zucchero', 10, 0.20),
('Zucchero', 11, 0.20),
('Zucchero', 12, 0.20),
('Zucchero', 13, 0.20),
('Zucchero', 15, 0.20),
('Zucchero', 17, 0.20),
('Zucchero', 18, 0.20),
('Zucchero', 19, 0.20);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Dipendenti`
--
ALTER TABLE `Dipendenti`
  ADD PRIMARY KEY (`Matricola`);

--
-- Indici per le tabelle `Magazzini`
--
ALTER TABLE `Magazzini`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `MateriePrime`
--
ALTER TABLE `MateriePrime`
  ADD PRIMARY KEY (`Tipologia`),
  ADD KEY `Codice` (`Codice`);

--
-- Indici per le tabelle `Prodotti`
--
ALTER TABLE `Prodotti`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Codice` (`Codice`),
  ADD KEY `Matricola` (`Matricola`);

--
-- Indici per le tabelle `Ricette`
--
ALTER TABLE `Ricette`
  ADD PRIMARY KEY (`Tipologia`,`Id`),
  ADD KEY `Id` (`Id`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `MateriePrime`
--
ALTER TABLE `MateriePrime`
  ADD CONSTRAINT `MateriePrime_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `Magazzini` (`Codice`);

--
-- Limiti per la tabella `Prodotti`
--
ALTER TABLE `Prodotti`
  ADD CONSTRAINT `Prodotti_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `Magazzini` (`Codice`),
  ADD CONSTRAINT `Prodotti_ibfk_2` FOREIGN KEY (`Matricola`) REFERENCES `Dipendenti` (`Matricola`);

--
-- Limiti per la tabella `Ricette`
--
ALTER TABLE `Ricette`
  ADD CONSTRAINT `Ricette_ibfk_1` FOREIGN KEY (`Tipologia`) REFERENCES `MateriePrime` (`Tipologia`),
  ADD CONSTRAINT `Ricette_ibfk_2` FOREIGN KEY (`Id`) REFERENCES `Prodotti` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
