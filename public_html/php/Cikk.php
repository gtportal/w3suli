<?php

// http://webfejlesztes.gtportal.eu/index.php?f0=7_tobbtbl
// http://webfejlesztes.gtportal.eu/index.php?f0=7_friss_04
// CLathatosag értékei: 0 = rendszergazdák, moderátorok, tulajdonos
//                      1 = csoport tagjai ????
//                      2 = bejelentkezett felhasználók
//                      3 = nyilvános

//Minta tömb
$Cikkek                      = array();
$Cikkek['id']                = '';
$Cikkek['CNev']              = '';
$Cikkek['CLeiras']           = '';
$Cikkek['CTartalom']         = '';
$Cikkek['CLathatosag']       = 0;
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
            if ($rowDB > 0) { $ErrorStr .= ' Err002,';}
            if (strlen($UjCNev)>60) { $ErrorStr .= ' Err003,';}
            if (strlen($UjCNev)<3)  { $ErrorStr .= ' Err004,';}
        } else {$ErrorStr = ' Err001,';}
        //Tartalom ellenőrzése
        if (isset($_POST['UjCTartalom'])) {
            $UjCTartalom = test_post($_POST['UjCTartalom']);             
            if ($_SESSION['AktFelhasznalo'.'FSzint']<4) {$UjCTartalom = SzintaxisCsere($UjCTartalom); } // Saját kódolás cseréje HTML elemekre
            if (strlen(!$UjCTartalom)){ $ErrorStr .= ' Err005';}
        }
        if (isset($_POST['UjCLeiras'])) {$UjCLeiras   = test_post($_POST['UjCLeiras']);}

        //=========REKORDOK LÉTREHOZÁSA =============
        if ($ErrorStr=='') {
            $InsertStr = "INSERT INTO Cikkek VALUES ('', '$UjCNev', '$UjCLeiras', '$UjCTartalom', 0, '$Fid', '$FNev', NOW(), NOW())";
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
            $HTMLkod .= "<textarea name='UjCLeiras' id='UjCLeiras' placeholder='Cikk leíráss' rows='2' cols='88'>".$UjCLeiras."</textarea></p>\n";
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
            if (strpos($_SESSION['ErrorStr'],'Err001')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Hiányzik a cikk neve! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Ilyen nevű cikk már létezik! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Az cikk neve túl hosszú! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err004')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Az cikk neve legalább 3 karakter hosszú kell legyen! ';
            }         
            //Cikk tartalom
            if (strpos($_SESSION['ErrorStr'],'Err005')!==false) {
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
            $HTMLkod .= "<textarea name='UjCLeiras' id='UjCLeiras' placeholder='Cikk leírás' rows='2' cols='88'>".$UjCLeiras."</textarea></p>\n";
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
        $HTMLkod .= "<label for='selectCikkValaszt' class='label_1'>Módosítandó cikk neve:</label><br>\n ";
        $HTMLkod .= "<select id='selectCikkValaszt' name='selectCikkValaszt' size='1'>";

        $SelectStr = "SELECT C.id, C.CNev
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.Cid= C.id 
                        WHERE OC.Oid=$Oid";
        
        
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCV 01 ");
        while($row = mysqli_fetch_array($result))
        {
            $CNev = $row['CNev'];
            if($_SESSION['SzerkCikk'.'id'] == $row['id']){$Select = " selected ";}else{$Select = "";}

            $HTMLkod.="<option value='$CNev' $Select >$CNev</option>";
        }
        $HTMLkod .= "</select>";
        //Submit
        $HTMLkod .= "<input type='submit' name='submitCikkValaszt' value='Kiválaszt'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";    
    }
           
    return $HTMLkod;
}

