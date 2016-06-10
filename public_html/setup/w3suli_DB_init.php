<?php

//------------------------------------------------------------------------------------------------------------------
// ----------- AlapAdatok TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_AlapAdatok()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS AlapAdatok";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "Az <b>'AlapAdatok'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
  CREATE TABLE IF NOT EXISTS AlapAdatok (
    id int(10) NOT NULL AUTO_INCREMENT,
    WebhelyNev varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    Iskola varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    Cim varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    Telefon varchar(20) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    Stilus smallint(6) NOT NULL DEFAULT '0',
    HeaderImg varchar(60) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    FavIcon varchar(60) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    HeaderStr varchar(200) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    GoogleKod varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    GooglePlus tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;    
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "Az <b>'AlapAdatok'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  }
  // Alapértelmezett oldalak feltöltése
   $InsertIntoStr = "INSERT INTO AlapAdatok 
       (id, WebhelyNev, Iskola, Cim, Telefon, Stilus, HeaderImg, FavIcon, HeaderStr, GoogleKod, GooglePlus) 
       VALUES
       (1, 'W3Suli', 'Iskola', 'Cim', 'Telefon', 2, 'w3logo.png', 'logo.png', '<span>W3Suli</span><br><span>blogmotor</span>', '', 1)" ;
    if (!mysqli_query($MySqliLink,$InsertIntoStr))  {
       $HTMLkod .=  " MySqli hiba (" .mysqli_errno($MySqliLink). "): " . mysqli_error($MySqliLink);   $Err=1; 
    }  
  return $HTMLkod;
}

//------------------------------------------------------------------------------------------------------------------
// ----------- Cikkek TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_Cikkek()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS Cikkek";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "A <b>'Cikkek'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
  CREATE TABLE IF NOT EXISTS Cikkek (
    id int(10) NOT NULL AUTO_INCREMENT,
    CNev varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    CLeiras varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    CTartalom text COLLATE utf8_hungarian_ci NOT NULL,
    CLathatosag tinyint(4) NOT NULL DEFAULT '1',
    KoElozetes tinyint(4) NOT NULL DEFAULT '0',
    SZoElozetes tinyint(4) NOT NULL DEFAULT '0',
    CSzerzo int(10) NOT NULL,
    CSzerzoNev varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
    CLetrehozasTime datetime NOT NULL,
    CModositasTime datetime NOT NULL,
    PRIMARY KEY (id)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;  
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "A <b>'Cikkek'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  return $HTMLkod;
}


//------------------------------------------------------------------------------------------------------------------
// ----------- CikkKepek TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_CikkKepek()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS CikkKepek";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "A <b>'CikkKepek'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS CikkKepek (
      id int(10) NOT NULL AUTO_INCREMENT,
      Cid int(10) NOT NULL,
      KFile varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      KNev varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      KLeiras varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      KSzelesseg smallint(6) NOT NULL DEFAULT '-1',
      KMagassag smallint(6) NOT NULL DEFAULT '-1',
      KStilus smallint(6) NOT NULL DEFAULT '0',
      KSorszam tinyint(4) NOT NULL DEFAULT '0',
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ; 
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "A <b>'CikkKepek'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  return $HTMLkod;
}

//------------------------------------------------------------------------------------------------------------------
// ----------- FCsoportTagok TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_FCsoportTagok()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS FCsoportTagok";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "A <b>'FCsoportTagok'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }

  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS FCsoportTagok (
      id int(10) NOT NULL AUTO_INCREMENT,
      Fid int(10) NOT NULL,
      CSid int(10) NOT NULL,
      KapcsTip tinyint(4) NOT NULL DEFAULT '0',
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "A <b>'FCsoportTagok'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
    // Alapértelmezett oldalak feltöltése
   $InsertIntoStr = "INSERT INTO FCsoportTagok (id, Fid, CSid, KapcsTip) VALUES
                     (1, 1, 1, 0);
       " ;
    if (!mysqli_query($MySqliLink,$InsertIntoStr))  {
       $HTMLkod .=  " MySqli hiba (" .mysqli_errno($MySqliLink). "): " . mysqli_error($MySqliLink);   $Err=1; 
    }  
  
  return $HTMLkod;
}


//------------------------------------------------------------------------------------------------------------------
// ----------- FelhasznaloCsoport TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_FelhasznaloCsoport()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS FelhasznaloCsoport";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "A <b>'FelhasznaloCsoport'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }

  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS FelhasznaloCsoport (
      id int(10) NOT NULL AUTO_INCREMENT,
      CsNev varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      CsLeiras varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "A <b>'FelhasznaloCsoport'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  // Alapértelmezett oldalak feltöltése
   $InsertIntoStr = "INSERT INTO FelhasznaloCsoport 
        (id, CsNev, CsLeiras) VALUES
        (1, 'webmesterek', 'Webmesterek csoportja');
       " ;
    if (!mysqli_query($MySqliLink,$InsertIntoStr))  {
       $HTMLkod .=  " MySqli hiba (" .mysqli_errno($MySqliLink). "): " . mysqli_error($MySqliLink);   $Err=1; 
    }  
  
  return $HTMLkod;
}



