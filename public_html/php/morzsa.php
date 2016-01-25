<?php

function getMorzsaHTML() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $DedSzuloId;
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
              ) {$Aktiv = true;} else {$Aktiv = false;}
           
           //Ha az adott oldal vagy annak egy leszármazottja aktív, akkor leszármazottjait is megjelenítjük
           if ($Aktiv) {
               $HTMLkod .= Menu_Szint2($OID);
            $HTMLkod .= "<a href='?f0=$OURL' >$ONev</a> | ";
           }
           $HTMLkod .= "\n"; 
        } 
        if ($HTMLkod > '') {$HTMLkod = "<ul class='Ul1'>\n $HTMLkod  </ul>\n";}
        mysqli_free_result($result);  
        return $HTMLkod1.$HTMLkod;
    }
    
    
    
    
    
    
    
?>