<?php

// http://webfejlesztes.gtportal.eu/index.php?f0=7_tobbtbl
// http://webfejlesztes.gtportal.eu/index.php?f0=7_friss_04
// CLathatosag értékei: 0 = rendszergazdák, moderátorok, tulajdonos
//                      1 = csoport tagjai ????
//                      2 = bejelentkezett felhasználók
//                      3+ = nyilvános
/*
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
 
 */


// ==================== ÚJ CIKK LÉTREHOZÁSA =================
function setUjCikk() {
    global $MySqliLink, $Aktoldal;
    $ErrorStr = "";
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) && (isset($_POST['submitUjCikkForm']))) {
        $FNev = $_SESSION['AktFelhasznalo'.'FNev'];
        $Fid  = $_SESSION['AktFelhasznalo'.'id'];
        $Oid  = $Aktoldal['id'];
        // ============== HIBAKEZELÉS =====================
        //Az oldalnév ellenőrzése  
        if (isset($_POST['UjCNev'])) {
            $UjCNev      = test_post($_POST['UjCNev']);
            $SelectStr   = "SELECT id FROM Cikkek WHERE CNev = '$UjCNev' LIMIT 1"; //echo "<h1>$SelectStr</h1>";
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
        if (isset($_POST['UjCLeiras']))   {$UjCLeiras   = test_post($_POST['UjCLeiras']);}  else {$UjCLeiras   ='';}
        if (isset($_POST['CLathatosag'])) {$CLathatosag = INT_post($_POST['CLathatosag']);} else {$CLathatosag =0;} 
        if (isset($_POST['CPrioritas']))  {$CPrioritas  = INT_post($_POST['CPrioritas']);}  else {$CPrioritas  =0;} 
        if (isset($_POST['KoElozetes']))  {$KoElozetes  = INT_post($_POST['KoElozetes']);}  else {$KoElozetes  =0;} 
        if (isset($_POST['SZoElozetes'])) {$SZoElozetes = INT_post($_POST['SZoElozetes']);} else {$SZoElozetes =0;} 
        //=========REKORDOK LÉTREHOZÁSA =============
        if ($ErrorStr=='') {
            $InsertStr = "INSERT INTO Cikkek VALUES ('', '$UjCNev', '$UjCLeiras', '$UjCTartalom', $CLathatosag, '$KoElozetes', '$SZoElozetes',
                                                     '$Fid', '$FNev', NOW(), NOW())";
            mysqli_query($MySqliLink, $InsertStr) OR die("Hiba iUC 01 ");

            $InsertStr = "INSERT INTO OldalCikkei VALUES ('', '$Oid', LAST_INSERT_ID(), $CPrioritas)";
            mysqli_query($MySqliLink, $InsertStr) OR die("Hiba iUC 02 ");
        }
    }
    return $ErrorStr;
}

