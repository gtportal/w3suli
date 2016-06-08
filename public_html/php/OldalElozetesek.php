<?php

/**
 *
 * @author    Szabó Máté
 */

function getOElozetesekHTML($SelStr) {
    global $MySqliLink, $Aktoldal, $AlapAdatok;
    $HTMLkod  = '';
    $Oid      = $Aktoldal['id'];
    $AlapKep  = 'img/ikonok/HeaderImg/'.$AlapAdatok['HeaderImg'];
    // Az oldalhoz kapcsolódó aloldalak neveit, kis képeit és rövíd leírásait kell itt megjeleníteni
    // Az aloldalak arról ismerhetők fel, hogy 'OSzuloId'-jükben az aktuális oldal 'id'-jét tárolják

    $SelectStr = $SelStr; 
    $result    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba Oe 01");
    $rowDB     = mysqli_num_rows($result); 
    if ($rowDB > 0) { 
        while ($row = mysqli_fetch_array($result)){            
            //-------------------------------------------------------------------------------------
            //OLDAL LÁTHATÓSÁGÁNAK VIZSGÁLATA
            //-------------------------------------------------------------------------------------
            if ($row['ONev']){
                $HTMLkod .= "<div class ='divOElozetesKulso'>\n";
                if ($row['OImg']!='') {
                    if ($row['OImgDir']!='') {
                        $KepUtvonal = "img/oldalak/".$row['OImgDir']."/";            
                      } else {
                        $KepUtvonal = "img/oldalak/";    
                      }
                    $Src = $KepUtvonal.$row['OImg']; 
                    $HTMLkod .= "<div class = 'divOElozetesKep'><img src='$Src'  class = 'imgOE' alt='' ></div>\n";                    
                } else {
                    $HTMLkod .= "<div class = 'divOElozetesKep'><img src='$AlapKep'  class = 'imgOE' alt='' ></div>\n";
                }
                $HTMLkod .= "<div class='divOElozetesTartalom'>\n";
                $HTMLkod .= "<h3>".$row['ONev']."</h3>\n";
                if ($row['OLeiras']!='') {$HTMLkod .= "<div class = 'divOElozetesLeir'>".$row['OLeiras']."</div>\n";}
                $HTMLkod .= "<a href='?f0=".$row['OUrl']."' class='OElink'>".$row['ONev']." részletesen...</a>\n";
                $HTMLkod .= "</div>\n";
                $HTMLkod .= "</div>\n";
            }                
        }
        mysqli_free_result($result);
    }   
    if ($HTMLkod!='') {$HTMLkod = "<div id='divOElozetesekKulso'>\n$HTMLkod </div>\n";} // Az összes előzetest becsomagoljuk
    return $HTMLkod;
}



?>
