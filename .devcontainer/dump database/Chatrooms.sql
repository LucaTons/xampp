-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Creato il: Feb 23, 2026 alle 08:01
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
-- Database: `Chatrooms`
--
CREATE DATABASE IF NOT EXISTS `Chatrooms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Chatrooms`;

-- --------------------------------------------------------

--
-- Struttura della tabella `Chatroom`
--

CREATE TABLE `Chatroom` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `CreataDa` varchar(50) NOT NULL,
  `DataCreazione` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Chatroom`
--

INSERT INTO `Chatroom` (`ID`, `Nome`, `CreataDa`, `DataCreazione`) VALUES
(1, 'Prova', 'ciao', '2026-01-28 11:26:52'),
(2, 'Generale', 'admin', '2026-01-10 08:00:00'),
(3, 'Tecnologia', 'mario', '2026-01-11 09:30:00'),
(4, 'Off-topic', 'giulia', '2026-01-12 13:15:00'),
(5, 'Studio', 'luca', '2026-01-15 07:45:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `Inviti`
--

CREATE TABLE `Inviti` (
  `ID` int(11) NOT NULL,
  `NomeStanza` varchar(255) NOT NULL,
  `Mittente` varchar(255) NOT NULL,
  `Destinatario` varchar(255) NOT NULL,
  `Accettato` tinyint(1) DEFAULT 0,
  `DataInvito` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Messaggi`
--

