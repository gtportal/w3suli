<?php 
$AlapAdatok['WebhelyNev'] = 'Webhely neve'; 
$AlapAdatok['Iskola']     = '';
$AlapAdatok['Cim']        = '';
$AlapAdatok['Telefon']    = '';
$AlapAdatok['Stilus']     =  0;


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

        if (isset($_POST['submitAlapbeallitasok'])) {  
            if (isset($_POST['WNev']))      {$WNev      = test_post($_POST['WNev']);}  
            if (isset($_POST['Iskola']))    {$Iskola    = test_post($_POST['Iskola']);} 
            if (isset($_POST['Cim']))       {$Cim       = test_post($_POST['Cim']);}  
            if (isset($_POST['Telefon']))   {$Telefon   = test_post($_POST['Telefon']);} 
            if (isset($_POST['Stilus']))    {$Stilus    = INT_post($_POST['Stilus']);}            
            if (isset($_POST['HeaderStr'])) {$HeaderStr = SQL_post($_POST['HeaderStr']);} 
            if (isset($_POST['GoogleKod'])) {$GoogleKod = SQL_post($_POST['GoogleKod']);} 
            if (isset($_POST['GooglePlus'])){$GooglePlus= 1;}      
            
            $UpdateStr =   "UPDATE AlapAdatok SET 
                            WebhelyNev='$WNev',
                            Iskola='$Iskola',
                            Cim='$Cim',
                            Telefon='$Telefon',
                            Stilus=$Stilus,
                            HeaderStr='$HeaderStr',  
                            GoogleKod='$GoogleKod',    
                            GooglePlus='$GooglePlus' 
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
    $HTMLkod  = '';
    $ErrorStr = '';         

    if ($_SESSION['AktFelhasznalo'.'FSzint']>6)  {
        $WNev        = $AlapAdatok['WebhelyNev'];
        $Iskola      = $AlapAdatok['Iskola'];
        $Cim         = $AlapAdatok['Cim'];
        $Telefon     = $AlapAdatok['Telefon'];
        $Stilus      = $AlapAdatok['Stilus'];
        $HeaderImg   = $AlapAdatok['HeaderImg'];
        $FavIconImg  = $AlapAdatok['FavIcon'];   
        
        $GoogleKod  = $AlapAdatok['GoogleKod'];
        $GooglePlus = $AlapAdatok['GooglePlus'];
        $HeaderStr  = $AlapAdatok['HeaderStr'];   

        $HTMLkod .= "<div id='divAlapbeallitasForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

        $HTMLkod .= "<form action='?f0=alapbeallitasok' method='post' id='formAlapbeallitasForm'>\n";
        $HTMLkod .= "<h2>A webhely alapadatainak beállítása:</h2>";
        $HTMLkod .= "<fieldset> <legend>A webhely adatai:</legend>";
        //Webhely neve
        $HTMLkod .= "<p class='pWNev'><label for='WNev' class='label_1'>A webhely neve:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='WNev' id='WNev' placeholder='Webhelynév' value='$WNev' size='40'></p>\n"; 
        
        //Fejléc szovege
        $HTMLkod .= "<p class='pIskola'><label for='HeaderStr' class='label_1'>Az oldalfejléc szövege:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='HeaderStr' id='HeaderStr' placeholder='Fejléc szöveg' value='$HeaderStr' size='40'></p>\n";
        
        //Google Követőkód
        $HTMLkod .= "<p class='pIskola'><label for='GoogleKod' class='label_1'>Google Követőkód:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='GoogleKod' id='GoogleKod' placeholder='Iskola neve' value='$GoogleKod' size='40'></p>\n";
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // Google+ gomb
        if($GooglePlus==1){$checked=" checked ";}else{$checked="";}
        $HTMLkod .="<input type='checkbox' id='GooglePlus' name='GooglePlus' value='0' $checked>";
        $HTMLkod .="<label for='GooglePlus' class='label_1'>Google+ gomb</label><br>";
        $HTMLkod .= "</fieldset>";             
        
        $HTMLkod .= "<fieldset> <legend>Az intézmény adatai:</legend>";
        //Iskola neve
        $HTMLkod .= "<p class='pIskola'><label for='Iskola' class='label_1'>Az iskola neve:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Iskola' id='Iskola' placeholder='Iskola neve' value='$Iskola' size='40'></p>\n"; 

        //Iskola címe
        $HTMLkod .= "<p class='pCim'><label for='Cim' class='label_1'>Az iskola címe:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Cim' id='Cim' placeholder='Iskola címe' value='$Cim' size='40'></p>\n"; 

        //Iskola telefonszám
        $HTMLkod .= "<p class='pTelefon'><label for='Telefon' class='label_1'>Az iskola telefonszáma:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Telefon' id='Telefon' placeholder='Iskola telefonszáma' value='$Telefon' size='40'></p>\n";
        $HTMLkod .= "</fieldset>";
       
        $HTMLkod .= "<fieldset> <legend>Az oldal stílusa:</legend>";
        //Stíluskiválasztó
        $HTMLkod .= "<p class='pStilus'><label for='Stilus' class='label_1'>Stilus:</label>\n ";
        $HTMLkod .= "<input type='number' name='Stilus' id='Stilus' min='0' max='13' step='1' value='$Stilus'></p>\n";  
        $HTMLkod .= "</fieldset>";

        //Submit
        $HTMLkod .= "<input type='submit' name='submitAlapbeallitasok' value='Módosítás'><br>\n";        
        $HTMLkod .= "</form>\n";            
          
        $HTMLkod .= "<br><hr><br>\n"; 
        $HeaderImgSrc= 'img/ikonok/HeaderImg/'.$HeaderImg;          
            $HTMLkod .= "<form action='?f0=alapbeallitasok' method='post' enctype='multipart/form-data' id='HeaderImgForm'>\n";
            $HTMLkod .= "<h2>Fejléc kis képének feltöltése</h2>\n";
            $HTMLkod .= "<fieldset> <legend>A kis kép kiválasztása:</legend>";
            $HTMLkod .= "<img src='$HeaderImgSrc' style='float:left;margin:5px;' alt='kis kép' height='60' id='HeaderImgKep'>\n";
            $HTMLkod .= "<input type='file' name='file' id='fileHeaderImgTolt' >";
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<input type='submit' name='submitHeaderImgTolt' id='submitHeaderImgTolt' value='Feltöltés'><br><br>";
            $HTMLkod .= "</form>\n";
        $HTMLkod .= "<br><hr><br>\n";     
            
        $FavIconSrc= 'img/ikonok/FavIcon/'.$FavIconImg;          
            $HTMLkod .= "<form action='?f0=alapbeallitasok' method='post' enctype='multipart/form-data' id='FavIconForm'>\n";
            $HTMLkod .= "<h2>Favicon feltöltése</h2>\n";
            $HTMLkod .= "<fieldset> <legend>A kis kép kiválasztása:</legend>";
            $HTMLkod .= "<img src='$FavIconSrc' style='float:left;margin:5px;' alt='kis kép' height='60' id='FavIconKep'>\n";
            $HTMLkod .= "<input type='file' name='file' id='fileFavIconTolt' >";
            $HTMLkod .= "</fieldset>";
            $HTMLkod .= "<input type='submit' name='submitFavIconTolt' id='submitFavIconTolt' value='Feltöltés'><br><br>";
            $HTMLkod .= "</form>\n";    

        $HTMLkod .= "</div>\n"; 
        return $HTMLkod;
    }
}


function getAlapbeallitasok() {
    global $MySqliLink;

    $SelectStr   = "SELECT * FROM AlapAdatok LIMIT 1";     
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gAb 1"); 
    $row         = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);

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