<?php

// http://webfejlesztes.gtportal.eu/index.php?f0=7_tobbtbl
// http://webfejlesztes.gtportal.eu/index.php?f0=7_friss_04


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
    global $MySqliLink, $Aktoldal;
    $ErrorStr = "";
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) && (isset($_POST['submitUjCikkForm']))) {
        $FNev = $_SESSION['AktFelhasznalo'.'FNev'];
        $Fid = $_SESSION['AktFelhasznalo'.'id'];
        $Oid = $Aktoldal['id'];
        // ============== HIBAKEZELÉS =====================
        //Az oldalnév ellenőrzése  
        if (isset($_POST['UjCNev'])) {
            $UjCNev      = test_post($_POST['UjCNev']);
            $SelectStr   = "SELECT id FROM Cikkek WHERE CNev = '$UjCNev' LIMIT 1";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUC 01 ");
            $rowDB       = mysqli_num_rows($result); mysqli_free_result($result);
            if ($rowDB > 0) { $ErrorStr .= ' Err302,';}
            if (strlen($UjCNev)>60) { $ErrorStr .= ' Err303,';}
            if (strlen($UjCNev)<3)  { $ErrorStr .= ' Err304,';}
        } else {$ErrorStr = ' Err301,';}
        //Tartalom ellenőrzése
        if (isset($_POST['UjCTartalom'])) {
            $UjCTartalom = test_post($_POST['UjCTartalom']);
            if (strlen(!$UjCTartalom)){ $ErrorStr .= ' Err305';}
        }
        if (isset($_POST['UjCLeiras'])) {$UjCLeiras   = test_post($_POST['UjCLeiras']);}

        //=========REKORDOK LÉTREHOZÁSA =============
        if ($ErrorStr=='') {
            $InsertStr = "INSERT INTO Cikkek VALUES ('', '$UjCNev', '$UjCLeiras', '$UjCTartalom', 1, '$Fid', '$FNev', NOW(), NOW())";
            mysqli_query($MySqliLink, $InsertStr) OR die("Hiba iUC 01 ");

            $InsertStr = "INSERT INTO OldalCikkei VALUES ('', '$Oid', LAST_INSERT_ID(), 1)";
            mysqli_query($MySqliLink, $InsertStr) OR die("Hiba iUC 02 ");
        }
    }
    return $ErrorStr;
}

function getUjCikkForm() {
// Az aktuális oldalhoz tartozó cikk létrehozásához szükséges form
    global $Aktoldal;
    $HTMLkod = '';
    $OUrl = $Aktoldal['OUrl'];
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1) {
        if (!isset($_POST['submitUjCikkForm']) || $_SESSION['ErrorStr'=='']){
        //Ha még nem lett elküldve vagy az új cikk már létrelött
            if (isset($_POST['UjCNev']))      {$UjCNev      = test_post($_POST['UjCNev']);}
            if (isset($_POST['UjCTartalom'])) {$UjCTartalom = test_post($_POST['UjCTartalom']);}
            if (isset($_POST['UjCLeiras']))   {$UjCLeiras   = test_post($_POST['UjCLeiras']);}
        
            //============FORM ÖSSZEÁLLÍTÁSA===================
            $HTMLkod .= "<div id='divUjCikkForm' >\n";
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formUjCikkForm'>\n";

            //Cikk neve
            $HTMLkod .= "<p class='pUjCNev'><label for='CUjNev' class='label_1'>Cikk neve:</label><br>\n ";
            $HTMLkod .= "<input type='text' name='UjCNev' id='UjCNev' placeholder='Cikk név' value='$UjCNev' size='60'></p>\n";
            //Cikk rövid leírása
            $HTMLkod .= "<p class='pUjCLeiras'><label for=UjCLeiras class='label_1'>Cikk rövid leírása:</label><br>\n ";
            $HTMLkod .= "<textarea name='UjCLeiras' id='UjCLeiras' placeholder='Cikk leírása' rows='2' cols='88'>".$UjCLeiras."</textarea></p>\n";
            //Cikk tartalma
            $HTMLkod .= "<p class='pUjCTartalom'><label for='UjCTartalom' class='label_1'>Cikk tartalma:</label><br>\n ";
            $HTMLkod .= "<textarea name='UjCTartalom' id='UjCTartalom' placeholder='Cikk tartalom' rows='8' cols='88'>".$UjCTartalom."</textarea></p>\n";
            //Submit
            $HTMLkod .= "<input type='submit' name='submitUjCikkForm' value='Létrehozás'><br>\n";

            $HTMLkod .= "</form>\n";
            $HTMLkod .= "</div>\n";
        } else {//Ha elküldték és hibás
            if (isset($_POST['UjCNev']))      {$UjCNev      = test_post($_POST['UjCNev']);}
            if (isset($_POST['UjCTartalom'])) {$UjCTartalom = test_post($_POST['UjCTartalom']);}
            if (isset($_POST['UjCLeiras']))   {$UjCLeiras   = test_post($_POST['UjCLeiras']);}
            
            // ============== HIBAKEZELÉS ===================== 
            $ErrorStr = '';
             //Cikknév
            if (strpos($_SESSION['ErrorStr'],'Err301')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Hiányzik a cikk neve! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err302')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Ilyen nevű cikk már létezik! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err303')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Az cikk neve túl hosszú! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err304')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Az cikk neve legalább 3 karakter hosszú kell legyen! ';
            }         
            //Cikk tartalom
            if (strpos($_SESSION['ErrorStr'],'Err305')!==false) {
              $ErrClassCTartalom = ' Error '; 
              $ErrorStr .= 'A tartalom nem lehet üres! ';
            }
            
            //============FORM ÖSSZEÁLLÍTÁSA===================
            $HTMLkod .= "<div id='divUjCikkForm' >\n";
            if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formUjCikkForm'>\n";

            //Cikk neve
            $HTMLkod .= "<p class='pUjCNev'><label for='CUjNev' class='label_1'>Cikk neve:</label><br>\n ";
            $HTMLkod .= "<input type='text' name='UjCNev' id='UjCNev' class='$ErrClassCNev' placeholder='Cikk név' value='$UjCNev' size='60'></p>\n";
            //Cikk rövid leírása
            $HTMLkod .= "<p class='pUjCLeiras'><label for=UjCLeiras class='label_1'>Cikk rövid leírása:</label><br>\n ";
            $HTMLkod .= "<textarea name='UjCLeiras' id='UjCLeiras' placeholder='Cikk leírása' rows='2' cols='88'>".$UjCLeiras."</textarea></p>\n";
            //Cikk tartalma
            $HTMLkod .= "<p class='pUjCTartalom'><label for='UjCTartalom' class='label_1'>Cikk tartalma:</label><br>\n ";
            $HTMLkod .= "<textarea name='UjCTartalom' id='UjCTartalom' class='$ErrClassCTartalom' placeholder='Cikk tartalom' rows='8' cols='88'>".$UjCTartalom."</textarea></p>\n";
            //Submit
            $HTMLkod .= "<input type='submit' name='submitUjCikkForm' value='Létrehozás'><br>\n";

            $HTMLkod .= "</form>\n";
            $HTMLkod .= "</div>\n";
        }
    }
    return $HTMLkod;
}

