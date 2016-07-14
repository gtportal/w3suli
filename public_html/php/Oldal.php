<?php


/**
 * OTipus
 *  0 = Kezdőlap
 *  1 = Kategória
 *  2 = Híroldal
 * 
 * 10 = Bejelentkezés
 * 11 = Kijelentkezés
 * 12 = Regisztráció
 * 13 = Felhasználó törlése
 * 14 = Felhasználó lista 
 * 15 = Adatmódosítás
 * 16 = Jelszómodosítás 
 * 
 * 20 = Felhasználói csoportok 
 * 21 = Oldaltérkép
 * 
 * 51 = Alapbeállítások
 * 52 = Kiegészítő tartalom
 * 53 = Főmenü linkjeinek beállítása
 *
 */

    // ============== ADATKEZELÉS - ALAPADATOK INICIALIZÁLÁSA =====================
    $Aktoldal['id']                 = -1;
    $Aktoldal['ONev']               = '';
    $Aktoldal['OUrl']               = '';
    $Aktoldal['OLathatosag']        = 0;
    $Aktoldal['OPrioritas']         = 0;
    $Aktoldal['OLeiras']            = '';
    $Aktoldal['OKulcsszavak']       = '';
    $Aktoldal['OSzuloId']           = 0;
    $Aktoldal['OTipus']             = -1;
    $Aktoldal['OTartalom']          = '';
    $Aktoldal['OImgDir']            = '';
    $Aktoldal['OImg']               = '';
    
    $Aktoldal['OLatszik']           = 1;

    $SzuloOldal['id']               = -1;
    $SzuloOldal['ONev']             = '';
    $SzuloOldal['OUrl']             = '';
    $SzuloOldal['OLathatosag']      = 0;
    $SzuloOldal['OSzuloId']         = 0;    
    $SzuloOldal['OPrioritas']       = 0;
    $SzuloOldal['OLeiras']          = '';
    $SzuloOldal['OTipus']           = 0;
    $SzuloOldal['OImgDir']          = '';
    $SzuloOldal['OImg']             = '';   
    
    $NagyszuloOldal['id']           = -1;
    $NagyszuloOldal['ONev']         = '';
    $NagyszuloOldal['OUrl']         = '';
    $NagyszuloOldal['OLathatosag']  = 0;
    $NagyszuloOldal['OSzuloId']     = 0;    
    $NagyszuloOldal['OPrioritas']   = 0;
    $NagyszuloOldal['OLeiras']      = '';
    $NagyszuloOldal['OTipus']       = 0;
    $NagyszuloOldal['OImgDir']      = '';
    $NagyszuloOldal['OImg']         = '';     
    
    function getOldalData($OUrl) {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $DedSzuloId, $UkSzuloId, $MySqliLink;
        // ============== ADATKEZELÉS - ADATOK BETOLVASÁSA =====================
        
        
        $tiszta_OURL = $OUrl;
        if ($tiszta_OURL=='') {$tiszta_OURL = 'kezdolap';}
        if ($tiszta_OURL=='') {$tiszta_OURL = 'Homepage';}
        //Az aktuális oldal adatainak betöltése
        $SelectStr   = "SELECT * FROM Oldalak WHERE OUrl='$tiszta_OURL' LIMIT 1"; //echo $SelectStr;
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOD 01 ");
        $rowDB       = mysqli_num_rows($result); 
        if ($rowDB > 0) {
          $row    = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);
          $Aktoldal['id']           = $row['id']; 
          $Aktoldal['ONev']         = $row['ONev']; 
          $Aktoldal['OUrl']         = $row['OUrl']; 
          $Aktoldal['OLathatosag']  = $row['OLathatosag']; 
          $Aktoldal['OPrioritas']   = $row['OPrioritas']; 
          $Aktoldal['OLeiras']      = $row['OLeiras']; 
          $Aktoldal['OKulcsszavak'] = $row['OKulcsszavak']; 
          $Aktoldal['OSzuloId']     = $row['OSzuloId']; 
          $Aktoldal['OTipus']       = $row['OTipus']; 
          $Aktoldal['OTartalom']    = $row['OTartalom']; 
          $Aktoldal['OImgDir']      = $row['OImgDir']; 
          $Aktoldal['OImg']         = $row['OImg']; 
        }
        //A szülőoldal adatainak betöltése
        if ($Aktoldal['OSzuloId']>0) {
          $Oid = $Aktoldal['OSzuloId'];  
          $SelectStr   = "SELECT * FROM Oldalak WHERE id=$Oid LIMIT 1"; 
          $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOD 02 "); 
          $rowDB       = mysqli_num_rows($result); 
          if ($rowDB > 0) {
            $row    = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);
            $SzuloOldal['id']           = $row['id']; 
            $SzuloOldal['ONev']         = $row['ONev']; 
            $SzuloOldal['OUrl']         = $row['OUrl']; 
            $SzuloOldal['OLathatosag']  = $row['OLathatosag']; 
            $SzuloOldal['OPrioritas']   = $row['OPrioritas']; 
            $SzuloOldal['OLeiras']      = $row['OLeiras']; 
            $SzuloOldal['OSzuloId']     = $row['OSzuloId']; 
            $SzuloOldal['OTipus']       = $row['OTipus']; 
            $SzuloOldal['OImgDir']      = $row['OImgDir']; 
            $SzuloOldal['OImg']         = $row['OImg']; 
            
          }  
        }
        
        //A nagyszülőoldal adatainak betöltése
        if ($SzuloOldal['OSzuloId']>0) {
          $Oid = $SzuloOldal['OSzuloId'];  
          $SelectStr   = "SELECT * FROM Oldalak WHERE id=$Oid LIMIT 1";        
          $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOD 03 ");
          $rowDB       = mysqli_num_rows($result); 
          if ($rowDB > 0) {
            $row    = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);
            $NagyszuloOldal['id']           = $row['id']; 
            $NagyszuloOldal['ONev']         = $row['ONev']; 
            $NagyszuloOldal['OUrl']         = $row['OUrl']; 
            $NagyszuloOldal['OLathatosag']  = $row['OLathatosag']; 
            $NagyszuloOldal['OPrioritas']   = $row['OPrioritas']; 
            $NagyszuloOldal['OLeiras']      = $row['OLeiras']; 
            $NagyszuloOldal['OSzuloId']     = $row['OSzuloId']; 
            $NagyszuloOldal['OTipus']       = $row['OTipus']; 
            $NagyszuloOldal['OImgDir']      = $row['OImgDir']; 
            $NagyszuloOldal['OImg']         = $row['OImg']; 
          }   
        }
        
        // Extra 4. szint kezelése
        if ($NagyszuloOldal['id']>1) {            
            $SelectStr   = "SELECT OSzuloId FROM Oldalak WHERE id=".$NagyszuloOldal['id']." LIMIT 1"; 
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOD 01 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) {
                $row    = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);
                if ($row['OSzuloId']>1) {$DedSzuloId = $row['OSzuloId'];}
            }
        }
        // Extra 5. szint kezelése
        if ($DedSzuloId>1) {            
            $SelectStr   = "SELECT OSzuloId FROM Oldalak WHERE id=".$DedSzuloId." LIMIT 1"; 
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOD 01x ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) {
                $row    = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);
                if ($row['OSzuloId']>1) {$UkSzuloId = $row['OSzuloId'];}
            }
        }        
        
        
        if($_SESSION['ElozoOldalId'] != $Aktoldal['id']){$_SESSION['SzerkCikk'.'id']=0; $_SESSION['SzerkCikk'.'Oid']=0; }
        //Ha nem szerkesztő oldal, akkor eltároljuk ez lesz az ElozoOldalId
        //Egy szerkesztés, be- vagy kijelentkezés után ide térünk vissza
        if ($Aktoldal['OTipus']<10) {$_SESSION['ElozoOldalId']   = $Aktoldal['id']; }
        
    }


    function getUjOldalForm() {
        global $Aktoldal;
        $HTMLkod  = '';
        $ErrorStr = ''; 

        //Csak rendszergazdáknak és moderátoroknak!
        if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!            
          $UjONev       = '';
          $UjOTipS      = '';   
          $ErrClassOTip = '';
          $Oid          = $Aktoldal['id'];    
          $OUrl         = $Aktoldal['OUrl'];    
        
          //Ha még nem lett elküldve vagy az új oldal már létrelött
          if (!isset($_POST['submitUjOldalForm']) || ($_SESSION['ErrorStr']==''))  {
            if (isset($_POST['UjONev']))       {$UjONev  = test_post($_POST['UjONev']);}
            if (isset($_POST['UjOTipValszt'])) {$UjOTipS = test_post($_POST['UjOTipValszt']);}  
            // ============== FORM ÖSSZEÁLLÍTÁSA =====================  
            $HTMLkod .= "<div id='divUjOldalForm' >\n";
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formUjOldalForm'>\n";   //echo "<h1>'. $ErrorStr</h1>";
            $HTMLkod .= "<h2>".U_OLDAL_UJLETREHOZ."</h2>\n ";
            $HTMLkod .= "<fieldset> <legend>".U_OLDAL_UJADAT.":</legend>";
            //Üzenet megjelenítése
            if ($ErrorStr!='') {
            $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
            //Oldalnév
            $HTMLkod .= "<p class='pUjONev'><label for='UjONev' class='label_1'>".U_OLDAL_CIM.":</label><br>\n ";
            $HTMLkod .= "<input type='text' name='UjONev' id='UjONev' placeholder='".U_OLDAL_CIM."' 
                          value='$UjONev' size='40'></p>\n";  
            //Oldaltípus
            $HTMLkod .=  "<label for='UjOTipValszt'>".U_OLDAL_TIPUS.": </label>\n
                          <select name='UjOTipValszt' id='UjOTipValszt' size='1' >\n"; 
            if ($UjOTipS=='Kategoria') {$Sel=' selected ';} else {$Sel='';}
            $HTMLkod .=  "<option value='Kategoria' $Sel>".U_OTIPUS_KAT."</option>\n";            
            if ($UjOTipS=='HirOldal') {$Sel=' selected ';} else {$Sel='';}
            $HTMLkod .=  "<option value='HirOldal' $Sel>".U_OTIPUS_HIR."</option>\n";
            $HTMLkod .=  "</select>\n";        
            $HTMLkod .= "</fieldset>";
            //Submit
            $HTMLkod .=  "<br><input type='submit' name='submitUjOldalForm' value='".U_BTN_LETRHOZAS."'><br>\n";        
            $HTMLkod .= "</form>\n";
            $HTMLkod .= "</div>\n";
          } else {
          //Ha elküldték és hibás    
            if (isset($_POST['UjONev']))       {$UjONev  = test_post($_POST['UjONev']);}
            if (isset($_POST['UjOTipValszt'])) {$UjOTipS = test_post($_POST['UjOTipValszt']);}
            
            // ============== HIBAKEZELÉS ===================== 
            $ErrClassONev = '';
            //Oldalnév
            $ErrClass = '';
            if (strpos($_SESSION['ErrorStr'],'Err001')!==false) {
              $ErrClassONev = ' Error '; 
              $ErrorStr .= U_OERR_CIMNINCS."!<br>";
            }   
            if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
              $ErrClassONev = ' Error '; 
              $ErrorStr .= U_OERR_CIMVANMAR."!<br>";
            }            
            if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
              $ErrClassONev = ' Error '; 
              $ErrorStr .= U_OERR_CIMHOSSZU."!<br>";
            }      

            //Oldaltípus
            $ErrClass = '';
            if (strpos($_SESSION['ErrorStr'],'Err004')!==false) {
              $ErrClassOTip = ' Error '; 
              $ErrorStr .= U_OERR_TIPUSNINCS."!<br>";
            }      
            // ============== FORM ÖSSZEÁLLÍTÁSA ===================== 
            $HTMLkod .= "<div id='divUjOldalForm' >\n";
            if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formUjOldalForm'>\n";
            $HTMLkod .= "<h2>".U_OLDAL_UJLETREHOZ."</h2>\n ";
            $HTMLkod .= "<fieldset> <legend>".U_OLDAL_UJADAT.":</legend>";
                        
            $HTMLkod .= "<p><label for='UjONev' class='label_1'>".U_OLDAL_CIM.":</label><br>\n ";
            $HTMLkod .= "<input type='text' name='UjONev' id='UjONev' placeholder='".U_OLDAL_CIM."' 
                         class='$ErrClassONev' value='$UjONev' size='40'></p>\n";              
            
            $HTMLkod .=  "<label for='UjOTipValszt'>".U_OLDAL_TIPUS.": </label>\n
                          <select name='UjOTipValszt' id='UjOTipValszt' size='1' class='$ErrClassOTip'>\n"; 
            if ($UjOTipS=='Kategoria') {$Sel=' selected ';} else {$Sel='';}
            $HTMLkod .=  "<option value='Kategoria' $Sel>".U_OTIPUS_KAT."</option>\n";            
            if ($UjOTipS=='HirOldal') {$Sel=' selected ';} else {$Sel='';}
            $HTMLkod .=  "<option value='HirOldal' $Sel>".U_OTIPUS_HIR."</option>\n";
            $HTMLkod .=  "</select>\n";  
            $HTMLkod .= "</fieldset>";
            //Submit
            $HTMLkod .=  "<br><input type='submit' name='submitUjOldalForm' value='".U_BTN_LETRHOZAS."'><br>\n";        
            $HTMLkod .= "</form>\n";
            $HTMLkod .= "</div>\n";
          }
        }
        return $HTMLkod;
    }    
    
    
    function setUjOldal() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink, $oURL;
        //Csak rendszergazdáknak és moderátoroknak!
        $ErrorStr = '';
        if (($_SESSION['AktFelhasznalo'.'FSzint']>4) && (isset($_POST['submitUjOldalForm'])))   { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
          // ============== HIBAKEZELÉS =====================
          //A beérkező adatok ellenőrzése  
          //Az oldalnév ellenőrzése  
          if (isset($_POST['UjONev'])) {
              $UjONev      = test_post($_POST['UjONev']);
              $UjOUrl      = getTXTtoURL($UjONev);
              $SelectStr   = "SELECT id FROM Oldalak WHERE OUrl='$UjOUrl' LIMIT 1"; // echo "<h1>$SelectStr</h1>";
              $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUO 01 ");
              $rowDB       = mysqli_num_rows($result); 
              if ($rowDB > 0) { $ErrorStr .= ' Err002,'; mysqli_free_result($result);}
              if (strlen($UjONev)>40) { $ErrorStr .= ' Err003,';}
              if (strlen($UjONev)<1)  { $ErrorStr .= ' Err001,';}
          } else {$ErrorStr = ' Err001,';}
          //A típus ellenőrzése
          if (isset($_POST['UjOTipValszt'])) {
            $UjOTipS = test_post($_POST['UjOTipValszt']);  //echo "<h1>UjOTipS: $UjOTipS</h1>";
            $UjOTipKod = 0;
            switch ($UjOTipS) {
              case  'Kategoria': $UjOTipKod = 1; break;
              case  'HirOldal' : $UjOTipKod = 2; break;
              default: $ErrorStr .= ' Err004,';
            }             
          } else {$ErrorStr .= ' Err004,';} 
          // ============== KÖNYVTÁRKEZELÉS - ÚJ KÉPKÖNYVTÁR LÉTREHOZÁSA   =====================
          if (($ErrorStr=='') && (($UjOTipKod == 1) || ($UjOTipKod == 2))) {
                $KTarNev ='';
                if ($ErrorStr=='') {
                    $KTarNev = getTXTtoURL($UjONev);
                    $ErrorStr= KepkonyvtarLetrehoz($KTarNev);     
                }          
          }
          
          // ============== ADATKEZELÉS - ÚJ REKORD LÉTREHOZÁSA   =====================
          if ($ErrorStr=='') {
           //Az oldal mentése
           $AktOid        = $Aktoldal['id'];  
           $OLathatosag   = $Aktoldal['OLathatosag'];
           $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', '$UjONev','$UjOUrl',$OLathatosag,1,'Az oldal leírása',
                                                          'Az oldal kulcsszavai',$AktOid,$UjOTipKod,'Az oldal tartalma','$KTarNev','')";
           
           if (!mysqli_query($MySqliLink,$InsertIntoStr)) {
               die("Hiba UO 01 ");               
           } else {
               $UjID= mysqli_insert_id($MySqliLink);  
               // =================== VÁLTÁS AZ ÚJ OLDALRA =========================
               // Megj. Az előző ID alapján lekérjük a hozzátartozó $oURL-t, amely alapján az aktuális olda adatainak kezelése folyik 
                $SelectStr   = "SELECT OUrl FROM Oldalak WHERE id=$UjID ";  //echo "<h1>$SelectStr</h1>";
                $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba VÁLTÁSI H 01aa ");
                $rowDB       = mysqli_num_rows($result); 
                if ($rowDB > 0) {
                    $row  = mysqli_fetch_array($result);
                    mysqli_free_result($result);
                    $oURL = $row['OUrl'];
                    getOldalData($oURL);       // Most már ez lesz az aktuális oldal
                }
                $ErrorStr = "A(z) $UjONev oldal elkészült.";
           }               
          }            
        }
        return $ErrorStr;
    }

    function getOldalForm() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
        $HTMLkod  = '';
        $ErrorStr = ''; 
       // echo $_SESSION['ErrorStr'];
        //Csak rendszergazdáknak és moderátoroknak!
        if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!            
      
          $Oid          = $Aktoldal['id'];  
          $ONev         = $Aktoldal['ONev'];
          $OUrl         = $Aktoldal['OUrl'];    
          $OLathatosag  = $Aktoldal['OLathatosag'];    
          $OPrioritas   = $Aktoldal['OPrioritas'];  
          $OLeiras      = $Aktoldal['OLeiras'];    
          $OKulcsszavak = $Aktoldal['OKulcsszavak'];  
          $OSzuloId     = $Aktoldal['OSzuloId'];    
          $OTipus       = $Aktoldal['OTipus'];      
          $OTartalom    = $Aktoldal['OTartalom'];   
          $OImgDir      = $Aktoldal['OImgDir'];   
          $OImg         = $Aktoldal['OImg']; 
          
          if ($OImgDir == '') {$OImgSrc= 'img/oldalak/'.$OImg;}
          else  {$OImgSrc= 'img/oldalak/'.$OImgDir.'/'.$OImg;}   //echo "OImgSrc: $OImgSrc";

          $OTipS        = '';
          if ($OTipus==1) {$OTipS = 'Kategoria';}
          if ($OTipus==2) {$OTipS = 'HirOldal';}
          //Ha még nem lett elküldve vagy az oldal adatait sikerült módosítani >> nem volt hiba
          if (!isset($_POST['submitOldalForm']) || ($_SESSION['ErrorStr']==''))  { 
            if (isset($_POST['ONev']))       {$ONev  = test_post($_POST['ONev']);}
            if (isset($_POST['OTipValszt'])) {$OTipS = test_post($_POST['OTipValszt']);}  
            
            
            //Kis kép feltöltése            
            $ErrClassKep    = '';
            if (isset($_POST['submitKisKepTolt'])) {
                $ErrorStr        = $_SESSION['ErrorStr'];
                if (strpos($ErrorStr,'Err')!==false) {
                    if (strpos($ErrorStr,'ErrK00')!==false) {
                        $ErrArr      = array('ErrK00' => U_FETOLT_ER000);  
                        $ErrorStr    = strtr($ErrorStr ,$ErrArr); 
                        $ErrClassKep = 'ErrorStr1';
                    }  
                    if (strpos($ErrorStr,'ErrK01')!==false) {
                       $ErrArr      = array('ErrK01' => U_FETOLT_ER001);  
                       $ErrorStr    = strtr($ErrorStr ,$ErrArr); 
                       $ErrClassKep = 'ErrorStr1';
                    }
                    if (strpos($ErrorStr,'ErrK02')!==false) {
                       $ErrArr       = array('ErrK02' => U_FETOLT_ER002);  
                       $ErrorStr     = strtr($ErrorStr ,$ErrArr); 
                       $ErrClassKep  = 'ErrorStr1';
                    }
                    if (strpos($ErrorStr,'ErrK03')!==false) {
                       $ErrArr       = array('ErrK03' => U_FETOLT_ER003);  
                       $ErrorStr     = strtr($ErrorStr ,$ErrArr);
                       $ErrClassKep  = 'ErrorStr1';
                    }    
                    if (strpos($ErrorStr,'ErrK05')!==false) {
                       $ErrArr       = array('ErrK05' => U_FETOLT_ER002);  
                       $ErrorStr     = strtr($ErrorStr ,$ErrArr); 
                       $ErrClassKep  = 'ErrorStr1';
                    }
                } 
                if ($ErrClassKep == '' ){
                    $ErrorStr        = "<p class='time'>".U_MODOSITVA.":".date("H.i.s.")."<p>".$ErrorStr; 
                } else {
                    $ErrorStr        = "<p class='time'>".U_ELKULDVE.":".date("H.i.s.")."<p>".$ErrorStr;
                }
            } 
            if (isset($_POST['submitOldalKepForm'])) {
              $ErrorStr          = "<p class='time'>".U_MODOSITVA.":".date("H.i.s.")."<p>";  

            }
            
            // ============== FORM ÖSSZEÁLLÍTÁSA =====================         
            $HTMLkod .= "<div id='divOldalForm' >\n";            
            
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' enctype='multipart/form-data' id='KisKepFelForm'>\n";
            $HTMLkod .= "<h2>".U_OLDAL_KISKEP."</h2>\n";
            if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
            $HTMLkod .= "<fieldset> <legend>".U_OLDAL_KISKEPVAL.":</legend>";
            $HTMLkod .= "<img src='$OImgSrc' style='float:left;margin:5px;' alt='".U_KIS_KEP."' height='60' id='KiskepKep'>\n";
            $HTMLkod .= "<input type='file' name='file' id='fileKepTolt' >";
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<input type='submit' name='submitKisKepTorol' id='submitKisKepTorol' value='".U_BTN_TOROL."'>";
            $HTMLkod .= "<input type='submit' name='submitKisKepTolt' id='submitKisKepTolt' value='".U_BTN_FELTOLT."'><br><br>";
            $HTMLkod .= "</form>\n";
            
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOldalForm'>\n";
            $HTMLkod .= "<h2>".U_OLDAL_MODOSIT."</h2> \n";
            $HTMLkod .= "<fieldset> <legend>".U_OLDAL_ADATAI.":</legend>";
            
            //Oldalnév
            $HTMLkod .= "<p class='pONev'><label for='ONev' class='label_1'>".U_OLDAL_CIM.":</label><br>\n ";
            $HTMLkod .= "<input type='text' name='ONev' id='ONev' placeholder='".U_OLDAL_CIM."' 
                          value='$ONev' size='40'></p>\n";  
            //Oldaltípus
            $HTMLkod .=  "<label for='pOTipValszt'>".U_OLDAL_TIPUS.": </label>\n
                          <select name='OTipValszt' id='OTipValszt' size='1' >\n"; 
            if ($OTipS=='Kategoria') {$Sel=' selected ';} else {$Sel='';}
            $HTMLkod .=  "<option value='Kategoria' $Sel>".U_OTIPUS_KAT."</option>\n";
            if ($OTipS=='HirOldal') {$Sel=' selected ';} else {$Sel='';}
            $HTMLkod .=  "<option value='HirOldal' $Sel>".U_OTIPUS_HIR."</option>\n";
            $HTMLkod .=  "</select>\n";   
            
            //Prioritás
            $HTMLkod .= "<p class='pOPrioritas'><label for='OPrioritas' class='label_1'>".U_PRIORITAS.":</label>\n ";
            $HTMLkod .= "<input type='number' name='OPrioritas' id='OPrioritas' min='0' max='127' step='1' value='$OPrioritas'></p>\n";  

        
            //Láthatóság
            $HTMLkod .= "<div id='divOLathatosag'><h2>".U_OLDAL_LATHATO.":</h2>";
            
            if($OLathatosag==0){$checked=" checked ";}else{$checked="";}
            $HTMLkod .="<input type='radio' id='OLathatosag_0' name='OLathatosag' value='0' $checked>";
            $HTMLkod .="<label for='OLathatosag_0' class='label_1'>".U_OLATHAT_SENKI."</label><br>";
            if($OLathatosag==1){$checked=" checked ";}else{$checked="";}
            $HTMLkod .="<input type='radio' id='OLathatosag_1' name='OLathatosag' value='1' $checked>";
            $HTMLkod .="<label for='OLathatosag_1' class='label_1'>".U_OLATHAT_MINDEN."</label><br>";
            if($OLathatosag==2){$checked=" checked ";}else{$checked="";}
            $HTMLkod .="<input type='radio' id='chOldalLathatosagForm' name='OLathatosag' value='2' $checked>";
            $HTMLkod .="<label for='chOldalLathatosagForm' class='label_1'>".U_OLATHAT_CSOP."</label><br>";
    
            
            $HTMLkod .=getOLathatosagForm();
            $HTMLkod .="</div>";
            
            //Kulcsszavak
            $HTMLkod .= "<p class='pOKulcsszavak'><label for='OKulcsszavak' class='label_1'>".U_OLDAL_KULCSSZAVAK.":</label>\n ";
            $HTMLkod .= "<input type='text' name='OKulcsszavak' id='OKulcsszavak' placeholder='".U_OLDAL_KULCSSZO."' 
                          value='$OKulcsszavak' size='40'></p>\n"; 
            
            //Rövíd leírás
            $HTMLkod .= "<p class='pOLeiras'><label for='OLeiras' class='label_1'>".U_LEIRAS.":</label><br>\n ";
            $HTMLkod .= "<textarea name='OLeiras' id='OLeiras' placeholder='".U_LEIRAS."' 
              rows='2' cols='100' >".$OLeiras."</textarea></p>\n";     
            
            //Tartalom
            $HTMLkod .= "<p class='pOTartalom'><label for='OTartalom' class='label_1'>".U_TARTALOM.":</label><br>\n ";
            $HTMLkod .= "<textarea name='OTartalom' id='OTartalom' placeholder='".U_TARTALOM."' 
              rows='8' cols='100' >".$OTartalom."</textarea></p>\n";
            $HTMLkod .= "</fieldset>";
            //Submit
            $HTMLkod .=  "<br><input type='submit' name='submitOldalForm' value='".U_BTN_MODOSITAS."'><br>\n";        
            $HTMLkod .= "</form>\n";
            $HTMLkod .= "</div>\n";
          } 
          //Ha elküldték és hibás, vagy a kis kép feltöltésénél történt hiba 
          if (isset($_POST['submitOldalForm'])  && ($_SESSION['ErrorStr']!='')){             
            if (isset($_POST['ONev']))       {$ONev  = test_post($_POST['ONev']);}
            if (isset($_POST['OTipValszt'])) {$OTipS = test_post($_POST['OTipValszt']);}
           // $ErrorStr = '';         
                        
            //Oldalnév
            $ErrClassONev = '';
            if (strpos($_SESSION['ErrorStr'],'Err001')!==false) {
              $ErrClassONev  = ' Error '; 
              $ErrorStr     .= U_OERR_CIMNINCS."!<br>";
            }            
            if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
              $ErrClassONev  = ' Error '; 
              $ErrorStr     .= U_OERR_CIMVANMAR."!<br>";
            }            
            if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
              $ErrClassONev  = ' Error '; 
              $ErrorStr     .= U_OERR_CIMHOSSZU."!<br>";
            }                     
            //Oldaltípus
            $ErrClassOTip    = '';
            if (strpos($_SESSION['ErrorStr'],'Err004')!==false) {
              $ErrClassOTip  = ' Error '; 
              $ErrorStr     .= U_OERR_TIPUSNINCS."!<br>";
            }
            
       
            // ============== FORM ÖSSZEÁLLÍTÁSA ===================== 
            $HTMLkod .= "<div id='divOldalForm' >\n";
            if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
            
            $HTMLkod .= "<h2>".U_OLDAL_KISKEP."</h2>\n";
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' enctype='multipart/form-data' id='KisKepFelForm'>\n";
            $HTMLkod .= "<fieldset> <legend>".U_OLDAL_KISKEPVAL.":</legend>";
            $HTMLkod .= "<img src='$OImgSrc' style='float:left;margin:5px;' alt='".U_KIS_KEP."' height='60' id='KiskepKep'>\n";
            $HTMLkod .= "<input type='file' name='file' id='fileKepTolt' >";
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<input type='submit' name='submitKisKepTorol' id='submitKisKepTorol' value='".U_BTN_TOROL."'>";
            $HTMLkod .= "<input type='submit' name='submitKisKepTolt' id='submitKisKepTolt' value='".U_BTN_FELTOLT."'><br><br>";
            $HTMLkod .= "</form>\n";
            
            $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOldalForm'>\n";
            $HTMLkod .= "<h2>".U_OLDAL_MODOSIT."</h2> \n";
            $HTMLkod .= "<fieldset> <legend>".U_OLDAL_ADATAI.":</legend>";
            
            $HTMLkod .= "<p class='pONev'><label for='ONev' class='label_1'>".U_OLDAL_CIM.":</label><br>\n ";
            $HTMLkod .= "<input type='text' name='ONev' id='ONev' placeholder='".U_OLDAL_CIM."' 
                         class='$ErrClassONev' value='$ONev' size='40'></p>\n"; 
            
            //Prioritás
            $HTMLkod .= "<p class='pOPrioritas'><label for='OPrioritas' class='label_1'>".U_PRIORITAS.":</label>\n ";
            $HTMLkod .= "<input type='number' name='OPrioritas' id='OPrioritas' min='0' max='100' step='1' value='$OPrioritas'></p>\n";  

 	    //Láthatóság
            $HTMLkod .= "<div id='divOLathatosag'><h2>".U_OLDAL_LATHATO.":</h2>";

            if(isset($_POST['OLathatosag']) && $_POST['OLathatosag']==0){$checked=" checked ";}else{$checked="";}
            $HTMLkod .="<input type='radio' id='OLathatosag_0' name='OLathatosag' value='0' $checked>";
            $HTMLkod .="<label for='OLathatosag_0' class='label_1'>".U_OLATHAT_SENKI."</label><br>";
            if(isset($_POST['OLathatosag']) && $_POST['OLathatosag']==1){$checked=" checked ";}else{$checked="";}
            $HTMLkod .="<input type='radio' id='OLathatosag_1' name='OLathatosag' value='1' $checked>";
            $HTMLkod .="<label for='OLathatosag_1' class='label_1'>".U_OLATHAT_MINDEN."</label><br>";
            if(isset($_POST['OLathatosag']) && $_POST['OLathatosag']==2){$checked=" checked ";}else{$checked="";}
            $HTMLkod .="<input type='radio' id='chOldalLathatosagForm' name='OLathatosag' value='2' $checked>";
            $HTMLkod .="<label for='chOldalLathatosagForm' class='label_1'>".U_OLATHAT_CSOP."</label><br>";
            
            $HTMLkod .=getOLathatosagForm();
            $HTMLkod .="</div>";  
            
            //Kulcsszavak
            $HTMLkod .= "<p class='pOKulcsszavak'><label for='OKulcsszavak' class='label_1'>".U_OLDAL_KULCSSZAVAK.":</label>\n ";
            $HTMLkod .= "<input type='text' name='OKulcsszavak' id='OKulcsszavak' placeholder='".U_OLDAL_KULCSSZO."' 
                          value='$OKulcsszavak' size='40'></p>\n"; 
            
     
            $HTMLkod .=  "<label for='OTipValszt'>".U_OLDAL_TIPUS.": </label>\n
                          <select name='OTipValszt' id='OTipValszt' size='1' class='$ErrClassOTip'>\n";           
            if ($OTipS=='Kategoria') {$Sel=' selected ';} else {$Sel='';}
            $HTMLkod .=  "<option value='Kategoria' $Sel>".U_OTIPUS_KAT."</option>\n";
            if ($OTipS=='HirOldal') {$Sel=' selected ';} else {$Sel='';}
            $HTMLkod .=  "<option value='HirOldal' $Sel>".U_OTIPUS_HIR."</option>\n";
            $HTMLkod .=  "</select>\n";    
            //Rövíd leírás
            $HTMLkod .= "<p  class='pOLeiras'><label for='OLeiras' class='label_1'>".U_LEIRAS.":</label><br>\n ";
            $HTMLkod .= "<textarea name='OLeiras' id='OLeiras' placeholder='".U_LEIRAS."' 
              rows='2' cols='100' >".$OLeiras."</textarea></p>\n";
            
            //Tartalom
            $HTMLkod .= "<p  class='pOTartalom'><label for='OTartalom' class='label_1'>".U_TARTALOM.":</label><br>\n ";
            $HTMLkod .= "<textarea name='OTartalom' id='OTartalom' placeholder='".U_TARTALOM."' 
              rows='8' cols='100' >".$OTartalom."</textarea></p>\n";
            $HTMLkod .= "</fieldset>";
            //Submit
            $HTMLkod .=  "<br><input type='submit' name='submitOldalForm' value='".U_BTN_MODOSITAS."'><br>\n";        
            $HTMLkod .= "</form>\n";            
            $HTMLkod .= "</div>\n";           
          }
        }
        return $HTMLkod;
   
    }

