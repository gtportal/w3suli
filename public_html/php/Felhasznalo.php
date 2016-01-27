<?php

$AktFelhasznalo            = array();
$AktFelhasznalo['FNev']    = '';
$AktFelhasznalo['FEmail']  = '';
$AktFelhasznalo['FSzint']  = 1;
$AktFelhasznalo['FSzerep'] = '';
$AktFelhasznalo['FKep']    = '';

// ============= Be és kijelentkezés ============
   function setBelepes() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getBelepesForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function setKilepes() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getKilepesForm() {
        // Lehet, hogy felesleges
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
    
    
// ============= Új felhasználó ============     
    function setUjFelhasznalo() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
    
    function getUjFelhasznaloForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }       
    
// ============= Felhasználó adatainak módosítása ============    
// A felhasználók adatait csak a rendszergazdák módosíthatják    
    function setFelhasznalo() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getFelhasznaloForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
  
    function setFelhasznaloValszt() {
        // A kiválasztott felhasználó ID-je a $_SESSION['SzerkFelhasznalo'] változóba!!!
        trigger_error('Not Implemented!', E_USER_WARNING);
    }    
    
    function getFelhasznaloValsztForm() {
        // A form megjeleníti a felhasználók nevét és lehetővé teszi egy felhasználó megjelölését
        // Később biztosítani kell a felhasználónevek szűkítését csoportok szerint
        trigger_error('Not Implemented!', E_USER_WARNING);
    }    
    
// ============= Felhasználó jelszavának módosítása ============ 
// A felhasználók jelszavait a rendszergazdák és a felhasználók is módosíthatják     
// Ha a felhasználó módosítja a jelszót, akkor meg kell adnia az érvényes jelszót   
    function SetUjJelszo() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
    
    function getUjJelszoForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
 
    
// ============= Felhasználó törlése ============    
    function setFelhasznaloTorol() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }    

    function getFelhasznaloTorolForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }    
    
// ============= Egy felhasználó adatainak lekérdezése============      
    function getAktFelhasznalo() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getFelhasznalo($Fid) {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }


    function getFelhasznaloLista() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

?>
