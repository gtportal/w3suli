<?php
    function getMenuLink($Oid) {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getMenuHTML() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId, $AlapAdatok;
         $HTMLkod1 = '';
         
        if ($AlapAdatok['GooglePlus']==1){ 
            $HTMLkod1 = '
            <!-- Helyezd el ezt a címkét ott, ahol a(z) +1 gomb modult meg szeretnéd jeleníteni. -->
            <div id="Gplusz" style="width:100%;text-align:center;margin-top:4px;"><div class="g-plusone"></div></div>
            ';}
         
        // ================ FELHASZNÁLÓ ÜDVÖZLÉSE ============================= 
        if ($_SESSION['AktFelhasznalo'.'FSzint']>1)  {             
           $HTMLkod1 .= "<div id='divFelhasznaloUdv'>\n Üdv: ";
           $HTMLkod1 .= $_SESSION['AktFelhasznalo'.'FNev'];
           $HTMLkod1 .= "</div>\n";                                                          //JAVÍTVA 2016.02.11.
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
            $HTMLkod2 .= "<li class='M1'><div>Felhasználókezelés</div></li>\n";             
            $HTMLkod2 .= "<li class='M1'><a href='?f0=regisztracio'>Regisztráció</a></li>\n"; 
            $HTMLkod2 .= "<li class='M1'><a href='?f0=adatmodositas'>Felhasználók szerkesztése</a></li>\n";
            $HTMLkod2 .= "<li class='M1'><a href='?f0=Felhasznaloi_csoportok'>Felhasználói csoportok</a></li>\n";
            $HTMLkod2 .= "<li class='M1'><a href='?f0=felhasznalo_lista'>Felhasználó lista</a></li>\n";
            $HTMLkod2 .= "</ul>\n";     
            
            $HTMLkod2 .= "<ul class='Ul1'>\n";
            $HTMLkod2 .= "<li class='M1'><div>Kiegészítő tartalmak</div></li>\n";             
            $HTMLkod2 .= "<li class='M1'><a href='?f0=kiegeszito_tartalom'>Kiegészítő tartalom</a></li>\n";    
            $HTMLkod2 .= "<li class='M1'><a href='?f0=Fomenu_linkek_beallitasa'>Főmenü linkjeinek beállítása</a></li>\n"; 
            $HTMLkod2 .= "<li class='M1'><a href='?f0=menuplusz'>Menü plusz infók</a></li>\n";   
            $HTMLkod2 .= "</ul>\n";  
            
            $HTMLkod2 .= "<ul class='Ul1'>\n";
            $HTMLkod2 .= "<li class='M1'><div>Alapinformációk</div></li>\n";              
            $HTMLkod2 .= "<li class='M1'><a href='?f0=alapbeallitasok'>Alapbeállítások</a></li>\n";
            $HTMLkod2 .= "<li class='M1'><a href='?f0=oldalterkep'>Oldaltérkép</a></li>\n"; 
            $HTMLkod2 .= "</ul>\n"; 
            
            $HTMLkod2 .= "<div class='divMenuInfo1'>Tartalom</div>\n";
        }
        if ($HTMLkod2 != '') {$HTMLkod1 .= $HTMLkod2;}
        
        // ================ KATEGÓRIÁK ÉS HÍROLDALAK TÖBBSZINTŰ LISTÁJA ============================= 
   // ================ KATEGÓRIÁK ÉS HÍROLDALAK TÖBBSZINTŰ LISTÁJA ============================= 
        $HTMLkod      = '';
        //Elso szint >> Szülő a kezdőlap
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=1 AND OTipus<10 order by OPrioritas DESC, ONev"; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId'];
                       // echo $OID."-  ".getOMenuLathatosagTeszt($OID)."<br>";
            if(getOMenuLathatosagTeszt($OID)>0){
                //Ha az adott oldal vagy annak első gyermeke aktív, akkor az 'AktLink' osztályba kerül
                if ($OID==$Aktoldal['id']       || $OID==$SzuloOldal['id'] ||
                    $OID==$NagyszuloOldal['id'] || $OID==$DedSzuloId) 
                    {$AktLink = "class='AktLink'";} else {$AktLink = "";}
                 $HTMLkod .= "<li class='M1'><a href='?f0=$OURL' $AktLink>$ONev</a>";
                 //Ha az adott oldal vagy annak egy leszármazottja aktív, akkor leszármazottjait is megjelenítjük
                 if ($AktLink>'') {$HTMLkod .= Menu_Szint2($OID);}
                 $HTMLkod .= "</li>\n";
            }
        } 
        $HTMLkod .= "<li class='M1'><a href='?f0=Archivum'>Archívum</a></li>\n";
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul1'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);          
   
        $HTMLkod  .= getMenuPluszHTML();
        return $HTMLkod1.$HTMLkod;
    }

    function Menu_Szint2($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Második szint 
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by OPrioritas DESC, ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId'];
            if(getOMenuLathatosagTeszt($OID)>0){
                //Ha az adott oldal vagy annak első gyermeke aktív, akkor az 'AktLink' osztályba kerül
                if ($OID==$Aktoldal['id']      || $OID==$SzuloOldal['id']    ||
                    $OID==$NagyszuloOldal['id'] || $OID==$DedSzuloId) 
                    {$AktLink = "class='AktLink'";} else {$AktLink = "";}  
                 $HTMLkod .= "<li class='M2'><a href='?f0=$OURL' $AktLink>$ONev</a>";
                 //Ha az adott oldal vagy annak egy leszármazottja aktív, akkor leszármazottjait is megjelenítjük
                 if ($AktLink>'') {$HTMLkod .= Menu_Szint3($OID);}
                 $HTMLkod .= "</li>\n";
            }
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul2'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod;
    }
    
    function Menu_Szint3($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Harmadik szint
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by OPrioritas DESC, ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
            if(getOMenuLathatosagTeszt($OID)>0){
                //Ha az adott oldal vagy annak egy gyermeke aktív, akkor az 'AktLink' osztályba kerül
                if ($OID==$Aktoldal['id']      || $OID==$SzuloOldal['id']    ||
                    $OID==$NagyszuloOldal['id'] || $OID==$DedSzuloId) 
                    {$AktLink = "class='AktLink'";} else {$AktLink = "";}
                 $HTMLkod .= "<li class='M3'><a href='?f0=$OURL' $AktLink>$ONev</a>";
                 //Ha az adott oldal vagy annak egy gyermeke aktív, akkor gyermekeit is megjelenítjük
                 if ($AktLink>'') {$HTMLkod .= Menu_Szint4($OID);}
                 $HTMLkod .= "</li>\n";   
            }
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul3'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod;
    }
    
    function Menu_Szint4($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Negyedik szint 
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by OPrioritas DESC, ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
            if(getOMenuLathatosagTeszt($OID)>0){
                //Ha az adott oldal aktív, akkor az 'AktLink' osztályba kerül
                if ($OID==$Aktoldal['id']      || $OID==$SzuloOldal['id']    ||
                   $OID==$NagyszuloOldal['id'] || $OID==$DedSzuloId)  
                   {$AktLink = "class='AktLink'";} else {$AktLink = "";}
                $HTMLkod .= "<li class='M4'><a href='?f0=$OURL' $AktLink>$ONev</a>";
                $HTMLkod .= "</li>\n";
            }
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul4'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod;
    }
?>
