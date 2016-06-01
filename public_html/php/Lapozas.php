<?php


function getKatLapinfo($MaxDBperOldal) {
    global $MySqliLink, $Aktoldal, $oURL, $oLap;
    $AktLap = 1;
    if ($_SESSION['LapozKat'.'OUrl']  == $Aktoldal['OUrl']) {$AktLap = $_SESSION['LapKat'.'CT'];}
    if ($AktLap>0) {$AktLap = $oLap; }
    $AktLap = 6;    
    
    $arrGyermekek = array(); 
    $arrLapinfo   = array();
    $arrLapinfo['SelectStr']   = '';
    $arrLapinfo['OsszDB']      = '';
    $arrLapinfo['LapozHTML']   = '';
    echo "<h1>oURL: $oURL - oLap: $oLap</h1>";
    
    $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=".$Aktoldal['id']." AND OTipus<10 order by OPrioritas DESC, ONev"; 
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba LInf 01 ");
    $MaxOldal    = mysqli_num_rows($result);
    if ($MaxOldal > $MaxDBperOldal) {        
        while($row   = mysqli_fetch_array($result)) {
            if(getOMenuLathatosagTeszt($row['id'])>0){
                $arrGyermekek[] = $row['id'];
            }
        } 
        $MaxLap         = ($MaxOldal) / $MaxDBperOldal;      
        settype($MaxLap, "integer"); $MaxLap++;    $MaxLap++;         if ($AktLap>$MaxLap) {$AktLap=$MaxLap;} if ($AktLap<1) {$AktLap=1;}
        
        $AktElsoOldal   = (($AktLap-1) * $MaxDBperOldal);
        $AktUtolsoOldal = ($AktLap * $MaxDBperOldal);        if ($AktUtolsoOldal>$MaxOldal) {$AktUtolsoOldal=$MaxOldal;}
        $OldalSzam      = $AktUtolsoOldal-$AktElsoOldal;
        $arrIdLista     = array_slice($arrGyermekek, $AktElsoOldal,$OldalSzam);
        $strIdLista     = implode(",", $arrIdLista);
        $arrLapinfo['SelectStr'] = "SELECT * FROM Oldalak WHERE id IN ($strIdLista)";
        
        
        $EllsoLap       = $AktLap - 5;                         if ($EllsoLap<1) {$EllsoLap=1;}
        $UtolsoLap      = $EllsoLap - 10;                      if ($UtolsoLap<$MaxLap) {$UtolsoLap=$MaxLap;}
        $_SESSION['LapozKat'.'OUrl']  == $Aktoldal['OUrl'];
        $_SESSION['LapozKat'.'CT']    == $AktLap;
        
        // Gyors vissza
        $LapozHTML = '';
        if ($AktLap > 5)   {$AktLap1 = $AktLap-5;
             if ($AktLap1>1) {$LapozHTML  .= "<a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt;&lt; </a>";} else
                             {$LapozHTML  .= "<a href='?f0=".$Aktoldal['OUrl']."'> &lt;&lt; </a>";}
        }     
        // Vissza
        if ($AktLap   > 2) {$AktLap1 = $AktLap-1; $LapozHTML  .= "<a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt; </a>";} 
        if ($AktLap  == 2) {$AktLap1 = $AktLap-1; $LapozHTML  .= "<a href='?f0=".$Aktoldal['OUrl']."'> &lt; </a>";} 
        // Számozott
        $LapozHTML   .= "<a href='?f0=".$Aktoldal['OUrl']."'> 1 </a>";
        for ($i=$EllsoLap+1;$i<=$UtolsoLap;$i++) {$LapozHTML  .= "<a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$i'> $i </a>";}
        // Előre
        if ($AktLap   < $MaxLap) {$AktLap1 = $AktLap+1; $LapozHTML  .= "<a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt; </a>";}
        // Gyors előre
        if ($AktLap+5 < $MaxLap) {$AktLap1 = $AktLap+5; $LapozHTML  .= "<a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt;&gt; </a>";}        
        
        echo "<h1>AktElsoOldal: $AktElsoOldal - AktUtolsoOldal: $AktUtolsoOldal - MaxLap: $MaxLap</h1>  ";
        echo "<h1>EllsoLap: $EllsoLap - UtolsoLap: $UtolsoLap - strIdLista: $strIdLista</h1>  ";
        echo "<h1>".$arrLapinfo['SelectStr']."</h1>  $LapozHTML";
    }
    if ($MaxOldal > 0) {mysqli_free_result($result);}
print_r($arrGyermekek);
    
    return $Lapinfo;
}

function getikkLapinfo($oURL,$oLap) {
    $Lapinfo = array(); 
    
   
    return $Lapinfo;
}

?>