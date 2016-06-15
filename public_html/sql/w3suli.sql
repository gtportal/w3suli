-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2016. Jún 15. 11:40
-- Szerver verzió: 5.5.49-0ubuntu0.14.04.1
-- PHP verzió: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `proba`
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
  `HeaderImg` varchar(60) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FavIcon` varchar(60) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `HeaderStr` varchar(200) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `GoogleKod` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `GooglePlus` tinyint(4) NOT NULL DEFAULT '0',
  `HEADextra` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FacebookURL` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `FacebookOK` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;

--
-- A tábla adatainak kiíratása `AlapAdatok`
--

INSERT INTO `AlapAdatok` (`id`, `WebhelyNev`, `Iskola`, `Cim`, `Telefon`, `Stilus`, `HeaderImg`, `FavIcon`, `HeaderStr`, `GoogleKod`, `GooglePlus`, `HEADextra`, `FacebookURL`, `FacebookOK`) VALUES
(1, 'W3Suli', 'Iskola', 'CÃ­m', 'Telefon', 2, 'w3logo.png', 'logo.png', '<span>W3Suli</span><br><span>Blogmotor</span>', 'Analytics 123', 0, 'HEAD1', 'FACE', 0);

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
  `KoElozetes` tinyint(4) NOT NULL DEFAULT '0',
  `SZoElozetes` tinyint(4) NOT NULL DEFAULT '0',
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
  `KSorszam` tinyint(4) NOT NULL DEFAULT '0',
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
  `KapcsTip` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;

--
-- A tábla adatainak kiíratása `FCsoportTagok`
--

INSERT INTO `FCsoportTagok` (`id`, `Fid`, `CSid`, `KapcsTip`) VALUES
(1, 1, 1, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;

--
-- A tábla adatainak kiíratása `FelhasznaloCsoport`
--

INSERT INTO `FelhasznaloCsoport` (`id`, `CsNev`, `CsLeiras`) VALUES
(1, 'Webmesterek', 'Webmesterek csoportja');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;

--
-- A tábla adatainak kiíratása `Felhasznalok`
--

INSERT INTO `Felhasznalok` (`id`, `FNev`, `FFNev`, `FJelszo`, `FEmail`, `FSzint`, `FSzerep`, `FKep`) VALUES
(1, 'proba', 'proba', 'c0a8e1e5e307cc5b33819b387b5f01fd', ' ', 7, 'Webmester', '');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `FoMenuLink`
--

DROP TABLE IF EXISTS `FoMenuLink`;
CREATE TABLE IF NOT EXISTS `FoMenuLink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `LNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `LURL` varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
  `LPrioritas` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=12 ;

--
-- A tábla adatainak kiíratása `FoMenuLink`
--

INSERT INTO `FoMenuLink` (`id`, `LNev`, `LURL`, `LPrioritas`) VALUES
(1, 'FelhasznÃ¡lÃ³i kÃ©zikÃ¶nyv', 'https://w3suli.hu/?f0=Felhasznaloi_kezikonyv', 1),
(2, '', '', 0),
(3, '', '', 0),
(4, '', '', 0),
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
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `KiegTNev` varchar(125) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `KiegTTartalom` text COLLATE utf8_hungarian_ci NOT NULL,
  `KiegTPrioritas` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=11 ;

--
-- A tábla adatainak kiíratása `KiegTartalom`
--

INSERT INTO `KiegTartalom` (`id`, `KiegTNev`, `KiegTTartalom`, `KiegTPrioritas`) VALUES
(1, '', '', 0),
(2, '', '', 0),
(3, '', '', 0),
(4, '', '', 0),
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
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `MenuPlNev` varchar(125) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `MenuPlTartalom` text COLLATE utf8_hungarian_ci NOT NULL,
  `MenuPlPrioritas` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=11 ;

--
-- A tábla adatainak kiíratása `MenuPlusz`
--

INSERT INTO `MenuPlusz` (`id`, `MenuPlNev`, `MenuPlTartalom`, `MenuPlPrioritas`) VALUES
(1, '', '', 0),
(2, '', '', 0),
(3, '', '', 0),
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Oid` int(11) NOT NULL,
  `CSid` int(11) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=16 ;

--
-- A tábla adatainak kiíratása `Oldalak`
--

INSERT INTO `Oldalak` (`id`, `ONev`, `OUrl`, `OLathatosag`, `OPrioritas`, `OLeiras`, `OKulcsszavak`, `OSzuloId`, `OTipus`, `OTartalom`, `OImgDir`, `OImg`) VALUES
(1, 'KezdÅ‘lap', 'Kezdolap', 1, 1, '', '', 0, 0, '', '', ''),
(2, 'BejelentkezÃ©s', 'bejelentkezes', 1, 1, '', '', 1, 10, '', '', ''),
(3, 'KijelentkezÃ©s', 'kijelentkezes', 1, 1, '', '', 1, 11, '', '', ''),
(4, 'RegisztrÃ¡ciÃ³', 'regisztracio', 1, 1, '', '', 1, 12, '', '', ''),
(5, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se', 'felhasznalo_torlese', 1, 1, '', '', 1, 13, '', '', ''),
(6, 'FelhasznÃ¡lÃ³ lista', 'felhasznalo_lista', 1, 1, '', '', 1, 14, '', '', ''),
(7, 'AdatmÃ³dosÃ­tÃ¡s', 'adatmodositas', 1, 1, '', '', 1, 15, '', '', ''),
(8, 'JelszÃ³modosÃ­tÃ¡s', 'jelszomodositas', 1, 1, '', '', 1, 16, '', '', ''),
(9, 'AlapbeÃ¡llÃ­tÃ¡sok', 'alapbeallitasok', 1, 1, '', '', -1, 51, '', '', ''),
(10, 'ArchÃ­vum', 'Archivum', 1, 1, 'ArchÃƒÂ­vum', 'ArchÃƒÂ­vum', 1, 22, '', 'Archivum', ''),
(11, 'KiegÃ©szÃ­tÅ‘ tartalom', 'kiegeszito_tartalom', 1, 1, '', '', 1, 52, '', '', ''),
(12, 'FÅ‘menÃ¼ linkek beÃ¡llÃ­tÃ¡sa', 'Fomenu_linkek_beallitasa', 1, 1, '', '', 1, 53, '', '', ''),
(13, 'FelhasznÃ¡lÃ³i csoportok', 'Felhasznaloi_csoportok', 1, 1, '', '', 1, 20, '', '', ''),
(14, 'OldaltÃ©rkÃ©p', 'oldalterkep', 1, 1, '', '', 1, 21, '', 'oldalterkep', ''),
(15, 'MenÃ¼ plusz infÃ³k', 'menuplusz', 1, 1, '', '', 1, 54, '', '', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

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