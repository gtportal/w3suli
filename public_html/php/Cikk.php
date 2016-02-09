<?php

// http://webfejlesztes.gtportal.eu/index.php?f0=7_tobbtbl
// http://webfejlesztes.gtportal.eu/index.php?f0=7_friss_04

// A set -ek az index.php-ba beillesztve
// A getCikkekHTML() oldal.php-ba beillesztve


//Minta tömb
$Cikkek                      = array();
$Cikkek['id']                = '';
$Cikkek['CNev']              = '';
$Cikkek['CLeiras']           = '';
$Cikkek['CTartalom']         = '';
$Cikkek['CLathatosag']       = 1;
$Cikkek['CSzerzo']           = '';
$Cikkek['CSzerzoNev']        = '';
$Cikkek['CLetrehozasTime']   = '';
$Cikkek['CModositasTime']    = '';


// ==================== ÚJ CIKK LÉTREHOZÁSA =================
function setUjCikk() {
	// Létrehoz egy az aktuális oldalhoz tartozó cikket
	trigger_error('Not Implemented!', E_USER_WARNING);
}

function getUjCikkForm() {
	// Az aktuális oldalhoz tartozó cikk létrehozásához szükséges form
	trigger_error('Not Implemented!', E_USER_WARNING);
}

// ==================== MEGLÉVŐ CIKK MÓDOSÍTÁSA =================

// egy DIV-be kerül a cikk kiválasztását és a módosítását lehetővé tévő form
// Minte a: Felhasznalo.php

function getCikkValasztForm() {
	// A felhasználóknál készített getFelhasznaloValasztForm() fgv-hez hasonló módon lehetővé teszi 
	// a választást az oldal cikkei közül
	// A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] munkamenet változókban tároljuk az 
	// aktuális cikk adatait	
}	

function getCikkForm() {
    $HTMLkod  = '';
    $HTMLkod .= getCikkValasztForm();
        
    //A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk űrlapja   
	trigger_error('Not Implemented!', E_USER_WARNING);
}

function setCikkValaszt() {
	// I.) A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] munkamenet változók beállítása, ha
	// a cikkválasztó űrlapot elküdték
	
	// II.) A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] munkamenet változók törlése, ha
	// sem a cikkválasztó űrlapot sem a cikk módosítása elküdték nem küldték el (pl. új oldalt töltenek le)	
	
}	


function setCikk() {
  $ErrorStr = '';
  $ErrorStr .= setFelhasznaloValaszt();	
	
  //A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk adatainak kezelése   
  trigger_error('Not Implemented!', E_USER_WARNING);	
	
}




// ==================== CIKK TÖRLÉSE =================

function setCikkTorol() {
	trigger_error('Not Implemented!', E_USER_WARNING);
}

function getCikkTorolForm() {
	trigger_error('Not Implemented!', E_USER_WARNING);
}


// ==================== AZ OLDAL CIKKEINEK MEGJELENÍTÉSE =================



// getCikkekHTML()-t használjuk !!!!



function getCikkHTML() {
  // Ha szükség van rá
}

function getCikkElozetesHTML() {
	trigger_error('Not Implemented!', E_USER_WARNING);
}



?>