CREATE TABLE `Messaggi` (
  `ID` int(11) NOT NULL,
  `Testo` text NOT NULL,
  `Giorno` date NOT NULL DEFAULT current_timestamp(),
  `NomeStanza` varchar(255) NOT NULL,
  `User` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Messaggi`
--

INSERT INTO `Messaggi` (`ID`, `Testo`, `Giorno`, `NomeStanza`, `User`) VALUES
(1, 'ciao', '2026-02-20', 'Prova', 'ciao'),
(2, 'ciao', '2026-02-20', 'Prova', 'ciao'),
(3, 'ciao', '2026-02-20', 'Prova', 'ciao'),
(4, 'hello world', '2026-02-20', 'Prova', 'ciao'),
(5, 'xfgbfg', '2026-02-20', 'Prova', 'ciao'),
(6, 'xfgbfg', '2026-02-20', 'Prova', 'ciao'),
(7, 'xfgbfg', '2026-02-20', 'Prova', 'ciao'),
(8, 'Ciao a tutti! Benvenuti nella chat.', '2026-01-10', 'Generale', 'admin'),
(9, 'Ciao! Finalmente funziona :)', '2026-01-10', 'Generale', 'mario'),
(10, 'Ottimo, ci voleva una chat del genere!', '2026-01-10', 'Generale', 'giulia'),
(11, 'Come state tutti quanti?', '2026-01-11', 'Generale', 'sara'),
(12, 'Bene grazie! E tu?', '2026-01-11', 'Generale', 'luca'),
(13, 'Anche io bene, grazie!', '2026-01-11', 'Generale', 'sara'),
(14, 'Buongiorno a tutti!', '2026-01-12', 'Generale', 'mario'),
(15, 'Buongiorno!', '2026-01-12', 'Generale', 'giulia'),
(16, 'Avete visto le novita di PHP 8.3?', '2026-01-11', 'Tecnologia', 'mario'),
(17, 'Si, le typed constants sono comode!', '2026-01-11', 'Tecnologia', 'luca'),
(18, 'Io sto usando ancora PHP 7 al lavoro purtroppo...', '2026-01-11', 'Tecnologia', 'sara'),
(19, 'Aggiorna! PHP 7 non ha piu supporto.', '2026-01-12', 'Tecnologia', 'mario'),
(20, 'Lo so lo so, ci sto lavorando.', '2026-01-12', 'Tecnologia', 'sara'),
(21, 'Qualcuno usa Docker per lo sviluppo locale?', '2026-01-13', 'Tecnologia', 'giulia'),
(22, 'Si, io lo uso sempre. Comodissimo.', '2026-01-13', 'Tecnologia', 'mario'),
(23, 'Anche io, non tornerei piu indietro.', '2026-01-14', 'Tecnologia', 'luca'),
(24, 'Che film avete visto ultimamente?', '2026-01-12', 'Off-topic', 'giulia'),
(25, 'Ho visto Oppenheimer, molto bello.', '2026-01-12', 'Off-topic', 'mario'),
(26, 'Anch io! La scena finale e incredibile.', '2026-01-12', 'Off-topic', 'sara'),
(27, 'Io preferisco le serie TV.', '2026-01-13', 'Off-topic', 'luca'),
(28, 'Cosa stai guardando?', '2026-01-13', 'Off-topic', 'giulia'),
(29, 'Sto finendo Breaking Bad, in ritardo lo so.', '2026-01-13', 'Off-topic', 'luca'),
(30, 'Classico intramontabile!', '2026-01-14', 'Off-topic', 'mario'),
(31, 'Ragazzi qualcuno ha capito le sessioni in PHP?', '2026-01-15', 'Studio', 'luca'),
(32, 'Si, cosa non ti e chiaro?', '2026-01-15', 'Studio', 'mario'),
(33, 'Come faccio a passare dati da una pagina all altra?', '2026-01-15', 'Studio', 'luca'),
(34, 'Usi $_SESSION, prima fai session_start() in cima.', '2026-01-15', 'Studio', 'mario'),
(35, 'Ah ok! E funziona su tutte le pagine?', '2026-01-15', 'Studio', 'luca'),
(36, 'Si finche non chiudi il browser o fai logout.', '2026-01-16', 'Studio', 'giulia'),
(37, 'Perfetto, grazie mille!', '2026-01-16', 'Studio', 'luca'),
(38, 'Prego! Studia bene :)', '2026-01-16', 'Studio', 'mario'),
(39, 'Hello World!', '2026-02-23', 'Prova', 'ciao'),
(40, 'Hello World!', '2026-02-23', 'Prova', 'ciao'),
(41, 'Hello World!', '2026-02-23', 'Prova', 'ciao');

-- --------------------------------------------------------

--
-- Struttura della tabella `Utenti`
--

CREATE TABLE `Utenti` (
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Utenti`
--

INSERT INTO `Utenti` (`Username`, `Password`) VALUES
('admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('ciao', '$2y$12$no5/jb.wulsUQ9Qi/PNN.eLTdVgmpR4aDNyeuHzZ0OY2cL.avfFJq'),
('giulia', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('luca', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('mario', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('sara', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Chatroom`
--
ALTER TABLE `Chatroom`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Nome` (`Nome`),
  ADD UNIQUE KEY `Nome_2` (`Nome`),
  ADD KEY `CreataDa` (`CreataDa`),
  ADD KEY `idx_chatroom_nome` (`Nome`);

--
-- Indici per le tabelle `Inviti`
--
ALTER TABLE `Inviti`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `NomeStanza` (`NomeStanza`);

--
-- Indici per le tabelle `Messaggi`
--
ALTER TABLE `Messaggi`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User` (`User`),
  ADD KEY `idx_messaggi_stanza` (`NomeStanza`),
  ADD KEY `idx_messaggi_giorno` (`Giorno`);

--
-- Indici per le tabelle `Utenti`
--
ALTER TABLE `Utenti`
  ADD PRIMARY KEY (`Username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Chatroom`
--
ALTER TABLE `Chatroom`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `Inviti`
--
ALTER TABLE `Inviti`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `Messaggi`
--
ALTER TABLE `Messaggi`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Chatroom`
--
ALTER TABLE `Chatroom`
  ADD CONSTRAINT `Chatroom_ibfk_1` FOREIGN KEY (`CreataDa`) REFERENCES `Utenti` (`Username`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Inviti`
--
ALTER TABLE `Inviti`
  ADD CONSTRAINT `Inviti_ibfk_1` FOREIGN KEY (`NomeStanza`) REFERENCES `Chatroom` (`Nome`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Messaggi`
--
ALTER TABLE `Messaggi`
  ADD CONSTRAINT `Messaggi_ibfk_1` FOREIGN KEY (`NomeStanza`) REFERENCES `Chatroom` (`Nome`),
  ADD CONSTRAINT `Messaggi_ibfk_2` FOREIGN KEY (`User`) REFERENCES `Utenti` (`Username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
