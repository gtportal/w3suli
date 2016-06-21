<?php

    function setOldalKepek() {          
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
        //Csak rendszergazdáknak és moderátoroknak!
        $ErrorStr = '';
        $Oid      = $Aktoldal['id'];
        if (($_SESSION['AktFelhasznalo'.'FSzint']>4) && (isset($_POST['submitOldalKepForm'])))   { 
          for($i=9; $i>=0; $i--) {              
            if  ((isset($_POST["KFile_$i"]))&&($_POST["KFile_$i"]!='')) { 
                $KFile      = $_POST["KFile_$i"];               
                $KNev       = '';
                $KLeiras    = '';
                $KSzelesseg = 0;
                $KMagassag  = 0;
                $KStilus    = 0;
                $KSorszam   = 0;   
                if (isset($_POST["KNev_$i"]))       {$KNev       = test_post($_POST["KNev_$i"]);}  
                if (isset($_POST["KLeiras_$i"]))    {$KLeiras    = test_post($_POST["KLeiras_$i"]);}
                if (isset($_POST["KSzelesseg_$i"])) {$KSzelesseg = test_post($_POST["KSzelesseg_$i"]);}  
                if (isset($_POST["KMagassag_$i"]))  {$KMagassag  = test_post($_POST["KMagassag_$i"]);} 
                if (isset($_POST["KStilus_$i"]))    {$KStilus    = test_post($_POST["KStilus_$i"]);}  
                if (isset($_POST["KSorszam_$i"]))   {$KSorszam   = test_post($_POST["KSorszam_$i"]);} 
                $UpdateStr = "UPDATE OldalKepek SET
                   KNev='$KNev', 
                   KLeiras='$KLeiras', 
                   KSzelesseg='$KSzelesseg', 
                   KMagassag='$KMagassag', 
                   KStilus='$KStilus', 
                   KSorszam='$KSorszam'             
                   WHERE KFile='$KFile' AND Oid=$Oid";
               if (!mysqli_query($MySqliLink,$UpdateStr)) {die("Hiba OK 01 ");}
            }
          }            
        } 
        return $ErrorStr;         
    }


