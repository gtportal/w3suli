<?php 
$AlapAdatok['WebhelyNev'] = 'Webhely neve'; 
$AlapAdatok['Iskola']     = '';
$AlapAdatok['Cim']        = '';
$AlapAdatok['Telefon']    = '';
$AlapAdatok['Stilus']     =  0;
$AlapAdatok['HeaderStr']  = '';
$AlapAdatok['GoogleKod']  = ''; 
$AlapAdatok['GooglePlus'] =  0;
$AlapAdatok['HeaderImg']  = '';
$AlapAdatok['FavIcon']    = ''; 

$AlapAdatok['HEADextra']  = ''; 
$AlapAdatok['FacebookURL']= ''; 

function setAlapbeallitasok() {  
    global $MySqliLink, $AlapAdatok;
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>6) 
    { // FSzint-et növelni, ha működik a felhasználókezelés!!!
        $WNev        = $AlapAdatok['WebhelyNev'];
        $Iskola      = $AlapAdatok['Iskola'];
        $Cim         = $AlapAdatok['Cim'];
        $Telefon     = $AlapAdatok['Telefon'];
        $Stilus      = $AlapAdatok['Stilus'];        
        $HeaderStr   = $AlapAdatok['HeaderStr'];
        $GoogleKod   = $AlapAdatok['GoogleKod'];        
        $GooglePlus  = 0; 
        $FacebookOK  = 0;
        $HEADextra   = $AlapAdatok['HEADextra'];  // HEADextra
        $FacebookURL = $AlapAdatok['FacebookURL'];// FacebookURL

        if (isset($_POST['submitAlapbeallitasok'])) {  
            if (isset($_POST['WNev']))      {$WNev      = test_post($_POST['WNev']);}  
            if (isset($_POST['Iskola']))    {$Iskola    = test_post($_POST['Iskola']);} 
            if (isset($_POST['Cim']))       {$Cim       = test_post($_POST['Cim']);}  
            if (isset($_POST['Telefon']))   {$Telefon   = test_post($_POST['Telefon']);} 
            if (isset($_POST['Stilus']))    {$Stilus    = INT_post($_POST['Stilus']);}            
            if (isset($_POST['HeaderStr'])) {$HeaderStr = SQL_post($_POST['HeaderStr']);} 
            if (isset($_POST['GoogleKod'])) {$GoogleKod = SQL_post($_POST['GoogleKod']);} 
            if (isset($_POST['GooglePlus'])){$GooglePlus= 1;}   
        if (isset($_POST['HEADextra']))   {$HEADextra   = SQL_post($_POST['HEADextra']);}
        if (isset($_POST['FacebookURL'])) {$FacebookURL = test_post($_POST['FacebookURL']);} 
        if (isset($_POST['FacebookOK']))  {$FacebookOK  = 0;} 
            
            $UpdateStr =   "UPDATE AlapAdatok SET 
                            WebhelyNev='$WNev',
                            Iskola='$Iskola',
                            Cim='$Cim',
                            Telefon='$Telefon',
                            Stilus=$Stilus,
                            HeaderStr='$HeaderStr',  
                            GoogleKod='$GoogleKod',    
                            GooglePlus='$GooglePlus',                                 
                        HEADextra='$HEADextra', 
                        FacebookURL='$FacebookURL', 
                        FacebookOK=$FacebookOK     

                            WHERE id>0 LIMIT 1"; 
            $result    = mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba sAb 01");
        }
        
         // ============== KÉP FELTÖLTÉSE HIBAKEZELÉSSEL =====================        
        if (isset($_POST['submitHeaderImgTolt'])  && isset($_FILES['file']))  {
            $HeaderImg     = test_post($_POST['file']);
            $KepUtvonal    = "img/ikonok/HeaderImg/"; 
            $HeaderImg     = setAlapKepFeltolt($KepUtvonal); 
            if ((strpos($HeaderImg,'Err')===false) && ($HeaderImg!='')) { 
                $UpdateStr = "UPDATE AlapAdatok SET 
                              HeaderImg='$HeaderImg'
                              WHERE id>0 LIMIT 1"; 
                if (!mysqli_query($MySqliLink,$UpdateStr))  {echo "Hiba setHI 01 ";} 
            }  
        } 
        if (isset($_POST['submitFavIconTolt']) && isset($_FILES['file']))  {
            $FavIconImg     = test_post($_POST['file']);
            $KepUtvonal     = "img/ikonok/FavIcon/"; 
            $FavIconImg     = setAlapKepFeltolt($KepUtvonal); 
            if ((strpos($HeaderImg,'Err')===false) && ($FavIconImg!='')) { 
                $UpdateStr  = "UPDATE AlapAdatok SET 
                               FavIcon='$FavIconImg'
                               WHERE id>0 LIMIT 1"; 
                if (!mysqli_query($MySqliLink,$UpdateStr))  {echo "Hiba setfII 01 ";} 
            }  
        } 
    }  
}