//------------------------------------------------------------------------------------------------------------------
// ----------- Felhasznalok TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_Felhasznalok()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS Felhasznalok";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "A <b>'Felhasznalok'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS Felhasznalok (
        id int(10) NOT NULL AUTO_INCREMENT,
        FNev varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
        FFNev varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
        FJelszo varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
        FEmail varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
        FSzint tinyint(4) NOT NULL DEFAULT '0',
        FSzerep varchar(30) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
        FKep varchar(40) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
        PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "A <b>'Felhasznalok'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  return $HTMLkod;
}


//------------------------------------------------------------------------------------------------------------------
// ----------- FoMenuLink TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_FoMenuLink()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS FoMenuLink";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "A <b>'FoMenuLink'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS FoMenuLink (
      id int(11) NOT NULL AUTO_INCREMENT,
      LNev varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      LURL varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
      LPrioritas tinyint(4) NOT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "A <b>'FoMenuLink'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
    // Alapértelmezett oldalak feltöltése
   $InsertIntoStr = "INSERT INTO FoMenuLink (id, LNev, LURL, LPrioritas) VALUES
        (1, 'Felhasználói kézikönyv', 'https://w3suli.hu/?f0=Felhasznaloi_kezikonyv', 1),
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
       " ;
    if (!mysqli_query($MySqliLink,$InsertIntoStr))  {
       $HTMLkod .=  " MySqli hiba (" .mysqli_errno($MySqliLink). "): " . mysqli_error($MySqliLink);   $Err=1; 
    }  
  
  return $HTMLkod;
}


//------------------------------------------------------------------------------------------------------------------
// ----------- KiegTartalom TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_KiegTartalom()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS KiegTartalom";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "A <b>'KiegTartalom'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS KiegTartalom (
      id int(10) NOT NULL AUTO_INCREMENT,
      KiegTNev varchar(125) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      KiegTTartalom text COLLATE utf8_hungarian_ci NOT NULL,
      KiegTPrioritas smallint(6) NOT NULL DEFAULT '0',
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "A <b>'KiegTartalom'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
      // Alapértelmezett oldalak feltöltése
   $InsertIntoStr = "INSERT INTO KiegTartalom (id, KiegTNev, KiegTTartalom, KiegTPrioritas) VALUES
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
       " ;
    if (!mysqli_query($MySqliLink,$InsertIntoStr))  {
       $HTMLkod .=  " MySqli hiba (" .mysqli_errno($MySqliLink). "): " . mysqli_error($MySqliLink);   $Err=1; 
    }  
  
  return $HTMLkod;
}


//------------------------------------------------------------------------------------------------------------------
// ----------- MenuPlusz TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_MenuPlusz()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS MenuPlusz";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "A <b>'MenuPlusz'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS MenuPlusz (
      id int(10) NOT NULL AUTO_INCREMENT,
      MenuPlNev varchar(125) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      MenuPlTartalom text COLLATE utf8_hungarian_ci NOT NULL,
      MenuPlPrioritas smallint(6) NOT NULL DEFAULT '0',
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "A <b>'MenuPlusz'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
        // Alapértelmezett oldalak feltöltése
   $InsertIntoStr = "INSERT INTO MenuPlusz (id, MenuPlNev, MenuPlTartalom, MenuPlPrioritas) VALUES
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
       " ;
    if (!mysqli_query($MySqliLink,$InsertIntoStr))  {
       $HTMLkod .=  " MySqli hiba (" .mysqli_errno($MySqliLink). "): " . mysqli_error($MySqliLink);   $Err=1; 
    } 
  
  
  return $HTMLkod;
}

//------------------------------------------------------------------------------------------------------------------
// ----------- OLathatosag TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_OLathatosag()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS OLathatosag";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "Az <b>'OLathatosag'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS OLathatosag (
      id int(11) NOT NULL AUTO_INCREMENT,
      Oid int(11) NOT NULL,
      CSid int(11) NOT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "Az <b>'OLathatosag'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  return $HTMLkod;
}