function setOldal() {
    global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
    
    $ErrorStr = '';  
    //Csak rendszergazdáknak és moderátoroknak!   
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4) { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
          
        $Oid          = $Aktoldal['id'];  
        $ONev         = $Aktoldal['ONev'];
        $OUrl         = $Aktoldal['OUrl'];    
        $OLathatosag  = $Aktoldal['OLathatosag'];    
        $OPrioritas   = $Aktoldal['OPrioritas'];  
        $OLeiras      = $Aktoldal['OLeiras'];    
        $OKulcsszavak = $Aktoldal['OKulcsszavak'];  
        $OSzuloId     = $Aktoldal['OSzuloId'];    
        $OTipus       = $Aktoldal['OTipus'];      
        $OTartalom    = $Aktoldal['OTartalom'];   
        $OImgDir      = $Aktoldal['OImgDir'];   
        $OImg         = $Aktoldal['OImg']; 
        
        if ($Aktoldal['OImgDir']=='') {$FelOImgDir= 'img/oldalak/';}
          else  {$FelOImgDir= 'img/oldalak/'.$Aktoldal['OImgDir'].'/';}
        
        // ============== KÉP FELTÖLTÉSE HIBAKEZELÉSSEL =====================        
        if (isset($_POST['submitKisKepTolt']))  {
          $OImgUj       = setKepFeltolt($FelOImgDir,$Aktoldal['OUrl']); 
          if (strpos($OImgUj,'ErrK')===false) {
             $AktOid    = $Aktoldal['id'];
             $UpdateStr = "UPDATE Oldalak SET 
                           OImg='$OImgUj'
                           WHERE id=$AktOid LIMIT 1"; 
             if (!mysqli_query($MySqliLink,$UpdateStr))  {$ErrorStr .= ' ErrK03 '.$OImgUj; }    
             $OImg      = $Aktoldal['OImg']=$OImgUj;
             
          } 
          if ($ErrorStr==''){ $ErrorStr .= $OImgUj; } 
        }    
        // ============== KÉP TÖRLÉSE =====================  
        // Kis kép törlése
        if (isset($_POST['submitKisKepTorol']))  {
        //  $OImgUj       = setKepTorol($FelOImgDir,$Aktoldal['OUrl']); 
            //A kép nevének lekrédezése  
            $AktOid    = $Aktoldal['id'];
            $OImg      = $Aktoldal['OImg'];
            $OImgDir   = $Aktoldal['OImgDir'];

            if ($OImg != '') {
                //A Kép törlése az oldal adatbázis rekordjából  
                $UpdateStr = "UPDATE Oldalak SET 
                              OImg=''
                              WHERE id=$AktOid LIMIT 1"; 
                if (!mysqli_query($MySqliLink,$UpdateStr))  {$ErrorStr .= ' ErrK03 '.$OImgUj; }    

                //Ellenőrizzük, hogy másik oldal használja-e, ha nem, akkor a fájlt is törlőljük
                $SelectStr1 = "SELECT id FROM Oldalak WHERE OImg='$OImg' AND OImgDir='$OImgDir' LIMIT 1";     // echo $SelectStr1; 
                $result1    = mysqli_query($MySqliLink,$SelectStr1) OR die("Hiba sMC 01 zz");
                $rowDB1     = mysqli_num_rows($result1);
                if ($rowDB1== 0) {
                    if ($OImgDir == '') {$OImgSrc= 'img/oldalak/'.$OImg;}
                    else  {$OImgSrc= 'img/oldalak/'.$OImgDir.'/'.$OImg;} 
                    unlink($OImgSrc);
                    
                } else {mysqli_free_result($result1);}
            }
          //if ($ErrorStr==''){ $ErrorStr .= $OImgUj; } 
        }           
        
          
        if (isset($_POST['submitOldalForm'])) { 
          // ============== HIBAKEZELÉS =====================          
          //A beérkező adatok ellenőrzése  
          //Az oldalnév ellenőrzése  
          if ((isset($_POST['ONev'])) && ($OUrl!='Kezdolap')){
              $ONev        = test_post($_POST['ONev']);
              $OUrl        = getTXTtoURL($ONev);          
              $SelectStr   = "SELECT id FROM Oldalak WHERE OUrl='$OUrl' LIMIT 1"; 
              $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUF 01 ");
              $rowDB       = mysqli_num_rows($result); 
              if ($rowDB > 0) { 
                  $row     = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);
                  if($row['id']!= $Aktoldal['id']) {$ErrorStr .= ' Err002,';} 
              }
              if (strlen($ONev)>40) { $ErrorStr .= ' Err003,';}
              if (strlen($ONev)<1)  { $ErrorStr .= ' Err001,';}
          } else if ($OUrl!='Kezdolap') {$ErrorStr = ' Err001,';}
          //A típus ellenőrzése
          if (isset($_POST['OTipValszt'])) {
            $OTipS = test_post($_POST['OTipValszt']); 
            $OTipKod = 0;
            switch ($OTipS) {
              case  'Kategoria': $OTipKod = 1; break;
              case  'HirOldal' : $OTipKod = 2; break;
              default: $ErrorStr .= ' Err004,';
            }             
          } else {$ErrorStr .= ' Err004,';} 
          // ============== OLDALNÉV VÁLTOZÁSÁNEK KEZELÉSE ===================== 
          if (($OUrl!='Kezdolap') && ($ONev!=$Aktoldal['ONev']) && ($ErrorStr=='') && (($OTipKod == 1) || ($OTipKod == 2))) {
                $OUrl  = getTXTtoURL($ONev);              
                // ============== KÖNYVTÁRKEZELÉS - A KÉPKÖNYVTÁR átnevezése   =====================
                if ($ErrorStr=='') {
                    $KTarNev = getTXTtoURL($ONev); //echo "<h1>Régi $KTarNev !!!!!!!!!</h1>";
                    $ErrorStr= KepkonyvtarAtnevez($OImgDir,$KTarNev);     
                }                  
                if ($ErrorStr=='') {$OImgDir=$KTarNev;}
          }
          
          // ============== ADATKEZELÉS - MÓDOSÍTÁS =====================
          if ($ErrorStr=='') {
            if (isset($_POST['OLeiras']))      {$OLeiras      = test_post($_POST['OLeiras']);}  
           // if (isset($_POST['OTartalom']))    {$OTartalom=test_post($_POST['OTartalom']);} 
            if (isset($_POST['OTartalom']))    {$OTartalom    = SQL_post($_POST['OTartalom']);} 
            if (isset($_POST['OPrioritas']))   {$OPrioritas   = INT_post($_POST['OPrioritas']);}  
	    if (isset($_POST['OLathatosag']))  {$OLathatosag  = INT_post($_POST['OLathatosag']);}
	    if (isset($_POST['OKulcsszavak'])) {$OKulcsszavak = test_post($_POST['OKulcsszavak']);}
           //Az oldal mentése
           $AktOid = $Aktoldal['id'];
                      
           //Ha a kezdőlapnál beállították láthatóságnak a 0-t, akkor a kezdőlapét módosítani kell
           
           if($OUrl=="Kezdolap"){$OLathatosag=1;};
           $UpdateStr = "UPDATE Oldalak SET 
                         OTipus=$OTipKod,
                         ONev='$ONev',
                         OUrl='$OUrl',
                         OPrioritas='$OPrioritas',
                         OLeiras='$OLeiras',
                         OKulcsszavak='$OKulcsszavak',
                         OLathatosag='$OLathatosag',
                         OImgDir='$OImgDir',
                         OTartalom='$OTartalom'    
                         WHERE id=$AktOid LIMIT 1"; 
           if (!mysqli_query($MySqliLink,$UpdateStr))  {echo "Hiba setO 01 ";}           
            
           if (isset($_POST['OLathatosag']))  {$OLathatosag=INT_post($_POST['OLathatosag']);}
           
//-------------------------------------------------------------------------------------
//OLDALLÁTHATÓSÁG BEÁLLÍTÁSA AZ ALOLDALAKRA IS
//-------------------------------------------------------------------------------------
            //ELSŐ SZINT
            $SelectStr     = "SELECT id FROM Oldalak WHERE OSzuloId=$AktOid";
            $result        = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba setO 02");
            $rowDB         = mysqli_num_rows($result); 
            if ($rowDB > 0) {
                while ($row = mysqli_fetch_array($result)){
                    $AktOGyermekId = $row['id'];
                    $UpdateStr     = "UPDATE Oldalak SET OLathatosag='$OLathatosag' WHERE id=$AktOGyermekId LIMIT 1"; 
                    if (!mysqli_query($MySqliLink,$UpdateStr))  {echo "Hiba setO 03 ";}

                    //MÁSODIK SZINT
                    $SelectStr   = "SELECT id FROM Oldalak WHERE OSzuloId=$AktOGyermekId";
                    $result_2    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba setO 04");
                    $rowDB       = mysqli_num_rows($result_2); 
                    if ($rowDB > 0) {
                        while ($row_2 = mysqli_fetch_array($result_2)){
                            $AktOUnokaId = $row_2['id'];
                            $UpdateStr   = "UPDATE Oldalak SET OLathatosag='$OLathatosag' WHERE id=$AktOUnokaId LIMIT 1"; 
                            if (!mysqli_query($MySqliLink,$UpdateStr))  {echo "Hiba setO 05 ";}

                            //HARMADIK SZINT
                            $SelectStr   = "SELECT id FROM Oldalak WHERE OSzuloId=$AktOUnokaId";
                            $result_3    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba setO 06");
                            $rowDB       = mysqli_num_rows($result_3); 
                            if ($rowDB > 0) {
                                while ($row_3 = mysqli_fetch_array($result_3)){
                                    $AktODedunokaId = $row_3['id'];
                                    $UpdateStr      = "UPDATE Oldalak SET OLathatosag='$OLathatosag' WHERE id=$AktODedunokaId LIMIT 1"; 
                                    if (!mysqli_query($MySqliLink,$UpdateStr))  {echo "Hiba setO 07 ";}

                                    //NEGYEDIK SZINT
                                    $SelectStr   = "SELECT id FROM Oldalak WHERE OSzuloId=$AktODedunokaId";
                                    $result_4 = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba setO 08");
                                    $rowDB       = mysqli_num_rows($result_3); 
                                    if ($rowDB > 0) {
                                        while ($row_4 = mysqli_fetch_array($result_4)){
                                            $AktOUkdunokaId = $row_4['id'];
                                            $UpdateStr      = "UPDATE Oldalak SET OLathatosag='$OLathatosag' WHERE id=$AktOUkdunokaId LIMIT 1"; 
                                            if (!mysqli_query($MySqliLink,$UpdateStr))  {echo "Hiba setO 09 ";}
                                        }
                                        mysqli_free_result($result_4);
                                    }    
                                }
                                mysqli_free_result($result_3);
                            }  
                        }
                        mysqli_free_result($result_2);
                    }
                    
                }
                mysqli_free_result($result);
            }
            getOldalData($OUrl);
            $ErrorStr = "A(z) $ONev oldal változott.";
           
          }
        } 
    }
    return $ErrorStr;
}