function getAlapbeallitasForm() {
    global $AlapAdatok;
    $HTMLkod     = '';
    $ErrorStr    = ''; 
    // MÉG NINCSENEK ILLESZTVE
    $HEADextra   = '';
    $FacebookOK  = 0;
    $FacebookURL = '';

    if ($_SESSION['AktFelhasznalo'.'FSzint']>6)  {
        $WNev        = $AlapAdatok['WebhelyNev'];
        $Iskola      = $AlapAdatok['Iskola'];
        $Cim         = $AlapAdatok['Cim'];
        $Telefon     = $AlapAdatok['Telefon'];
        $Stilus      = $AlapAdatok['Stilus'];
        $HeaderImg   = $AlapAdatok['HeaderImg'];
        $FavIconImg  = $AlapAdatok['FavIcon'];   
        
        $GoogleKod   = $AlapAdatok['GoogleKod'];
        $GooglePlus  = $AlapAdatok['GooglePlus'];
        $HeaderStr   = $AlapAdatok['HeaderStr'];  
    
        $HEADextra   = $AlapAdatok['HEADextra'];  // HEADextra
        $FacebookURL = $AlapAdatok['FacebookURL'];// FacebookURL
        $FacebookOK  = $AlapAdatok['FacebookOK']; // FacebookOK

        $HTMLkod .= "<div id='divAlapbeallitasForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

        $HTMLkod .= "<form action='?f0=alapbeallitasok' method='post' id='formAlapbeallitasForm'>\n";
        $HTMLkod .= "<h2>".U_ALAPBE_WBEALL.":</h2>";
        $HTMLkod .= "<fieldset> <legend>".U_ALAPBE_WADATOK.":</legend>";
        //Webhely neve
        $HTMLkod .= "<p class='pWNev'><label for='WNev' class='label_1'>".U_ALAPBE_WNEV.":</label><br>\n ";
        $HTMLkod .= "<input type='text' name='WNev' id='WNev' placeholder='".U_ALAPBE_WNEV."' value='$WNev' size='40'></p>\n"; 
        
        //Fejléc szovege
        $HTMLkod .= "<p class='pIskola'><label for='HeaderStr' class='label_1'>".U_ALAPBE_HSZOVEG.":</label><br>\n ";
        $HTMLkod .= "<input type='text' name='HeaderStr' id='HeaderStr' placeholder='".U_ALAPBE_HSZOVEGPL."' value='$HeaderStr' size='40'></p>\n";
        $HTMLkod .= "</fieldset>";                   
        
        $HTMLkod .= "<fieldset> <legend>".U_ALAPBE_CEGADATOK.":</legend>";
        //Iskola neve
        $HTMLkod .= "<p class='pIskola'><label for='Iskola' class='label_1'>".U_ALAPBE_CEGNEV.":</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Iskola' id='Iskola' placeholder='".U_ALAPBE_CEGNEV."' value='$Iskola' size='40'></p>\n"; 

        //Iskola címe
        $HTMLkod .= "<p class='pCim'><label for='Cim' class='label_1'>".U_ALAPBE_CEGCIM.":</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Cim' id='Cim' placeholder='".U_ALAPBE_CEGCIM."' value='$Cim' size='40'></p>\n"; 

        //Iskola telefonszám
        $HTMLkod .= "<p class='pTelefon'><label for='Telefon' class='label_1'>".U_ALAPBE_CEGTEL.":</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Telefon' id='Telefon' placeholder='".U_ALAPBE_CEGTEL."' value='$Telefon' size='40'></p>\n";
        $HTMLkod .= "</fieldset>";
       
        $HTMLkod .= "<fieldset> <legend>".U_ALAPBE_WSITLUS.":</legend>";
        //Stíluskiválasztó
        $HTMLkod .= "<p class='pStilus'><label for='Stilus' class='label_1'>".U_ALAPBE_SITLUS.":</label>\n ";
        $HTMLkod .= "<input type='number' name='Stilus' id='Stilus' min='0' max='13' step='1' value='$Stilus'></p>\n";  
        $HTMLkod .= "</fieldset>";

        $HTMLkod .= "<fieldset> <legend>".U_ALAPBE_KOZOSSEGI.":</legend>";
        // Google+ gomb
        if($GooglePlus==1){$checked=" checked ";}else{$checked="";}
        $HTMLkod .="<input type='checkbox' id='GooglePlus' name='GooglePlus' value='0' $checked>";
        $HTMLkod .="<label for='GooglePlus' class='label_1'>".U_ALAPBE_GOOGLEPL."</label><br>";
        // Facebook
        if($FacebookOK==1){$checked=" checked ";}else{$checked="";}
        $HTMLkod .="<input type='checkbox' id='FacebookOK' name='FacebookOK' value='0' $checked>";
        $HTMLkod .="<label for='FacebookOK' class='label_1'>".U_ALAPBE_FACEBOOKOK."</label><br>";
        $HTMLkod .= "<p class='pTelefon'><label for='FacebookURL' class='label_1'>".U_ALAPBE_FACEBOOKURL.":</label><br>\n ";
        $HTMLkod .= "<input type='text' name='FacebookURL' id='FacebookURL' placeholder='".U_ALAPBE_FACEBOOKURL."' value='$FacebookURL' size='40'></p>\n";
        $HTMLkod .= "</fieldset>";  

        $HTMLkod .= "<fieldset> <legend>".U_ALAPBE_EXTRAK.":</legend>";
        //Google Követőkód
        $HTMLkod .= "<p class='pIskola'><label for='GoogleKod' class='label_1'>".U_ALAPBE_ANALYTICS.":</label><br>\n ";
        $HTMLkod .= "<input type='text' name='GoogleKod' id='GoogleKod' placeholder='".U_ALAPBE_ANALYTICS."' value='$GoogleKod' size='40'></p>\n";
        $HTMLkod .= "<p class='pIskola'><label for='HEADextra' class='label_1'>".U_ALAPBE_HEADEXTRA.":</label><br>\n ";
        $HTMLkod_ = "<input type='text' name='HEADextra' id='HEADextra' placeholder='".U_ALAPBE_HEADEXTRA."' value='$HEADextra' size='40'></p>\n";
        $HTMLkod .= "<textarea name='HEADextra' id='HEADextra' rows='2' cols='50' >$HEADextra</textarea>";
        $HTMLkod .= "</fieldset>";
        
        //Submit
        $HTMLkod .= "<input type='submit' name='submitAlapbeallitasok' value='".U_BTN_MODOSITAS."'><br>\n";        
        $HTMLkod .= "</form>\n";            
          
        $HTMLkod .= "<br><hr><br>\n"; 
        $HeaderImgSrc= 'img/ikonok/HeaderImg/'.$HeaderImg;          
            $HTMLkod .= "<form action='?f0=alapbeallitasok' method='post' enctype='multipart/form-data' id='HeaderImgForm'>\n";
            $HTMLkod .= "<h2>".U_ALAPBE_KISKEP."</h2>\n";
            $HTMLkod .= "<fieldset> <legend>".U_ALAPBE_KISKEPVAL.":</legend>";
            $HTMLkod .= "<img src='$HeaderImgSrc' style='float:left;margin:5px;' alt='kis kép' height='60' id='HeaderImgKep'>\n";
            $HTMLkod .= "<input type='file' name='file' id='fileHeaderImgTolt' >";
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<input type='submit' name='submitHeaderImgTolt' id='submitHeaderImgTolt' value='".U_BTN_FELTOLT."'><br><br>";
            $HTMLkod .= "</form>\n";
        $HTMLkod .= "<br><hr><br>\n";     
            
        $FavIconSrc= 'img/ikonok/FavIcon/'.$FavIconImg;          
            $HTMLkod .= "<form action='?f0=alapbeallitasok' method='post' enctype='multipart/form-data' id='FavIconForm'>\n";
            $HTMLkod .= "<h2>".U_ALAPBE_IKON."</h2>\n";
            $HTMLkod .= "<fieldset> <legend>".U_ALAPBE_IKONV.":</legend>";
            $HTMLkod .= "<img src='$FavIconSrc' style='float:left;margin:5px;' alt='kis kép' height='60' id='FavIconKep'>\n";
            $HTMLkod .= "<input type='file' name='file' id='fileFavIconTolt' >";
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<input type='submit' name='submitFavIconTolt' id='submitFavIconTolt' value='".U_BTN_FELTOLT."'><br><br>";
            $HTMLkod .= "</form>\n";    

        $HTMLkod .= "</div>\n"; 
        return $HTMLkod;
    }
}


