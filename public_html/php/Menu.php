<?php
    function getMenuLink($Oid) {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getMenuHTML() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
         $HTMLkod1 = '';
        // ================ FELHASZNÁLÓ ÜDVÖZLÉSE ============================= 
        if ($_SESSION['AktFelhasznalo'.'FSzint']>1)  {             
           $HTMLkod1 .= "<div id='divFelhasznaloUdv'>\n Üdv: ";
           $HTMLkod1 .= $_SESSION['AktFelhasznalo'.'FNev'];
           $HTMLkod1 .= "<div>\n";           
        }
        
        // ================ FELHASZNÁLÓKEZELÉSHEZ TARTOZÓ OLDALAK ============================= 
        $HTMLkod1     .= "<ul class='Ul1'>\n";
        if ($_SESSION['AktFelhasznalo'.'FSzint']>1)  { 
            $HTMLkod1 .= "<li class='M1'><a href='?f0=kijelentkezes'>Kijelentkezés</a></li>\n";            
            $HTMLkod1 .= "<li class='M1'><a href='?f0=jelszomodositas'>Jelszómodosítás</a></li>\n";            
        } else {
            $HTMLkod1 .= "<li class='M1'><a href='?f0=bejelentkezes'>Bejelentkezés</a></li>\n";  
            
        }
        $HTMLkod1     .= "</ul>\n";
        
        // ================ RENDSZERGAZDÁK OLDALAI ============================= 
        $HTMLkod2      = '';
        if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { 
            $HTMLkod2 .= "<ul class='Ul1'>\n";
            $HTMLkod2 .= "<li class='M1'><a href='?f0=regisztracio'>Regisztráció</a></li>\n"; 
            $HTMLkod2 .= "<li class='M1'><a href='?f0=adatmodositas'>Adatmódosítás</a></li>\n";
            $HTMLkod2 .= "<li class='M1'><a href='?f0=alapbeallitasok'>Alapbeállítások</a></li>\n"; 
            $HTMLkod2 .= "<li class='M1'><a href='?f0=felhasznalo_lista'>Felhasználó lista</a></li>\n";
            $HTMLkod2 .= "<li class='M1'><a href='?f0=jelszomodositas'>Jelszómodosítás</a></li>\n";
            $HTMLkod2 .= "<li class='M1'><a href='?f0=kiegeszito_tartalom'>Kiegészítő tartalom</a></li>\n";            
            $HTMLkod2 .= "</ul>\n";
        }
        if ($HTMLkod2 != '') {$HTMLkod1 .= $HTMLkod2;}
        
        // ================ KATEGÓRIÁK ÉS HÍROLDALAK TÖBBSZINTŰ LISTÁJA ============================= 
        $HTMLkod      = '';
        //Elso szint >> Szülő a keszdőlap
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=1 AND OTipus<10 order by ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
           //Ha az adott oldal vagy annak első gyermeke aktív, akkor az 'AktLink' osztályba kerül
           if ($OID==$Aktoldal['id']       || 
               $OID==$SzuloOldal['id']     ||
               $OID==$NagyszuloOldal['id'] ||
               $OID==$DedSzuloId   
              ) {$AktLink = "class='AktLink'";} else {$AktLink = "";}
           $HTMLkod .= "<li class='M1'><a href='?f0=$OURL' $AktLink>$ONev</a>";
           //Ha az adott oldal vagy annak egy leszármazottja aktív, akkor leszármazottjait is megjelenítjük
           if ($AktLink>'') {$HTMLkod .= Menu_Szint2($OID);}
           $HTMLkod .= "</li>\n"; 
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul1'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod1.$HTMLkod;
    }

    function Menu_Szint2($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Második szint 
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
           //Ha az adott oldal vagy annak első gyermeke aktív, akkor az 'AktLink' osztályba kerül
           if ($OID==$Aktoldal['id']      || 
               $OID==$SzuloOldal['id']    ||
               $OID==$NagyszuloOldal['id'] ||
               $OID==$DedSzuloId   
              ) {$AktLink = "class='AktLink'";} else {$AktLink = "";}
           $HTMLkod .= "<li class='M2'><a href='?f0=$OURL' $AktLink>$ONev</a>";
           //Ha az adott oldal vagy annak egy leszármazottja aktív, akkor leszármazottjait is megjelenítjük
           if ($AktLink>'') {$HTMLkod .= Menu_Szint3($OID);}
           $HTMLkod .= "</li>\n";           
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul2'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod;
    }
    
    function Menu_Szint3($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Harmadik szint
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
           //Ha az adott oldal vagy annak egy gyermeke aktív, akkor az 'AktLink' osztályba kerül
           if ($OID==$Aktoldal['id']      || 
               $OID==$SzuloOldal['id']    ||
               $OID==$NagyszuloOldal['id'] ||
               $OID==$DedSzuloId   
              ) {$AktLink = "class='AktLink'";} else {$AktLink = "";}
           $HTMLkod .= "<li class='M3'><a href='?f0=$OURL' $AktLink>$ONev</a>";
           //Ha az adott oldal vagy annak egy gyermeke aktív, akkor gyermekeit is megjelenítjük
           if ($AktLink>'') {$HTMLkod .= Menu_Szint4($OID);}
           $HTMLkod .= "</li>\n";           
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul3'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod;
    }
    
    function Menu_Szint4($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Negyedik szint 
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
           //Ha az adott oldal aktív, akkor az 'AktLink' osztályba kerül
           if ($OID==$Aktoldal['id']      || 
               $OID==$SzuloOldal['id']    ||
               $OID==$NagyszuloOldal['id'] ||
               $OID==$DedSzuloId   
              )  {$AktLink = "class='AktLink'";} else {$AktLink = "";}
           $HTMLkod .= "<li class='M4'><a href='?f0=$OURL' $AktLink>$ONev</a>";
           $HTMLkod .= "</li>\n";           
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul4'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod;
    }
?>
