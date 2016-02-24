<?php

 $OldalCikk               = array();
 $OldalCikk['id']         = 0;
 $OldalCikk['Oid']        = 0; 
 $OldalCikk['Cid']        = 0; 
 $OldalCikk['CPrioritas'] = 0; 
 


function getCikkekHTML() {
    global $MySqliLink, $Aktoldal;
    $HTMLkod  = '';
    $Oid = $Aktoldal['id'];
    
    // Egyelőre az összes, az oldalhoz tartozó cikket megjelenítjük, később lapozunk
    
    $SelectStr = "SELECT C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.Cid= C.id
                        WHERE OC.Oid=$Oid
                        ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
    $result = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01");

    if ($_SESSION['AktFelhasznalo'.'FSzint']>2) {
        while ($row = mysqli_fetch_array($result)){
                $HTMLkod .= "<div class ='divCikkKulso'><h2>".$row['CNev']."</h2>\n";
                $HTMLkod .= "<div class = 'divCikkLiras'>".$row['CLeiras']."</div>\n";
                $HTMLkod .= "<div class = 'divCikkTartalom'>".$row['CTartalom']."</div>\n";
                $HTMLkod .= "<p class='pCszerzoNev'>".$row['CSzerzoNev']."</p><p class='pCModTime'>".$row['CModositasTime']."</p></div>\n";
        }
    } else {
        if ($_SESSION['AktFelhasznalo'.'FSzint']==2) {
            while ($row = mysqli_fetch_array($result)){
                if ($row['CLathatosag'] > 1 || $row['CSzerzo'] == $_SESSION['AktFelhasznalo'.'id']) {
                    $HTMLkod .= "<div class ='divCikkKulso'><h2>".$row['CNev']."</h2>\n";
                    $HTMLkod .= "<div class = 'divCikkLiras'>".$row['CLeiras']."</div>\n";
                    $HTMLkod .= "<div class = 'divCikkTartalom'>".$row['CTartalom']."</div>\n";
                    $HTMLkod .= "<p class='pCszerzoNev'>".$row['CSzerzoNev']."</p><p class='pCModTime'>".$row['CModositasTime']."</p></div>\n";
                }
            }
        }
        if ($_SESSION['AktFelhasznalo'.'FSzint']==1) {
            while ($row = mysqli_fetch_array($result)){
                if ($row['CLathatosag'] > 2 || $row['CSzerzo'] == $_SESSION['AktFelhasznalo'.'id']) {
                    $HTMLkod .= "<div class ='divCikkKulso'><h2>".$row['CNev']."</h2>\n";
                    $HTMLkod .= "<div class = 'divCikkLiras'>".$row['CLeiras']."</div>\n";
                    $HTMLkod .= "<div class = 'divCikkTartalom'>".$row['CTartalom']."</div>\n";
                    $HTMLkod .= "<p class='pCszerzoNev'>".$row['CSzerzoNev']."</p><p class='pCModTime'>".$row['CModositasTime']."</p></div>\n";
                }
            }
        }
    }
    
    
    
    
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
                    $UjCikk = false;
            } else {$UjCikk = true;}
            if (isset($_POST['submitCikkForm']) && $_SESSION['ErrorStr']!=''){
                    $Cikk = false;
            } else {$Cikk = true;}
    $HTMLkod .= "<div id='divCikkek'>";
    if ($UjCikk && $Cikk && $TorolCikk && $CikkKep && !isset($_POST['submitCikkValaszt'])){
        $HTMLkod  .= "<input name='chFormkodCikk'  id='chFormkodCikk'   value='chFormkodCikk'   type='checkbox'>\n";
        $HTMLkod  .= "<label for='chFormkodCikk'   class='chLabel'    id='labelchFormkodCikk'>Cikk szerkesztése</label>\n";
    } else {
        $HTMLkod  .= "<input name='chFormkodCikk'  id='chFormkodCikk'   value='chFormkodCikk'   type='checkbox' checked >\n";
        $HTMLkod  .= "<label for='chFormkodCikk'   class='chLabel'    id='labelchFormkodCikk'>Cikk szerkesztése</label>\n";
    }
        $HTMLkod  .= "<div id='divFormkodCikk'>\n";
        if ($UjCikk){// ====UjCikkForm megjelenítését szabályozó input elem====
            $HTMLkod  .= "<input name='chCikkForm'  id='chUjCikkForm' value='chUjCikkForm' type='radio'>\n";
            $HTMLkod  .= "<label for='chUjCikkForm' class='chLabel'   id='labelUjCikkForm'>Új cikk</label>\n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'  id='chUjCikkForm' value='chUjCikkForm' type='radio' checked >\n";
            $HTMLkod  .= "<label for='chUjCikkForm' class='chLabel'   id='labelUjCikkForm'>Új cikk</label>\n";
        }
        if ($Cikk){//=======CikkForm megjelenítését szabályozó input elem=======
            $HTMLkod  .= "<input name='chCikkForm'  id='chCikkForm'  value='chCikkForm'   type='radio'>\n";
            $HTMLkod  .= "<label for='chCikkForm'   class='chLabel'  id='labelCikkForm'>Cikk módosítása</label>\n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'  id='chCikkForm'  value='chCikkForm'   type='radio' checked >\n";
            $HTMLkod  .= "<label for='chCikkForm'   class='chLabel'  id='labelCikkForm'>Cikk módosítása</label>\n";
        }
        if (1){//========CikkTorolForm megjelenítését szabályozó input elem========
            $HTMLkod  .= "<input name='chCikkForm'     id='chCikkTorolForm' value='chCikkTorolForm'  type='radio'>\n";
            $HTMLkod  .= "<label for='chCikkTorolForm' class='chLabel'      id='labelCikkTorolForm'>Cikk törlése</label>\n \n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'     id='chCikkTorolForm' value='chCikkTorolForm'  type='radio' checked >\n";
            $HTMLkod  .= "<label for='chCikkTorolForm' class='chLabel'      id='labelCikkTorolForm'>Cikk törlése</label>\n \n";
        }
        if (1){//=========CikkKepForm megjelenítését szabályozó input elem=========
            $HTMLkod  .= "<input name='chCikkForm'   id='chCikkKepForm' value='chCikkKepForm'  type='radio'>\n";
            $HTMLkod  .= "<label for='chCikkKepForm' class='chLabel'    id='labelCikkKepForm'>Cikk képeinek módosítása</label>\n \n";
        } else {
            $HTMLkod  .= "<input name='chCikkForm'   id='chCikkKepForm' value='chCikkKepForm'  type='radio' checked >\n";
            $HTMLkod  .= "<label for='chCikkKepForm' class='chLabel'    id='labelCikkKepForm'>Cikk képeinek módosítása</label>\n \n";
        }
        
        
        $HTMLkod  .= getCikkValasztForm();
        $HTMLkod  .= getUjCikkForm();
        $HTMLkod  .= getCikkForm();
        $HTMLkod  .= getCikkTorolForm();
        $HTMLkod  .= getCikkKepForm();                        // Élesítve!!!
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