function setOldalKepFeltolt() {
    global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
    //Csak rendszergazdáknak és moderátoroknak!
    $ErrorStr = '';
    if (($_SESSION['AktFelhasznalo'.'FSzint']>4) && (isset($_POST['submit_KepekFeltoltForm'])))   {          
      $Oid          = $Aktoldal['id'];
      $UploadErr    = '';
      if ($Aktoldal['OImgDir']!='') {
        $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/";            
      } else {
        $KepUtvonal = "img/oldalak/";    
      }
      
      //=============== Lehetséges Fájlnevek ==================
      $lehetKFile     = array();
      for($i=9; $i>=0; $i--) {
        $lehetKFile[] = $Aktoldal['OUrl'].'_'.$i; 
      }
      //print_r($lehetKFile);
      //=============== Létező Fájlnevek ==================
      $vanKFile     = array();
      $SelectStr    = "SELECT KFile FROM OldalKepek WHERE Oid=$Oid" ; 
      $result       = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sKF 01");  
      $rowDB        = mysqli_num_rows($result); 
      if ($rowDB > 0) {
        while($row  = mysqli_fetch_array($result))
        {
          $temp       = explode(".", $row['KFile']);
          $vanKFile[] = $temp[0];
        }
        mysqli_free_result($result);
      }     
      //=============== Használható Fájlnevek ==================
      $UploadErr   = '';
      $OkKFile     = array();
      $OkKFile0    = array_diff($lehetKFile,$vanKFile);
      foreach ($OkKFile0 as $key => $value) {$OkKFile[] = $value;}
      $OkKFileCt   = count($OkKFile);
      $KFileDb     = count($_FILES['OKepFile']['name']);
      if (($KFileDb==1) && ($_FILES["OKepFile"]["name"][0]=='') || ($KFileDb==0)) {
          $KFileDb=0; $UploadErr = 'ErrK00';          
      } else {
        if (isset($_FILES["OKepFile"])) {
          $i=0;  
          while (($i<$KFileDb) && ($OkKFileCt>0)) {        
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $temp        = explode(".", $_FILES["OKepFile"]["name"][$i]);          
            $extension   = end($temp);  
            $FNev        = $_FILES["OKepFile"]["name"][$i];
            $AktFileNev  = $OkKFile[$OkKFileCt-1].'.'.$extension;              
            if (( ($_FILES["OKepFile"]["type"][$i] == "image/gif")
               || ($_FILES["OKepFile"]["type"][$i] == "image/jpeg")
               || ($_FILES["OKepFile"]["type"][$i] == "image/jpg")
               || ($_FILES["OKepFile"]["type"][$i] == "image/pjpeg")
               || ($_FILES["OKepFile"]["type"][$i] == "image/x-png")
               || ($_FILES["OKepFile"]["type"][$i] == "image/png"))
               && ($_FILES["OKepFile"]["size"][$i] < 2000000)
               && in_array($extension, $allowedExts))
            {
              if ($_FILES["OKepFile"]["error"][$i] > 0) {
                $UploadErr .= "ErrK02".$_FILES["OKepFile"]["name"][$i]."<br>"; 
              } else {
                  //echo $KepUtvonal.$AktFileNev."RRRR";
                if (file_exists($KepUtvonal.$AktFileNev)) { 
                  //Meglévő kép felülírása
                  if (!@unlink($KepUtvonal.$AktFileNev)) {
                      $UploadErr = 'ErrK02'.$FNev."<br>"; // Nem sikerült a törlés
                  } else {    
                      if (move_uploaded_file($_FILES["OKepFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev)) {
                          $UploadErr    .=   "OK001".$FNev." <br>"; $KepOK=true;
                          $InsertIntoStr = "INSERT INTO OldalKepek VALUES ('', $Oid,'$AktFileNev','','',0,0,0,0)";
                          if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba sKF 02");}
                      } else {
                          $UploadErr .= "ErrK05".$FNev."<br>";                     
                      }
                  }
                } else {
                  //Új kép feltöltése
                  if (move_uploaded_file($_FILES["OKepFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev)){ 
                  $UploadErr    .=  "OK001".$FNev." <br>"; $KepOK=true;        
                  $InsertIntoStr = "INSERT INTO OldalKepek VALUES ('', $Oid,'$AktFileNev','','',0,0,0,0)";
                  if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba sKF 03");}
                  } else {
                      $UploadErr .= "ErrK05".$FNev."<br>";                     
                  }
                }
              }
            } else {
              if ($AktFileNev >'') {$UploadErr .= "ErrK01".$FNev."<br>"; }
            }            
            $i++; $OkKFileCt--;
          }
          if ($i<$KFileDb) { $UploadErr .= "<h1>".U_CSAK_10_KEP."!</h1>";}
        }   
      }      
      return $UploadErr;       
    }
}


    function setOldalKepTorol() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
        //Csak rendszergazdáknak és moderátoroknak!
        $ErrorStr = '';
        $Oid      = $Aktoldal['id'];
        if (($_SESSION['AktFelhasznalo'.'FSzint']>4) && (isset($_POST['submitOldalKepForm'])))   { 
          for($i=9; $i>=0; $i--) {
            if  (isset($_POST["KTorol_$i"])) {
                $KFile = $_POST["KTorol_$i"];
                $DeletetStr = "Delete FROM OldalKepek  WHERE KFile='$KFile' AND Oid=$Oid"; 
                if (!mysqli_query($MySqliLink,$DeletetStr)) {die("Hiba Okt 01");}
                
                //Kép törlése
                if ($Aktoldal['OImgDir']!='') {
                    $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/";            
                } else {
                    $KepUtvonal = "img/oldalak/";    
                }
                
                $AktFile = $KepUtvonal.$KFile;                  
                if (!@unlink($AktFile)) {
                    $ErrorStr = 'Err100'; // Nem sikerült a törlés
                }
               
            }
          }            
        } 
        return $ErrorStr;
    }


    function getOldalKepekHTML() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }


function getOldalKepForm() {
    global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
    $HTMLkod          = '';
    $ErrorStr         = ''; 
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4) {
        

            $Oid          = $Aktoldal['id'];
            if ($Aktoldal['OImgDir']!='') {
              $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/";            
            } else {
              $KepUtvonal = "img/oldalak/";    
            }
            $KepCT        = 0;
            $OUrl         = $Aktoldal['OUrl'];        
            $OldalKepek   = array();
            $OldalKepInit = array();
            $InfoClass    = '';

            $OldalKepInit['Oid']        = $Oid;
            $OldalKepInit['KFile']      = '';
            $OldalKepInit['KNev']       = '';
            $OldalKepInit['KLeiras']    = '';
            $OldalKepInit['KSzelesseg'] = 0;
            $OldalKepInit['KMagassag']  = 0;
            $OldalKepInit['KStilus']    = 0;
            $OldalKepInit['KSorszam']   = 0; 

            //Az aktuális oldalhoz kapcsolódó képek beolvasása adatbázisból
            $SelectStr     = "SELECT * FROM OldalKepek WHERE Oid=$Oid order by KSorszam "; 
            $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OKepF 01 ");
            $rowDB         = mysqli_num_rows($result); 
            if ($rowDB > 0) {
              while($row   = mysqli_fetch_array($result)) {
                $OldalKep               = array();
                $OldalKep['id']         = $row['id'];
                $OldalKep['Oid']        = $row['Oid'];
                $OldalKep['KFile']      = $row['KFile'];
                $OldalKep['KNev']       = $row['KNev'];
                $OldalKep['KLeiras']    = $row['KLeiras'];
                $OldalKep['KSzelesseg'] = $row['KSzelesseg'];
                $OldalKep['KMagassag']  = $row['KMagassag'];
                $OldalKep['KStilus']    = $row['KStilus'];
                $OldalKep['KSorszam']   = $row['KSorszam'];
                $OldalKepek[]           = $OldalKep;
                $KepCT++;
              }
              mysqli_free_result($result);
            }
            //Ha az adatbázisban tárol képek száma kisebb mint 10, akkor a hiányzó képek adatait inicializáljuk                       
         //   for($i=$KepCT;$i<10;$i++) { $OldalKepek[] = $OldalKepInit; }          

            //Hibakezelés  
            $ErrClassKep        = '';
            if (isset($_POST['submit_KepekFeltoltForm'])) {
                $ErrorStr       = $_SESSION['ErrorStr'];
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
                if (strpos($ErrorStr,'OK001')!==false) {
                   $ErrArr       = array('OK001' => U_FETOLT_OK);  
                   $ErrorStr     = strtr($ErrorStr ,$ErrArr); 
                }  
                if ($ErrClassKep == '' ){
                   $ErrorStr      = "<p class='time'>".U_MODOSITVA.": ".date("H.i.s.")."<p>".$ErrorStr; 
                   $InfoClass     = ' OKInfo ';
                } else {
                   $ErrorStr      = "<p class='time'>".U_ELKULDVE.": ".date("H.i.s.")."<p>".$ErrorStr;
                   $InfoClass     = ' ErrorInfo ';
                }
            } 
            if (isset($_POST['submitOldalKepForm'])) {
              $ErrorStr           = "<p class='time'>".U_MODOSITVA.": ".date("H.i.s.")."<p>";  
              $InfoClass          = ' OKInfo ';  
            }


            // ============== A KepekFeltoltForm ÖSSZEÁLLÍTÁSA =====================
            $HTMLkod0    ='';
            $HTMLkod0   .="  <div id='KepekFeltoltForm'>

                                   <form action='?f0=$OUrl'  method='post' enctype='multipart/form-data'>
                                     <h2>".U_KEPEK_FELTOLTESE."</h2>
                                     <fieldset> <legend>".U_KEPEK_VALASZT.":</legend>
                                     <input type='file' name='OKepFile[]' id='file_KepekFeltoltForm' multiple='multiple'>
                                     </fieldset>
                                     <input type='submit' name='submit_KepekFeltoltForm' id='submit_KepekFeltoltForm' value='".U_BTN_FELTOLT."'>                                      
                                   </form><br>
                                 </div>";


            // ============== Az OldalKepForm ÖSSZEÁLLÍTÁSA =====================
            $HTMLkod1    ='';
            $ErrClassONev='';
            $KepCT;
            for($i=0;$i<$KepCT;$i++) {
              $HTMLkod1 .= "<div class='Kepszerk'>"; 
              $j = $i+1;
              $HTMLkod1 .= "<fieldset> <legend>$j. ".U_KEPEK_ADAT.":</legend>";;
              $HTMLkod1 .= "<h3>$j. ".U_KEPEK_KEP."</h3>";
              $Src       = $KepUtvonal.$OldalKepek[$i]['KFile'];
              $HTMLkod1 .= "<img src='$Src' alt='$i. kép' >"; 
              $HTMLkod1 .= "<input type='hidden' name='KFile_$i' value='".$OldalKepek[$i]['KFile']."'>";

              $HTMLkod1 .= "<div style='float:left;'>";
              $HTMLkod1 .= "<p class='pKSorszam'><label for='KSorszam_$i' class='label_1'>".U_SORSZAM.":</label>\n ";
              $HTMLkod1 .= "<input type='number' name='KSorszam_$i' id='KSorszam_$i' min='0' max='20' step='1'
                             class='$ErrClassONev' value='".$OldalKepek[$i]['KSorszam']."' ></p>\n"; 

              $HTMLkod1 .= "<p class='pKNev'><label for='KNev_$i' class='label_1'>".U_CIM.":</label>\n ";
              $HTMLkod1 .= "<input type='text' name='KNev_$i' id='KNev_$i' placeholder='".U_CIM."' 
                             class='$ErrClassONev' value='".$OldalKepek[$i]['KNev']."' size='20'></p>\n"; 

              $HTMLkod1 .= "<p class='pKLeiras'><label for='KLeiras_$i' class='label_1'>".U_LEIRAS.":</label>\n ";
              $HTMLkod1 .= "<input type='text' name='KLeiras_$i' id='KLeiras_$i' placeholder='".U_LEIRAS."' 
                             class='$ErrClassONev' value='".$OldalKepek[$i]['KLeiras']."' size='60'></p>\n"; 

              $HTMLkod1 .= "<p class='pKSzelesseg'><label for='KSzelesseg_$i' class='label_1'>".U_SZELESSEG.":</label>\n ";
              $HTMLkod1 .= "<input type='number' name='KSzelesseg_$i' id='KSzelesseg_$i' min='0' max='20' step='1'
                             class='$ErrClassONev' value='".$OldalKepek[$i]['KSzelesseg']."' ></p>\n"; 

              $HTMLkod1 .= "<p class='pKMagassag'><label for='KMagassag_$i' class='label_1'>".U_MAGASSAG.":</label>\n ";
              $HTMLkod1 .= "<input type='number' name='KMagassag_$i' id='KMagassag_$i' min='0' max='20' step='1'
                             class='$ErrClassONev' value='".$OldalKepek[$i]['KMagassag']."' ></p>\n"; 

              $HTMLkod1 .= "<p class='pKStilus'><label for='KStilus_$i' class='label_1'>".U_STILUS.":</label>\n ";
              $HTMLkod1 .= "<input type='number' name='KStilus_$i' id='KStilus_$i' min='0' max='20' step='1'
                             class='$ErrClassONev' value='".$OldalKepek[$i]['KStilus']."' ></p>\n";           

              $HTMLkod1 .= "<p class='pKTorol'><label for='KTorol_$i' class='label_1'>".U_TORTLES.":</label>\n ";
              $HTMLkod1 .= "<input type='checkbox' name='KTorol_$i' id='KTorol_$i' 
                             class='$ErrClassONev' value='".$OldalKepek[$i]['KFile']."' ></p>\n";           

              $HTMLkod1 .= "</div>";
              $HTMLkod1 .= "</fieldset>";;
              $HTMLkod1 .= "</div>";  
            }         

            // ============== A HTML KÓD ÖSSZEÁLLÍTÁSA =====================        
            $HTMLkod .= "<div id='divOldalKepForm' >\n";
            $HTMLkod .= "<div class='$InfoClass'>$ErrorStr </div>";
            $HTMLkod .= $HTMLkod0;
            $HTMLkod .= "<br><div><form action='?f0=$OUrl' method='post' id='formOldalKepForm'>\n";
            $HTMLkod .= "<h2>".U_KEPEK_MODOSIT."</h2>\n";

            $HTMLkod .= $HTMLkod1;

            $HTMLkod .=  "<input type='submit' name='submitOldalKepForm' value='".U_BTN_MODOSITAS."'><br><br>\n";        
            $HTMLkod .= "</form></div>\n";
            $HTMLkod .= "</div>\n";
           
    }
    return $HTMLkod;
}


    
    
//=========================================================================================================================
//
// KÉPKÖNYVTÁR KEZELÉS
// 
//=========================================================================================================================
    function KepkonyvtarLetrehoz($KTarNev) {
        $ErrorStr       = '';
        $AktAlKonytart  = 'img/oldalak/'.$KTarNev;       
        if (!is_dir($AktAlKonytart)) {     
            if (!mkdir($AktAlKonytart, 0777)) {
              $ErrorStr = 'Err100'; // Nem sikerült létrehozni
            }
        }  else {
            $ErrorStr   = 'Err101'; // Van már adott néven könyvtár
            
        }
        return $ErrorStr;        
    }
    
    function KepkonyvtarAtnevez($RKTarNev,$UKTarNev) {
        $ErrorStr     = '';
        $RegiKonytart = 'img/oldalak/'.$RKTarNev; 
        $UjKonytart   = 'img/oldalak/'.$UKTarNev; 
      
        if (is_dir($RegiKonytart)) {  
            if (!is_dir($UjKonytart)) { 
                if (!rename($RegiKonytart, $UjKonytart)) {
                    $ErrorStr .= 'Err100'; // Nem sikerült átnevezni
                }
            } else {$ErrorStr .= 'Err101';} // Van már az új néven könyvtár
        }  else { $ErrorStr   .= 'Err101'; }  // A forráskönyvtár nem létezik
        return $ErrorStr;    
    }
    
  function KepkonyvtarTorol($KTarNev) {
        $ErrorStr         = '';
        $AktAlKonytart    = 'img/oldalak/'.$KTarNev;     
        $DocKonyvtar      = 'img/oldalak/'.$KTarNev."/".doc;  
        // Dokumentumkönyvtár törlése
        if (is_dir($DocKonyvtar)) {  
            $files = array_diff(scandir($DocKonyvtar), array('.','..')); 
            foreach ($files as $file) {
              unlink("$DocKonyvtar/$file");
            }
            if (!rmdir($DocKonyvtar)) {
                $ErrorStr .= 'Err100'; // Nem sikerült a törlés
            }
        }   
        
        // Képkönyvtár törlése
        if (is_dir($AktAlKonytart)) {  
            $files = array_diff(scandir($AktAlKonytart), array('.','..')); 
            foreach ($files as $file) {
              unlink("$AktAlKonytart/$file");
            }
            if (!rmdir($AktAlKonytart)) {
                $ErrorStr .= 'Err100'; // Nem sikerült a törlés
            }
        }  else {
            $ErrorStr     .= 'Err101'; //A könyvtár nem létezik
            
        }
        return $ErrorStr; 
    }
?>