function getUjCikkForm() {
// Az aktuális oldalhoz tartozó cikk létrehozásához szükséges form
    global $Aktoldal;
    $HTMLkod     = '';
    $OUrl        = $Aktoldal['OUrl'];
    $UjCNev      = '';
    $UjCTartalom = '';
    $UjCLeiras   = '';
    $CLathatosag = 0;
    $CPrioritas  = 0;
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1) {
        if (!isset($_POST['submitUjCikkForm']) || $_SESSION['ErrorStr'=='']){
        //Ha még nem lett elküldve vagy az új cikk már létrelött
            if (isset($_POST['UjCNev']))      {$UjCNev      = test_post($_POST['UjCNev']);}
            if (isset($_POST['UjCTartalom'])) {$UjCTartalom = test_post($_POST['UjCTartalom']);}
            if (isset($_POST['UjCLeiras']))   {$UjCLeiras   = test_post($_POST['UjCLeiras']);}
            if (isset($_POST['CLathatosag'])) {$CLathatosag = INT_post($_POST['CLathatosag']);} 
            if (isset($_POST['CPrioritas']))  {$CPrioritas  = INT_post($_POST['CPrioritas']);}
            
            //============FORM ÖSSZEÁLLÍTÁSA===================
            $HTMLkod .= "<div id='divUjCikkForm' >\n";
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formUjCikkForm'>\n";
            $HTMLkod .= "<p class='ErrorStr'>$OKstr</p>";
            $HTMLkod .= "<h2>Új cikk létrehozása</h2>\n ";
            $HTMLkod .= "<fieldset> <legend>Az új cikk adatai:</legend>";
            //Cikk neve
            $HTMLkod .= "<p class='pUjCNev'><label for='CUjNev' class='label_1'>Cikk neve:</label><br>\n ";
            $HTMLkod .= "<input type='text' name='UjCNev' id='UjCNev' placeholder='Cikk név' value='$UjCNev' size='60'></p>\n";
            //Cikk rövid leírása
            $HTMLkod .= "<p class='pUjCLeiras'><label for=UjCLeiras class='label_1'>Cikk rövid leírása:</label><br>\n ";
            $HTMLkod .= "<textarea name='UjCLeiras' id='UjCLeiras' placeholder='Cikk leíráss' rows='2' cols='88'>".$UjCLeiras."</textarea></p>\n";
            //Cikk tartalma
            $HTMLkod .= "<p class='pUjCTartalom'><label for='UjCTartalom' class='label_1'>Cikk tartalma:</label><br>\n ";
            $HTMLkod .= "<textarea name='UjCTartalom' id='UjCTartalom' placeholder='Cikk tartalom' rows='8' cols='88'>".$UjCTartalom."</textarea></p>\n";
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<fieldset> <legend>Az új cikk láthatósága:</legend>";
            if ($_SESSION['AktFelhasznalo'.'FSzint']>2) {
                //Láthatóság
                $HTMLkod .="<input type='radio' id='CLathatosag_0' name='CLathatosag' value='0' checked>";
                $HTMLkod .="<label for='CLathatosag_0' class='label_1'>Moderátor és tulajdonos</label><br>";

                $HTMLkod .="<input type='radio' id='CLathatosag_1' name='CLathatosag' value='1'>";
                $HTMLkod .="<label for='CLathatosag_1' class='label_1'>Bejelentkezett felhasználók</label><br>";     

                $HTMLkod .="<input type='radio' id='CLathatosag_2' name='CLathatosag' value='2' >";
                $HTMLkod .="<label for='CLathatosag_2' class='label_1'>Csoport számára látható</label><br>";

                $HTMLkod .="<input type='radio' id='CLathatosag_3' name='CLathatosag' value='3' >";
                $HTMLkod .="<label for='CLathatosag_3' class='label_1'>Nyilvános <b>(mindenki látja)</b></label><br>";  

                $HTMLkod .="<input type='radio' id='CLathatosag_A' name='CLathatosag' value='-1' >";
                $HTMLkod .="<label for='CLathatosag_A' class='label_1'>Archívumban olvasható <b>(mindenki látja)</b></label><br>";                 
            }
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<fieldset> <legend>Az új cikk pozíciója:</legend>";
            //Prioritas
            $HTMLkod .= "<p class='pCPrioritas'><label for='CPrioritas' class='label_1'>Prioritás:</label>\n ";
            $HTMLkod .= "<input type='number' name='CPrioritas' id='CPrioritas' min='0' max='100' step='1' value='$CPrioritas'></p>\n";
            
            $HTMLkod .= "</fieldset>";
            //Submit
            $HTMLkod .= "<input type='submit' name='submitUjCikkForm' value='Létrehozás'><br>\n";

            $HTMLkod .= "</form>\n";
            $HTMLkod .= "</div>\n";
        } else {//Ha elküldték és hibás
            if (isset($_POST['UjCNev']))      {$UjCNev      = test_post($_POST['UjCNev']);}
            if (isset($_POST['UjCTartalom'])) {$UjCTartalom = test_post($_POST['UjCTartalom']);}
            if (isset($_POST['UjCLeiras']))   {$UjCLeiras   = test_post($_POST['UjCLeiras']);}
            if (isset($_POST['CLathatosag'])) {$CLathatosag = INT_post($_POST['CLathatosag']);} 
            if (isset($_POST['CPrioritas']))  {$CPrioritas  = INT_post($_POST['CPrioritas']);}
            
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
            $OKstr ='';
            if (isset($_POST['submitUjCikkForm'])&&($ErrorStr=='')){$OKstr ='A cikk elkészült.';} 
            $HTMLkod .= "<p class='ErrorStr'>$OKstr</p>";
            $HTMLkod .= "<h2>Új cikk létrehozása</h2>\n ";

            $HTMLkod .= "<fieldset> <legend>Az új cikk adatai:</legend>";
            //Cikk neve
            $HTMLkod .= "<p class='pUjCNev'><label for='CUjNev' class='label_1'>Cikk neve:</label><br>\n ";
            $HTMLkod .= "<input type='text' name='UjCNev' id='UjCNev' class='$ErrClassCNev' placeholder='Cikk név' value='$UjCNev' size='60'></p>\n";
            //Cikk rövid leírása
            $HTMLkod .= "<p class='pUjCLeiras'><label for=UjCLeiras class='label_1'>Cikk rövid leírása:</label><br>\n ";
            $HTMLkod .= "<textarea name='UjCLeiras' id='UjCLeiras' placeholder='Cikk leírás' rows='2' cols='88'>".$UjCLeiras."</textarea></p>\n";
            //Cikk tartalma
            $HTMLkod .= "<p class='pUjCTartalom'><label for='UjCTartalom' class='label_1'>Cikk tartalma:</label><br>\n ";
            $HTMLkod .= "<textarea name='UjCTartalom' id='UjCTartalom' class='$ErrClassCTartalom' placeholder='Cikk tartalom' rows='8' cols='88'>".$UjCTartalom."</textarea></p>\n";
           
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<fieldset> <legend>Az új cikk láthatósága:</legend>";
            
            if ($_SESSION['AktFelhasznalo'.'FSzint']>2) {
                if($CLathatosag==0){$checked=" checked ";}else{$checked="";}
                $HTMLkod .="<input type='radio' id='CLathatosag_0' name='CLathatosag' value='0' $checked>";
                $HTMLkod .="<label for='CLathatosag_0' class='label_1'>Moderátor és tulajdonos</label><br>";

                if($CLathatosag==1){$checked=" checked ";}else{$checked="";}
                $HTMLkod .="<input type='radio' id='CLathatosag_1' name='CLathatosag' value='1' $checked>";
                $HTMLkod .="<label for='CLathatosag_1' class='label_1'>Bejelentkezett felhasználók</label><br>";     

                if($CLathatosag==2){$checked=" checked ";}else{$checked="";}
                $HTMLkod .="<input type='radio' id='CLathatosag_2' name='CLathatosag' value='2' $checked>";
                $HTMLkod .="<label for='CLathatosag_2' class='label_1'>Csoport számára látható</label><br>";

                if($CLathatosag==3){$checked=" checked ";}else{$checked="";}
                $HTMLkod .="<input type='radio' id='CLathatosag_3' name='CLathatosag' value='3' $checked>";
                $HTMLkod .="<label for='CLathatosag_3' class='label_1'>Nyilvános <b>(mindenki látja)</b></label><br>";  

                if($CLathatosag==-1){$checked=" checked ";}else{$checked="";}
                $HTMLkod .="<input type='radio' id='CLathatosag_A' name='CLathatosag' value='-1' $checked>";
                $HTMLkod .="<label for='CLathatosag_A' class='label_1'>Archívumban olvasható <b>(mindenki látja)</b></label><br>";   
            }

            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<fieldset> <legend>Az új cikk pozíciója:</legend>";
            //Prioritas
            $HTMLkod .= "<p class='pCPrioritas'><label for='CPrioritas' class='label_1'>Prioritás:</label>\n ";
            $HTMLkod .= "<input type='number' name='CPrioritas' id='CPrioritas' min='0' max='100' step='1' value='$CPrioritas'></p>\n";
            
            $HTMLkod .= "</fieldset>";
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
    $OUrl     = $Aktoldal['OUrl'];
    $Oid      = $Aktoldal['id'];
    $Fid      = $_SESSION['AktFelhasznalo'.'id'];
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  

        $HTMLkod .= "<div id='divCikkValaszt' >\n";
        if ($ErrorStr!='') {
            $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";
        }
        $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formCikkValaszt'>\n";
        $HTMLkod .= "<h2>Cikk kiválasztása</h2>\n";
        $HTMLkod .= "<i>Az itt kiválasztott jellemzőit tudja módosítani. Új cikk létrehozásához és régi törléséhez nincs szükség itt cikk kiválasztására.</i><br>\n";
        //Cikk kiválasztása a lenyíló listából
        $HTMLkod .= "<label for='selectCikkValaszt' class='label_1' id='labelCikkValaszt'>Módosítandó cikk neve:</label>\n ";
        $Felkover = '';
        if(($_SESSION['SzerkCikk'.'id']) && (($_SESSION['SzerkCikk'.'id'])>0)) {$Felkover = "class='felkover'";}
        $HTMLkod .= "<select id='selectCikkValaszt' name='selectCikkValaszt' size='1' $Felkover>";
        if ($_SESSION['AktFelhasznalo'.'FSzint']==2) {
            $SelectStr = "SELECT C.id, C.CNev
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id 
                            WHERE OC.Oid=$Oid 
                            AND C.CSzerzo=$Fid";
        } else {
            $SelectStr = "SELECT C.id, C.CNev
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id 
                            WHERE OC.Oid=$Oid";
        }
        
        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCV 01 ");
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
    $HTMLkod    = '';
    $OUrl       = $Aktoldal['OUrl'];
    $Oid        = $Aktoldal['id'];
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
            
            $KoElozetes  = $row['KoElozetes'];
            $SZoElozetes = $row['SZoElozetes'];
            
            $SelectStr   = "SELECT * FROM OldalCikkei WHERE Cid=$id AND Oid=$Oid LIMIT 1"; //echo "<h1>$SelectStr</h1>";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sC 02axc ");
            $row         = mysqli_fetch_array($result);  mysqli_free_result($result);            
            $CPrioritas  = $row['CPrioritas'];            
            
            if ($_SESSION['SzerkCikk'.'id']>0)
            {   //============FORM ÖSSZEÁLLÍTÁSA===================
                $HTMLkod .= "<div id='divCikkForm' >\n";
                $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formCikkForm'>\n"; 

                $HTMLkod .= "<h2>Cikk módosítása</h2>\n";
                $HTMLkod .= "<fieldset> <legend>Az cikk adatai:</legend>";
                //Cikk neve
                $HTMLkod .= "<p class='pCNev'><label for='CNev' class='label_1'>Módosított cikk neve:</label><br>\n ";
                $HTMLkod .= "<input type='text' name='CNev' id='CNev' placeholder='Cikk név' value='$CNev' size='60'></p>\n";
                //Cikk rövid leírása
                $HTMLkod .= "<p class='pCLeiras'><label for=CLeiras class='label_1'>Módosított cikk rövid leírása:</label><br>\n ";
                $HTMLkod .= "<textarea name='CLeiras' id='CLeiras' placeholder='Cikk leírás' rows='2' cols='88'>".$CLeiras."</textarea></p>\n";
                //Cikk tartalma
                $HTMLkod .= "<p class='pCTartalom'><label for='CTartalom' class='label_1'>Módosított cikk tartalma:</label><br>\n ";
                $HTMLkod .= "<textarea name='CTartalom' id='CTartalom' placeholder='Cikk tartalom' rows='8' cols='88'>".$CTartalom."</textarea></p>\n";

                if ($_SESSION['AktFelhasznalo'.'FSzint']>2) {
                    //Láthatóság
                    $HTMLkod .= "</fieldset>";
                    $HTMLkod .= "<fieldset> <legend>A cikk láthatósága:</legend>";        
                        
                    if($CLathatosag==0){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_0' name='CLathatosag' value='0' $checked>";
                    $HTMLkod .="<label for='CLathatosag_0' class='label_1'>Moderátor és tulajdonos</label><br>";
                    
                    if($CLathatosag==1){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_1' name='CLathatosag' value='1' $checked>";
                    $HTMLkod .="<label for='CLathatosag_1' class='label_1'>Bejelentkezett felhasználók</label><br>";     
                    
                    if($CLathatosag==2){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_2' name='CLathatosag' value='2' $checked>";
                    $HTMLkod .="<label for='CLathatosag_2' class='label_1'>Csoport számára látható</label><br>";
                    
                    if($CLathatosag==3){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_3' name='CLathatosag' value='3' $checked>";
                    $HTMLkod .="<label for='CLathatosag_3' class='label_1'>Nyilvános <b>(mindenki látja)</b></label><br>";  
                    
                    if($CLathatosag==-1){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_A' name='CLathatosag' value='-1' $checked>";
                    $HTMLkod .="<label for='CLathatosag_A' class='label_1'>Archívumban olvasható <b>(mindenki látja)</b></label><br>";  
                    
                    //Előzetes kezdőlapra
                    //$HTMLkod .= "<h2>Cikkelőzetesek megjelenítése kezdőlapon</h2>"; 
                    $HTMLkod .= "</fieldset>";
                    $HTMLkod .= "<fieldset> <legend>Cikkelőzetesek megjelenítése <u>kezdőlapon</u>:</legend>";  
                    if($KoElozetes==0){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='KoElozetes0' name='KoElozetes' value='0' $checked>";
                    $HTMLkod .="<label for='KoElozetes0' class='label_1'>Előzetese <b>nem</b> látszik kezdőlapon</b></label><br>";
                    if($KoElozetes==1){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='KoElozetes1' name='KoElozetes' value='1' $checked>";
                    $HTMLkod .="<label for='KoElozetes1' class='label_1'>Előzetese látszik kezdőlapon</b></label><br>";  
                    if($KoElozetes==2){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='KoElozetes2' name='KoElozetes' value='2' $checked>";
                    $HTMLkod .="<label for='KoElozetes2' class='label_1'>Előzetese <b>kiemelten</b> látszik kezdőlapon</b></label>";
                    
                    //Előzetes szülőoldalra
                    //$HTMLkod .= "<h2>Cikkelőzetesek megjelenítése szülőoldalon</h2>";
                    $HTMLkod .= "</fieldset>";
                    $HTMLkod .= "<fieldset> <legend>Cikkelőzetesek megjelenítése <u>szülőoldalon</u>:</legend>"; 
                    if($SZoElozetes==0){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='SZoElozetes0' name='SZoElozetes' value='0' $checked>";
                    $HTMLkod .="<label for='SZoElozetes0' class='label_1'>Előzetese <b>nem</b> látszik szülőoldalon</b></label><br>";  
                    if($SZoElozetes==1){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='SZoElozetes1' name='SZoElozetes' value='1' $checked>";
                    $HTMLkod .="<label for='SZoElozetes1' class='label_1'>Előzetese látszik szülőoldalon</b></label><br>"; 
                    if($SZoElozetes==2){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='SZoElozetes2' name='SZoElozetes' value='2' $checked>";
                    $HTMLkod .="<label for='SZoElozetes2' class='label_1'>Előzetese <b>kiemelten</b> látszik szülőoldalon</b></label><br>"; 
                    
                }
                //Prioritas
                $HTMLkod .= "</fieldset>";
                $HTMLkod .= "<fieldset> <legend>A cikk pozíciója:</legend>";
                $HTMLkod .= "<p class='pCPrioritas'><label for='CPrioritas' class='label_1'>Prioritás:</label>\n ";
                $HTMLkod .= "<input type='number' name='CPrioritas' id='CPrioritas' min='0' max='127' step='1' value='$CPrioritas'></p>\n";
                
                $HTMLkod .= "</fieldset>";
                //Submit
                $HTMLkod .= "<input type='submit' name='submitCikkForm' value='Módosítás'><br>\n";

                $HTMLkod .= "</form>\n";
                $HTMLkod .= "</div>\n";
            }
        } else {//Ha elküldték és hibás 
            $CNev        ='';
            $CTartalom   ='';
            $CLeiras     ='';
            $CLathatosag =0;
            $CPrioritas  =0;
            $KoElozetes  =0;
            $SZoElozetes =0;
            
            if (isset($_POST['CNev']))        {$CNev        = test_post($_POST['CNev']);}
            if (isset($_POST['CTartalom']))   {$CTartalom   = STR_post($_POST['CTartalom']);}
            if (isset($_POST['CLeiras']))     {$CLeiras     = test_post($_POST['CLeiras']);}
            if (isset($_POST['CLathatosag'])) {$CLathatosag = INT_post($_POST['CLathatosag']);} 
            if (isset($_POST['CPrioritas']))  {$CPrioritas  = INT_post($_POST['CPrioritas']);}
            
            if (isset($_POST['KoElozetes']))  {$KoElozetes  = INT_post($_POST['KoElozetes']);}
            if (isset($_POST['SZoElozetes'])) {$SZoElozetes = INT_post($_POST['SZoElozetes']);}
            
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
            $HTMLkod .= "<h2>Cikk módosítása</h2>\n";
            
            $HTMLkod .= "<fieldset> <legend>Az cikk adatai:</legend>";

            //Cikk neve
            $HTMLkod .= "<p class='pCNev'><label for='CNev' class='label_1'>Cikk neve:</label><br>\n ";
            $HTMLkod .= "<input type='text' name='CNev' id='CNev' class='$ErrClassCNev' placeholder='Cikk név' value='$CNev' size='60'></p>\n";
            //Cikk rövid leírása
            $HTMLkod .= "<p class='pCLeiras'><label for=CLeiras class='label_1'>Cikk rövid leírása:</label><br>\n ";
            $HTMLkod .= "<textarea name='CLeiras' id='CLeiras' placeholder='Cikk leírás' rows='2' cols='88'>".$CLeiras."</textarea></p>\n";
            //Cikk tartalma
            $HTMLkod .= "<p class='pCTartalom'><label for='CTartalom' class='label_1'>Cikk tartalma:</label><br>\n ";
            $HTMLkod .= "<textarea name='CTartalom' id='CTartalom' class='$ErrClassCTartalom' placeholder='Cikk tartalom' rows='8' cols='88'>".$CTartalom."</textarea></p>\n";

            if ($_SESSION['AktFelhasznalo'.'FSzint']>2) {            
                //Láthatóság
                 //Láthatóság
                    $HTMLkod .= "</fieldset>";
                    $HTMLkod .= "<fieldset> <legend>A cikk láthatósága:</legend>";        
                        
                    if($CLathatosag==0){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_0' name='CLathatosag' value='0' $checked>";
                    $HTMLkod .="<label for='CLathatosag_0' class='label_1'>Moderátor és tulajdonos</label><br>";
                    
                    if($CLathatosag==1){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_1' name='CLathatosag' value='1' $checked>";
                    $HTMLkod .="<label for='CLathatosag_1' class='label_1'>Bejelentkezett felhasználók</label><br>";     
                    
                    if($CLathatosag==2){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_2' name='CLathatosag' value='2' $checked>";
                    $HTMLkod .="<label for='CLathatosag_2' class='label_1'>Csoport számára látható</label><br>";
                    
                    if($CLathatosag==3){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_3' name='CLathatosag' value='3' $checked>";
                    $HTMLkod .="<label for='CLathatosag_3' class='label_1'>Nyilvános <b>(mindenki látja)</b></label><br>";  
                    
                    if($CLathatosag==-1){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='CLathatosag_A' name='CLathatosag' value='-1' $checked>";
                    $HTMLkod .="<label for='CLathatosag_A' class='label_1'>Archívumban olvasható <b>(mindenki látja)</b></label><br>";  
                    
                    //Előzetes kezdőlapra
                    //$HTMLkod .= "<h2>Cikkelőzetesek megjelenítése kezdőlapon</h2>"; 
                    $HTMLkod .= "</fieldset>";
                    $HTMLkod .= "<fieldset> <legend>Cikkelőzetesek megjelenítése <u>kezdőlapon</u>:</legend>";  
                    if($KoElozetes==0){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='KoElozetes0' name='KoElozetes' value='0' $checked>";
                    $HTMLkod .="<label for='KoElozetes0' class='label_1'>Előzetese <b>nem</b> látszik kezdőlapon</b></label><br>";
                    if($KoElozetes==1){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='KoElozetes1' name='KoElozetes' value='1' $checked>";
                    $HTMLkod .="<label for='KoElozetes1' class='label_1'>Előzetese látszik kezdőlapon</b></label><br>";  
                    if($KoElozetes==2){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='KoElozetes2' name='KoElozetes' value='2' $checked>";
                    $HTMLkod .="<label for='KoElozetes2' class='label_1'>Előzetese <b>kiemelten</b> látszik kezdőlapon</b></label>";
                    
                    //Előzetes szülőoldalra
                    //$HTMLkod .= "<h2>Cikkelőzetesek megjelenítése szülőoldalon</h2>";
                    $HTMLkod .= "</fieldset>";
                    $HTMLkod .= "<fieldset> <legend>Cikkelőzetesek megjelenítése <u>szülőoldalon</u>:</legend>"; 
                    if($SZoElozetes==0){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='SZoElozetes0' name='SZoElozetes' value='0' $checked>";
                    $HTMLkod .="<label for='SZoElozetes0' class='label_1'>Előzetese <b>nem</b> látszik szülőoldalon</b></label><br>";  
                    if($SZoElozetes==1){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='SZoElozetes1' name='SZoElozetes' value='1' $checked>";
                    $HTMLkod .="<label for='SZoElozetes1' class='label_1'>Előzetese látszik szülőoldalon</b></label><br>"; 
                    if($SZoElozetes==2){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='SZoElozetes2' name='SZoElozetes' value='2' $checked>";
                    $HTMLkod .="<label for='SZoElozetes2' class='label_1'>Előzetese <b>kiemelten</b> látszik szülőoldalon</b></label><br>"; 
                   
            }
            //Prioritas
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<fieldset> <legend>A cikk pozíciója:</legend>";
            $HTMLkod .= "<p class='pCPrioritas'><label for='CPrioritas' class='label_1'>Prioritás:</label>\n ";
            $HTMLkod .= "<input type='number' name='CPrioritas' id='CPrioritas' min='0' max='127' step='1' value='$CPrioritas'></p>\n";

            $HTMLkod .= "</fieldset>";            
            //Submit
            $HTMLkod .= "<input type='submit' name='submitCikkForm' value='Módosítás'><br>\n";

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
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $CNev = '';
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
    $ErrorStr    = "";
    $ErrorStr   .= setCikkValaszt();
    $id          = $_SESSION['SzerkCikk'.'id'];
    $CLeiras     = '';
    $CPrioritas  = 0;
    $CLathatosag = 0;
    $KoElozetes  = 0;
    $SZoElozetes = 0;    
    
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) && (isset($_POST['submitCikkForm']))) {
        $Oid = $Aktoldal['id'];
        // ============== HIBAKEZELÉS =====================
        //Az oldalnév ellenőrzése  
        if (isset($_POST['CNev'])) {
            $CNev      = test_post($_POST['CNev']);
            
            $SelectStr =   "SELECT C.id, C.CNev
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid = C.id
                            WHERE OC.Oid=$Oid
                            AND C.CNev='$CNev'";
            $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sMC 01 ");
            $rowDB     = mysqli_num_rows($result);
            if ($rowDB > 0) {
		$row   = mysqli_fetch_array($result);
		if($id!=$row['id']) {$ErrorStr .= ' Err002,';}
            }
            mysqli_free_result($result);            
            if (strlen($CNev)>60) { $ErrorStr .= ' Err003,';}
            if (strlen($CNev)<3)  { $ErrorStr .= ' Err004,';}
        } else {$ErrorStr = ' Err001,';}
        
         
        //Tartalom ellenőrzése
        if (isset($_POST['CTartalom'])) {
            //$CTartalom = test_post($_POST['CTartalom']); 
            if ($_SESSION['AktFelhasznalo'.'FSzint']<4) {$CTartalom = test_post($_POST['CTartalom']); } else {$CTartalom = SQL_post($_POST['CTartalom']); }
            if ($_SESSION['AktFelhasznalo'.'FSzint']<4) {$CTartalom = SzintaxisCsere($CTartalom); }
            if (strlen(!$CTartalom)){ $ErrorStr .= ' Err005';}
        }
        if (isset($_POST['CLeiras']))     {$CLeiras     = test_post($_POST['CLeiras']);}
        if (isset($_POST['CLathatosag'])) {$CLathatosag = INT_post($_POST['CLathatosag']);}
        if (isset($_POST['CPrioritas']))  {$CPrioritas  = INT_post($_POST['CPrioritas']);}
        if (isset($_POST['KoElozetes']))  {$KoElozetes  = INT_post($_POST['KoElozetes']);}
        if (isset($_POST['SZoElozetes'])) {$SZoElozetes = INT_post($_POST['SZoElozetes']);}

        if ($ErrorStr=='') {
            //==========Névváltozás ellenőrzése===========        
            $SelectStr = "SELECT CNev From Cikkek WHERE id=$id";                         echo "<h1>$SZoElozetes</h1>";
            $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba CKnv 02345");
            $row       = mysqli_fetch_array($result);  mysql_free_result($result);
            $RCNev     = $row['CNev'];
            if ($RCNev!=$CNev) {$ErrorStr=KepekAtnevez($RCNev,$CNev,$id);}

            if ($ErrorStr=='') {
                //=========REKORDOK MÓDOSÍTÁSA =============        
                $UpdateStr = "UPDATE Cikkek SET
                            CNev           = '$CNev',
                            CTartalom      = '$CTartalom',
                            Cleiras        =  '$CLeiras',
                            CLathatosag    =  '$CLathatosag', 
                            KoElozetes     =  '$KoElozetes',
                            SZoElozetes    =  '$SZoElozetes',     
                            CModositasTime = NOW()
                            WHERE       id = $id";
                mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uMC 01 ");
                $UpdateStr   = "UPDATE OldalCikkei SET
                                CPrioritas = $CPrioritas
                                WHERE   Cid=$id AND Oid=$Oid LIMIT 1"; 
                mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uMC 02 ");
            }
            
        }
    }
    return $ErrorStr;
}




// ==================== CIKK TÖRLÉSE =================

function setCikkTorol() { 
    global $MySqliLink, $Aktoldal;
    if (isset($_POST['submitCikkTorol'])) { 
        $SelectStr  = "SELECT Cid FROM OldalCikkei WHERE Oid=".$Aktoldal['id'];
        $result     = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba COT 01");
        while ($row = mysqli_fetch_array($result)){   
            $i      = $row['Cid']; 
            $id     = $_POST["CikkTorolId_$i"]; 
            if ($_POST["CikkTorol_$i"]){  
                $DeleteStr = "DELETE FROM Cikkek WHERE id = $id";
                mysqli_query($MySqliLink, $DeleteStr);
                $DeleteStr = "DELETE FROM OldalCikkei WHERE Cid = $id";
                mysqli_query($MySqliLink, $DeleteStr);
                CikkOsszesKepTorol($id,$Aktoldal['OImgDir']);
            }
        }    
    }
}

function EgyCikkTorol($Cid) {
    global $MySqliLink, $Aktoldal;
    $DeleteStr = "DELETE FROM Cikkek WHERE id = $id";
    mysqli_query($MySqliLink, $DeleteStr);
    $DeleteStr = "DELETE FROM OldalCikkei WHERE Cid = $id";
    mysqli_query($MySqliLink, $DeleteStr);
    CikkOsszesKepTorol($id,$Aktoldal['OImgDir']);
}

function OldalOsszesCikkekTorol($Oid) {
    global $MySqliLink;
    $ErrorStr   = ""; 
    $SelectStr  = "SELECT OImgDir FROM Oldalak WHERE id = $Oid";     
    $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OOCT1 1"); 
    $row        = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);
    $OImgDir    = $row['OImgDir'];
    $SelectStr  = "SELECT Cid FROM OldalCikkei WHERE Oid=$Oid";  
    $result     = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba OOCT2 01");
    while ($row = mysqli_fetch_array($result)){   
        $id     = $row['Cid'];
        
            $ErrorStr .= CikkOsszesKepTorol($id,$OImgDir);
            $DeleteStr = "DELETE FROM Cikkek WHERE id = $id";
            mysqli_query($MySqliLink, $DeleteStr);
            $DeleteStr = "DELETE FROM OldalCikkei WHERE Cid = $id";
            mysqli_query($MySqliLink, $DeleteStr);
            
        
    }   
    return $ErrorStr;
}

function getCikkTorolForm() {
    global $MySqliLink, $Aktoldal;
    $HTMLkod = "";
    $Oid     = $Aktoldal['id'];
    $OUrl    = $Aktoldal['OUrl'];
    $Fid     = $_SESSION['AktFelhasznalo'.'id'];
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1) {
        if ($_SESSION['AktFelhasznalo'.'FSzint']==2) {
            $SelectStr = "SELECT C.id, C.CNev
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id 
                            WHERE OC.Oid=$Oid 
                            AND C.CSzerzo=$Fid";
            $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCT 01 ");
        } else {
            $SelectStr = "SELECT C.id, C.CNev
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id 
                            WHERE OC.Oid=$Oid";
            $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCT 01 ");
        }
        
        $HTMLkod .= "<div id='divCikkTorolForm' >\n";
        $HTMLkod .= "<h2>Cikkek törlése</h2>\n";
        $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formCikkTorolForm'>\n";
        
        $HTMLkod .= "<fieldset> <legend>Az oldal cikkei:</legend>";
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $CNev = $row['CNev'];
            $id   = $row['id'];

            //Törlésre jelölés
            $HTMLkod .= "<p class='pCikkTorol'><input type='checkbox' name='CikkTorol_$id' id='CikkTorol_$id'>"
                    . "<label for='CikkTorol_$id' class='label_1'>$CNev</label>\n ";
            $HTMLkod .= "</p>\n";

            //id
            $HTMLkod .= "<input type='hidden' name='CikkTorolId_$id' id='CikkTorolId_$id' value='$id'>\n";

            $i++;
        }
        $HTMLkod .= "<input type='hidden' name='TorolDB' id='TorolDB' value='$i'>\n";
        $HTMLkod .= "</fieldset>";
        
        $HTMLkod .= "<input type='submit' name='submitCikkTorol' id='submitCikkTorol' value='Törlés'>\n";
        $HTMLkod .= "</form>\n";
        $HTMLkod .= "</div>\n";
    }
    return $HTMLkod;
}

function getKezdolapCikkelozetesekHTML() {  
    global $MySqliLink, $AlapAdatok;
    $HTMLkod   = '';
    $SelectStr =   "SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE C.KoElozetes>0  ORDER BY C.KoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";
    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sMC 01 ");
    $rowDB     = mysqli_num_rows($result);
    if ($rowDB > 0) {
        $AlapKep  = 'img/ikonok/HeaderImg/'.$AlapAdatok['HeaderImg'];
        while ($row    = mysqli_fetch_array($result)){
           $Cid        = $row['Cid']; 
           $CNev       = $row['CNev'];
           $OImgDir    = $row['OImgDir'];
           $CTartalom  = $row['CTartalom'];
           $CLeiras    = $row['CLeiras'];
           $Horgony    = "#".getTXTtoURL($row['CNev']);
           $CikkLink   = "<a class='Jobbra' href='?f0=".$row['OUrl'].$Horgony."'>".$row['CNev']." részletesen...</a>";
           if ($OImgDir!='') {
               $KepUtvonal = "img/oldalak/".$OImgDir."/";
           } else {
               $KepUtvonal = "img/oldalak/";
           }
           $HTMLimg    = getElsoKepHTML($Cid,$KepUtvonal);  
           if ($HTMLimg==''){ $HTMLimg="<img src='$AlapKep'  class = 'imgOE' alt=''>";}
           $HTMLkod   .= "<div class ='divOElozetesKulso'>\n";          
           $HTMLkod   .= "<div class = 'divOElozetesKep'>$HTMLimg</div>\n";   
           $HTMLkod   .= "<div class='divOElozetesTartalom'>\n";
           $HTMLkod   .= "<h3>".$CNev."</h3>\n";
           if ($CLeiras!='') {$HTMLkod .= "<div class = 'divOElozetesLeir'>".$CLeiras."</div>\n";}    
           $HTMLkod .= "</div>\n";
           $HTMLkod .= $CikkLink;
           $HTMLkod .= "<p class='pCszerzoNev'> Szerző: ".$row['CSzerzoNev']."</p>\n";           
           $HTMLkod .= "<p class='pCModTime'>Közzétéve: ".$row['CModositasTime']." </p>\n";
           $HTMLkod .= "</div>\n";    
        }
    }
    if ($HTMLkod!='') {$HTMLkod = "<div class ='divCElozetesKulso'>\n <h2>Hírelőzetesek</h2> $HTMLkod</div>"; }
    return $HTMLkod;
}

function getSzulooldalCikkelozetesekHTML() {  
    global $MySqliLink, $Aktoldal, $AlapAdatok;
    $Oid       = $Aktoldal['id'];
    $HTMLkod   = '';
    $SelectStr =   "SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE O.OSzuloId=$Oid AND C.SZoElozetes>0 ORDER BY C.SZoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";
    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sMC 01 ");
    $rowDB     = mysqli_num_rows($result);
    if ($rowDB > 0) {
        $AlapKep  = 'img/ikonok/HeaderImg/'.$AlapAdatok['HeaderImg'];
        while ($row    = mysqli_fetch_array($result)){
           $Cid        = $row['Cid']; 
           $CNev       = $row['CNev'];
           $OImgDir    = $row['OImgDir'];
           $CTartalom  = $row['CTartalom'];
           $CLeiras    = $row['CLeiras'];
           $Horgony    = "#".getTXTtoURL($row['CNev']);
           $CikkLink   = "<a class='OElink' href='?f0=".$row['OUrl'].$Horgony."'>".$row['CNev']." részletesen...</a>";
           if ($OImgDir!='') {
               $KepUtvonal = "img/oldalak/".$OImgDir."/";
           } else {
               $KepUtvonal = "img/oldalak/";
           }
           $HTMLimg    = getElsoKepHTML($Cid,$KepUtvonal);  
           if ($HTMLimg==''){ $HTMLimg="<img src='$AlapKep'  class = 'imgOE' alt=''>";}
           $HTMLkod   .= "<div class ='divOElozetesKulso'>\n";          
           $HTMLkod   .= "<div class = 'divOElozetesKep'>$HTMLimg</div>\n";   
           $HTMLkod   .= "<div class='divOElozetesTartalom'>\n";
           $HTMLkod   .= "<h3>".$CNev."</h3>\n";
           if ($CLeiras!='') {$HTMLkod .= "<div class = 'divOElozetesLeir'>".$CLeiras."</div>\n";}    
           $HTMLkod .= "</div>\n";
           $HTMLkod .= $CikkLink;
           $HTMLkod .= "<p class='pCszerzoNev'> Szerző: ".$row['CSzerzoNev']."</p>\n";           
           $HTMLkod .= "<p class='pCModTime'>Közzétéve: ".$row['CModositasTime']." </p>\n";
           $HTMLkod .= "</div>\n";
           
        }
    }
    if ($HTMLkod!='') {$HTMLkod = "<div class ='divCElozetesKulso'>\n <h2>Hírelőzetesek</h2> $HTMLkod</div>"; }
    return $HTMLkod;
}


function getCikkElozetesHTML() {
	trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
