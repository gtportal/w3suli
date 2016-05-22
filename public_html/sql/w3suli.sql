

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
`id` int(10) NOT NULL,
  `WebhelyNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Iskola` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Cim` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Telefon` varchar(20) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `Stilus` smallint(6) NOT NULL DEFAULT '0',
  `HeaderImg` varchar(60) COLLATE utf8_hungarian_ci NOT NULL,
  `FavIcon` varchar(60) COLLATE utf8_hungarian_ci NOT NULL,
  `HeaderStr` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `GoogleKod` varchar(40) COLLATE utf8_hungarian_ci NOT NULL,
  `GooglePlus` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `AlapAdatok`
--

INSERT INTO `AlapAdatok` (`id`, `WebhelyNev`, `Iskola`, `Cim`, `Telefon`, `Stilus`, `HeaderImg`, `FavIcon`, `HeaderStr`, `GoogleKod`, `GooglePlus`) VALUES
(3, 'Egressy Projektek', 'BMSZC Egressy GÃ¡bor SzakkÃ¶zÃ©piskolÃ¡ja', '1049 Budapest, Egressy Ãºt 71.', 'Telefon&#58; 2527777', 4, 'egressy_ferde200.png', 'logo.png', '<span>BMSZC Egressy GÃ¡bor SzakkÃ¶zÃ©piskolÃ¡ja<br>projektek</span>', '', 0);

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
  `KoElozetes` tinyint(4) NOT NULL DEFAULT '0',
  `SZoElozetes` tinyint(4) NOT NULL DEFAULT '0',
  `CSzerzo` int(10) NOT NULL,
  `CSzerzoNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CLetrehozasTime` datetime NOT NULL,
  `CModositasTime` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=39 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=61 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=15 ;

--
-- A tábla adatainak kiíratása `FCsoportTagok`
--