function getAlapbeallitasok() {
    global $MySqliLink;
    $SelectStr   = "SELECT * FROM AlapAdatok LIMIT 1";     
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gAb 1"); 
    $rowDB       = mysqli_num_rows($result);                    
    if($rowDB>0){
       $row      = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);
    }
    return $row;
}

function getStyleClass() {
    trigger_error('Not Implemented!', E_USER_WARNING);
}

function setAlapKepFeltolt($AktKonytart) {
    $UploadErr   = '';
    $allowedExts = array("gif", "jpeg", "jpg", "png", "JPG", "JPEG");
    $temp        = explode(".", $_FILES["file"]["name"]); 
    $extension   = end($temp);
    if ((($_FILES["file"]["type"] == "image/gif")
      || ($_FILES["file"]["type"]   == "image/jpeg")
      || ($_FILES["file"]["type"]   == "image/jpg")
      || ($_FILES["file"]["type"]   == "image/pjpeg")
      || ($_FILES["file"]["type"]   == "image/x-png")
      || ($_FILES["file"]["type"]   == "image/png"))
      && ($_FILES["file"]["size"]   < 2000000)
      && in_array($extension, $allowedExts))
    {
      if ($_FILES["file"]["error"] > 0) {
        $UploadErr = "Err001: " . $_FILES["file"]["error"] . "<br>"; 
      } else {          
        $UploadErr =AlapKonyvtarUrit($AktKonytart); 
        if ($UploadErr==''){
            $CelFilename =   $AktKonytart.$_FILES["file"]["name"];
            if (file_exists($CelFilename)) {
              //Meglévő kép felülírása
              move_uploaded_file($_FILES["file"]["tmp_name"],$CelFilename);
              $UploadErr =  $_FILES["file"]["name"]; 
            } else {
              //Új kép feltöltése
              move_uploaded_file($_FILES["file"]["tmp_name"],$CelFilename);
              $UploadErr =  $_FILES["file"]["name"]; 
            }
        }
      }
    } else {
      if ($_FILES["file"]["name"] >'') {$UploadErr = "Err02";}
    }
    return $UploadErr;
}


  function AlapKonyvtarUrit($KTarNev) {
        $ErrorStr         = '';
        $AktAlKonytart    = $KTarNev;     
        if (is_dir($AktAlKonytart)) {  
            $files = array_diff(scandir($AktAlKonytart), array('.','..')); 
            foreach ($files as $file) {
              unlink("$AktAlKonytart/$file");
            }
        }  else {
            $ErrorStr     .= 'Err100'; //A könyvtár nem létezik            
        }
        return $ErrorStr; 
    }

?>