function getCikkForm() {
    //A $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk űrlapja
    global $Aktoldal, $MySqliLink;
    $CPrioritas = 1;
    $HTMLkod = '';
    $OUrl = $Aktoldal['OUrl'];
    $Oid = $Aktoldal['id'];
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1) {
        if (!isset($_POST['submitCikkForm']) || $_SESSION['ErrorStr'=='']){
        //Ha még nem lett elküldve vagy a cikk már módosítva lett
            $id = 0;
            if (isset($_SESSION['SzerkCikk'.'id'])) {$id = $_SESSION['SzerkCikk'.'id'];}
            $SelectStr   = "SELECT * FROM Cikkek WHERE id=$id LIMIT 1"; //echo "<h1>$SelectStr</h1>";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sC 02a ");
            $row         = mysqli_fetch_array($result);  mysqli_free_result($result);            
            $CNev        = $row['CNev'];
            $CLeiras     = $row['CLeiras'];
            $CTartalom   = $row['CTartalom'];
            $CLathatosag = $row['CLathatosag'];
            
            $SelectStr   = "SELECT * FROM OldalCikkei WHERE Cid=$id AND Oid=$Oid LIMIT 1"; //echo "<h1>$SelectStr</h1>";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sC 02axc ");
            $row         = mysqli_fetch_array($result);  mysqli_free_result($result);            
            $CPrioritas  = $row['CPrioritas'];            
            
            if ($_SESSION['SzerkCikk'.'id']>0)
            {   //============FORM ÖSSZEÁLLÍTÁSA===================
                $HTMLkod .= "<div id='divCikkForm' >\n";
                $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formCikkForm'>\n"; 

                //Cikk neve
                $HTMLkod .= "<p class='pCNev'><label for='CNev' class='label_1'>Módosított cikk neve:</label><br>\n ";
                $HTMLkod .= "<input type='text' name='CNev' id='CNev' placeholder='Cikk név' value='$CNev' size='60'></p>\n";
                //Cikk rövid leírása
                $HTMLkod .= "<p class='pCLeiras'><label for=CLeiras class='label_1'>Módosított cikk rövid leírása:</label><br>\n ";
                $HTMLkod .= "<textarea name='CLeiras' id='CLeiras' placeholder='Cikk leírás' rows='2' cols='88'>".$CLeiras."</textarea></p>\n";
                //Cikk tartalma
                $HTMLkod .= "<p class='pCTartalom'><label for='CTartalom' class='label_1'>Módosított cikk tartalma:</label><br>\n ";
                $HTMLkod .= "<textarea name='CTartalom' id='CTartalom' placeholder='Cikk tartalom' rows='8' cols='88'>".$CTartalom."</textarea></p>\n";

                //Láthatóság
                $HTMLkod .= "<p class='pCikkLathatosag'><label for='CLathatosag' class='label_1'>Láthatóság:</label>\n ";
                $HTMLkod .= "<input type='number' name='CLathatosag' id='CLathatosag' min='0' max='100' step='1' value='$CLathatosag'></p>\n";
                
                //Prioritas
                $HTMLkod .= "<p class='pCPrioritas'><label for='CPrioritas' class='label_1'>Prioritás:</label>\n ";
                $HTMLkod .= "<input type='number' name='CPrioritas' id='CPrioritas' min='0' max='100' step='1' value='$CPrioritas'></p>\n";
                //Submit
                $HTMLkod .= "<input type='submit' name='submitCikkForm' value='Módosítás'><br>\n";

                $HTMLkod .= "</form>\n";
                $HTMLkod .= "</div>\n";
            }
        } else {//Ha elküldték és hibás 
       
            if (isset($_POST['CNev']))      {$CNev      = test_post($_POST['CNev']);}
            if (isset($_POST['CTartalom'])) {$CTartalom = test_post($_POST['CTartalom']);}
            if (isset($_POST['CLeiras']))   {$CLeiras   = test_post($_POST['CLeiras']);}
            if (isset($_POST['CLathatosag']))   {$CLathatosag  = test_post($_POST['CLathatosag']);} 
            if (isset($_POST['CPrioritas']))   {$CPrioritas  = test_post($_POST['CPrioritas']);}
            
            // ============== HIBAKEZELÉS ===================== 
            $ErrorStr = '';
             //Cikknév
            if (strpos($_SESSION['ErrorStr'],'Err001')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Hiányzik a cikk neve! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Ilyen nevű cikk már létezik! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Az cikk neve túl hosszú! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err004')!==false) {
              $ErrClassCNev = ' Error '; 
              $ErrorStr .= 'Az cikk neve legalább 3 karakter hosszú kell legyen! ';
            }         
            //Cikk tartalom
            if (strpos($_SESSION['ErrorStr'],'Err005')!==false) {
              $ErrClassCTartalom = ' Error '; 
              $ErrorStr .= 'A tartalom nem lehet üres! ';
            }
            
            //============FORM ÖSSZEÁLLÍTÁSA===================
            $HTMLkod .= "<div id='divCikkForm' >\n";
            if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formCikkForm'>\n";

            //Cikk neve
            $HTMLkod .= "<p class='pCNev'><label for='CNev' class='label_1'>Cikk neve:</label><br>\n ";
            $HTMLkod .= "<input type='text' name='CNev' id='CNev' class='$ErrClassCNev' placeholder='Cikk név' value='$CNev' size='60'></p>\n";
            //Cikk rövid leírása
            $HTMLkod .= "<p class='pCLeiras'><label for=CLeiras class='label_1'>Cikk rövid leírása:</label><br>\n ";
            $HTMLkod .= "<textarea name='CLeiras' id='CLeiras' placeholder='Cikk leírás' rows='2' cols='88'>".$CLeiras."</textarea></p>\n";
            //Cikk tartalma
            $HTMLkod .= "<p class='pCTartalom'><label for='CTartalom' class='label_1'>Cikk tartalma:</label><br>\n ";
            $HTMLkod .= "<textarea name='CTartalom' id='CTartalom' class='$ErrClassCTartalom' placeholder='Cikk tartalom' rows='8' cols='88'>".$CTartalom."</textarea></p>\n";
            //Láthatóság
            $HTMLkod .= "<p class='pCikkLathatosag'><label for='CLathatosag' class='label_1'>Láthatóság:</label>\n ";
            $HTMLkod .= "<input type='number' name='CLathatosag' id='CLathatosag' min='0' max='100' step='1' value='$CLathatosag'></p>\n";

            //Prioritas
            $HTMLkod .= "<p class='pCPrioritas'><label for='CPrioritas' class='label_1'>Prioritás:</label>\n ";
            $HTMLkod .= "<input type='number' name='CPrioritas' id='CPrioritas' min='0' max='100' step='1' value='$CPrioritas'></p>\n";            
            //Submit
            $HTMLkod .= "<input type='submit' name='submitCikkForm' value='Létrehozás'><br>\n";

            $HTMLkod .= "</form>\n";
            $HTMLkod .= "</div>\n";
        }
    }
    return $HTMLkod;
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
  global $MySqliLink, $Aktoldal;
    $ErrorStr = "";
    $ErrorStr .= setCikkValaszt();
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) && (isset($_POST['submitCikkForm']))) {
        $Oid = $Aktoldal['id'];
        // ============== HIBAKEZELÉS =====================
        //Az oldalnév ellenőrzése  
        if (isset($_POST['CNev'])) {
            $CNev      = test_post($_POST['CNev']);
            $id       = $_SESSION['SzerkCikk'.'id'];
            $SelectStr = "SELECT C.id, C.CNev
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.Cid = C.id
                        WHERE OC.Oid=$Oid
                        AND C.CNev='$CNev'";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sMC 01 ");
            $rowDB       = mysqli_num_rows($result);
            if ($rowDB > 0) {
		$row = mysqli_fetch_array($result);
		if($id!=$row['id']) {$ErrorStr .= ' Err002,';}
            }
            mysqli_free_result($result);
            
            if (strlen($CNev)>60) { $ErrorStr .= ' Err003,';}
            if (strlen($CNev)<3)  { $ErrorStr .= ' Err004,';}
        } else {$ErrorStr = ' Err001,';}
        
        //Tartalom ellenőrzése
        if (isset($_POST['CTartalom'])) {
            $CTartalom = test_post($_POST['CTartalom']);
            if ($_SESSION['AktFelhasznalo'.'FSzint']<4) {$CTartalom = SzintaxisCsere($CTartalom); }
            if (strlen(!$CTartalom)){ $ErrorStr .= ' Err005';}
        }
        if (isset($_POST['CLeiras'])) {$CLeiras   = test_post($_POST['CLeiras']);}
        if (isset($_POST['CLathatosag'])) {$CLathatosag   = test_post($_POST['CLathatosag']);}
        if (isset($_POST['CPrioritas'])) {$CPrioritas   = test_post($_POST['CPrioritas']);}

        //=========REKORDOK MÓDOSÍTÁSA =============
        if ($ErrorStr=='') {
            $UpdateStr = "UPDATE Cikkek SET
                        CNev       = '$CNev',
                        CTartalom  = '$CTartalom',
                        Cleiras =  '$CLeiras',
                        CLathatosag =  '$CLathatosag',    
                        CModositasTime = NOW()
                        WHERE id = $id";
            mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uMC 01 ");
            $UpdateStr   = "UPDATE OldalCikkei SET
                             CPrioritas = $CPrioritas
                            WHERE Cid=$id AND Oid=$Oid LIMIT 1"; 
            
            
        }
    }
    return $ErrorStr;
}