INSERT INTO `FCsoportTagok` (`id`, `Fid`, `CSid`, `KapcsTip`) VALUES
(14, 23, 5, 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `FelhasznaloCsoport`
--

DROP TABLE IF EXISTS `FelhasznaloCsoport`;
CREATE TABLE IF NOT EXISTS `FelhasznaloCsoport` (
`id` int(10) NOT NULL,
  `CsNev` varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
  `CsLeiras` varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=7 ;

--
-- A tábla adatainak kiíratása `FelhasznaloCsoport`
--

INSERT INTO `FelhasznaloCsoport` (`id`, `CsNev`, `CsLeiras`) VALUES
(5, 'rendszergazdÃ¡k', 'RendszergazdÃ¡k csoportja'),
(6, 'tanÃ¡rok', 'tanÃ¡rok');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=24 ;

--
-- A tábla adatainak kiíratása `Felhasznalok`
--

INSERT INTO `Felhasznalok` (`id`, `FNev`, `FFNev`, `FJelszo`, `FEmail`, `FSzint`, `FSzerep`, `FKep`) VALUES
(10, 'tesztelek', 'tesztelek', 'bf7fd979986bf2313dca63d533cc8a7f', 'r@r.hu', 5, 'tanÃƒÂ¡r', '');

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
(1, 'Ã“rarend', 'http&#58;//www.egressy.eu/orarend/', 9),
(2, 'HelyettesÃ­tÃ©s', 'http&#58;//www.egressy.eu/helyettesites/', 8),
(3, 'E-ellenÃ¶rzÅ‘', 'http&#58;//egressy.e-naplo.hu/xkirpub/', 7),
(4, 'E-naplÃ³', 'http&#58;//egressy.e-naplo.hu/xkirpda/', 6),
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
(1, 'Egressy munkaterv', '<iframe src="https://www.google.com/calendar/embed?showPrint=0&showTabs=0&showCalendars=0&showTz=0&mode=AGENDA&height=200&wkst=2&hl=hu&bgcolor=%23ffffff&src=524n3edasspkp56dhcbq9vf8e0%40group.calendar.google.com&color=%ff000000&ctz=Europe%2FBudapest" style=" border-width:0 " width="300" height="300" frameborder="0" scrolling="no"></iframe>', 9),
(2, '', '', 8),
(3, '', '', 7),
(4, '', '', 9),
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
(1, 'A NTP-MTTD-15-0194 projektÃ¼nk tÃ¡mogatÃ³i&#58;', '<img src="./img/ikonok/eem_logo.jpg">', 9),
(2, '', '<img src="./img/ikonok/eet_logo.jpg">', 8),
(3, '', '<img src="./img/ikonok/ntp_logo.jpg">', 5),
(4, '', '', 2),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=106 ;

--
-- A tábla adatainak kiíratása `Oldalak`
--

INSERT INTO `Oldalak` (`id`, `ONev`, `OUrl`, `OLathatosag`, `OPrioritas`, `OLeiras`, `OKulcsszavak`, `OSzuloId`, `OTipus`, `OTartalom`, `OImgDir`, `OImg`) VALUES
(1, 'KezdÅ‘lap', 'Kezdolap', 1, 1, 'A W3 Suli projekt cÃ©lja egy az iskolÃ¡k igÃ©nyeihez alkalmazkodÃ³ blog-motor elkÃ©szÃ­tÃ©se, amely lehetÅ‘vÃ© teszi, hogy tanulÃ³i Ã©s tanÃ¡ri csoportok egyarÃ¡nt bemutathassÃ¡k tevÃ©kenysÃ©gÃ¼ket egy iskola honlapjÃ¡n.', 'KezdÅ‘lap kulcsszavai', 0, 0, '', '', ''),
(11, 'ArchÃ­vum', 'Archivum', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 1, 22, 'Az oldal tartalma', 'Archivum', ''),
(12, 'BejelentkezÃ©s', 'bejelentkezes', 1, 1, 'BejelentkezÃ©s leÃ­rÃ¡sa', 'BejelentkezÃ©s kulcsszavai', 1, 10, 'BejelentkezÃ©s tartalma', '', ''),
(13, 'KijelentkezÃ©s', 'kijelentkezes', 1, 1, 'KijelentkezÃ©s leÃ­rÃ¡sa', 'KijelentkezÃ©s kulcsszavai', 1, 11, 'KijelentkezÃ©s tartalma', '', ''),
(14, 'RegisztrÃ¡ciÃ³', 'regisztracio', 1, 1, 'RegisztrÃ¡ciÃ³ leÃ­rÃ¡sa', 'RegisztrÃ¡ciÃ³ kulcsszavai', 1, 12, 'RegisztrÃ¡ciÃ³ tartalma', '', ''),
(15, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se', 'felhasznalo_torlese', 1, 1, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se leÃ­rÃ¡sa', 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se kulcsszavai', 1, 13, 'FelhasznÃ¡lÃ³ tÃ¶rlÃ©se tartalma', '', ''),
(16, 'FelhasznÃ¡lÃ³ lista', 'felhasznalo_lista', 1, 1, 'FelhasznÃ¡lÃ³ lista leÃ­rÃ¡sa', 'FelhasznÃ¡lÃ³ lista kulcsszavai', 1, 14, 'FelhasznÃ¡lÃ³ lista tartalma', '', ''),
(17, 'AdatmÃ³dosÃ­tÃ¡s', 'adatmodositas', 1, 1, 'AdatmÃ³dosÃ­tÃ¡s leÃ­rÃ¡sa', 'AdatmÃ³dosÃ­tÃ¡s kulcsszavai', 1, 15, 'AdatmÃ³dosÃ­tÃ¡s tartalma', '', ''),
(18, 'JelszÃ³modosÃ­tÃ¡s', 'jelszomodositas', 1, 1, 'JelszÃ³modosÃ­tÃ¡s leÃ­rÃ¡sa', 'JelszÃ³modosÃ­tÃ¡s kulcsszavai', 1, 16, 'JelszÃ³modosÃ­tÃ¡s tartalma', '', ''),
(19, 'AlapbeÃ¡llÃ­tÃ¡sok', 'alapbeallitasok', 1, 1, 'AlapbeÃ¡llÃ­tÃ¡sok leÃ­rÃ¡sa', 'AlapbeÃ¡llÃ­tÃ¡sok kulcsszavai', -1, 51, 'AlapbeÃ¡llÃ­tÃ¡sok tartalma', '', ''),
(35, 'Kieg. tartalom', 'kiegeszito_tartalom', 1, 1, 'Az oldal leÃ­rÃ¡sa', 'Az oldal kulcsszavai', 1, 52, 'Az oldal tartalma', '', ''),
(39, 'FÅ‘menÃ¼ linkek beÃ¡llÃ­tÃ¡sa', 'Fomenu_linkek_beallitasa', 1, 1, 'FÅ‘menÃ¼ linkek beÃ¡llÃ­tÃ¡sa', 'Az oldal kulcsszavai', 1, 53, '', '', ''),
(41, 'FelhasznÃ¡lÃ³i csoportok', 'Felhasznaloi_csoportok', 1, 1, 'Oldal leÃ­rÃ¡sa', 'Oldal kulcsszavai', 1, 20, 'Oldal tartalma', 'Felhasznaloi_csoportok', ''),
(42, 'OldaltÃ©rkÃ©p', 'oldalterkep', 1, 1, 'OldaltÃ©rkÃ©p leÃ­rÃ¡sa', 'OldaltÃ©rkÃ©p kulcsszavai', 1, 21, 'AlapbeÃ¡llÃ­tÃ¡sok tartalma', 'oldalterkep', ''),
(43, 'MenÅ± plusz infÃ³k', 'menuplusz', 1, 1, 'MenÅ± plusz infÃ³k leÃ­rÃ¡sa', 'MenÅ± plusz infÃ³k kulcsszavai', 1, 54, 'MenÅ± plusz infÃ³k tartalma', '', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=39 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;

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
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `CikkKepek`
--
ALTER TABLE `CikkKepek`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `FCsoportTagok`
--
ALTER TABLE `FCsoportTagok`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `FelhasznaloCsoport`
--
ALTER TABLE `FelhasznaloCsoport`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Felhasznalok`
--
ALTER TABLE `Felhasznalok`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Oldalak`
--
ALTER TABLE `Oldalak`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `OldalCikkei`
--
ALTER TABLE `OldalCikkei`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `OldalKepek`
--
ALTER TABLE `OldalKepek`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `OModeratorok`
--
ALTER TABLE `OModeratorok`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `rangsor`
--
ALTER TABLE `rangsor`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
