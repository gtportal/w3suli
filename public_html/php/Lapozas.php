<?php


function getKatLapinfo($MaxDBperOldal) {
    global $MySqliLink, $Aktoldal, $oURL, $oLap;
    $AktLap = 1;
    if ($_SESSION['LapozKat'.'OUrl']  == $Aktoldal['OUrl']) {$AktLap = $_SESSION['LapKat'.'CT'];}
    if ($AktLap>0) {$AktLap  = $oLap; }
    $arrGyermekek            = array(); 
    $arrLapinfo              = array();
    $arrLapinfo['SelectStr'] = '';
    $arrLapinfo['OsszDB']    = '';
    $arrLapinfo['LapozHTML'] = '';
  
    $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=".$Aktoldal['id']." AND OTipus<10 order by OPrioritas DESC, ONev"; 
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba LInf 01 ");
    $MaxOldal    = mysqli_num_rows($result);
    if ($MaxOldal > 0) {        
        while($row   = mysqli_fetch_array($result)) {
            if(getOMenuLathatosagTeszt($row['id'])>0){
                $arrGyermekek[] = $row['id'];
            }
        } 
        if ($MaxOldal > $MaxDBperOldal) {
            $MaxLap         = ($MaxOldal-1) / $MaxDBperOldal;      
            settype($MaxLap, "integer"); $MaxLap++;          
            if ($AktLap>$MaxLap) {$AktLap=$MaxLap;} if ($AktLap<1) {$AktLap=1;}

            $AktElsoOldal   = (($AktLap-1) * $MaxDBperOldal);
            $AktUtolsoOldal = ($AktLap * $MaxDBperOldal);    
            if ($AktUtolsoOldal>$MaxOldal) {$AktUtolsoOldal=$MaxOldal;}
            $OldalSzam      = $AktUtolsoOldal-$AktElsoOldal;
            $arrIdLista     = array_slice($arrGyermekek, $AktElsoOldal,$OldalSzam);
            $strIdLista     = implode(",", $arrIdLista);
            $arrLapinfo['SelectStr'] = "SELECT * FROM Oldalak WHERE id IN ($strIdLista)";        

            $EllsoLap       = $AktLap - 5;     if ($EllsoLap<1) {$EllsoLap=1;}
            $UtolsoLap      = $EllsoLap - 10;  if ($UtolsoLap<$MaxLap) {$UtolsoLap=$MaxLap;}
            $_SESSION['LapozKat'.'OUrl']  == $Aktoldal['OUrl'];
            $_SESSION['LapozKat'.'CT']    == $AktLap;

            // Gyors vissza
            $LapozHTML = '';
            if ($AktLap > 5)   {$AktLap1 = $AktLap-5;
                 if ($AktLap1>1) {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt;&lt; </a></li>";} else
                                 {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt;&lt; </a></li>";}
            }     
            // Vissza
            if ($AktLap   > 2) {
                $AktLap1 = $AktLap-1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt; </a></li>";            
            } 
            if ($AktLap  == 2) {
                $AktLap1 = $AktLap-1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt; </a></li>";            
            } 
            // Számozott
            if ($AktLap  ==1) {$AktLink=" class='AktLink' ";} else {$AktLink="";}
            $LapozHTML   .= "<li><a href='?f0=".$Aktoldal['OUrl']."' $AktLink> 1 </a></li>";
            for ($i=$EllsoLap+1;$i<=$UtolsoLap;$i++) {
                if ($AktLap==$i) {$AktLink=" class='AktLink' ";} else {$AktLink="";}
                $LapozHTML .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$i' $AktLink> $i </a></li>";            
            }
            // Előre
            if ($AktLap   < $MaxLap) {
                $AktLap1 = $AktLap+1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt; </a></li>";            
            }
            // Gyors előre
            if ($AktLap+5 <= $MaxLap) {
                $AktLap1 = $AktLap+5; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt;&gt; </a></li>";            
            }        
            $LapozHTML = "<div class='divOLapozas'>$LapozHTML </div>";
        } else {
            $strIdLista     = implode(",", $arrGyermekek);
            $arrLapinfo['SelectStr'] = "SELECT * FROM Oldalak WHERE id IN ($strIdLista)";            
        }
    }
    if ($MaxOldal > 0) {mysqli_free_result($result);}

    $arrLapinfo['LapozHTML']= $LapozHTML;
    return $arrLapinfo;
}

function getCikkLapinfo($MaxDBperOldal) {
   global $MySqliLink, $Aktoldal, $oURL, $oLap, $oCim;
    $AktLap = 1;
    if ($_SESSION['LapozCikk'.'OUrl']  == $Aktoldal['OUrl']) 
                   {$AktLap  = $_SESSION['LapozCikk'.'CT'];}
    if ($AktLap>0) {$AktLap  = $oLap; }
    $arrGyermekek            = array(); 
    $arrLapinfo              = array();
    $arrLapinfo['SelectStr'] = '';
    $arrLapinfo['OsszDB']    = '';
    $arrLapinfo['LapozHTML'] = '';
  
    if ($_SESSION['AktFelhasznalo'.'FSzint']>2) {
    $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.Cid= C.id
                        WHERE OC.Oid=".$Aktoldal['id']."
                        ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
    }
 
    if ($_SESSION['AktFelhasznalo'.'FSzint']==2) {
    $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.Cid= C.id
                        WHERE 
                           OC.Oid=".$Aktoldal['id']." AND
                           (C.CSzerzo=".$_SESSION['AktFelhasznalo'.'id']."  OR  
                           C.CLathatosag>1 )    
                        ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
    }    
    
   if ($_SESSION['AktFelhasznalo'.'FSzint']==1) {
    $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.Cid= C.id
                        WHERE 
                           OC.Oid=".$Aktoldal['id']." AND
                           C.CLathatosag>2     
                        ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
    }    
    
    $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba CLInf 01 ");
    $MaxCikk       = mysqli_num_rows($result);
    if ($MaxCikk > 0) {    
        $CikkCT    = 0;
        $AktCikkCT = 0;
        while($row = mysqli_fetch_array($result)) {
            $arrGyermekek[] = $row['id'];
            $AktCikkURL     = $row['CNev'];
            // Az aktuális cikk sorszámának meghatározása !!!!!!!!!!!!!!!!!!!!!!
            $CikkCT++;  
        } 
        if ($MaxCikk > $MaxDBperOldal) {
            $MaxLap         = ($MaxCikk-1) / $MaxDBperOldal;      
            settype($MaxLap, "integer"); $MaxLap++;          
            if ($AktLap>$MaxLap) {$AktLap=$MaxLap;} if ($AktLap<1) {$AktLap=1;}

            $AktElsoCikk   = (($AktLap-1) * $MaxDBperOldal);
            $AktUtolsoCikk = ($AktLap * $MaxDBperOldal);    
            if ($AktUtolsoCikk>$MaxCikk) {$AktUtolsoCikk=$MaxCikk;}
            $CikkAktDBSzam  = $AktUtolsoCikk-$AktElsoCikk;
            $arrIdLista     = array_slice($arrGyermekek, $AktElsoCikk,$CikkAktDBSzam);
            $strIdLista     = implode(",", $arrIdLista);
            $arrLapinfo['SelectStr'] = "SELECT * FROM Cikkek WHERE id IN ($strIdLista)";        

            $EllsoLap       = $AktLap - 5;     if ($EllsoLap<1) {$EllsoLap=1;}
            $UtolsoLap      = $EllsoLap - 10;  if ($UtolsoLap<$MaxLap) {$UtolsoLap=$MaxLap;}
            $_SESSION['LapozCikk'.'OUrl']  == $Aktoldal['OUrl'];
            $_SESSION['LapozCikk'.'CT']    == $AktLap;

            // Gyors vissza
            $LapozHTML = '';
            if ($AktLap > 5)   {$AktLap1 = $AktLap-5;
                 if ($AktLap1>1) {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt;&lt; </a></li>";} else
                                 {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt;&lt; </a></li>";}
            }     
            // Vissza
            if ($AktLap   > 2) {
                $AktLap1 = $AktLap-1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt; </a></li>";            
            } 
            if ($AktLap  == 2) {
                $AktLap1 = $AktLap-1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt; </a></li>";            
            } 
            // Számozott
            if ($AktLap  ==1) {$AktLink=" class='AktLink' ";} else {$AktLink="";}
            $LapozHTML   .= "<li><a href='?f0=".$Aktoldal['OUrl']."' $AktLink> 1 </a></li>";
            for ($i=$EllsoLap+1;$i<=$UtolsoLap;$i++) {
                if ($AktLap==$i) {$AktLink=" class='AktLink' ";} else {$AktLink="";}
                $LapozHTML .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$i' $AktLink> $i </a></li>";            
            }
            // Előre
            if ($AktLap   < $MaxLap) {
                $AktLap1 = $AktLap+1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt; </a></li>";            
            }
            // Gyors előre
            if ($AktLap+5 < $MaxLap) {
                $AktLap1 = $AktLap+5; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt;&gt; </a></li>";            
            }        
            $LapozHTML = "<div class='divOLapozas'>$LapozHTML </div>";

        } else {
            $strIdLista     = implode(",", $arrGyermekek);
            $arrLapinfo['SelectStr'] = "SELECT * FROM Cikkek WHERE id IN ($strIdLista)";   
        }       
    }
    if ($MaxCikk > 0) {mysqli_free_result($result);}
    $arrLapinfo['LapozHTML']= $LapozHTML;
    return $arrLapinfo;
}

?>