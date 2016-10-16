<?php

    function getMenuHTML() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId, $AlapAdatok;
        $HTMLkod1 = '';         
        if (($AlapAdatok['GooglePlus']==2) || (($AlapAdatok['GooglePlus']==1)&& ($Aktoldal['OTipus'])==0) ||
             (($AlapAdatok['GooglePlus']==1)&& ($Aktoldal['OTipus'])>100)){  
          $HTMLkod1 .= '
            <!-- Helyezd el ezt a címkét ott, ahol a(z) +1 gomb modult meg szeretnéd jeleníteni. -->
            <div id="Gplusz" style="width:100%;text-align:center;margin-top:4px; height:31px;"><div class="g-plusone"></div></div>
            ';            
        }            
        
        if ($Aktoldal['OTipus']>100) { echo getModulMenu();}
        
        // ================ FELHASZNÁLÓ ÜDVÖZLÉSE ============================= 
        if ($_SESSION['AktFelhasznalo'.'FSzint']>1)  {             
           $HTMLkod1 .= "<div id='divFelhasznaloUdv'>\n ".U_MENU_UDV.": ";
           $HTMLkod1 .= $_SESSION['AktFelhasznalo'.'FNev'];
           $HTMLkod1 .= "</div>\n";                                                          //JAVÍTVA 2016.02.11.
        }
        
        // ================ FELHASZNÁLÓKEZELÉSHEZ TARTOZÓ OLDALAK ============================= 
        $HTMLkod1     .= "<ul class='Ul1'>\n";
        if ($_SESSION['AktFelhasznalo'.'FSzint']>1)  { 
            $HTMLkod1 .= "<li class='M1'><a href='?f0=kijelentkezes'>".U_MENU_KIJEL."</a></li>\n";            
            $HTMLkod1 .= "<li class='M1'><a href='?f0=jelszomodositas'>".U_MENU_JELSZO."</a></li>\n";            
        } else {
            $HTMLkod1 .= "<li class='M1'><a href='?f0=bejelentkezes'>".U_MENU_BEJEL."</a></li>\n";  
            
        }
        $HTMLkod1     .= "</ul>\n";
        // ================ RENDSZERGAZDÁK OLDALAI ============================= 
        $HTMLkod2      = '';
        if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { 
            $HTMLkod2 .= "<ul class='Ul1'>\n";
            $HTMLkod2 .= "<li class='M1'><div>".U_MENU_FKEZEL."</div></li>\n";             
            $HTMLkod2 .= "<li class='M1'><a href='?f0=regisztracio'>".U_MENU_REG."</a></li>\n"; 
            $HTMLkod2 .= "<li class='M1'><a href='?f0=adatmodositas'>".U_MENU_FSZERK."</a></li>\n";
            $HTMLkod2 .= "<li class='M1'><a href='?f0=Felhasznaloi_csoportok'>".U_MENU_FCSOP."</a></li>\n";
            //$HTMLkod2 .= "<li class='M1'><a href='?f0=felhasznalo_lista'>"Felhasználó lista".U_MENU_FLISTA.".</a></li>\n";
            $HTMLkod2 .= "</ul><br>\n";     
        }
        if ($_SESSION['AktFelhasznalo'.'FSzint']>5)  { 
            $HTMLkod2 .= "<ul class='Ul1'>\n";
            $HTMLkod2 .= "<li class='M1'><div>".U_MENU_KIEGTAK."</div></li>\n";             
            $HTMLkod2 .= "<li class='M1'><a href='?f0=kiegeszito_tartalom'>".U_MENU_KIEGT."</a></li>\n";    
            $HTMLkod2 .= "<li class='M1'><a href='?f0=Fomenu_linkek_beallitasa'>".U_MENU_FMENU."</a></li>\n"; 
            $HTMLkod2 .= "<li class='M1'><a href='?f0=menuplusz'>".U_MENU_MENUPL."</a></li>\n";   
            $HTMLkod2 .= "</ul><br>\n";  
        }
        if ($_SESSION['AktFelhasznalo'.'FSzint']>6)  { 
            $HTMLkod2 .= "<ul class='Ul1'>\n";
            $HTMLkod2 .= "<li class='M1'><div>".U_MENU_ALAPINF."</div></li>\n";              
            $HTMLkod2 .= "<li class='M1'><a href='?f0=alapbeallitasok'>".U_MENU_ALAPBEALL."</a></li>\n";             
            $HTMLkod2 .= "</ul>\n"; 
            
            $HTMLkod2 .= "<div class='divMenuInfo1'>".U_MENU_TARTALOM."</div>\n";
        }
        if ($HTMLkod2 != '') {$HTMLkod1 .= $HTMLkod2;}
        
        $HTMLkod1 .= getFoMenuPLHTML();
        
        // ================ KATEGÓRIÁK ÉS HÍROLDALAK TÖBBSZINTŰ LISTÁJA ============================= 
   // ================ KATEGÓRIÁK ÉS HÍROLDALAK TÖBBSZINTŰ LISTÁJA ============================= 
        $HTMLkod      = '';
        //Elso szint >> Szülő a kezdőlap
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=1 AND (OTipus<10 OR OTipus>100) order by OPrioritas DESC, ONev"; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        $rowDB       = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            while($row  = mysqli_fetch_array($result)) {
                $ONev   = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; 
                $OSzulo = $row['OSzuloId'];
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
            mysqli_free_result($result);
        }
        $HTMLkod .= "<li class='M1'><a href='?f0=Archivum'>".U_AB_ARCH."</a></li>\n";
        $HTMLkod .= "<li class='M1'><a href='?f0=oldalterkep'>".U_AB_OTERKEP."</a></li>\n"; 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul1'>\n $HTMLkod  </ul>\n";}     
   
        $HTMLkod  .= getMenuPluszHTML();
        return $HTMLkod1.$HTMLkod;
    }

    function Menu_Szint2($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Második szint 
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by OPrioritas DESC, ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        $rowDB       = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            while($row  = mysqli_fetch_array($result)) {
                $ONev   = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId'];
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
            mysqli_free_result($result); 
        }
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul2'>\n $HTMLkod  </ul>\n";}         
        return $HTMLkod;
    }
    
    function Menu_Szint3($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Harmadik szint
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by OPrioritas DESC, ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        $rowDB       = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            while($row = mysqli_fetch_array($result)) {
                $ONev  = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
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
            mysqli_free_result($result);
        }
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul3'>\n $HTMLkod  </ul>\n";}
        return $HTMLkod;
    }
    
    function Menu_Szint4($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Negyedik szint 
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by OPrioritas DESC, ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        $rowDB       = mysqli_num_rows($result); 
        if ($rowDB > 0) {
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
            mysqli_free_result($result); 
        }
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul4'>\n $HTMLkod  </ul>\n";}
        return $HTMLkod;
    }
?>
