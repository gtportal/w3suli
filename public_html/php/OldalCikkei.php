<?php

// A tartalomban lecseréli a #0, #1, #2 .... kódokat img elemekre
function getCikkepCsereL($Cid,$CTartalom,$KepUtvonal) {
    global $MySqliLink, $Aktoldal;
    $HTMLkod       = '';    
    $SelectStr     = "SELECT KNev, KFile, KSzelesseg, KMagassag, KStilus FROM CikkKepek WHERE Cid=$Cid ORDER BY KSorszam";
    $result        = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01");
    $HTMLHirKepTMB = array('','','','','');
    $i             = 0;
    $rowDB         = mysqli_num_rows($result); 
    if ($rowDB > 0) {
        while ($row   = mysqli_fetch_array($result)){
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
        mysqli_free_result($result);
    }
    $arr          = array( "#1" => "$HTMLHirKepTMB[0]", "#2" => "$HTMLHirKepTMB[1]", "#3" => "$HTMLHirKepTMB[2]", 
                           "#4" => "$HTMLHirKepTMB[3]", "#5" => "$HTMLHirKepTMB[4]", "#6" => "$HTMLHirKepTMB[5]",
                           "#7" => "$HTMLHirKepTMB[6]", "#8" => "$HTMLHirKepTMB[7]", "#9" => "$HTMLHirKepTMB[8]",
                          "#10" => "$HTMLHirKepTMB[9]", "##" => "");  
    $HTMLkod      = strtr($CTartalom ,$arr);
    $HTMLkod      = getDokumentumCsereL($Cid,$HTMLkod,$KepUtvonal);
    return $HTMLkod;            
}

function getDokumentumCsereL($Cid,$CTartalom,$KepUtvonal) {
    global $MySqliLink, $Aktoldal;
    $HTMLkod       = '';    
    $SelectStr     = "SELECT DNev, DFile, DLeiras, DMeretKB, DFFile FROM CikkDokumentumok WHERE Cid=$Cid ORDER BY DSorszam"; //echo "SelectStr:" .$SelectStr ."<br>";
   
    $result        = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01y123");
    $HTMLHirDocTMB = array('','','','','');
    $i             = 0;
    $rowDB         = mysqli_num_rows($result); 
    if ($rowDB > 0) {
        while ($row   = mysqli_fetch_array($result)){
            $Src      = $KepUtvonal."doc/".$row['DFile'];
            $DNev     = $row['DNev']; 
            $DLeiras  = $row['DLeiras']; 
            $DMeretKB = $row['DMeretKB'];
            $DLinkKod   = "<a href='$Src'>$DNev</a><span> ($DMeretKB KB)</span>";
            $HTMLHirDocTMB[$i] = $DLinkKod;
            $i++;
        }
        mysqli_free_result($result);
    }
    $arr          = array( "#D1" => "$HTMLHirDocTMB[0]", "#D2" => "$HTMLHirDocTMB[1]", "#D3" => "$HTMLHirDocTMB[2]", 
                           "#D4" => "$HTMLHirDocTMB[3]", "#D5" => "$HTMLHirDocTMB[4]");  
    $HTMLkod      = strtr($CTartalom ,$arr);
    return $HTMLkod;            
}

function getCikkekHTML($SelStr) {
    global $MySqliLink, $Aktoldal;
    $HTMLkod      = '';
    $Oid          = $Aktoldal['id']; 
    if ($Aktoldal['OImgDir']!='') {
      $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/";
    } else {
      $KepUtvonal = "img/oldalak/";
    }    
    $SelectStr    = $SelStr;
    $result       = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01a");
    $rowDB        = mysqli_num_rows($result);
    if (($_SESSION['AktFelhasznalo'.'FSzint']>0) && ($rowDB>0)) {
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
        mysqli_free_result($result);
    }   
    if ($HTMLkod!='') {$HTMLkod = "<div id='divCikkekKulso'>\n$HTMLkod </div>\n";} // Az összes cikkek becsomagoljuk    
    
    return $HTMLkod;
}


function getCikkekForm() {
    $HTMLkod  = '';
    // Új cikk és
     
    if ($_SESSION['AktFelhasznalo'.'FSzint']==2) {
        if (2==getOLathatosagTeszt()) { $_SESSION['AktFelhasznalo'.'FSzint'] = 3;}            
    }
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>2) {  // Meg. A FSzint vizsgálata FONTOS!!! Később még a tulajdonossal bővűl.

        $HTMLkod .= "<div id='divCikkek'>";
        if (isset($_POST['submitCikkValaszt']) || isset($_POST['submitUjCikkForm'])            || isset($_POST['submitCikkForm']) ||
            isset($_POST['submitCikkTorol'])   || isset($_POST['submit_CikkKepekFeltoltForm']) || isset($_POST['submitCikkKepForm']) ||
                                                  isset($_POST['submit_CikkDokokFeltoltForm']) || isset($_POST['submitCikkDokForm'])                
           ){    
            $HTMLkod  .= "<input name='chFormkodCikk'  id='chFormkodCikk' value='chFormkodCikk'   type='checkbox' checked >\n";
            $HTMLkod  .= "<label for='chFormkodCikk'   class='chLabel'    id='labelchFormkodCikk'>".U_CIKK_SZERK."</label>\n";
        } else {
            $HTMLkod  .= "<input name='chFormkodCikk'  id='chFormkodCikk' value='chFormkodCikk'   type='checkbox'  >\n";
            $HTMLkod  .= "<label for='chFormkodCikk'   class='chLabel'    id='labelchFormkodCikk'>".U_CIKK_SZERK."</label>\n";
        }
        $HTMLkod  .= getCikkValasztForm();
        $HTMLkod  .= "<div id='divFormkodCikk'>\n";
        if (isset($_POST['submitUjCikkForm'])){//=====UjCikkForm megjelenítését szabályozó input elem=====
            $HTMLkod  .= "<input name='chCikkForm'  id='chUjCikkForm' value='chUjCikkForm' type='radio' checked>\n";
            $HTMLkod  .= "<label for='chUjCikkForm' class='chLabel'   id='labelUjCikkForm'>".U_CIKK_UJ."</label>\n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'  id='chUjCikkForm' value='chUjCikkForm' type='radio'  >\n";
            $HTMLkod  .= "<label for='chUjCikkForm' class='chLabel'   id='labelUjCikkForm'>".U_CIKK_UJ."</label>\n";
        }
        if (isset($_POST['submitCikkForm'])){//=======CikkForm megjelenítését szabályozó input elem=======
            $HTMLkod  .= "<input name='chCikkForm'  id='chCikkForm'  value='chCikkForm'   type='radio' checked>\n";
            $HTMLkod  .= "<label for='chCikkForm'   class='chLabel'  id='labelCikkForm'>".U_CIKK_MOD."</label>\n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'  id='chCikkForm'  value='chCikkForm'   type='radio'  >\n";
            $HTMLkod  .= "<label for='chCikkForm'   class='chLabel'  id='labelCikkForm'>".U_CIKK_MOD."</label>\n";
        }
        if (isset($_POST['submitCikkTorol'])){//==CikkTorolForm megjelenítését szabályozó input elem==
            $HTMLkod  .= "<input name='chCikkForm'     id='chCikkTorolForm' value='chCikkTorolForm'  type='radio' checked>\n";
            $HTMLkod  .= "<label for='chCikkTorolForm' class='chLabel'      id='labelCikkTorolForm'>".U_CIKK_TOR."</label>\n \n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'     id='chCikkTorolForm' value='chCikkTorolForm'  type='radio'  >\n";
            $HTMLkod  .= "<label for='chCikkTorolForm' class='chLabel'      id='labelCikkTorolForm'>".U_CIKK_TOR."</label>\n \n";
        }
        if (isset($_POST['submit_CikkKepekFeltoltForm']) || isset($_POST['submitCikkKepForm'])) {//====CikkKepForm megjelenítését szabályozó input elem====
            $HTMLkod  .= "<input name='chCikkForm'   id='chCikkKepForm' value='chCikkKepForm'  type='radio' checked>\n";
            $HTMLkod  .= "<label for='chCikkKepForm' class='chLabel'    id='labelCikkKepForm'>".U_CIKK_KEP."</label>\n \n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'   id='chCikkKepForm' value='chCikkKepForm'  type='radio'  >\n";
            $HTMLkod  .= "<label for='chCikkKepForm' class='chLabel'    id='labelCikkKepForm'>".U_CIKK_KEP."</label>\n \n";
        } 
        // Cikk dokumentumai
        if (isset($_POST['submit_CikkDokokFeltoltForm']) || isset($_POST['submitCikkDokForm'])) {//====CikkKepForm megjelenítését szabályozó input elem====
            $HTMLkod  .= "<input name='chCikkForm'   id='chCikkDokForm' value='chCikkDokForm'  type='radio' checked>\n";
            $HTMLkod  .= "<label for='chCikkDokForm' class='chLabel'    id='labelCikkDokForm'>".U_CIKK_DOK."</label>\n \n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'   id='chCikkDokForm' value='chCikkDokForm'  type='radio'  >\n";
            $HTMLkod  .= "<label for='chCikkDokForm' class='chLabel'    id='labelCikkDokForm'>".U_CIKK_DOK."</label>\n \n";
        } 

        $HTMLkod  .= getUjCikkForm();
        $HTMLkod  .= getCikkForm();
        $HTMLkod  .= getCikkTorolForm();
        $HTMLkod  .= getCikkKepForm();
        $HTMLkod  .= getCikkDokForm();
        $HTMLkod  .= "</div>\n";
        $HTMLkod  .= "</div>\n"; 
    }
    return $HTMLkod;
}


function setCikkekLista($Oid) {
    trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
