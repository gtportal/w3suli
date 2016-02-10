<?php

$FelhasznaloCsopor = array();
$FelhasznaloCsopor['id']       = 0;
$FelhasznaloCsopor['CsNev']    = '';
$FelhasznaloCsopor['CsLeiras'] = '';

function getCsoportValasztForm() {
	// A felhasználóknál készített getFelhasznaloValasztForm() fgv-hez hasonló módon lehetővé teszi 
	// a választást a létező közül
	// A $_SESSION['SzerkFCsoport'] munkamenet változóban tároljuk az 
	// aktuális csoport id-jét	
}

function setCsoportValaszt() {
	// I.) A $_SESSION['SzerkFCsoport'] munkamenet változó beállítása, ha
	// a kiválasztó űrlapot elküdték

	
}


function initFCsoport() {
    global $MySqliLink;
    //Az oldalak táblában létrehzzuk a "Felhasználói csoportok" nevű oldalt
    //Késöbb átkerül az Init.php-ba !!!!

    $SelectStr   = "SELECT id FROM Oldalak WHERE OTipus=20 ";  //echo "<h1>$SelectStr</h1>";
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba FCSi 01aa ");
    $rowDB       = mysqli_num_rows($result); 
    if ($rowDB == 0) {
        //Kezdőlap létrehozása
        $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Felhasználói csoportok','Felhasznaloi_csoportok',1,1,'Oldal leírása',
                          'Oldal kulcsszavai',1,20,'Oldal tartalma','','')";        
        if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 01 ");} 
    }
}


// ============= Új felhasználó csoport létrehozása ============  
function setUjFCsoport() {
    global $MySqliLink;
    $ErrorStr = ''; 
    initFCsoport();


    return $ErrorStr;
}

function getUjFCsoportForm() {
    global $MySqliLink;
    $HTMLkod   = '';
    
    return $HTMLkod;
}

// ============= Felhasználó csoport adatainak módosítása ============     
function setFCsoport() {
    global $MySqliLink;
    $ErrorStr = ''; 
    
    return $ErrorStr;
}

function getFCsoportForm() {        // Ez kerül meghívásra a Felhasználói csoport kezelése oldalon
    global $MySqliLink;
    $HTMLkod   = '';
    
    return $HTMLkod;
}    
    
// ============= Felhasználó csoport törlése ============  
function setFCsoportTorol() {
    global $MySqliLink;
    $ErrorStr = ''; 
    
    return $ErrorStr;
}

function getFCsoportTorolForm() {
    global $MySqliLink;
    $HTMLkod   = '';
    
    return $HTMLkod;
}      
    
// ============= Felhasználó csoport tagjainak listázása ============     
function getCsoportListaHTML() {
    global $MySqliLink;
    $HTMLkod   = '';
    
    return $HTMLkod;
}



?>
