-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2016. Jan 29. 13:03
-- Szerver verzió: 5.5.47-0ubuntu0.14.04.1
-- PHP verzió: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `w3suli`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `AlapAdatok`
--

DROP TABLE IF EXISTS `AlapAdatok`;
CREATE TABLE IF NOT EXISTS `AlapAdatok` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `WebhelyNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Iskola` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Cim` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Telefon` varchar(20) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Stilus` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `AlapAdatok`
--

INSERT INTO `AlapAdatok` (`id`, `WebhelyNev`, `Iskola`, `Cim`, `Telefon`, `Stilus`) VALUES
(3, 'WebhelyNev012', 'Iskola123', 'Cim234', 'Telefon345', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Cikkek`
--

DROP TABLE IF EXISTS `Cikkek`;
CREATE TABLE IF NOT EXISTS `Cikkek` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `CNev` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CTartalom` text COLLATE utf8_hungarian_ci NOT NULL,
  `CLathatosag` tinyint(4) NOT NULL DEFAULT '1',
  `CSzerzo` int(10) NOT NULL,
  `CSzerzoNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CLetrehozasTime` datetime NOT NULL,
  `CModositasTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `CikkKepek`
--

DROP TABLE IF EXISTS `CikkKepek`;
CREATE TABLE IF NOT EXISTS `CikkKepek` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Cid` int(10) NOT NULL,
  `KFile` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KSzelesseg` smallint(6) NOT NULL DEFAULT '-1',
  `KMagassag` smallint(6) NOT NULL DEFAULT '-1',
  `KStilus` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `FCsoportTagok`
--

DROP TABLE IF EXISTS `FCsoportTagok`;
CREATE TABLE IF NOT EXISTS `FCsoportTagok` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Fid` int(10) NOT NULL,
  `CSid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `FelhasznaloCsoport`
--

DROP TABLE IF EXISTS `FelhasznaloCsoport`;
CREATE TABLE IF NOT EXISTS `FelhasznaloCsoport` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `CsNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CsLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Felhasznalok`
--

DROP TABLE IF EXISTS `Felhasznalok`;
CREATE TABLE IF NOT EXISTS `Felhasznalok` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `FNev` varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FFNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FJelszo` varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FEmail` varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FSzint` tinyint(4) NOT NULL DEFAULT '0',
  `FSzerep` varchar(30) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FKep` varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `Felhasznalok`
--

INSERT INTO `Felhasznalok` (`id`, `FNev`, `FFNev`, `FJelszo`, `FEmail`, `FSzint`, `FSzerep`, `FKep`) VALUES
(3, 'root', 'Root', 'root', 'root@root.hu', 5, 'Root', 'root.jpg');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `KiegTartalom`
--

DROP TABLE IF EXISTS `KiegTartalom`;
CREATE TABLE IF NOT EXISTS `KiegTartalom` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `KiegTNev` varchar(125) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KiegTTartalom` text COLLATE utf8_hungarian_ci NOT NULL,
  `KiegTPrioritas` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Oldalak`
--

DROP TABLE IF EXISTS `Oldalak`;
CREATE TABLE IF NOT EXISTS `Oldalak` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `OImg` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=36 ;

--
-- A tábla adatainak kiíratása `Oldalak`
--