// ==================== CIKK TÖRLÉSE =================

function setCikkTorol() {
    global $MySqliLink, $Aktoldal;
    if (isset($_POST['submitCikkTorol'])) {
        $TorolDB = test_post($_POST['TorolDB']);
        for ($i = 0; $i < $TorolDB; $i++){
            $id = test_post($_POST["CikkTorolId_$i"]);
            if ($_POST["CikkTorol_$i"]){
                $DeleteStr = "DELETE FROM Cikkek WHERE id = $id";
                mysqli_query($MySqliLink, $DeleteStr);
                $DeleteStr = "DELETE FROM OldalCikkei WHERE Cid = $id";
                mysqli_query($MySqliLink, $DeleteStr);
            }
        }
    }
}

function getCikkTorolForm() {
    global $MySqliLink, $Aktoldal;
    $HTMLkod = "";
    $Oid = $Aktoldal['id'];
    $OUrl = $Aktoldal['OUrl'];
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1) {
        $SelectStr = "SELECT C.id, C.CNev
                        FROM Cikkek AS C
                        LEFT JOIN OldalCikkei AS OC
                        ON OC.Cid= C.id 
                        WHERE OC.Oid=$Oid";
        $result = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCT 01 ");

        $HTMLkod .= "<div id='divCikkTorolForm' >\n";
        $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formCikkTorolForm'>\n";
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $CNev = $row['CNev'];
            $id = $row['id'];

            //Törlésre jelölés
            $HTMLkod .= "<p class='pCikkTorol'><input type='checkbox' name='CikkTorol_$i' id='CikkTorol_$i'>"
                    . "<label for='CikkTorol_$i' class='label_1'>$CNev</label>\n ";
            $HTMLkod .= "</p>\n";

            //id
            $HTMLkod .= "<input type='hidden' name='CikkTorolId_$i' id='CikkTorolId_$i' value='$id'>\n";

            $i++;
        }
        $HTMLkod .= "<input type='hidden' name='TorolDB' id='TorolDB' value='$i'>\n";
        
        $HTMLkod .= "<input type='submit' name='submitCikkTorol' id='submitCikkTorol' value='Törlés'>\n";
        $HTMLkod .= "</form>\n";
        $HTMLkod .= "</div>\n";
    }
    return $HTMLkod;
}


function getCikkElozetesHTML() {
	trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
