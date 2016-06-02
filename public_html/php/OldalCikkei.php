<?php
/*
 $OldalCikk               = array();
 $OldalCikk['id']         = 0;
 $OldalCikk['Oid']        = 0; 
 $OldalCikk['Cid']        = 0; 
 $OldalCikk['CPrioritas'] = 0; 
*/ 
 // A tartalomban lecseréli a #0, #1, #2 .... kódokat img elemekre
 
function getCikkepCsereL($Cid,$CTartalom,$KepUtvonal) {
    global $MySqliLink, $Aktoldal;
    $HTMLkod      = '';    
    
    $SelectStr = "SELECT KNev, KFile, KSzelesseg, KMagassag, KStilus FROM CikkKepek WHERE Cid=$Cid ORDER BY KSorszam DESC";
    $result    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01");
    $HTMLHirKepTMB = array();
    $i = 0;
    while ($row = mysqli_fetch_array($result)){
        $Src      = $KepUtvonal.$row['KFile'];
        $KNev     = $row['KNev']; 
        $KepMeret = '';
        if ($row['KSzelesseg']>0)   {$KepMeret = "style='max-width:".$row['KSzelesseg']."px;'";} else {
           if ($row['KMagassag']>0) {$KepMeret = "style='max-height:".$row['KMagassag']."px;'";}             
        }
        $KepStilus= " KepStyle".$row['KStilus']." ";
        $imgkod   = "<div class = 'divCikkKepN $KepStilus' $KepMeret >";
        $imgkod  .= "<img src='$Src'  class = 'imgCikkKepN $KepStilus' alt='$KNev' $KepMeret>"; //echo "<h1>KFile ".$row['KFile']."Src $Src</h1>";
        $imgkod  .= "</div>\n";
        $HTMLHirKepTMB[$i] = $imgkod;
        $i++;
    }  
    $arr          = array( "#1" => "$HTMLHirKepTMB[0]", "#2" => "$HTMLHirKepTMB[1]", "#3" => "$HTMLHirKepTMB[2]", 
                           "#4" => "$HTMLHirKepTMB[3]", "#5" => "$HTMLHirKepTMB[4]", "##" => "");  
    $HTMLkod      = strtr($CTartalom ,$arr);
    return $HTMLkod;            
}