// ==================== MEGLÉVŐ CIKK MÓDOSÍTÁSA =================

// egy DIV-be kerül a cikk kiválasztását és a módosítását lehetővé tévő form
// Minte a: Felhasznalo.php

function getCikkValasztForm() {
	// A felhasználóknál készített getFelhasznaloValasztForm() fgv-hez hasonló módon lehetővé teszi 
	// a választást az oldal cikkei közül
	// A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] munkamenet változókban tároljuk az 
	// aktuális cikk adatait
    global $MySqliLink, $Aktoldal;
    $HTMLkod  = '';
    $ErrorStr = '';
    $OUrl = $Aktoldal['OUrl'];
    $Oid = $Aktoldal['id'];
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  

        $HTMLkod .= "<div id='divCikkValaszt' >\n";
        if ($ErrorStr!='') {
            $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";
        }

        $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formCikkValaszt'>\n";

        //Cikk kiválasztása a lenyíló listából
        $HTMLkod .= "<select name='selectCikkValaszt' size='1'>";

        $SelectStr = "SELECT C.id, C.CNev
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.id=$Oid";
        
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCV 01 ");
        while($row = mysqli_fetch_array($result))
        {
            $CNev = $row['CNev'];
            if($_SESSION['SzerkCikk'.'id'] == $row['id']){$Select = " selected ";}else{$Select = "";}

            $HTMLkod.="<option value='$CNev' $Select >$CNev</option>";
        }	
        //Submit
        $HTMLkod .= "<input type='submit' name='submitCikkValaszt' value='Kiválaszt'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";    
    }
           
    return $HTMLkod;
}

function getCikkForm() {
    $HTMLkod  = '';
    //$HTMLkod .= getCikkValasztForm();
        
    //A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk űrlapja   
	trigger_error('Not Implemented!', E_USER_WARNING);
}

function setCikkValaszt() {
// I.) A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] munkamenet változók beállítása, ha
// a cikkválasztó űrlapot elküdték

// II.) A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] munkamenet változók törlése, ha
// sem a cikkválasztó űrlapot sem a cikk módosítása elküdték nem küldték el (pl. új oldalt töltenek le)	
    global $MySqliLink;
    $ErrorStr = '';
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $CNev     = '';

        // ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitCikkValaszt'])) {
            if (isset($_POST['selectCikkValaszt'])) {$CNev = test_post($_POST['selectCikkValaszt']);}      

            if($CNev!='')
            {
                $SelectStr   = "SELECT id FROM Cikkek WHERE CNev='$CNev' LIMIT 1";
                $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCV 02 ");
                $row         = mysqli_fetch_array($result);  mysqli_free_result($result);

                $_SESSION['SzerkCikk'.'id'] = $row['id'];
            }
        }
    }
    return $ErrorStr;
}


function setCikk() {
  $ErrorStr = '';
  
  $ErrorStr .= setCikkValaszt();
	
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
