-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Hoszt: mysql.e-tiger.net
-- Létrehozás ideje: 2016. Már 29. 10:42
-- Szerver verzió: 5.5.47-MariaDB-log
-- PHP verzió: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `13417_w3suli`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `AlapAdatok`
--

DROP TABLE IF EXISTS `AlapAdatok`;
CREATE TABLE IF NOT EXISTS `AlapAdatok` (
`id` int(10) NOT NULL,
  `WebhelyNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Iskola` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Cim` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Telefon` varchar(20) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Stilus` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `AlapAdatok`
--

INSERT INTO `AlapAdatok` (`id`, `WebhelyNev`, `Iskola`, `Cim`, `Telefon`, `Stilus`) VALUES
(3, 'w3 Suli', 'Iskola neve', 'Cim', 'Telefon', 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Cikkek`
--

DROP TABLE IF EXISTS `Cikkek`;
CREATE TABLE IF NOT EXISTS `Cikkek` (
`id` int(10) NOT NULL,
  `CNev` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CTartalom` text COLLATE utf8_hungarian_ci NOT NULL,
  `CLathatosag` tinyint(4) NOT NULL DEFAULT '1',
  `CSzerzo` int(10) NOT NULL,
  `CSzerzoNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CLetrehozasTime` datetime NOT NULL,
  `CModositasTime` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=25 ;

--
-- A tábla adatainak kiíratása `Cikkek`
--

INSERT INTO `Cikkek` (`id`, `CNev`, `CLeiras`, `CTartalom`, `CLathatosag`, `CSzerzo`, `CSzerzoNev`, `CLetrehozasTime`, `CModositasTime`) VALUES
(1, 'TehetsÃ©gnap', '', '#1', 5, 10, 'tesztelek', '2016-02-21 08:08:21', '2016-03-25 15:37:03'),
(2, 'A W3 Suli projekt', '', 'A W3 Suli projekt cÃ©lja egy az iskolÃ¡k igÃ©nyeihez alkalmazkodÃ³ blog-motor elkÃ©szÃ­tÃ©se, amely lehetÅ‘vÃ© teszi, hogy tanulÃ³i Ã©s tanÃ¡ri csoportok egyarÃ¡nt bemutathassÃ¡k tevÃ©kenysÃ©gÃ¼ket egy iskola honlapjÃ¡n.\r\n<br><br>\r\nA projekt rÃ©sztvevÅ‘i a Budapesti MÅ±szaki SzakkÃ©pzÃ©si Centrum Egressy GÃ¡bor KÃ©t TanÃ­tÃ¡si NyelvÅ± SzakkÃ¶zÃ©piskolÃ¡jÃ¡nak tanulÃ³i.\r\n<br><br>\r\nA W3 Suli kÃ¶zÃ©piskolai oktatÃ¡shoz kÃ©szÃ¼l, de szabadon letÃ¶lthetÅ‘ Ã©s hasznÃ¡lhatÃ³ GNU General Public License v3 alatt, a forrÃ¡sra mutatÃ³ link elhelyezÃ©sÃ©vel az oldalon.\r\n<br><br>\r\n\r\n\r\nA projekt a <b>Budapesti MÅ±szaki SzakkÃ©pzÃ©si Centrum Egressy GÃ¡bor KÃ©t TanÃ­tÃ¡si NyelvÅ± SzakkÃ¶zÃ©piskolÃ¡ja NTP-MTTD-15-0194 pÃ¡lyÃ¡zata</b> keretÃ©ben valÃ³sul meg.\r\n\r\n<h3>A projekt tÃ¡mogatÃ³i</h3>\r\n#1 #2 #3', 7, 10, 'tesztelek', '2016-02-21 09:13:11', '2016-03-25 16:30:22'),
(3, '1. cikk', '', '1. cikk', 1, 10, 'tesztelek', '2016-02-21 13:29:29', '2016-02-21 13:29:29'),
(4, '2. cikk', '', '2. cikk', 1, 10, 'tesztelek', '2016-02-21 13:29:57', '2016-02-21 13:29:57'),
(5, '3. cikk', '', '3. cikk', 1, 10, 'tesztelek', '2016-02-21 13:30:14', '2016-02-21 13:30:14'),
(6, '4. cikk', '', '4. cikk', 1, 10, 'tesztelek', '2016-02-21 13:30:24', '2016-02-21 13:30:24'),
(7, '5. cikk', '', '5. cikk', 1, 10, 'tesztelek', '2016-02-21 13:30:35', '2016-02-21 13:30:35'),
(8, '6. cikk', '', '6. cikk', 1, 10, 'tesztelek', '2016-02-21 13:30:52', '2016-02-21 13:30:52'),
(9, '7. cikk', '', '7. cikk', 1, 10, 'tesztelek', '2016-02-21 13:31:01', '2016-02-21 13:31:01'),
(10, '8. cikk', '', '8. cikk', 1, 10, 'tesztelek', '2016-02-21 13:31:11', '2016-02-21 13:31:11'),
(11, '9. cikk', '', '9. cikk', 1, 10, 'tesztelek', '2016-02-21 13:31:20', '2016-02-21 13:31:20'),
(12, '10. cikk', '', '10. cikk', 1, 10, 'tesztelek', '2016-02-21 13:31:37', '2016-02-21 13:31:37'),
(13, '11. cikk', '', '11. cikk', 1, 10, 'tesztelek', '2016-02-21 13:31:47', '2016-02-21 13:31:47'),
(14, '12. cikk', '', '12. cikk', 1, 10, 'tesztelek', '2016-02-21 13:31:56', '2016-02-21 13:31:56'),
(15, '13. cikk', '', '13. cikk', 1, 10, 'tesztelek', '2016-02-21 13:32:10', '2016-02-21 13:32:10'),
(16, '14. cikk', '', '14. cikk', 1, 10, 'tesztelek', '2016-02-21 13:32:20', '2016-02-21 13:32:20'),
(17, '15. cikk', '', '15. cikk', 1, 10, 'tesztelek', '2016-02-21 13:32:32', '2016-02-21 13:32:32'),
(18, '16. cikk', '', '16. cikk', 1, 10, 'tesztelek', '2016-02-21 13:32:53', '2016-02-21 13:32:53'),
(19, '17. cikk', '', '17. cikk', 1, 10, 'tesztelek', '2016-02-21 13:33:04', '2016-02-21 13:33:04'),
(20, '18. cikk', '', '18. cikk', 1, 10, 'tesztelek', '2016-02-21 13:33:14', '2016-02-21 13:33:14'),
(21, '19. cikk', '', '19. cikk', 1, 10, 'tesztelek', '2016-02-21 13:33:23', '2016-02-21 13:33:23'),
(22, '20. cikk', '', '20. cikk', 1, 10, 'tesztelek', '2016-02-21 13:33:37', '2016-02-21 13:33:37'),
(23, '21. cikk', '', '21. cikk', 1, 10, 'tesztelek', '2016-02-21 13:33:46', '2016-02-21 13:33:46'),
(24, '22. cikk', '', '22. cikk', 1, 10, 'tesztelek', '2016-02-21 13:33:58', '2016-02-21 13:33:58');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `CikkKepek`
--

DROP TABLE IF EXISTS `CikkKepek`;
CREATE TABLE IF NOT EXISTS `CikkKepek` (
`id` int(10) NOT NULL,
  `Cid` int(10) NOT NULL,
  `KFile` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KSzelesseg` smallint(6) NOT NULL DEFAULT '-1',
  `KMagassag` smallint(6) NOT NULL DEFAULT '-1',
  `KStilus` smallint(6) NOT NULL DEFAULT '0',
  `KSorszam` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=34 ;

--
-- A tábla adatainak kiíratása `CikkKepek`
--

INSERT INTO `CikkKepek` (`id`, `Cid`, `KFile`, `KNev`, `KLeiras`, `KSzelesseg`, `KMagassag`, `KStilus`, `KSorszam`) VALUES
(19, 25, 'cikk_25_0.jpg', '', '', 0, 0, 0, 0),
(20, 25, 'cikk_25_1.jpg', '', '', 0, 0, 0, 0),
(21, 25, 'cikk_25_2.jpg', '', '', 0, 0, 0, 0),
(22, 25, 'cikk_25_3.jpg', '', '', 0, 0, 0, 0),
(23, 25, 'cikk_25_4.jpg', '', '', 0, 0, 0, 0),
(24, 25, 'cikk_25_5.jpg', '', '', 0, 0, 0, 0),
(25, 25, 'cikk_25_6.jpg', '', '', 0, 0, 0, 0),
(26, 25, 'cikk_25_7.jpg', '', '', 0, 0, 0, 0),
(27, 25, 'cikk_25_8.jpg', '', '', 0, 0, 0, 0),
(28, 25, 'cikk_25_9.jpg', '', '', 0, 0, 0, 0),
(29, 1, 'cikk_1_0.jpg', 'TehetsÃ©gnap plakÃ¡t', '', 0, 0, 0, 0),
(30, 2, 'cikk_2_0.jpg', 'Nemzeti TehetsÃ©gsegÃ­tÅ‘ Program', '', 0, 0, 2, 1),
(31, 2, 'cikk_2_1.jpg', 'Emberei ErÅ‘forrÃ¡s TÃ¡mogatÃ¡skezelÅ‘', '', 0, 0, 2, 2),
(33, 2, 'cikk_2_2.jpg', 'Emberi ErÅ‘forrÃ¡sok MinisztÃ©riuma', '', 0, 0, 2, 3);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `FCsoportTagok`
--

DROP TABLE IF EXISTS `FCsoportTagok`;
CREATE TABLE IF NOT EXISTS `FCsoportTagok` (
`id` int(10) NOT NULL,
  `Fid` int(10) NOT NULL,
  `CSid` int(10) NOT NULL,
  `KapcsTip` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=7 ;

--
-- A tábla adatainak kiíratása `FCsoportTagok`
--

INSERT INTO `FCsoportTagok` (`id`, `Fid`, `CSid`, `KapcsTip`) VALUES
(6, 15, 1, 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `FelhasznaloCsoport`
--

DROP TABLE IF EXISTS `FelhasznaloCsoport`;
CREATE TABLE IF NOT EXISTS `FelhasznaloCsoport` (
`id` int(10) NOT NULL,
  `CsNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CsLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=6 ;

--
-- A tábla adatainak kiíratása `FelhasznaloCsoport`
--

INSERT INTO `FelhasznaloCsoport` (`id`, `CsNev`, `CsLeiras`) VALUES
(1, '13.I', '13.I osztÃ¡ly'),
(2, '10.K', '10.K osztÃ¡ly'),
(3, '11.A', '11.A osztÃ¡ly'),
(4, 'tanÃ¡rok', 'tanÃ¡r csoport'),
(5, 'rendszergazdÃ¡k', 'RendszergazdÃ¡k csoportja');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Felhasznalok`
--

DROP TABLE IF EXISTS `Felhasznalok`;
CREATE TABLE IF NOT EXISTS `Felhasznalok` (
`id` int(10) NOT NULL,
  `FNev` varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FFNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FJelszo` varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FEmail` varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FSzint` tinyint(4) NOT NULL DEFAULT '0',
  `FSzerep` varchar(30) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FKep` varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=16 ;

--
-- A tábla adatainak kiíratása `Felhasznalok`
--

INSERT INTO `Felhasznalok` (`id`, `FNev`, `FFNev`, `FJelszo`, `FEmail`, `FSzint`, `FSzerep`, `FKep`) VALUES
(4, 'Rendszergazda1', 'Rendszergazda', 'Rendszergazda', 'rendszergazda@rendszergazda.hu', 5, 'tanÃ¡r', ''),
(10, 'tesztelek', 'tesztelek', '209b6f880c9b7b1c4d5fc8dc4a16441c', 'r@r.hu', 5, 'tanÃƒÂ¡r', ''),
(15, 'TÃ³th-KovÃ¡cs GellÃ©rt', 'tkgellert', 'fd19fc4285310dd91aa4ef2b1bcd1524', 'gellert.tothkovacs@egressy.info', 2, 'tanulÃ³', '');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `FoMenuLink`
--

DROP TABLE IF EXISTS `FoMenuLink`;
CREATE TABLE IF NOT EXISTS `FoMenuLink` (
`id` int(11) NOT NULL,
  `LNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `LURL` varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
  `LPrioritas` tinyint(4) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=12 ;

--
-- A tábla adatainak kiíratása `FoMenuLink`
--

INSERT INTO `FoMenuLink` (`id`, `LNev`, `LURL`, `LPrioritas`) VALUES
(1, 'Ã“rarend', 'http://www.egressy.eu/orarend/', 9),
(2, 'HelyettesÃ­tÃ©s', 'http://www.egressy.eu/helyettesites/', 8),
(3, 'E-ellenÃ¶rzÅ‘', 'http://egressy.e-naplo.hu/xkirpub/', 6),
(4, 'E-naplÃ³', 'http://egressy.e-naplo.hu/xkirpda/', 7),
(5, '', '', 0),
(6, '', '', 0),
(7, '', '', 0),
(8, '', '', 0),
(9, '', '', 0),
(10, '', '', 0),
(11, '', '', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `KiegTartalom`
--

DROP TABLE IF EXISTS `KiegTartalom`;
CREATE TABLE IF NOT EXISTS `KiegTartalom` (
`id` int(10) NOT NULL,
  `KiegTNev` varchar(125) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KiegTTartalom` text COLLATE utf8_hungarian_ci NOT NULL,
  `KiegTPrioritas` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=11 ;

--
-- A tábla adatainak kiíratása `KiegTartalom`
--

INSERT INTO `KiegTartalom` (`id`, `KiegTNev`, `KiegTTartalom`, `KiegTPrioritas`) VALUES
(1, '', '<a href="./letolt/w3_suli_kornel.pptx">\r\n<img src="./img/ikonok/kornel_eloadas.png" width="300">\r\n</a>', 6),
(2, 'Munkaterv', '<iframe src="https://www.google.com/calendar/embed?showPrint=0&showTabs=0&showCalendars=0&showTz=0&mode=AGENDA&height=200&wkst=2&hl=hu&bgcolor=%23666666&src=524n3edasspkp56dhcbq9vf8e0%40group.calendar.google.com&color=%ff000000&ctz=Europe%2FBudapest" style=" border-width:0 " width="300" height="300" frameborder="0" scrolling="no"></iframe>', 1),
(3, '', '', 2),
(4, 'TovÃ¡bbi kiemelt informÃ¡ciÃ³k', 'ÃrvÃ­ztÅ±rÅ‘ fÃºrÃ³gÃ©p', 0),
(5, '', '', 0),
(6, '', '', 0),
(7, '', '', 0),
(8, '', '', 0),
(9, '', '', 0),
(10, '', '', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `MenuPlusz`
--

DROP TABLE IF EXISTS `MenuPlusz`;
CREATE TABLE IF NOT EXISTS `MenuPlusz` (
`id` int(10) NOT NULL,
  `MenuPlNev` varchar(125) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `MenuPlTartalom` text COLLATE utf8_hungarian_ci NOT NULL,
  `MenuPlPrioritas` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=11 ;

--
-- A tábla adatainak kiíratása `MenuPlusz`
--

INSERT INTO `MenuPlusz` (`id`, `MenuPlNev`, `MenuPlTartalom`, `MenuPlPrioritas`) VALUES
(1, 'A projekt tÃ¡mogatÃ³i: ', '<img src="./img/ikonok/eem_logo.jpg">', 9),
(2, '', '<img src="./img/ikonok/eet_logo.jpg">', 8),
(3, '', '<img src="./img/ikonok/ntp_logo.jpg">', 1),
(4, '', '', 0),
(5, '', '', 0),
(6, '', '', 0),
(7, '', '', 0),
(8, '', '', 0),
(9, '', '', 0),
(10, '', '', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `OLathatosag`
--

DROP TABLE IF EXISTS `OLathatosag`;
CREATE TABLE IF NOT EXISTS `OLathatosag` (
`id` int(11) NOT NULL,
  `Oid` int(11) NOT NULL,
  `CSid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Oldalak`
--

DROP TABLE IF EXISTS `Oldalak`;
CREATE TABLE IF NOT EXISTS `Oldalak` (
`id` int(10) NOT NULL,
  `ONev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `OUrl` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `OLathatosag` tinyint(4) NOT NULL DEFAULT '0',
  `OPrioritas` smallint(6) NOT NULL DEFAULT '0',
  `OLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `OKulcsszavak` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `OSzuloId` int(10) NOT NULL DEFAULT '0',
  `OTipus` tinyint(4) NOT NULL DEFAULT '1',
  `OTartalom` text COLLATE utf8_hungarian_ci NOT NULL,
  `OImgDir` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `OImg` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=96 ;

--
-- A tábla adatainak kiíratása `Oldalak`
--

INSERT INTO `Oldalak` (`id`, `ONev`, `OUrl`, `OLathatosag`, `OPrioritas`, `OLeiras`, `OKulcsszavak`, `OSzuloId`, `OTipus`, `OTartalom`, `OImgDir`, `OImg`) VALUES
(1, 'KezdÅ‘lap', 'Kezdolap', 1, 1, 'A W3 Suli projekt cÃ©lja egy az iskolÃ¡k igÃ©nyeihez alkalmazkodÃ³ blog-motor elkÃ©szÃ­tÃ©se, amely lehetÅ‘vÃ© teszi, hogy tanulÃ³i Ã©s tanÃ¡ri csoportok egyarÃ¡nt bemutathassÃ¡k tevÃ©kenysÃ©gÃ¼ket egy iskola honlapjÃ¡n.', 'KezdÅ‘lap kulcsszavai', 0, 1, '', '', ''),
(12, 'BejelentkezÃ©s', 'bejelentkezes', 1, 1, 'BejelentkezÃ©s leÃ­rÃ¡sa', 'BejelentkezÃ©s kulcsszavai', 1, 10, 'BejelentkezÃ©s tartalma', '', ''),
(13, 'KijelentkezÃ©s', 'kijelentkezes', 1, 1, 'KijelentkezÃ©s leÃ­rÃ¡sa', 'KijelentkezÃ©s kulcsszavai', 1, 11, 'KijelentkezÃ©s tartalma', '', ''),
(14, 'RegisztrÃ¡ciÃ³', 'regisztracio', 1, 1, 'RegisztrÃ¡ciÃ³ leÃ­rÃ¡sa', 'RegisztrÃ¡ciÃ³ kulcsszavai', 1, 12, 'RegisztrÃ¡ciÃ³ tartalma', '', ''),
(15, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se', 'felhasznalo_torlese', 1, 1, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se leÃ­rÃ¡sa', 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se kulcsszavai', 1, 13, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se tartalma', '', ''),
(16, 'FelhasznÃ¡lÃ³ lista', 'felhasznalo_lista', 1, 1, 'FelhasznÃ¡lÃ³ lista leÃ­rÃ¡sa', 'FelhasznÃ¡lÃ³ lista kulcsszavai', 1, 14, 'FelhasznÃ¡lÃ³ lista tartalma', '', ''),
(17, 'AdatmÃ³dosÃ­tÃ¡s', 'adatmodositas', 1, 1, 'AdatmÃ³dosÃ­tÃ¡s leÃ­rÃ¡sa', 'AdatmÃ³dosÃ­tÃ¡s kulcsszavai', 1, 15, 'AdatmÃ³dosÃ­tÃ¡s tartalma', '', ''),
(18, 'JelszÃ³modosÃ­tÃ¡s', 'jelszomodositas', 1, 1, 'JelszÃ³modosÃ­tÃ¡s leÃ­rÃ¡sa', 'JelszÃ³modosÃ­tÃ¡s kulcsszavai', 1, 16, 'JelszÃ³modosÃ­tÃ¡s tartalma', '', ''),
(19, 'AlapbeÃ¡llÃ­tÃ¡sok', 'alapbeallitasok', 1, 1, 'AlapbeÃ¡llÃ­tÃ¡sok leÃ­rÃ¡sa', 'AlapbeÃ¡llÃ­tÃ¡sok kulcsszavai', -1, 51, 'AlapbeÃ¡llÃ­tÃ¡sok tartalma', '', ''),
(20, 'ttt', 'ttt', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', -1, 1, '<section> <article> <img src="img/Shrek_fierce.jpg" alt="leÃ­rÃ¡s" title="1 cikk cÃ­me" style="float:left;"> <h2>1 cikk cÃ­me</h2> "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. <br><br> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." </article> <article> <img src="img/Shrek_1.jpg" alt="leÃ­rÃ¡s" title="2 cikk cÃ­me" style="float:left;"> <h2>2. cikk cÃ­me</h2> "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br><br> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." </article> </section>', '', ''),
(22, 'OsztÃ¡lyok', 'Osztalyok', 1, 48, 'A W3 Suli blog-motort hasznÃ¡lÃ³ iskolÃ¡k minden osztÃ¡lya sajÃ¡t weboldalakkal rendelkezhet.', 'Az oldal kulcsszavai', 1, 2, 'A W3 Suli blog-motort hasznÃ¡lÃ³ iskolÃ¡k minden osztÃ¡lya sajÃ¡t weboldalakkal rendelkezhet. Az osztÃ¡lyok weboldalai tovÃ¡bbi tematikus aloldalak hozhatÃ³k lÃ©tre egy kirÃ¡ndulÃ¡shoz, projekthez vagy tetszÅ‘leges esemÃ©nyhez.', '', ''),
(23, '9.H', '9_H', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, '<section> <article> <img src="img/Shrek_fierce.jpg" alt="leÃ­rÃ¡s" title="1 cikk cÃ­me" style="float:left;"> <h2>1 cikk cÃ­me</h2> "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. <br><br> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." </article> <article> <img src="img/Shrek_1.jpg" alt="leÃ­rÃ¡s" title="2 cikk cÃ­me" style="float:left;"> <h2>2. cikk cÃ­me</h2> "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br><br> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." </article> </section>', '', ''),
(27, '13.I', '13_I', 1, 99, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma jfcgfgjcf', '', ''),
(28, '9.A', '9_A', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', 'w3333.jpg'),
(31, 'Iskola dokumentumai', 'Iskola_dokumentumai', 1, 50, 'A W3 Suli blog-motort hasznÃ¡lÃ³ iskolÃ¡k egyszerÅ±en kÃ¶zzÃ©tehetik dokumentumaikat HTML, DOC, PDF.... formÃ¡tumban.', 'Az oldal kulcsszavai', 1, 2, 'A W3 Suli blog-motort hasznÃ¡lÃ³ iskolÃ¡k egyszerÅ±en kÃ¶zzÃ©tehetik dokumentumaikat HTML, DOC, PDF.... formÃ¡tumban.', '', ''),
(35, 'Kieg. tartalom', 'kiegeszito_tartalom', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 1, 52, 'Az oldal tartalma', '', ''),
(39, 'FÅ‘menÃ¼ linkek beÃ¡llÃ­tÃ¡sa', 'Fomenu_linkek_beallitasa', 1, 1, 'FÅ‘menÃ¼ linkek beÃ¡llÃ­tÃ¡sa', 'Az oldal kulcsszavai', 1, 53, '', '', ''),
(40, 'A blog-motor bemutatÃ¡sa', 'A_blog-motor_bemutatasa', 1, 100, 'A W3 Suli nyÃ­lt forrÃ¡skÃ³rÃº blog-motor szabadon letÃ¶lthetÅ‘, mÃ³dosÃ­thatÃ³ Ã©s hasznÃ¡lhatÃ³ GNU General Public License v3 alatt, a forrÃ¡sra mutatÃ³ link elhelyezÃ©sÃ©vel az oldalon.', 'W3 Suli bemutatÃ¡sa', 1, 1, 'A W3 Suli nyÃ­lt forrÃ¡skÃ³rÃº blog-motor szabadon letÃ¶lthetÅ‘, mÃ³dosÃ­thatÃ³ Ã©s hasznÃ¡lhatÃ³ GNU General Public License v3 alatt, a forrÃ¡sra mutatÃ³ link elhelyezÃ©sÃ©vel az oldalon.', '', ''),
(41, 'FelhasznÃ¡lÃ³i csoportok', 'Felhasznaloi_csoportok', 1, 1, 'Oldal leÃ­rÃ¡sa', 'Oldal kulcsszavai', 1, 20, 'Oldal tartalma', '', ''),
(42, 'OldaltÃ©rkÃ©p', 'oldalterkep', 1, 1, 'OldaltÃ©rkÃ©p leÃ­rÃ¡sa', 'OldaltÃ©rkÃ©p kulcsszavai', 1, 21, 'AlapbeÃ¡llÃ­tÃ¡sok tartalma', '', ''),
(43, 'MenÅ± plusz infÃ³k', 'menuplusz', 1, 1, 'MenÅ± plusz infÃ³k leÃ­rÃ¡sa', 'MenÅ± plusz infÃ³k kulcsszavai', 1, 54, 'MenÅ± plusz infÃ³k tartalma', '', ''),
(44, 'A W3 Suli projekt cÃ©lja', 'A_W3_Suli_projekt_celja', 1, 100, 'A W3 Suli projekt cÃ©lja segÃ­tsÃ©get jelenteni iskolai webmesterek szÃ¡mÃ¡ra az intÃ©zmÃ©ny honlapjÃ¡nak elkÃ©szÃ­tÃ©sÃ©hez.', 'A W3 Suli projekt cÃ©lja', 40, 2, 'A W3 Suli projekt cÃ©lja segÃ­tsÃ©get jelenteni iskolai webmesterek szÃ¡mÃ¡ra az intÃ©zmÃ©ny honlapjÃ¡nak elkÃ©szÃ­tÃ©sÃ©hez.', '', ''),
(45, 'FelhasznÃ¡lÃ¡si feltÃ©telek', 'Felhasznalasi_feltetelek', 1, 99, 'A W3 Suli projekt sorÃ¡n kÃ©szÃ¼lÅ‘ blog-motor szabadon letÃ¶lthetÅ‘, hasznÃ¡lhatÃ³, mÃ³dosÃ­thatÃ³ Ã©s tovÃ¡bbadhatÃ³.', 'FelhasznÃ¡lÃ¡si feltÃ©telek', 40, 2, 'A W3 Suli projekt sorÃ¡n kÃ©szÃ¼lÅ‘ blog-motor szabadon letÃ¶lthetÅ‘, hasznÃ¡lhatÃ³, mÃ³dosÃ­thatÃ³ Ã©s tovÃ¡bbadhatÃ³. CsupÃ¡n erre a webhelyre mutatÃ³ linket kÃ©rÃ¼nk elhelyezni minden W3 Suli blog-motort hasznÃ¡lÃ³ webhelyrÅ‘l.', '', ''),
(46, 'A blog-motor letÃ¶ltÃ©se', 'A_blog-motor_letoltese', 1, 1, 'A W3 Suli projekt forrÃ¡skÃ³dja megtekinthetÅ‘ Ã©s letÃ¶lthetÅ‘ a github.com/gtportal/w3suli oldalon.', 'Az oldal kulcsszavai', 40, 2, 'A W3 Suli projekt forrÃ¡skÃ³dja megtekinthetÅ‘ Ã©s letÃ¶lthetÅ‘ a https://github.com/gtportal/w3suli oldalon.', '', ''),
(47, 'A blog-motor telepÃ­tÃ©se', 'A_blog-motor_telepitese', 1, 1, 'A blog-motorhoz telepÃ­tÅ‘ kÃ©szÃ¼l. Itt lesz elÃ©rhetÅ‘.', 'Az oldal kulcsszavai', 40, 2, 'A blog-motorhoz telepÃ­tÅ‘ kÃ©szÃ¼l. Itt lesz elÃ©rhetÅ‘.', '', ''),
(48, '9.K', '9_K', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(50, '9.Ny', '9_Ny', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(51, '10.A', '10_A', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(52, '10.C', '10_C', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(53, '10.H', '10_H', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(54, '10.K', '10_K', 1, 98, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(55, '11.A', '11_A', 1, 90, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(56, '11.C', '11_C', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(58, '12.A', '12_A', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(59, '12.C', '12_C', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(61, '12.H', '12_H', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(62, '11.K', '11_K', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(63, '11.H', '11_H', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(64, '13.A', '13_A', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(65, '13.B', '13_B', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(66, '13.C', '13_C', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(67, '13.D', '13_D', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(68, '13.E', '13_E', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(69, '13.G', '13_G', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(70, '13.H', '13_H', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(71, 'SzallagavatÃ³', 'Szallagavato', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 27, 1, 'Az oldal tartalma', '', ''),
(72, 'W3Suli projekt', 'W3Suli_projekt', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 27, 1, 'Az oldal tartalma', '', ''),
(73, 'CSS-ek kÃ©szÃ­tÃ©se', 'CSS-ek_keszitese', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 72, 1, 'Az oldal tartalma', '', ''),
(74, 'TesztelÃ¼nk', 'Tesztelunk', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 27, 1, 'Az oldal tartalma', '', ''),
(75, 'KÃ©pzÃ©sek', 'Kepzesek', 1, 49, 'A W3 Suli blog-motort hasznÃ¡lÃ³ iskolÃ¡k rÃ©szletesen bemutathatjÃ¡k kÃ©pzÃ©seiket.', 'Az oldal kulcsszavai', 1, 1, 'A W3 Suli blog-motort hasznÃ¡lÃ³ iskolÃ¡k rÃ©szletesen bemutathatjÃ¡k kÃ©pzÃ©seiket.', '', ''),
(76, 'TanÃ¡raink', 'Tanaraink', 1, 47, 'A W3 Suli blog-motort hasznÃ¡lÃ³ iskolÃ¡k pedagÃ³gusai HTML ismeretek nÃ©lkÃ¼l is kÃ¶zzÃ©tehetnek sajÃ¡t weboldalakat.', 'Az oldal kulcsszavai', 1, 1, 'A W3 Suli blog-motort hasznÃ¡lÃ³ iskolÃ¡k pedagÃ³gusai HTML ismeretek nÃ©lkÃ¼l is kÃ¶zzÃ©tehetnek sajÃ¡t weboldalakat, amelyen megoszthatnak tananyagokat, informÃ¡ciÃ³kat, Ã©lmÃ©nyeket...\r\n\r\nA tanÃ¡rok weboldalai munkakÃ¶zÃ¶ssÃ©gek alÃ¡ rendelhetÅ‘k.', '', ''),
(77, 'A W3 Suli projekt rÃ©sztvevÅ‘i', 'A_W3_Suli_projekt_resztvevoi', 1, 99, 'A W3 Suli nyÃ­lt forrÃ¡skÃ³rÃº blog-motor kÃ©szÃ­tÅ‘i a BMSZC Egressy GÃ¡bor KÃ©t TanÃ­tÃ¡si NyelvÅ± SzakkÃ¶zÃ©piskolÃ¡jÃ¡nak tanulÃ³i.', 'blog-motor kÃ©szÃ­tÅ‘i', 1, 1, 'A W3 Suli nyÃ­lt forrÃ¡skÃ³rÃº blog-motor kÃ©szÃ­tÅ‘i a BMSZC Egressy GÃ¡bor KÃ©t TanÃ­tÃ¡si NyelvÅ± SzakkÃ¶zÃ©piskolÃ¡jÃ¡nak tanulÃ³i.', '', ''),
(78, 'Teszt kategÃ³ria', 'Teszt_kategoria', 1, 0, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 1, 1, 'Az oldal tartalma', '', ''),
(81, 't45', 't45', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 78, 1, 'Az oldal tartalma', '', ''),
(82, 't45aaa', 't45aaa', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 81, 1, 'Az oldal tartalma', '', ''),
(86, 'ccccc', 'ccccc', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 78, 1, 'Az oldal tartalma', '', ''),
(87, 'ccccc4565', 'ccccc4565', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 86, 1, 'Az oldal tartalma r', '', ''),
(88, 'elektronikai munkakÃ¶zÃ¶ssÃ©g', 'elektronikai_munkakozosseg', 1, 6, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 76, 1, 'Az oldal tartalma', '', ''),
(90, 'informatikai munkakÃ¶zÃ¶ssÃ©g', 'informatikai_munkakozosseg', 1, 7, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 76, 1, 'Az oldal tartalma', '', ''),
(91, 'humÃ¡n munkakÃ¶zÃ¶ssÃ©g', 'human_munkakozosseg', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 76, 1, 'Az oldal tartalma', '', ''),
(92, 'reÃ¡l munkakÃ¶zÃ¶ssÃ©g', 'real_munkakozosseg', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 76, 1, 'Az oldal tartalma', '', ''),
(93, 'idegennyelvi munkakÃ¶zÃ¶ssÃ©g', 'idegennyelvi_munkakozosseg', 1, 3, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 76, 1, 'Az oldal tartalma', '', ''),
(94, 'az iskola vezetÅ‘sÃ©ge', 'az_iskola_vezetosege', 1, 9, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 76, 1, 'Az oldal tartalma', '', ''),
(95, 'osztÃ¡lyfÅ‘nÃ¶ki munkakÃ¶zÃ¶ssÃ©g', 'osztalyfonoki_munkakozosseg', 1, 8, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 76, 1, 'Az oldal tartalma', '', '');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `OldalCikkei`
--

DROP TABLE IF EXISTS `OldalCikkei`;
CREATE TABLE IF NOT EXISTS `OldalCikkei` (
`id` int(10) NOT NULL,
  `Oid` int(10) NOT NULL,
  `Cid` int(10) NOT NULL,
  `CPrioritas` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=25 ;

--
-- A tábla adatainak kiíratása `OldalCikkei`
--

INSERT INTO `OldalCikkei` (`id`, `Oid`, `Cid`, `CPrioritas`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 78, 3, 1),
(4, 78, 4, 1),
(5, 78, 5, 1),
(6, 78, 6, 1),
(7, 78, 7, 1),
(8, 78, 8, 1),
(9, 78, 9, 1),
(10, 78, 10, 1),
(11, 78, 11, 1),
(12, 78, 12, 1),
(13, 78, 13, 1),
(14, 78, 14, 1),
(15, 78, 15, 1),
(16, 78, 16, 1),
(17, 78, 17, 1),
(18, 78, 18, 1),
(19, 78, 19, 1),
(20, 78, 20, 1),
(21, 78, 21, 1),
(22, 78, 22, 1),
(23, 78, 23, 1),
(24, 78, 24, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `OldalKepek`
--

DROP TABLE IF EXISTS `OldalKepek`;
CREATE TABLE IF NOT EXISTS `OldalKepek` (
`id` int(10) NOT NULL,
  `Oid` int(10) NOT NULL,
  `KFile` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KSzelesseg` smallint(6) NOT NULL DEFAULT '-1',
  `KMagassag` smallint(6) NOT NULL DEFAULT '-1',
  `KStilus` smallint(6) NOT NULL DEFAULT '0',
  `KSorszam` tinyint(4) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=31 ;

--
-- A tábla adatainak kiíratása `OldalKepek`
--

INSERT INTO `OldalKepek` (`id`, `Oid`, `KFile`, `KNev`, `KLeiras`, `KSzelesseg`, `KMagassag`, `KStilus`, `KSorszam`) VALUES
(6, 1, 'kezdolap_3.png', 'rrrrrrrrrrrr', 'fffffffffffffffffffffff', 0, 0, 0, 0),
(8, 1, 'kezdolap_4.png', 'hjjhkjgv', 'ghkgvhgv', 0, 0, 0, 0),
(10, 1, 'kezdolap_5.png', 'rrrrrrrrrrrrr', 'rrrrrrrrrrrrrrrr', 0, 0, 0, 0),
(13, 1, 'kezdolap_2.jpg', 'reeeeeeeeeeee', 'wwwwwwwwwwwww', 0, 0, 0, 0),
(14, 1, 'kezdolap_6.png', '', '', 0, 0, 0, 0),
(25, 1, 'kezdolap_0.jpeg', '', '', 0, 0, 0, 0),
(28, 1, 'kezdolap_8.jpg', '', '', 0, 0, 0, 0),
(29, 1, 'kezdolap_9.jpg', '', '', 0, 0, 0, 0),
(30, 1, 'kezdolap_1.jpg', '', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `OModeratorok`
--

DROP TABLE IF EXISTS `OModeratorok`;
CREATE TABLE IF NOT EXISTS `OModeratorok` (
`id` int(10) NOT NULL,
  `Oid` int(10) NOT NULL,
  `Fid` int(10) NOT NULL DEFAULT '-1',
  `CSid` int(10) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `rangsor`
--

DROP TABLE IF EXISTS `rangsor`;
CREATE TABLE IF NOT EXISTS `rangsor` (
`id` int(11) NOT NULL,
  `nev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
  `kod` varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
  `elektro` varchar(40) COLLATE utf8_hungarian_ci NOT NULL,
  `info` varchar(40) COLLATE utf8_hungarian_ci NOT NULL,
  `gepesz` varchar(40) COLLATE utf8_hungarian_ci NOT NULL,
  `kettan` varchar(40) COLLATE utf8_hungarian_ci NOT NULL,
  `oktat` varchar(40) COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AlapAdatok`
--
ALTER TABLE `AlapAdatok`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Cikkek`
--
ALTER TABLE `Cikkek`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `CikkKepek`
--
ALTER TABLE `CikkKepek`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `FCsoportTagok`
--
ALTER TABLE `FCsoportTagok`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `FelhasznaloCsoport`
--
ALTER TABLE `FelhasznaloCsoport`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Felhasznalok`
--
ALTER TABLE `Felhasznalok`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `FoMenuLink`
--
ALTER TABLE `FoMenuLink`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `KiegTartalom`
--
ALTER TABLE `KiegTartalom`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `MenuPlusz`
--
ALTER TABLE `MenuPlusz`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `OLathatosag`
--
ALTER TABLE `OLathatosag`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Oldalak`
--
ALTER TABLE `Oldalak`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `OldalCikkei`
--
ALTER TABLE `OldalCikkei`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `OldalKepek`
--
ALTER TABLE `OldalKepek`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `OModeratorok`
--
ALTER TABLE `OModeratorok`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `rangsor`
--
ALTER TABLE `rangsor`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AlapAdatok`
--
ALTER TABLE `AlapAdatok`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Cikkek`
--
ALTER TABLE `Cikkek`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `CikkKepek`
--
ALTER TABLE `CikkKepek`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `FCsoportTagok`
--
ALTER TABLE `FCsoportTagok`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `FelhasznaloCsoport`
--
ALTER TABLE `FelhasznaloCsoport`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Felhasznalok`
--
ALTER TABLE `Felhasznalok`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `FoMenuLink`
--
ALTER TABLE `FoMenuLink`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `KiegTartalom`
--
ALTER TABLE `KiegTartalom`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `MenuPlusz`
--
ALTER TABLE `MenuPlusz`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `OLathatosag`
--
ALTER TABLE `OLathatosag`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Oldalak`
--
ALTER TABLE `Oldalak`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=96;
--
-- AUTO_INCREMENT for table `OldalCikkei`
--
ALTER TABLE `OldalCikkei`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `OldalKepek`
--
ALTER TABLE `OldalKepek`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `OModeratorok`
--
ALTER TABLE `OModeratorok`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `rangsor`
--
ALTER TABLE `rangsor`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;