function getCikkekHTML($SelStr) {
    global $MySqliLink, $Aktoldal;
    $HTMLkod  = '';
    $Oid      = $Aktoldal['id']; 
    if ($Aktoldal['OImgDir']!='') {
      $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/";
    } else {
      $KepUtvonal = "img/oldalak/";
    }    
    // Egyelőre az összes, az oldalhoz tartozó cikket megjelenítjük, később lapozunk    
    $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.Cid= C.id
                        WHERE OC.Oid=$Oid
                        ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
    $SelectStr = $SelStr;
    $result    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01a");
    if ($_SESSION['AktFelhasznalo'.'FSzint']>2) {
        while ($row = mysqli_fetch_array($result)){     
                $Horgony   = "<a name='".getTXTtoURL($row['CNev'])."'></a>";
                
                $CTartalom = getCikkepCsereL($row['id'],$row['CTartalom'],$KepUtvonal);  // Képek beillesztése #0, #1,.. helyére   
                $CTartalom = SzintaxisCsere($CTartalom);
                $HTMLkod .= "<div class ='divCikkKulso'>$Horgony<h2>".$row['CNev']."</h2>\n";
                
                //$HTMLkod .= "<h2>".getTXTtoURL($row['CNev'])."</h2>\n";                   //!!!!!!!!!!!!!!!!!!!!!!!!!!!      
                
               // $HTMLkod .= "<div class = 'divCikkLiras'>".$row['CLeiras']."</div>\n";
                $HTMLkod .= "<div class = 'divCikkTartalom'>\n";
                $HTMLkod .= $CTartalom."\n";
                $HTMLkod .= getCikkKepekHTML($row['id']);
                $HTMLkod .= "</div>\n";
                $HTMLkod .= "<p class='pCszerzoNev'> Szerző: ".$row['CSzerzoNev']."</p><p class='pCModTime'>Közzétéve: ".$row['CModositasTime']."</p></div>\n";
        }
    } else {
        if ($_SESSION['AktFelhasznalo'.'FSzint']==2) {
            while ($row = mysqli_fetch_array($result)){
                if ($row['CLathatosag'] > 1 || $row['CSzerzo'] == $_SESSION['AktFelhasznalo'.'id']) {
                    $Horgony   = "<a name='".getTXTtoURL($row['CNev'])."'></a>";
                   // $CTartalom = SzintaxisCsere($CTartalom);
                    $CTartalom = getCikkepCsereL($row['id'],$row['CTartalom'],$KepUtvonal);  // Képek beillesztése #0, #1,.. helyére     
                    $CTartalom = SzintaxisCsere($CTartalom);
                    $HTMLkod  .= "<div class ='divCikkKulso'>$Horgony<h2>".$row['CNev']."</h2>\n";
                   // $HTMLkod  .= "<div class = 'divCikkLiras'>".$row['CLeiras']."</div>\n";
                    $HTMLkod  .= "<div class = 'divCikkTartalom'>\n";
                    $HTMLkod  .= $CTartalom."\n";
                    $HTMLkod  .= getCikkKepekHTML($row['id']);
                    $HTMLkod  .= "</div>\n";
                    $HTMLkod  .= "<p class='pCszerzoNev'> Szerző: ".$row['CSzerzoNev']."</p><p class='pCModTime'>Közzétéve: ".$row['CModositasTime']."</p></div>\n";
                }
            }
        }
        if ($_SESSION['AktFelhasznalo'.'FSzint']==1) {
            while ($row = mysqli_fetch_array($result)){
                if ($row['CLathatosag'] > 2 || $row['CSzerzo'] == $_SESSION['AktFelhasznalo'.'id']) { 
                    $Horgony   = "<a name='".getTXTtoURL($row['CNev'])."'></a>";
                   // $CTartalom = SzintaxisCsere($CTartalom);
                    $CTartalom = getCikkepCsereL($row['id'],$row['CTartalom'],$KepUtvonal);  // Képek beillesztése #0, #1,.. helyére
                    $CTartalom = SzintaxisCsere($CTartalom);
                    $HTMLkod .= "<div class ='divCikkKulso'>$Horgony<h2>".$row['CNev']."</h2>\n";
                    $HTMLkod .= "<div class = 'divCikkTartalom'>\n";
                    $HTMLkod .= $CTartalom."\n";
                    $HTMLkod .= getCikkKepekHTML($row['id']);
                    $HTMLkod .= "</div>\n";
                    $HTMLkod .= "<p class='pCszerzoNev'> Szerző: ".$row['CSzerzoNev']."</p><p class='pCModTime'>Közzétéve: ".$row['CModositasTime']."</p></div>\n";
                }
            }
        }
    }
    
    if ($HTMLkod!='') {$HTMLkod = "<div id='divCikkekKulso'>\n$HTMLkod </div>\n";} // Az összes cikkek becsomagoljuk
    
    
    return $HTMLkod;
}


