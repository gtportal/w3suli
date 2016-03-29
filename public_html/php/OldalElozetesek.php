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
            
            //-------------------------------------------------------------------------------------
            //OLDAL LÁTHATÓSÁGÁNAK VIZSGÁLATA
            //-------------------------------------------------------------------------------------

            if(getOMenuLathatosagTeszt($row['id'])>0){
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

                    $HTMLkod .= "<div class='divOElozetesTartalom'>\n";
                    $HTMLkod .= "<h3>".$row['ONev']."</h3>\n";
                    if ($row['OLeiras']!='') {$HTMLkod .= "<div class = 'divOElozetesLeir'>".$row['OLeiras']."\n";}
                    $HTMLkod .= "</div>\n";
                    $HTMLkod .= "<a href='?f0=".$row['OUrl']."' class='OElink'>".$row['ONev']." résztletesen...</a>\n";
                    $HTMLkod .= "</div>\n";
                    $HTMLkod .= "</div>\n";

                }
            }    
        }
    }
   
     if ($HTMLkod!='') {$HTMLkod = "<div id='divOElozetesekKulso'>\n$HTMLkod </div>\n";} // Az összes előzetest becsomagoljuk
    return $HTMLkod;
}



?>