//------------------------------------------------------------------------------------------------------------------
// ----------- Oldalak TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_Oldalak()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS Oldalak";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "Az <b>'Oldalak'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS Oldalak (
      id int(10) NOT NULL AUTO_INCREMENT,
      ONev varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      OUrl varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      OLathatosag tinyint(4) NOT NULL DEFAULT '0',
      OPrioritas smallint(6) NOT NULL DEFAULT '0',
      OLeiras varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      OKulcsszavak varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      OSzuloId int(10) NOT NULL DEFAULT '0',
      OTipus tinyint(4) NOT NULL DEFAULT '1',
      OTartalom text COLLATE utf8_hungarian_ci NOT NULL,
      OImgDir varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      OImg varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "Az <b>'Oldalak'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  
  // Alapértelmezett oldalak feltöltése
   $InsertIntoStr = "INSERT INTO Oldalak 
    (id, ONev, OUrl, OLathatosag, OPrioritas, OLeiras, OKulcsszavak, OSzuloId, OTipus, OTartalom, OImgDir, OImg) 
    VALUES
    (1, 'Kezdőlap', 'Kezdolap', 1, 1, '', '', 0, 0, '', '', ''),    
    (2, 'Bejelentkezés', 'bejelentkezes', 1, 1, '', '', 1, 10, '', '', ''),
    (3, 'Kijelentkezés', 'kijelentkezes', 1, 1, '', '', 1, 11, '', '', ''),
    (4, 'Regisztráció', 'regisztracio', 1, 1, '', '', 1, 12, '', '', ''),
    (5, 'Felhasználó törlése', 'felhasznalo_torlese', 1, 1, '', '', 1, 13, '', '', ''),
    (6, 'Felhasználó lista', 'felhasznalo_lista', 1, 1, '', '', 1, 14, '', '', ''),
    (7, 'Adatmódosítás', 'adatmodositas', 1, 1, '', '', 1, 15, '', '', ''),
    (8, 'Jelszómodosítás', 'jelszomodositas', 1, 1, '', '', 1, 16, '', '', ''),
    (9, 'Alapbeállítások', 'alapbeallitasok', 1, 1, '', '', -1, 51, '', '', ''),
    (10, 'Archí­vum', 'Archivum', 1, 1, 'ArchÃ­vum', 'ArchÃ­vum', 1, 22, '', 'Archivum', ''),
    (11, 'Kiegészítő tartalom', 'kiegeszito_tartalom', 1, 1, '', '', 1, 52, '', '', ''),
    (12, 'Főmenü linkek beállí­tása', 'Fomenu_linkek_beallitasa', 1, 1, '', '', 1, 53, '', '', ''),
    
    (13, 'Felhasználói csoportok', 'Felhasznaloi_csoportok', 1, 1, '', '', 1, 20, '', '', ''),
    (14, 'Oldaltérkép', 'oldalterkep', 1, 1, '', '', 1, 21, '', 'oldalterkep', ''),
    (15, 'Menü plusz infók', 'menuplusz', 1, 1, '', '', 1, 54, '', '', '')
    " ;
    if (!mysqli_query($MySqliLink,$InsertIntoStr))  {
       $HTMLkod .=  " MySqli hiba (" .mysqli_errno($MySqliLink). "): " . mysqli_error($MySqliLink);   $Err=1; 
    }  
  
  return $HTMLkod;
}


//------------------------------------------------------------------------------------------------------------------
// ----------- OldalCikkei TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_OldalCikkei()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS OldalCikkei";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "Az <b>'OldalCikkei'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS OldalCikkei (
      id int(10) NOT NULL AUTO_INCREMENT,
      Oid int(10) NOT NULL,
      Cid int(10) NOT NULL,
      CPrioritas smallint(6) NOT NULL DEFAULT '1',
      PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "Az <b>'OldalCikkei'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  return $HTMLkod;
}


//------------------------------------------------------------------------------------------------------------------
// ----------- OldalKepek TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_OldalKepek()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS OldalKepek";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "Az <b>'OldalKepek'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS OldalKepek (
      id int(10) NOT NULL AUTO_INCREMENT,
      Oid int(10) NOT NULL,
      KFile varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      KNev varchar(50) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      KLeiras varchar(255) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '',
      KSzelesseg smallint(6) NOT NULL DEFAULT '-1',
      KMagassag smallint(6) NOT NULL DEFAULT '-1',
      KStilus smallint(6) NOT NULL DEFAULT '0',
      KSorszam tinyint(4) NOT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "Az <b>'OldalKepek'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  return $HTMLkod;
}

//------------------------------------------------------------------------------------------------------------------
// ----------- OModeratorok TÁBLA LÉTREHOZÁSA
//------------------------------------------------------------------------------------------------------------------
function Letrehoz_OModeratorok()
{
global $MySqliLink, $Err; 
  $DropTableStr = "DROP TABLE IF EXISTS OModeratorok";
  if (mysqli_query($MySqliLink,$DropTableStr))
  {
    $HTMLkod = "Az <b>'OModeratorok'</b> tábla törlődött.<br>"; 
  } else { 
    $Err=1;  $HTMLkod = "MySqli hiba "; 
  }
  $CreateTableStr="
    CREATE TABLE IF NOT EXISTS OModeratorok (
      id int(10) NOT NULL AUTO_INCREMENT,
      Oid int(10) NOT NULL,
      Fid int(10) NOT NULL DEFAULT '-1',
      CSid int(10) NOT NULL DEFAULT '-1',
      PRIMARY KEY (id),
      UNIQUE KEY id (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;
  ";
  if (mysqli_query($MySqliLink,$CreateTableStr))
  {
    $HTMLkod .= "Az <b>'OModeratorok'</b> tábla elkészült.<br>";
  } else { 
    $Err=1; $HTMLkod .= "MySqli hiba ";
  } 
  return $HTMLkod;
}

?>


