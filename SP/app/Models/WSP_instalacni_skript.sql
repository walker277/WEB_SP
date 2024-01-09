-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost
-- Vytvořeno: Úte 09. led 2024, 01:57
-- Verze serveru: 10.4.28-MariaDB
-- Verze PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `WSP`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `CLANEK`
--

CREATE TABLE `CLANEK` (
  `idCLANEK` int(11) NOT NULL,
  `schvalen` tinyint(4) NOT NULL,
  `recenzent_1` int(11) NOT NULL,
  `recenzent_2` int(11) NOT NULL,
  `recenzent_3` int(11) DEFAULT NULL,
  `hodnoceni_1` int(11) NOT NULL,
  `hodnoceni_2` int(11) NOT NULL,
  `hodnoceni_3` int(11) NOT NULL,
  `nazev` varchar(100) NOT NULL,
  `abstrakt` text NOT NULL,
  `cesta` varchar(200) NOT NULL,
  `komentar_1` text DEFAULT NULL,
  `komentar_2` text DEFAULT NULL,
  `komentar_3` text DEFAULT NULL,
  `autori` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `CLANEK`
--

INSERT INTO `CLANEK` (`idCLANEK`, `schvalen`, `recenzent_1`, `recenzent_2`, `recenzent_3`, `hodnoceni_1`, `hodnoceni_2`, `hodnoceni_3`, `nazev`, `abstrakt`, `cesta`, `komentar_1`, `komentar_2`, `komentar_3`, `autori`) VALUES
(43, 2, 6, 12, 14, 2, 7, 8, 'Teamový článek', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus nisi ac dui pretium, non pharetra quam congue. Praesent euismod vehicula ex, quis auctor erat. Aenean a risus odio. Vivamus pretium lacus sit amet sem lacinia, vitae vestibulum ante ultricies. Phasellus condimentum sit amet tellus in viverra. Integer at dui nec leo sollicitudin consectetur. In metus orci, lobortis nec elementum eleifend, congue ac lorem. Etiam dictum nec dui eget vehicula. Quisque placerat est id sodales interdum. Vestibulum ac libero ac purus scelerisque semper quis nec ex. Nulla facilisi. Vivamus feugiat enim at elit mattis interdum. Mauris malesuada augue massa, in ullamcorper enim interdum vitae. ', 'soubory/pdf_SP.pdf', 'Nejhořší článek co jsem kdy četla.', 'Tento článek mi přijde velice kvalitní', 'XDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD', 'Autoři: Karel Rychlý, Kamil Hladký'),
(68, 2, 13, 15, 14, 8, 10, 9, 'dolor sit amet', 'Vestibulum euismod ullamcorper massa vitae blandit. Quisque at quam est. Pellentesque rutrum risus eu nisi sodales, nec varius ipsum porttitor. In tempus, massa a pulvinar varius, massa sapien aliquam ex, eleifend tempus nunc mi ut magna. Mauris lorem purus, dignissim eu magna non, congue rutrum nulla. Morbi facilisis egestas nulla quis tempus. Nulla ultrices luctus neque in mollis. ', 'soubory/clanek_web1.pdf', ' Integer leo urna, accumsan ac purus sed, pellentesque viverra augue. Aenean gravida malesuada mauris, sed pharetra ipsum ultricies tincidunt. Nullam purus libero, gravida vel enim sed, eleifend auctor sem. Aenean id ornare arcu. Mauris pretium dolor quis mi fringilla rhoncus quis in nisl. Morbi ullamcorper risus at bibendum fermentum. Sed neque mi, aliquet vitae leo at, venenatis convallis turpis. ', 'est, vel laoreet orci volutpat sed. Proin eros nulla, vulputate a consectetur at, dapibus ac erat. Nullam pellentesque, tortor quis bibendum congue, mi velit pellentesque ligula, a venenatis erat mauris ac erat. Aliquam erat volutpat. Ut tempus commodo dolor, ut bibendum urna. Phasellus interdum libero sed justo volutpat egestas.', 'Donec ex enim, pulvinar eget dignissim eu, bibendum quis neque. Praesent sagittis felis est, ac pharetra magna vestibulum eu. Nam sit amet cursus lectus, eget sollicitudin tortor. In elementum feugiat lacus, elementum semper erat varius id. Integer sodales fringilla rhoncus. Proin maximus, arcu eu fermentum vehicula, ligula ligula placerat orci, in eleifend orci velit a nisi. Aliquam erat volutpat. Suspendisse sagittis elementum turpis sed placerat. Nulla venenatis ut enim ultricies laoreet. Sed pellentesque convallis porta. Donec ultrices, dolor eu suscipit tempor, ligula sapien malesuada nisi, eu imperdiet ante dui quis ante. Duis finibus mauris erat, ac venenatis quam varius ac. Mauris rutrum elementum ex, et malesuada metus facilisis quis. ', 'Autoři: Karel Rychlý, Kamil Hladký, Josef Drobný'),
(69, 1, 15, 6, 13, 3, 2, 4, ' Fusce malesuada eros', 'In dapibus convallis ex, nec blandit orci sollicitudin elementum. Nullam lobortis nisi dui, et sagittis ante maximus vel. In varius rutrum semper. Quisque cursus pellentesque massa, non sagittis diam. Phasellus interdum lacinia luctus. Donec a dapibus magna. Sed sollicitudin, purus ut condimentum porta, turpis orci mattis tellus, et faucibus massa eros quis eros. Sed quis felis quis tortor varius vulputate at ac ante. Donec at diam non lacus tincidunt feugiat. Sed mattis metus at felis condimentum, eu commodo enim mollis. Aliquam eget lacinia nisl. Suspendisse sed nisl metus. ', 'soubory/clanek_web2.pdf', 'Mauris et lectus sed turpis interdum dapibus. Praesent euismod nisi ac neque sollicitudin suscipit. Donec pretium maximus accumsan. Donec sagittis orci nec vulputate molestie. Nulla tincidunt accumsan elit eu porttitor. Proin lacinia, lectus quis rutrum luctus, orci nunc consequat orci, vitae hendrerit nunc magna vel felis. Mauris mauris lacus, posuere et hendrerit sed, rhoncus sed nisi. Suspendisse potenti. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis et aliquam urna. Pellentesque rutrum accumsan odio, vitae ultrices purus sollicitudin quis. Praesent dictum nec velit sed accumsan. Maecenas blandit, metus ac ornare malesuada, diam enim auctor lacus, ac tristique lectus libero nec nisi. Morbi aliquam luctus elit in suscipit. Vestibulum neque nibh, commodo at bibendum non, sodales non nisi. In auctor eros ut viverra tempus. ', 'Vivamus velit lorem, mollis ut sem ac, ultrices porta nisi. Donec nec justo non purus fermentum semper quis quis dolor. Quisque ante turpis, mattis vitae malesuada in, laoreet eget ipsum. Aenean eget tempor lorem. Maecenas ut consectetur justo, nec maximus risus. Nullam eget rhoncus mi, ut facilisis dui. Nunc sit amet finibus magna. Vivamus vitae elementum elit. Etiam pretium, lectus eu malesuada pretium, nulla dui consectetur dui, laoreet volutpat lacus tellus sed mi. Phasellus tincidunt massa eros. Vestibulum elementum, odio sit amet lobortis sagittis, lorem neque pharetra lacus, in vehicula felis massa posuere elit. Proin justo erat, sodales in posuere a, tristique in erat. Duis molestie, mauris at interdum varius, nisi leo suscipit leo, eu aliquam nulla est et eros. ', 'Aenean placerat varius feugiat. In laoreet dui enim, sed commodo lectus semper eget. Proin lorem dui, aliquam in cursus ut, porta sit amet est. Interdum et malesuada fames ac ante ipsum primis in faucibus. In et nunc sem. Nullam consectetur velit purus, non ullamcorper magna malesuada a. In hac habitasse platea dictumst. Nam sollicitudin nisi libero, sit amet gravida est placerat in. Maecenas tristique commodo molestie. ', 'Autor: Kamil Hladký'),
(70, 0, 6, 0, 0, 0, 0, 0, 'Donec vehicula est ', 'Donec bibendum vitae sapien quis egestas. Donec nulla magna, hendrerit tempor diam in, vehicula iaculis lacus. Proin aliquam felis sagittis nulla interdum fringilla. Nulla et volutpat dui. In nisi eros, consectetur vel mollis non, lacinia non elit. Suspendisse gravida sodales ligula non hendrerit. Cras eros diam, ultricies non nibh id, vestibulum fermentum tellus. Fusce placerat, sem a feugiat consequat, ex nisl condimentum nisi, sed tincidunt dolor ex nec dolor. Maecenas nec est facilisis, iaculis nunc at, sagittis leo. Suspendisse ultricies magna vel velit congue volutpat. Etiam congue, nisi in gravida aliquam, nibh enim molestie dolor, ut bibendum sem nulla nec leo. ', 'soubory/clanek_web3.pdf', '', NULL, NULL, 'Autoři: Josef Drobný, Andrea plachá');

-- --------------------------------------------------------

--
-- Struktura tabulky `CLANKY_AUTORA`
--

CREATE TABLE `CLANKY_AUTORA` (
  `idClankyAutora` int(11) NOT NULL,
  `id_uzivatel` int(11) NOT NULL,
  `idCLANEK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `CLANKY_AUTORA`
--

INSERT INTO `CLANKY_AUTORA` (`idClankyAutora`, `id_uzivatel`, `idCLANEK`) VALUES
(32, 4, 43),
(33, 10, 43),
(84, 4, 68),
(85, 10, 68),
(86, 11, 68),
(87, 10, 69),
(88, 11, 70),
(89, 16, 70);

-- --------------------------------------------------------

--
-- Struktura tabulky `DOTAZ`
--

CREATE TABLE `DOTAZ` (
  `id_dotaz` int(11) NOT NULL,
  `e_mail` varchar(50) NOT NULL,
  `jmeno` varchar(50) NOT NULL,
  `dotaz` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `DOTAZ`
--

INSERT INTO `DOTAZ` (`id_dotaz`, `e_mail`, `jmeno`, `dotaz`) VALUES
(1, 'ipsum@ips.cz', 'Emil Doptávající', 'Mohu si na této stránce udělat uživatelský účet.');

-- --------------------------------------------------------

--
-- Struktura tabulky `pravo`
--

CREATE TABLE `pravo` (
  `id_pravo` int(11) NOT NULL,
  `nazev` varchar(50) NOT NULL,
  `vaha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `pravo`
--

INSERT INTO `pravo` (`id_pravo`, `nazev`, `vaha`) VALUES
(1, 'SuperAdmin', 20),
(2, 'Admin', 10),
(3, 'Recenzent', 5),
(4, 'Autor', 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `UZIVATEL`
--

CREATE TABLE `UZIVATEL` (
  `id_uzivatel` int(11) NOT NULL,
  `id_pravo` int(11) NOT NULL,
  `jmeno_prijmeni` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pohlavi` varchar(4) NOT NULL,
  `datum_narozeni` date NOT NULL,
  `Zablokovany` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `UZIVATEL`
--

INSERT INTO `UZIVATEL` (`id_uzivatel`, `id_pravo`, `jmeno_prijmeni`, `username`, `password`, `email`, `pohlavi`, `datum_narozeni`, `Zablokovany`) VALUES
(4, 4, 'Karel Rychlý', 'autor', '$2y$10$aS0s4ZKl4ocXE0NIQi3q5.QQEFRRae/YAksQrffqC.24ElMS1ZqdO', 'ips@ipsum.cz', 'muz', '1974-03-12', 0),
(6, 3, 'Lucie Věřící', 'recenzent', '$2y$10$r05lo9pD0gZxLfq86c2V3.eG5n8OOIuw4quSM/mASJeQf43FCAjnW', 'ipsum@ip.cz', 'zena', '1981-12-07', 0),
(7, 2, 'František Dalekohledící', 'admin', '$2y$10$1uI607/lZhiUvxYiayf/jeLUGmTTUCU4JJW6.Itr4wqZWohZjabZ2', 'ips@ipsmu.cz', 'muz', '1997-03-05', 0),
(8, 2, 'Natálie Nováková', 'admin2', '$2y$10$R6ErdljRisaGZ55O/m4e2u6fmkkjU.g2y9dHgAX/Vl1kHUKF9mp2K', 'ipsum@ips.cz', 'zena', '2000-05-04', 0),
(9, 4, 'Jaroslav Potrhlý', 'nebezpečný', '$2y$10$VQeCdajrkAgxPM162VrNN.Tgl2gV7EYuYOLRKLlkvE07aOaGuXVnC', 'ipsum@ips.cz', 'muz', '2002-07-01', 1),
(10, 4, 'Kamil Hladký', 'autor2', '$2y$10$mwMYh.n0r8GtXgBKSZirh.n0ql05G1BwTjPMU5W2emQsR.jOQ8fP2', 'ips@ip.ch', 'muz', '1985-01-12', 0),
(11, 4, 'Josef Drobný', 'autor3', '$2y$10$4rDhLo7HKF.BEVx5UWKur.c1xS0EJV7ZHX5zpGkzeoVZs7I7xwDm2', 'ispu@ip.de', 'muz', '1995-01-11', 0),
(12, 3, 'Natálie Nová', 'recenzent2', '$2y$10$NYLtPDJO75IRKOzQ8kNFze930xCfN/jLVEofWfpoHsdVdummRS2yq', 'ips@ipsum.cz', 'zena', '1998-03-11', 0),
(13, 3, 'Monika Pomalá', 'recenzent3', '$2y$10$bjkEA1hFE7uM.TNJOiP3suIebI22CoH9iGtPFfdbGxO9/swyVXAS2', 'ips@ipsum.cz', 'zena', '1992-08-05', 0),
(14, 3, 'Karel Ostrý', 'recenzent4', '$2y$10$bqJiXMx2hb1pna9G3OWRc.y6xVoErLtyzO8ydRn8ph6wb5M7q5yYa', 'ips@ipsum.cz', 'muz', '2000-02-24', 0),
(15, 3, 'Lukáš Kořínek', 'recenzent5', '$2y$10$JG85pzGzgWcPcGn6mjHxgOxLSiSVOwMDos6wZ4kzh6Lz7dpjicu.K', 'ips@ipsum.cz', 'muz', '1995-01-18', 0),
(16, 4, 'Andrea plachá', 'autor4', '$2y$10$1LmGS8bRHC8SroY/YY/QeuSs74eOAkfvpVWg90UOQiR6n23z2UJtK', 'ipsum@ips.de', 'zena', '2003-08-08', 0),
(20, 1, 'Oldřich Hrozivý', 'superAdmin', '$2y$10$kKeRIvnEQ0NM1wO7eL1sHu5X/M1pVLPEwnSIgFIRutwFhMeSdiCfm', 'ipsum@ips.cz', 'muz', '1986-03-12', 0);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `CLANEK`
--
ALTER TABLE `CLANEK`
  ADD PRIMARY KEY (`idCLANEK`);

--
-- Indexy pro tabulku `CLANKY_AUTORA`
--
ALTER TABLE `CLANKY_AUTORA`
  ADD PRIMARY KEY (`idClankyAutora`),
  ADD KEY `id_uzivatel` (`id_uzivatel`),
  ADD KEY `idCLANEK` (`idCLANEK`);

--
-- Indexy pro tabulku `DOTAZ`
--
ALTER TABLE `DOTAZ`
  ADD PRIMARY KEY (`id_dotaz`);

--
-- Indexy pro tabulku `pravo`
--
ALTER TABLE `pravo`
  ADD PRIMARY KEY (`id_pravo`);

--
-- Indexy pro tabulku `UZIVATEL`
--
ALTER TABLE `UZIVATEL`
  ADD PRIMARY KEY (`id_uzivatel`),
  ADD KEY `fk_uzivatel_pravo_id_pravo_idx` (`id_pravo`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `CLANEK`
--
ALTER TABLE `CLANEK`
  MODIFY `idCLANEK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pro tabulku `CLANKY_AUTORA`
--
ALTER TABLE `CLANKY_AUTORA`
  MODIFY `idClankyAutora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT pro tabulku `DOTAZ`
--
ALTER TABLE `DOTAZ`
  MODIFY `id_dotaz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pro tabulku `pravo`
--
ALTER TABLE `pravo`
  MODIFY `id_pravo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `UZIVATEL`
--
ALTER TABLE `UZIVATEL`
  MODIFY `id_uzivatel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `CLANKY_AUTORA`
--
ALTER TABLE `CLANKY_AUTORA`
  ADD CONSTRAINT `CLANKY_AUTORA_ibfk_1` FOREIGN KEY (`id_uzivatel`) REFERENCES `UZIVATEL` (`id_uzivatel`),
  ADD CONSTRAINT `CLANKY_AUTORA_ibfk_2` FOREIGN KEY (`idCLANEK`) REFERENCES `CLANEK` (`idCLANEK`);

--
-- Omezení pro tabulku `UZIVATEL`
--
ALTER TABLE `UZIVATEL`
  ADD CONSTRAINT `fk_uzivatel_pravo_id_pravo` FOREIGN KEY (`id_pravo`) REFERENCES `pravo` (`id_pravo`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
