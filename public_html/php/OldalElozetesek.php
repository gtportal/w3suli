<?php

/**
 *
 * @author    Szabó Máté
 */

function getOElozetesekHTML() {
    global $MySqliLink, $Aktoldal;
    $HTMLkod  = '';
    $Oid      = $Aktoldal['id'];
    // Az oldalhoz kapcsolódó aloldalak neveit, kis képeit és rövíd leírásait kell itt megjeleníteni
    // Az aloldalak arról ismerhetők fel, hogy 'OSzuloId'-jükben az aktuális oldal 'id'-jét tárolják

    $SelectStr = "SELECT * FROM Oldalak WHERE OSzuloId=$Oid AND OTipus<3 ORDER BY OPrioritas DESC, ONev"; 
    $result    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba Oe 01");
    $rowDB     = mysqli_num_rows($result); 
    if ($rowDB > 0) { 
        while ($row = mysqli_fetch_array($result)){
            if ($row['ONev']){
                $HTMLkod .= "<div class ='divOElozetesKulso'>\n";
                if ($row['OImg']!='') {
                    if ($Aktoldal['OImgDir']!='') {
                        $KepUtvonal = "img/".$Aktoldal['OImgDir']."/";            
                      } else {
                        $KepUtvonal = "img/";    
                      }
                    $Src = $KepUtvonal.$row['OImg']; 
                    $HTMLkod .= "<div class = 'divOElozetesKep'><img src='$Src'  class = 'imgOE'></div>\n";                    
                } else {
                    $HTMLkod .= "<div class = 'divOElozetesKep'> </div>\n";
                }
                $HTMLkod .= "<h2>".$row['ONev']."</h2>\n";
                if ($row['OLeiras']!='') {$HTMLkod .= "<div class = 'divOElozetesLeir'>".$row['OLeiras']."\n";}
                $HTMLkod .= "<a href='?f0=".$row['OUrl']."'>".$row['ONev']." résztletesen...</a>\n";
                $HTMLkod .= "</div></div>\n";
                
            }
        }
    }
   
    
    return $HTMLkod;
}



?>