function getCikkekForm() {
    $HTMLkod  = '';
    // Új cikk és
    // a $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk kezelése
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1) {  // Meg. A FSzint vizsgálata FONTOS!!! Később még a tulajdonossal bővűl.
            $UjCikk = true;
            $Cikk = true;
            $TorolCikk = true;
            $CikkKep = true;
            if (isset($_POST['submitUjCikkForm']) && $_SESSION['ErrorStr']!=''){
                    $UjCikk    = false;
            } else {$UjCikk    = true;}
            if (isset($_POST['submitCikkForm']) && $_SESSION['ErrorStr']!=''){
                    $Cikk      = false;
            } else {$Cikk      = true;}
            if (isset($_POST['submitCikkTorol']) && $_SESSION['ErrorStr']!=''){
                    $TorolCikk = false;
            } else {$TorolCikk = true;}
            if ((isset($_POST['submit_CikkKepekFeltoltForm']) || isset($_POST['submitCikkKepForm'])) && $_SESSION['ErrorStr']!=''){
                    $CikkKep   = false;
            } else {$CikkKep   = true;}
    $HTMLkod .= "<div id='divCikkek'>";
    
    if (isset($_POST['submitCikkValaszt']) || isset($_POST['submitUjCikkForm']) || isset($_POST['submitCikkForm']) ||
        isset($_POST['submitCikkTorol']) || isset($_POST['submit_CikkKepekFeltoltForm']) || isset($_POST['submitCikkKepForm'])                 
       ){    
        $HTMLkod  .= "<input name='chFormkodCikk'  id='chFormkodCikk'   value='chFormkodCikk'   type='checkbox' checked >\n";
        $HTMLkod  .= "<label for='chFormkodCikk'   class='chLabel'    id='labelchFormkodCikk'>Cikk szerkesztése</label>\n";
    } else {
        $HTMLkod  .= "<input name='chFormkodCikk'  id='chFormkodCikk'   value='chFormkodCikk'   type='checkbox'  >\n";
        $HTMLkod  .= "<label for='chFormkodCikk'   class='chLabel'    id='labelchFormkodCikk'>Cikk szerkesztése</label>\n";
    }
    $HTMLkod  .= getCikkValasztForm();
        $HTMLkod  .= "<div id='divFormkodCikk'>\n";
        if ($_POST['submitUjCikkForm']){//=====UjCikkForm megjelenítését szabályozó input elem=====
            $HTMLkod  .= "<input name='chCikkForm'  id='chUjCikkForm' value='chUjCikkForm' type='radio' checked>\n";
            $HTMLkod  .= "<label for='chUjCikkForm' class='chLabel'   id='labelUjCikkForm'>Új cikk</label>\n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'  id='chUjCikkForm' value='chUjCikkForm' type='radio'  >\n";
            $HTMLkod  .= "<label for='chUjCikkForm' class='chLabel'   id='labelUjCikkForm'>Új cikk</label>\n";
        }
        if (isset($_POST['submitCikkForm'])){//=======CikkForm megjelenítését szabályozó input elem=======
            $HTMLkod  .= "<input name='chCikkForm'  id='chCikkForm'  value='chCikkForm'   type='radio' checked>\n";
            $HTMLkod  .= "<label for='chCikkForm'   class='chLabel'  id='labelCikkForm'>Cikk módosítása</label>\n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'  id='chCikkForm'  value='chCikkForm'   type='radio'  >\n";
            $HTMLkod  .= "<label for='chCikkForm'   class='chLabel'  id='labelCikkForm'>Cikk módosítása</label>\n";
        }
        if (isset($_POST['submitCikkTorol'])){//==CikkTorolForm megjelenítését szabályozó input elem==
            $HTMLkod  .= "<input name='chCikkForm'     id='chCikkTorolForm' value='chCikkTorolForm'  type='radio' checked>\n";
            $HTMLkod  .= "<label for='chCikkTorolForm' class='chLabel'      id='labelCikkTorolForm'>Cikk törlése</label>\n \n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'     id='chCikkTorolForm' value='chCikkTorolForm'  type='radio'  >\n";
            $HTMLkod  .= "<label for='chCikkTorolForm' class='chLabel'      id='labelCikkTorolForm'>Cikk törlése</label>\n \n";
        }
        if (isset($_POST['submit_CikkKepekFeltoltForm']) || isset($_POST['submitCikkKepForm'])) {//====CikkKepForm megjelenítését szabályozó input elem====
            $HTMLkod  .= "<input name='chCikkForm'   id='chCikkKepForm' value='chCikkKepForm'  type='radio' checked>\n";
            $HTMLkod  .= "<label for='chCikkKepForm' class='chLabel'    id='labelCikkKepForm'>Cikk képeinek módosítása</label>\n \n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'   id='chCikkKepForm' value='chCikkKepForm'  type='radio'  >\n";
            $HTMLkod  .= "<label for='chCikkKepForm' class='chLabel'    id='labelCikkKepForm'>Cikk képeinek módosítása</label>\n \n";
        } 
        
       // $HTMLkod  .= getCikkValasztForm();
        $HTMLkod  .= getUjCikkForm();
        $HTMLkod  .= getCikkForm();
        $HTMLkod  .= getCikkTorolForm();
        $HTMLkod  .= getCikkKepForm();
        $HTMLkod  .= "</div>\n";
    $HTMLkod  .= "</div>\n";
    //...
  
    }
    return $HTMLkod;
}


function setCikkekLista($Oid) {
    trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