INSERT INTO `Oldalak` (`id`, `ONev`, `OUrl`, `OLathatosag`, `OPrioritas`, `OLeiras`, `OKulcsszavak`, `OSzuloId`, `OTipus`, `OTartalom`, `OImgDir`, `OImg`) VALUES
(1, 'KezdÅ‘lap', 'kezdolap', 1, 1, 'KezdÅ‘lap leÃ­rÃ¡sa', 'KezdÅ‘lap kulcsszavai', 0, 0, 'KezdÅ‘lap tartalma', '', ''),
(12, 'BejelentkezÃ©s', 'bejelentkezes', 1, 1, 'BejelentkezÃ©s leÃ­rÃ¡sa', 'BejelentkezÃ©s kulcsszavai', 1, 10, 'BejelentkezÃ©s tartalma', '', ''),
(13, 'KijelentkezÃ©s', 'kijelentkezes', 1, 1, 'KijelentkezÃ©s leÃ­rÃ¡sa', 'KijelentkezÃ©s kulcsszavai', 1, 11, 'KijelentkezÃ©s tartalma', '', ''),
(14, 'RegisztrÃ¡ciÃ³', 'regisztracio', 1, 1, 'RegisztrÃ¡ciÃ³ leÃ­rÃ¡sa', 'RegisztrÃ¡ciÃ³ kulcsszavai', 1, 12, 'RegisztrÃ¡ciÃ³ tartalma', '', ''),
(15, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se', 'felhasznalo_torlese', 1, 1, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se leÃ­rÃ¡sa', 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se kulcsszavai', 1, 13, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se tartalma', '', ''),
(16, 'FelhasznÃ¡lÃ³ lista', 'felhasznalo_lista', 1, 1, 'FelhasznÃ¡lÃ³ lista leÃ­rÃ¡sa', 'FelhasznÃ¡lÃ³ lista kulcsszavai', 1, 14, 'FelhasznÃ¡lÃ³ lista tartalma', '', ''),
(17, 'AdatmÃ³dosÃ­tÃ¡s', 'adatmodositas', 1, 1, 'AdatmÃ³dosÃ­tÃ¡s leÃ­rÃ¡sa', 'AdatmÃ³dosÃ­tÃ¡s kulcsszavai', 1, 15, 'AdatmÃ³dosÃ­tÃ¡s tartalma', '', ''),
(18, 'JelszÃ³modosÃ­tÃ¡s', 'jelszomodositas', 1, 1, 'JelszÃ³modosÃ­tÃ¡s leÃ­rÃ¡sa', 'JelszÃ³modosÃ­tÃ¡s kulcsszavai', 1, 16, 'JelszÃ³modosÃ­tÃ¡s tartalma', '', ''),
(19, 'AlapbeÃ¡llÃ­tÃ¡sok', 'alapbeallitasok', 1, 1, 'AlapbeÃ¡llÃ­tÃ¡sok leÃ­rÃ¡sa', 'AlapbeÃ¡llÃ­tÃ¡sok kulcsszavai', -1, 51, 'AlapbeÃ¡llÃ­tÃ¡sok tartalma', '', ''),
(20, 'ttt', 'ttt', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', -1, 1, 'Az oldal tartalma', '', ''),
(21, 'tre', 'tre', 1, 1, 'rrrrrrrrrrrrr', 'Az oldal kulcsszavai', 1, 1, 'zzzzzttttttttt', '', 'tre.jpg'),
(22, 'tree12345', 'tree12345', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 1, 2, 'Az oldal tartalma ijuohu itzfutfrutrd', '', ''),
(23, 'wert', 'wert', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', ''),
(24, 'zzzzzzzzz3a1', 'zzzzzzzzz3a1', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 23, 2, 'Az oldal tartalma', '', ''),
(25, 'q123', 'q123', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 24, 2, 'Az oldal tartalma', '', ''),
(27, 'rtz1', 'rtz1', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 2, 'Az oldal tartalma', '', ''),
(28, 'w3333', 'w3333', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 22, 1, 'Az oldal tartalma', '', 'w3333.jpg'),
(31, 'zzzz321a', 'zzzz321a', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 1, 2, 'Az oldal tartalma', '', ''),
(32, 'rt123', 'rt123', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 31, 2, 'Az oldal tartalma', '', ''),
(33, 'LLLLLLLLLLLLL', 'LLLLLLLLLLLLL', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 31, 2, 'Az oldal tartalma', '', ''),
(34, 'TR10101', 'TR10101', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 31, 2, 'Az oldal tartalma', '', ''),
(35, 'Kieg. tartalom', 'kiegeszito_tartalom', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 1, 52, 'Az oldal tartalma', '', '');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `OldalCikkei`
--

DROP TABLE IF EXISTS `OldalCikkei`;
CREATE TABLE IF NOT EXISTS `OldalCikkei` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Oid` int(10) NOT NULL,
  `Cid` int(10) NOT NULL,
  `CPrioritas` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `OldalKepek`
--

DROP TABLE IF EXISTS `OldalKepek`;
CREATE TABLE IF NOT EXISTS `OldalKepek` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Oid` int(10) NOT NULL,
  `KFile` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KSzelesseg` smallint(6) NOT NULL DEFAULT '-1',
  `KMagassag` smallint(6) NOT NULL DEFAULT '-1',
  `KStilus` smallint(6) NOT NULL DEFAULT '0',
  `KSorszam` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=30 ;

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
(26, 1, 'kezdolap_1.png', '', '', 0, 0, 0, 0),
(27, 1, 'kezdolap_7.png', '', '', 0, 0, 0, 0),
(28, 1, 'kezdolap_8.jpg', '', '', 0, 0, 0, 0),
(29, 1, 'kezdolap_9.jpg', '', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `OModeratorok`
--

DROP TABLE IF EXISTS `OModeratorok`;
CREATE TABLE IF NOT EXISTS `OModeratorok` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Oid` int(10) NOT NULL,
  `Fid` int(10) NOT NULL DEFAULT '-1',
  `CSid` int(10) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;