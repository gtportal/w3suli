<?php


    $CsoportTagok = array();
    $CsoportTagok['id']   = -1;
    $CsoportTagok['Fid']  = -1;
    $CsoportTagok['CSid'] = -1;    
    
// ============= Felhasználó csoport tagjainak ============  
function setCsoportTagok() {
    global $MySqliLink;
    $ErrorStr = ''; 
    
    return $ErrorStr;
}

function getCsoportTagsagForm() {
    global $MySqliLink;
    $HTMLkod   = '';
    
    return $HTMLkod;
}

// ============= Annak eldöntése, hogy egy felhasználó tagja-e egy csoportnak ============     
function getTesztTagsag($Fid, $CSid) {
    trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
