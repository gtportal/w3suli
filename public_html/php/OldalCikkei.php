<?php

 $OldalCikk               = array();
 $OldalCikk['id']         = 0;
 $OldalCikk['Oid']        = 0; 
 $OldalCikk['Cid']        = 0; 
 $OldalCikk['CPrioritas'] = 0; 
 


function getCikkekHTML() {
    $HTMLkod  = '';
    
    // Egyenlőre az összes, az oldalhoz tartozó cikket megjelenítjük, később lapozunk
    //...
    
    
    
    
    
    //....
    return $HTMLkod;
}


function getCikkekForm() {
    $HTMLkod  = '';
    // Új cikk és
    // a $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk kezelése
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
  
  
    return $HTMLkod;
}


function setCikkekLista($Oid) {
    trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
