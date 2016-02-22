<?php

/**
 * XXX detailed description test módosítva 1
 *
 * @author    XXX
 * @version   XXX
 * @copyright XXX
 */

function getOldalterkepHTML() {
    // A webhely oldalainak nevét és linkjét kell kiíratni struktúrált formában
    // Minte a menu.php - itt azonban nem különböztetjük meg az aktuális oldalt
    global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
 
    $HTMLkod  = '';
    $HTMLkod .= "<h1>getOldalterkepHTML</h1>";
    
   
        $HTMLkod      = '';
        //Elso szint >> Szülő a keszdőlap
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=1 AND OTipus<10 order by OPrioritas DESC, ONev"; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
           //Ha az adott oldal vagy annak első gyermeke aktív, akkor az 'AktLink' osztályba kerül
           if ($OID==$Aktoldal['id']       || 
               $OID==$SzuloOldal['id']     ||
               $OID==$NagyszuloOldal['id'] ||
               $OID==$DedSzuloId   
              ) {$AktLink = "class='AktLink'";} else {$AktLink = "";}
           $HTMLkod .= "<li class='M1a'><a href='?f0=$OURL' $AktLink>$ONev</a>";
           //Ha az adott oldal vagy annak egy leszármazottja aktív, akkor leszármazottjait is megjelenítjük
           $HTMLkod .= Oldalterkep_Szint2($OID);
           $HTMLkod .= "</li>\n"; 
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul1a'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);     
    
    return $HTMLkod;
}

function Oldalterkep_Szint2($OID) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
        $HTMLkod      = '';
        //Második szint 
        $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$OID order by OPrioritas DESC, ONev "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        while($row   = mysqli_fetch_array($result)) {
            $ONev = $row['ONev']; $OURL = $row['OUrl']; $OID  = $row['id']; $OSzulo = $row['OSzuloId']; 
           //Ha az adott oldal vagy annak első gyermeke aktív, akkor az 'AktLink' osztályba kerül
           if ($OID==$Aktoldal['id']      || 
               $OID==$SzuloOldal['id']    ||
               $OID==$NagyszuloOldal['id'] ||
               $OID==$DedSzuloId   
              ) {$AktLink = "class='AktLink'";} else {$AktLink = "";}
           $HTMLkod .= "<li class='M2a'><a href='?f0=$OURL' $AktLink>$ONev</a>";
           //Ha az adott oldal vagy annak egy leszármazottja aktív, akkor leszármazottjait is megjelenítjük
           //$HTMLkod .= Oldalterkep_Szint3($OID);
           $HTMLkod .= "</li>\n";           
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul2a'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod;
        }



?>