function setOldalTorol() {
    global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
    //Csak rendszergazdáknak és moderátoroknak!
    $ErrorStr = '';
    if (($_SESSION['AktFelhasznalo'.'FSzint']>4) && (($Aktoldal['OTipus'] == 1) || ($Aktoldal['OTipus'] == 2))) {    
        if (($_SESSION['AktFelhasznalo'.'FSzint']>4) && (isset($_POST['submitOldalTorolForm'])))  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
            // ============== HIBAKEZELÉS =====================
            $Oid    = $Aktoldal['id'];
            $ONev   = $Aktoldal['ONev'];
            $SzOUrl = $SzuloOldal['OUrl'];
            //Ellenőrizük, hogy van-e gyermekoldala
            $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$Oid LIMIT 1";        
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sOT 01 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) { $ErrorStr = 'Err001';  mysqli_free_result($result); }   
        }
        
        if (($_SESSION['AktFelhasznalo'.'FSzint']>4) && (isset($_POST['submitOldalTorolVegleges'])))  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
            // ============== HIBAKEZELÉS =====================
            $Oid    = $Aktoldal['id'];
            $ONev   = $Aktoldal['ONev'];
            $SzOUrl = $SzuloOldal['OUrl'];
            //Ellenőrizük, hogy van-e gyermekoldala
            $SelectStr   = "SELECT * FROM Oldalak WHERE OSzuloId=$Oid LIMIT 1";        
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sOT 01 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) { $ErrorStr .= 'Err001'; mysqli_free_result($result);  } 
            if ($ErrorStr == '') {          
                //Az oldal cikkeinek törlés
                $ErrorStr.= OldalOsszesCikkekTorol($Oid);
               // ============== KÖNYVTÁRKEZELÉS - ÚJ KÉPKÖNYVTÁR LÉTREHOZÁSA   =====================  
                if ($ErrorStr == '') { 
                    $KTarNev  = '';               
                    $KTarNev  = $Aktoldal['OImgDir']; 
                    $ErrorStr.= KepkonyvtarTorol($KTarNev);     
                    if ($ErrorStr=='') {          
                        // ============== ADATKEZELÉS - TÖRLÉS ===================== 
                        //Ha gyermektelen, akkor tölőljük  
                        $DeletetStr = "Delete FROM Oldalak  WHERE id=$Oid"; 
                        if (!mysqli_query($MySqliLink,$DeletetStr)) {die("Hiba sOT 02");} 
                        //Üzenet a törlésről
                        $ErrorStr = $ONev.' - '.U_OLDAL_TOROLVE ;
                        //Az aktuális oldal megszünt, így visszalépünk szlőoldalához 
                        getOldalData($SzOUrl);
                    }
                }
            }
        }
        return $ErrorStr;
    }
}    

    function getOldalTorolForm() {
      global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
        $HTMLkod  = '';
        //Csak rendszergazdáknak és moderátoroknak!
        if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
          $OUrl       = $Aktoldal['OUrl'];
          $Oid        = $Aktoldal['id'];  
          $ONev       = $Aktoldal['ONev'];
          $OTipus     = $Aktoldal['OTipus'];
          $SzOUrl     = $SzuloOldal['OUrl'];
          $SzOid      = $SzuloOldal['id'];
          //Csak akkor tötőlhető egy oldal, ha 0<típusa<10
          if ((0<$OTipus) && ($OTipus<10)) {
            //Ha még nem lett elküldve vagy az oldal adatait sikerült módosítani >> nem volt hiba
            if ((!isset($_POST['submitOldalTorolForm'])) && (!isset($_POST['submitOldalTorolVegleges'])))  { 
              // ============== FORM ÖSSZEÁLLÍTÁSA =====================   
              $HTMLkod .= "<div id='divOldalTorolForm' >\n";
              $HTMLkod .= "<h2>".U_OLDAL_TOROL."</h2>\n";
              $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOldalTorolForm'>\n"; 
              $HTMLkod .= "<p class='FontosStr'>".U_OLDAL_TOROL1_1." <u>$ONev</u> ".U_OLDAL_TOROL1_2."?</p>";
              $HTMLkod .= "<p class='FontosStr'>".U_OLDAL_TOROL2."!</p>";  
              $HTMLkod .= "<p class='FontosStr'>".U_OLDAL_TOROL3.".</p>";               
              $HTMLkod .=  "<input type='submit' name='submitOldalTorolForm' value='".U_BTN_TOROL."'><br>\n";        
              $HTMLkod .= "</form>\n";
              $HTMLkod .= "</div>\n";
            }
            //Ha elküldték és hibás 
            if (isset($_POST['submitOldalTorolForm']) &&  (strpos($_SESSION['ErrorStr'],'Err0')!==false))  {  
              // ============== FORM ÖSSZEÁLLÍTÁSA =====================   
              $HTMLkod .= "<div id='divOldalTorolForm' >\n";
              $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOldalTorolForm'>\n";
              $HTMLkod .= "<h2> $ONev - ".U_OLDAL_TOROL."</h2>\n";
              //$HTMLkod .= "<p class='ErrorStr'>".$_SESSION['ErrorStr']."</p>";
              if ($_SESSION['ErrorStr']=='Err001') {$HTMLkod .= "<p class='FontosStr'>".U_OLDAL_TOROL4."!</p>";}
              $HTMLkod .=  "<br><input type='submit' name='submitOldalTorolVegleges' value='".U_BTN_TOROL."'><br>\n";        
              $HTMLkod .= "</form>\n";              
              $HTMLkod .= "</div>\n";
            }
            //Ha elküldték és az oldal törőlhető 
            if (isset($_POST['submitOldalTorolForm']) &&  (strpos($_SESSION['ErrorStr'],'Err0')===false))  { 
              // ============== FORM ÖSSZEÁLLÍTÁSA =====================   
              $HTMLkod .= "<div id='divOldalTorolForm' >\n";
              $HTMLkod .= "<h2> $ONev - ".U_OLDAL_TOROL."</h2>\n";
              $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOldalTorolForm'>\n"; 
              $HTMLkod .= "<p class='FontosStr'>".U_OLDAL_TOROL5."? </p>";
              $HTMLkod .=  "<br><input type='submit' name='submitOldalTorolVegleges' value='".U_BTN_TOROL."'><br>\n";        
              $HTMLkod .= "</form>\n";              
              $HTMLkod .= "</div>\n";
            } 
            //Ha törőltük az oldalt
            if (isset($_POST['submitOldalTorolVegleges']) &&  (strpos($_SESSION['ErrorStr'],'Err0')===false))  {  
              // ============== DIV ÖSSZEÁLLÍTÁSA =====================   
              $HTMLkod .= "<div id='divOldalTorolForm' >\n";              
              $HTMLkod .= "<p class='FontosStr'>".$_SESSION['ErrorStr']."</p>";                       
              $HTMLkod .= "</div>\n";
            } 
          }
        }
        return $HTMLkod;
    }


    function getOldalHTML() {
      global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $DedSzuloId, $AlapAdatok, $MySqliLink, $UkSzuloId;  
      
      $HTMLFormkod   = '';
      if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  {  // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        if(isset($_POST['submitOldalTorolForm'])           || isset($_POST['submitOldalTorolVegleges']) ||
           isset($_POST['submitOModeratorCsoportValaszt']) || isset($_POST['submitOModeratorValaszt']) ||         // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
           isset($_POST['submitOldalForm'])                || isset($_POST['submitUjOldalForm']) ||   
+          isset($_POST['submitOModeratorCsoportValaszt']) || isset($_POST['submitOModeratorValaszt']) ||
+          isset($_POST['submitOModeratorCsoport'])        || isset($_POST['submitKisKepTolt']) ||    
           isset($_POST['submit_KepekFeltoltForm'])        || isset($_POST['submitOldalKepForm']))
			{$checked = " checked ";} else {$checked = "";}                                 //
        $HTMLFormkod  .= "  <input name='chFormkod'   id='chFormkod'   value='chFormkod'   type='checkbox' $checked>\n";
        $HTMLFormkod  .= "  <label for='chFormkod'    class='chLabel'    id='labelchFormkod'>".U_OLDAL_SZ."</label>\n";  
        $HTMLFormkod  .= "<div id='divFormkod'>\n";    
        $HTMLkod       = ''; 
        // ================ A FORMOK MEGJELENÍTÉSÉT SZABÁLYZÓ INPUT ELEMEK =============================        
        if ($UkSzuloId==0) { //5. szintű oldal már nem hozható 
          if(isset($_POST['submitUjOldalForm'])) {$checked = " checked ";} else {$checked = "";} 
          $HTMLFormkod  .= "  <input name='chOldalForm'   id='chUjOldalForm' value='chUjOldalForm' type='radio' $checked>\n";
          $HTMLFormkod  .= "  <label for='chUjOldalForm'  class='chLabel'    id='labelUjOldalForm'>".U_OLDAL_UJ."</label>\n";
        }
        
        if(isset($_POST['submitOldalForm'])  || isset($_POST['submitKisKepTolt'])) {$checked = " checked ";} else {$checked = "";}
        $HTMLFormkod  .= "  <input name='chOldalForm'   id='chOldalForm'   value='chOldalForm'   type='radio' $checked>\n";
        $HTMLFormkod  .= "  <label for='chOldalForm'    class='chLabel'    id='labelOldalForm'>".U_OLDAL_MOD."</label>\n";
        if(isset($_POST['submitOldalTorolForm']) || isset($_POST['submitOldalTorolVegleges'])) {$checked = " checked ";} else {$checked = "";}
        $HTMLFormkod  .= "  <input name='chOldalForm'   id='chOldalTorolForm'  value='chOldalTorolForm'  type='radio' $checked>\n";
        $HTMLFormkod  .= "  <label for='chOldalTorolForm'   class='chLabel'    id='labelOldalTorolForm'>".U_OLDAL_TOR."</label>\n \n";
        if(isset($_POST['submitOldalKepForm']) || isset($_POST['submit_KepekFeltoltForm'])) {$checked = " checked ";} else {$checked = "";}
        $HTMLFormkod  .= "  <input name='chOldalForm'   id='chOldalKepForm' value='chOldalKepForm'  type='radio' $checked>\n";
        $HTMLFormkod  .= "  <label for='chOldalKepForm' class='chLabel'     id='labelOldalKepForm'>".U_OLDAL_KEP."</label>\n \n";
        
        if(isset($_POST['submitOModeratorValaszt']) || isset($_POST['submitOModeratorCsoportValaszt']) || isset($_POST['submitOModeratorCsoport'])) {$checked = " checked ";} else {$checked = "";}
        $HTMLFormkod  .= "  <input name='chOldalForm'   id='chOldalModeratorForm' value='chOldalModeratorForm'  type='radio' $checked>\n";
        $HTMLFormkod  .= "  <label for='chOldalModeratorForm' class='chLabel'     id='labelOldalModeratorForm'>".U_OLDAL_MODE."</label>\n \n";
        // ================ AZ OLDAL MÓDOSÍTSÁT VÉGZŐ FORMOK ====================================
        
        if ($UkSzuloId==0) {$HTMLFormkod  .= getUjOldalForm();} //5. szintű oldal már nem hozható létre
        $HTMLFormkod  .= getOldalForm();
        $HTMLFormkod  .= getOldalTorolForm();
        $HTMLFormkod  .= getOldalKepForm();
        $HTMLFormkod  .= getOModeratorForm(); 
        $HTMLFormkod  .= "</div>\n\n";
      }
      if ($_SESSION['AktFelhasznalo'.'FSzint']>0)  {  // FSzint-et növelni, ha működik a felhasználókezelés!!!
        // ================ AZ OLDALTARTALMÁNAK MEGJELENÍTÉSE =============================     
        $HTMLkod       = ''; 
        // ----------  Speciális tartalom kiíratása  ----------------------------
        $HTMLkod  .= "<div id='divOTartalom'>\n";  
        //$HTMLkod  .= "<h1> OTipus:".$Aktoldal['OTipus']."</h1>\n";
        /*
if ($_SESSION['AktFelhasznalo'.'FSzint'] > 5) { 
    $HTMLkod    .= getTisztaURL(). "<br>"; 
    print_r($_GET);
    $HTMLkod    .= getRootURL();
     
}*/
        switch ($Aktoldal['OTipus']) {
          case 0:   $HTMLkod  .= "<h1>".$AlapAdatok['WebhelyNev']."</h1>\n "; // Kezdőlap                    
                    $arrLapozC    = getCikkLapinfo(10);
                    $LapozHTMLC   = $arrLapozC['LapozHTML'];
                    $SelStrC      = $arrLapozC['SelectStr'];
                    $arrLapozO    = getKatLapinfo(10);
                    $LapozHTMLO   = $arrLapozO['LapozHTML'];
                    $SelStrO      = $arrLapozO['SelectStr']; 
                    $arrLapozCE   = getCikkElozetesLapinfo(10,0);
                    $LapozHTMLCE  = $arrLapozCE['LapozHTML'];
                    $SelStrCE     = $arrLapozCE['SelectStr']; // echo "<h1>XXX $SelStrCE</h1>";                   

                    $HTMLkod     .= $HTMLFormkod;
                    $HTMLkod     .= "<main>";
                    $HTMLkod     .= getCikkekForm();
                    
                    if ($SelStrCE!='') {
                        $HTMLkod .= $LapozHTMLCE;
                        $HTMLkod .= getKezdolapCikkelozetesekHTML($SelStrCE);
                        $HTMLkod .= $LapozHTMLCE;
                    }
                    
                   // $HTMLkod     .= getKezdolapCikkelozetesekHTML();
                    $HTMLkod     .= $Aktoldal['OTartalom'].'<br>';
                     
                    if ($SelStrC!='') {
                        $HTMLkod   .= $LapozHTMLC;
                        $HTMLkod   .= getCikkekHTML($SelStrC);
                        $HTMLkod   .= $LapozHTMLC;
                    }    
                        
                    if ($SelStrO!='') {
                        $HTMLkod   .= $LapozHTMLO;
                        $HTMLkod   .= getOElozetesekHTML($SelStrO);
                        $HTMLkod   .= $LapozHTMLO;
                    }
                        
                    $HTMLkod  .= "</main>";                   
                break;
          case 1:   $HTMLkod  .= "<h1>".$Aktoldal['ONev']."</h1> \n"; // Kategória
                    if (getOLathatosagTeszt() > 0)
                    { // Csak akkor érdekes, ha látogató, vagy bejelentkezett felhasználó     
                        $arrLapozC    = getCikkLapinfo(10);
                        $LapozHTMLC   = $arrLapozC['LapozHTML'];
                        $SelStrC      = $arrLapozC['SelectStr'];
                        $arrLapozO    = getKatLapinfo(10);
                        $LapozHTMLO   = $arrLapozO['LapozHTML'];
                        $SelStrO      = $arrLapozO['SelectStr'];    
                        
                        $HTMLkod  .= $HTMLFormkod;                        
                        $HTMLkod  .= "<main>";                        
                        $HTMLkod  .= getCikkekForm();

                        $HTMLkod  .= $Aktoldal['OTartalom'].'<br>';
                        
                        if ($SelStrC!='') {
                            $HTMLkod   .= $LapozHTMLC;
                            $HTMLkod   .= getCikkekHTML($SelStrC);
                            $HTMLkod   .= $LapozHTMLC;
                        }    

                        if ($SelStrO!='') {
                            $HTMLkod   .= $LapozHTMLO;
                            $HTMLkod   .= getOElozetesekHTML($SelStrO);
                            $HTMLkod   .= $LapozHTMLO;
                        }
                                             
                       // $HTMLkod  .= getSzulooldalCikkelozetesekHTML(); 
                  
                        $HTMLkod  .= "</main>";
                    }
                    else
                    {
                        $HTMLkod .= "<h3>".U_NINCSJOGA."!</h3>";
                    }
                   break;     
          case 2:   $HTMLkod  .= "<h1>".$Aktoldal['ONev']."</h1> \n"; // Híroldal
                    if (getOLathatosagTeszt() > 0) {
                        $arrLapozC    = getCikkLapinfo(10);
                        $LapozHTMLC   = $arrLapozC['LapozHTML'];
                        $SelStrC      = $arrLapozC['SelectStr'];
                        $arrLapozOE   = getKatLapinfo(10);
                        $LapozHTMLOE  = $arrLapozOE['LapozHTML'];
                        $SelStrOE     = $arrLapozOE['SelectStr'];  
                        $arrLapozCE   = getCikkElozetesLapinfo(10,1);
                        $LapozHTMLCE  = $arrLapozCE['LapozHTML'];
                        $SelStrCE     = $arrLapozCE['SelectStr'];  
                        
                        $HTMLkod  .= $HTMLFormkod;
                        $HTMLkod  .= "<main>";
                        $HTMLkod  .= getCikkekForm();
                        $HTMLkod  .= $Aktoldal['OTartalom'].'<br>';
                        
                        if ($SelStrCE!='') {
                            $HTMLkod   .= $LapozHTMLCE;
                            $HTMLkod  .= getSzulooldalCikkelozetesekHTML($SelStrCE);
                            $HTMLkod   .= $LapozHTMLCE;
                        }
                        
                        if ($SelStrC!='') {
                            $HTMLkod   .= $LapozHTMLC;
                            $HTMLkod   .= getCikkekHTML($SelStrC);
                            $HTMLkod   .= $LapozHTMLC;
                        }  
                       
                        $HTMLkod  .= "</main>";
                    }
                    else
                    {
                        $HTMLkod .= "<h3>".U_NINCSJOGA."!</h3>";
                    }     
                   break; 
          case 10:  $HTMLkod  .= "<h1>".Bejelentkezés."</h1> \n";
                    $HTMLkod  .= getBelepesForm();
                   break; 
          case 11:  $HTMLkod  .= "<h1>".Kijelentkezés."</h1> \n";
                    $HTMLkod  .= getKilepesForm();
                   break;
          case 12:  $HTMLkod  .= "<h1>".U_AB_REG."</h1> \n";
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {
                        $HTMLkod      .= getUjFelhasznaloForm();
                    } else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";}
                   break;
          case 13:  $HTMLkod  .= "<h1>".U_AB_FTOROL."</h1> \n";
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {
                          $HTMLkod    .= getFelhasznaloTorol();
                    } else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";}      
                   break;
          case 14:  $HTMLkod  .= "<h1>".U_AB_FLISTA."</h1> \n";
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {
                          $HTMLkod    .= getFelhasznaloLista();
                    } else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";}
                   break;               
          case 15:  $HTMLkod  .= "<h1>".U_AB_ADMODOSIT."</h1> \n"; 
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {
                          $HTMLkod     .= getFelhasznaloForm();                            
                    }  else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";}    
                    break; 
          case 16:  $HTMLkod  .= "<h1>".U_AB_JSZMOD."</h1> \n";              
                    $HTMLkod  .= getUjJelszoForm();
                   break;               
          case 20:  $HTMLkod  .= "<h1>".U_FCSOPORTHOZ."</h1> \n";
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {
                          $HTMLkod     .= getFCsoportForm();
                    }  else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";}      
                   break;  
          case 21:  $HTMLkod  .= "<h1>".U_AB_OTERKEP."</h1> \n";
                    $HTMLkod  .= getOldalterkepHTML();
                   break;        
          case 22:  $HTMLkod  .= "<h1>".U_AB_ARCH."</h1> \n";
                    require_once("php/achivum.php");
                    $HTMLkod  .= getArchivHTML(); 
                   break;                
          case 51:  $HTMLkod  .= "<h1>".U_AB_ALAPBEALL."</h1> \n";
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 6) {
                          $HTMLkod     .= getAlapbeallitasForm();
                    }  else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";}        
                   break;    
          case 52:  $HTMLkod  .= "<h1>".U_AB_KIEGT."</h1> \n";
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 5) {
                           $HTMLkod    .= getKiegTForm();   
                    }  else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";} 
                   break;   
          case 53:  $HTMLkod  .= "<h1>".U_AB_FOLINK."</h1> \n";
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 5) {
                          $HTMLkod     .= getFoMenuForm();
                    }  else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";}        
                   break;       
          case 54:  $HTMLkod  .= "<h1>".U_AB_MENUPL."</h1> \n";
                    if ($_SESSION['AktFelhasznalo'.'FSzint'] > 5) {
                          $HTMLkod     .= getMenuPluszForm();
                    }  else {$HTMLkod  .= "<h3>".U_NINCSJOGA."!</h3>";}        
                   break;     
          default:  $HTMLkod  .= "<h1>".U_HIBA_OLDAL."</h1> \n";
                    $HTMLkod  .= U_HIBA_OLDALINF;                       
        }
        $HTMLkod  .= "</div>\n"; 
      }  
      return $HTMLkod;
    }

    function getHead() {
        global $Aktoldal, $AlapAdatok, $RootURL, $TisztaOURL;
 
        $HTMLkod      = '';
        //Az oldal neve
        if ($Aktoldal['OTipus']!=0) {
           $title   = $Aktoldal['ONev']." - ".$AlapAdatok['WebhelyNev'];
        } else {
           $title   = $AlapAdatok['WebhelyNev'];  
        }
         
        $HTMLkod    .= "<title>$title</title>\n";
        //Az aktuális stíluslap linkje 
        $HTMLkod    .= "<link type='text/css' rel='stylesheet' media='all'   href='css/w3suli_stilus_".$AlapAdatok['Stilus'].".css' />\n";
        $description = $Aktoldal['OLeiras'];
        $HTMLkod    .= "  <meta name='description' content='$description'> \n";
        
        $keywords    = $Aktoldal['OKulcsszavak'];
        $HTMLkod    .= "  <meta name='keywords' content='$keywords'> \n";
        
     //   $TisztaURL   = getTisztaURL();
        $ImgSrc      = $RootURL.'/img/ikonok/HeaderImg/'.$AlapAdatok['HeaderImg'];
        
        if ($Aktoldal['OImg']!='') {
            if ($Aktoldal['OImgDir']!='') {
                $KepUtvonal = "/img/oldalak/".$Aktoldal['OImgDir']."/";            
            } else {
                $KepUtvonal = "/img/oldalak/";    
            }
            $ImgSrc = $RootURL.$KepUtvonal.$Aktoldal['OImg']; 
        } 
        
        
      //ELŐKÉSZÍTVE  
        $HTMLkod    .= "\n<link rel='canonical' href='$TisztaOURL' />\n";   
        
        
     //   echo "<h1>XXXXXX: ".$AlapAdatok['FacebookOK']."</h1>";
        if (($AlapAdatok['FacebookOK']==2) || (($AlapAdatok['FacebookOK']==1)&& ($Aktoldal['OTipus'])==0)){
            $FacebookURL     = $AlapAdatok['FacebookURL'];
            if (strpos($FacebookURL,"facebook.com") === false) {
                $HTMLkod    .= "<meta property='og:url'         content='$TisztaOURL' />\n";
                $HTMLkod    .= "<meta property='og:type'        content='website' />\n";
                $HTMLkod    .= "<meta property='og:title'       content='$title' />\n";
                $HTMLkod    .= "<meta property='og:description' content='$description' />\n";
                $HTMLkod    .= "<meta property='og:image'       content='$ImgSrc' />\n";       
            }
        }
        //https://www.facebook.com/w3suli.blogmotor/?fre
        
        $HTMLkod    .= $AlapAdatok['HEADextra']." \n";
        
        return $HTMLkod;
    }


?>
