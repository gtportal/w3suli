<?php

/* 
    Author     : Szép Balázs
*/

/* Példatömb
$CikkKepek = array();
$CikkKepek['id']         = 0;
$CikkKepek['Cid']        = 0;
$CikkKepek['KFile']      = '';
$CikkKepek['KNev']       = '';
$CikkKepek['KLeiras']    = '';
$CikkKepek['KSzelesseg'] = 0;
$CikkKepek['KMagassag']  = 0;
$CikkKepek['KStilus']    = 0;
$CikkKepek['KSorszam']   = 0;
*/

function setCikkKepFeltolt() {
    global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
    $ErrorStr = '';
    $Cid      = $_SESSION['SzerkCikk'.'id'];
    //Csak rendszergazdáknak, moderátoroknak és regisztrált felhasználóknak!
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) && (isset($_POST['submit_CikkKepekFeltoltForm'])) && ($Cid>0))   {          
      $Oid          = $Aktoldal['id'];
      $UploadErr    = '';
      if ($Aktoldal['OImgDir']!='') {
        $KepUtvonal = "img/".$Aktoldal['OImgDir']."/";
      } else {
        $KepUtvonal = "img/";
      }
      //=============== Lehetséges Fájlnevek ==================
      // Fájlnév felépítése cikk_$Cid_$KSorszam.kiterjesztés
      $lehetKFile     = array();
      for($i=9; $i>=0; $i--) {
        $lehetKFile[] = 'cikk_'.$Cid.'_'.$i; 
      }
      //print_r($lehetKFile);
      //=============== Létező Fájlnevek ==================
      $vanKFile     = array();
      $SelectStr    = "SELECT KFile FROM CikkKepek WHERE Cid=$Cid" ;
      $result       = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba CKF 01");
      $rowDB        = mysqli_num_rows($result);
      if ($rowDB > 0) {
        while($row = mysqli_fetch_array($result))
        {
          $temp       = explode(".", $row['KFile']);
          $vanKFile[] = $temp[0];
        }
      }
      //=============== Használható Fájlnevek ==================
      $OkKFile     = array();
      $OkKFile0    = array_diff($lehetKFile,$vanKFile);
      foreach ($OkKFile0 as $key => $value) {$OkKFile[] = $value;}
      $OkKFileCt   = count($OkKFile);
      $KFileDb     = count($_FILES['COKepFile']['name']);
      if (($KFileDb==1) && ($_FILES["COKepFile"]["name"][0]=='')) {$KFileDb=0; $UploadErr = "<p class='Error'>Nincs fájl kijelölve. </p><br>\n";}
     
      if (isset($_FILES["COKepFile"])) {
        $i=0;
        while (($i<$KFileDb) && ($OkKFileCt>0)) {
          if ($i==0) {$UploadErr = '';}
          $allowedExts = array("gif", "jpeg", "jpg", "png");
          $temp        = explode(".", $_FILES["COKepFile"]["name"][$i]);
          $extension   = end($temp);
            
         // print_r($OkKFile);
          $AktFileNev = $OkKFile[$OkKFileCt-1].'.'.$extension;
          
          if (( ($_FILES["COKepFile"]["type"][$i] == "image/gif")
             || ($_FILES["COKepFile"]["type"][$i] == "image/jpeg")
             || ($_FILES["COKepFile"]["type"][$i] == "image/jpg")
             || ($_FILES["COKepFile"]["type"][$i] == "image/pjpeg")
             || ($_FILES["COKepFile"]["type"][$i] == "image/x-png")
             || ($_FILES["COKepFile"]["type"][$i] == "image/png"))
             && ($_FILES["COKepFile"]["size"][$i] < 2000000)
             && in_array($extension, $allowedExts))
          {
            if ($_FILES["COKepFile"]["error"][$i] > 0) {
              $UploadErr = "<p class='Error'> Hibakód: " . $_FILES["COKepFile"]["error"][$i] . "</p><br>\n"; 
            } else {
              if (file_exists($KepUtvonal.$AktFileNev)) {
                //Meglévő kép felülírása
                move_uploaded_file($_FILES["COKepFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev);
                $UploadErr .=  "<p class='Uzenet'> Felülírva: " .$KepUtvonal.$AktFileNev."</p><br>\n"; $KepOK=true;
                $InsertIntoStr = "INSERT INTO CikkKepek VALUES ('', $Cid,'$AktFileNev','','',0,0,0,0)";
                if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba CKF 02");}
              } else {
                //Új kép feltöltése
                move_uploaded_file($_FILES["COKepFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev);
                $UploadErr .=  "<p class='Uzenet'> Feltöltve: ". $_FILES["COKepFile"]["name"][$i]."</p>\n"; $KepOK=true;        
                $InsertIntoStr = "INSERT INTO CikkKepek VALUES ('', $Cid,'$AktFileNev','','',0,0,0,0)";
                if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba CKF 03");}
              }
            }
          } else {
            if ($AktFileNev >'') {$UploadErr .= "<p class='Error'> Err101 Érvénytelen file.".$_FILES["COKepFile"]["name"][$i]."</p>\n";}
          }
          $i++; $OkKFileCt--;
        }
        if ($i<$KFileDb) { $UploadErr .= "<p class='Error'> Csak 10 kép tölthető fel!</p>\n";}
      }
      return $UploadErr;
    }
}


function getCikkKepFeltoltForm() {
    global $Aktoldal;
    $Oid        = $Aktoldal['id']; 
    $OUrl       = $Aktoldal['OUrl'];
    $HTMLkod    ='';    
    $HTMLkod   ="  <div id='CikkKepekFeltoltForm'>
                           <h3>Képek feltöltése a cikkhez:</h3>
                           <form action='?f0=$OUrl'  method='post' enctype='multipart/form-data'>
                             <input type='file' name='COKepFile[]' id='file_CikkKepekFeltoltForm' multiple='multiple'>
                             <input type='submit' name='submit_CikkKepekFeltoltForm' id='submit_CikkKepekFeltoltForm' value='Feltöltés'>                                      
                           </form>
                         </div>";
    return $HTMLkod;
}

function setCikkKepek() {
    global $Aktoldal, $MySqliLink;
    $ErrorStr   = '';
    $Oid = 0;
    $Cid        = $_SESSION['SzerkCikk'.'id'];
    $SelectStr = "SELECT Oid From OldalCikkei WHERE Cid='$Cid'";
    $result = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCK 02");
    $row = mysqli_fetch_array($result);    mysql_free_result($result);
    $Oid = $row['Oid'];
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) && (isset($_POST['submitCikkKepForm'])))   {
        if (isset($_POST["rowDB"])) {$rowDB=test_post($_POST["rowDB"]);}
        
        //=============================HIBAKEZELÉS==============================
        for($i=0; $i<$rowDB; $i++) {
            if (isset($_POST["CKNev_$i"]))       {$KNev=test_post($_POST["CKNev_$i"]);
                if(strlen($KNev)>40) { $ErrorStr .= "<p class='Error'> Err_$i - A kép neve maximum 40 karakter lehet. </p><br>\n";}
            }
        }
        //====================POST beillesztése adatbázisba=====================
        if ($Oid == $Aktoldal['id'] && $ErrorStr=='') {
            for($i=0; $i<$rowDB; $i++) {
                if  ((isset($_POST["CKFile_$i"]))&&($_POST["CKFile_$i"]!='')) {
                    $KFile      = FileNevTisztit($_POST["CKFile_$i"]);
                    $KNev       = '';
                    $KLeiras    = '';
                    $KSzelesseg = 0;
                    $KMagassag  = 0;
                    $KStilus    = 0;
                    $KSorszam   = 0;
                    if (isset($_POST["CKNev_$i"]))       {$KNev      =test_post($_POST["CKNev_$i"]);}
                    if (isset($_POST["CKLeiras_$i"]))    {$KLeiras   =test_post($_POST["CKLeiras_$i"]);}
                    if (isset($_POST["CKSzelesseg_$i"])) {$KSzelesseg=test_post($_POST["CKSzelesseg_$i"]);}
                    if (isset($_POST["CKMagassag_$i"]))  {$KMagassag =test_post($_POST["CKMagassag_$i"]);}
                    if (isset($_POST["CKStilus_$i"]))    {$KStilus   =test_post($_POST["CKStilus_$i"]);}
                    if (isset($_POST["CKSorszam_$i"]))   {$KSorszam  =test_post($_POST["CKSorszam_$i"]);}
                }
                $UpdateStr = "UPDATE CikkKepek SET 
                            KNev='$KNev', 
                            KLeiras='$KLeiras', 
                            KSzelesseg='$KSzelesseg', 
                            KMagassag='$KMagassag', 
                            KStilus='$KStilus', 
                            KSorszam='$KSorszam' 
                            WHERE KFile='$KFile' 
                            AND Cid=$Cid";
                mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uCK 01");
                setCikkKepTorol($i);
            }
        }
    }
    return $ErrorStr;
}

function getCikkKepForm() {
    global $Aktoldal, $MySqliLink;
    $Oid        = $Aktoldal['id'];
    $Cid        = $_SESSION['SzerkCikk'.'id']; 
    $OUrl = $Aktoldal['OUrl'];
    $HTMLkod    ='';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1){      
        $HTMLkod .= "<div id='divCikkKepForm' >\n";
        $HTMLkod   .=getCikkKepFeltoltForm();   

        // a $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk képeinek kezelése
        // Minta OldalKeptar.php getOldalKepForm()- fgv-e. 
        // Változás: a képek feltöltéséhez szüksége form saját fgv-t kap. >> getCikkKepFeltoltForm()

        if ($Aktoldal['OImgDir']!='') {
          $KepUtvonal = "img/".$Aktoldal['OImgDir']."/";            
        } else {
          $KepUtvonal = "img/";    
        }
        //Az aktuális cikkhez kapcsolódó képek beolvasása adatbázisból 
          $SelectStr   = "SELECT * FROM CikkKepek WHERE Cid=$Cid order by KSorszam ";
          $SelectStr   = "SELECT *
                          FROM CikkKepek AS CK
                          LEFT JOIN OldalCikkei AS OC
                          ON OC.Cid= CK.Cid 
                          WHERE OC.Oid=$Oid
                          AND CK.Cid=$Cid";
          $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCK 01 ");
          $rowDB       = mysqli_num_rows($result);
        if (strpos($_SESSION['ErrorStr'],'Err_')==false)  {
            while($row   = mysqli_fetch_array($result)) {
                $CikkKep               = array();
                $CikkKep['id']         = $row['id'];
                $CikkKep['KFile']      = $row['KFile'];
                $CikkKep['KNev']       = $row['KNev'];
                $CikkKep['KLeiras']    = $row['KLeiras'];
                $CikkKep['KSzelesseg'] = $row['KSzelesseg'];
                $CikkKep['KMagassag']  = $row['KMagassag'];
                $CikkKep['KStilus']    = $row['KStilus'];
                $CikkKep['KSorszam']   = $row['KSorszam'];
                $CikkKep['KTorol']     = $row['KFile'];
                $CikkKepek[]           = $CikkKep;
            }
        } else {
            for($i = 0; $i < $_POST['rowDB']; $i++) {
                $CikkKep               = array();
                $CikkKep['KFile']      = $_POST["CKFile_$i"];
                $CikkKep['KNev']       = $_POST["CKNev_$i"];
                $CikkKep['KLeiras']    = $_POST["CKLeiras_$i"];
                $CikkKep['KSzelesseg'] = $_POST["CKSzelesseg_$i"];
                $CikkKep['KMagassag']  = $_POST["CKMagassag_$i"];
                $CikkKep['KStilus']    = $_POST["CKStilus_$i"];
                $CikkKep['KSorszam']   = $_POST["CKSorszam_$i"];
                $CikkKep['KTorol']     = $_POST["CKTorol_$i"];
                $CikkKepek[]           = $CikkKep;
            }
        }
        if ($rowDB != 0){
            $HTMLkod1    ='';
            $ErrClassCNev='';
            for($i = 0; $i < $rowDB; $i++) {
                $checked = "";
                if (strpos($_SESSION['ErrorStr'],'Err_')!=false) {
                    if ($_POST["CKTorol_$i"]) {$checked = " checked ";}
                }
                
                if (strpos($_SESSION['ErrorStr'],"Err_$i")!=false) {
                    $ErrClassCNev = "Error";
                } else {$ErrClassCNev='';}
                $HTMLkod1 .= "<div class='Kepszerk'>"; 

                $HTMLkod1 .= "<h3>$i. kép</h3>";
                $Src       = $KepUtvonal.$CikkKepek[$i]['KFile'];
                $HTMLkod1 .= "<img src='$Src' alt='$i. kép' >";
                $HTMLkod1 .= "<input type='hidden' name='CKFile_$i' value='".$CikkKepek[$i]['KFile']."'>";

                $HTMLkod1 .= "<div style='float:left;'>";
                $HTMLkod1 .= "<p class='pKSorszam'><label for='CKSorszam_$i' class='label_1'>Sorszám:</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CKSorszam_$i' id='CKSorszam_$i' min='0' max='1000' step='1' value='".$CikkKepek[$i]['KSorszam']."' ></p>\n"; 

                $HTMLkod1 .= "<p class='pKNev'><label for='CKNev_$i' class='label_1'>A kép neve:</label>\n ";
                $HTMLkod1 .= "<input type='text' name='CKNev_$i' id='CKNev_$i' placeholder='Képnév' 
                               class='$ErrClassCNev' value='".$CikkKepek[$i]['KNev']."' size='1000'></p>\n"; 

                $HTMLkod1 .= "<p class='pKLeiras'><label for='CKLeiras_$i' class='label_1'>Leírás:</label>\n ";
                $HTMLkod1 .= "<input type='text' name='CKLeiras_$i' id='CKLeiras_$i' placeholder='Leírás'  value='".$CikkKepek[$i]['KLeiras']."' size='60'></p>\n"; 

                $HTMLkod1 .= "<p class='pKSzelesseg'><label for='CKSzelesseg_$i' class='label_1'>Szélesség:</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CKSzelesseg_$i' id='CKSzelesseg_$i' min='0' max='1000' step='1' value='".$CikkKepek[$i]['KSzelesseg']."' ></p>\n"; 

                $HTMLkod1 .= "<p class='pKMagassag'><label for='CKMagassag_$i' class='label_1'>Magasság:</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CKMagassag_$i' id='CKMagassag_$i' min='0' max='1000' step='1' value='".$CikkKepek[$i]['KMagassag']."' ></p>\n"; 

                $HTMLkod1 .= "<p class='pKStilus'><label for='CKStilus_$i' class='label_1'>Stílus:</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CKStilus_$i' id='CKStilus_$i' min='0' max='1000' step='1' value='".$CikkKepek[$i]['KStilus']."' ></p>\n";           

                $HTMLkod1 .= "<p class='pKTorol'><label for='CKTorol_$i' class='label_1'>TÖRLÉS:</label>\n ";
                $HTMLkod1 .= "<input type='checkbox' name='CKTorol_$i' id='CKTorol_$i'  value='".$CikkKepek[$i]['KFile']."' $checked ></p>\n";           
                $HTMLkod1 .= "</div>";
                $HTMLkod1 .= "</div>";  
            }
            // ============== A HTML KÓD ÖSSZEÁLLÍTÁSA =====================   
            $HTMLkod1 .= "<input type='hidden' name='rowDB' value='$rowDB'>";
            $HTMLkod .= $_SESSION['ErrorStr'];
            $HTMLkod .= "<div id='divCikkKepForm1'><form action='?f0=$OUrl' method='post' id='formCikkKepForm'>\n";
            $HTMLkod .= $HTMLkod1;
            

            $HTMLkod .=  "<input type='submit' name='submitCikkKepForm' value='Adatok módosítása' style='clear:left;'><br><br>\n";        
            $HTMLkod .= "</form></div>\n";
        }
        $HTMLkod .= "</div> <br style='clear:left;'>\n";
    }
    return $HTMLkod;
}


function setCikkKepTorol($i) {
    global $Aktoldal, $MySqliLink;
    $Cid        = $_SESSION['SzerkCikk'.'id'];
    if (isset($_POST["rowDB"])) {$rowDB=test_post($_POST["rowDB"]);}
    if ($Aktoldal['OImgDir']!='') {
      $KepUtvonal = "img/".$Aktoldal['OImgDir']."/";
    } else {
      $KepUtvonal = "img/";
    }
    if  (isset($_POST["CKTorol_$i"]) && $_POST["CKTorol_$i"]) {
        $KFile = $_POST["CKTorol_$i"];
        unlink($KepUtvonal.$KFile);
        $DeletetStr = "Delete FROM CikkKepek  WHERE KFile='$KFile' AND Cid=$Cid";
        mysqli_query($MySqliLink,$DeletetStr) OR die("Hiba dCK 01");
    }
}


function getCikkKepTorolForm() {
    // Integrálható a getCikkKepForm() fgv-be.
    trigger_error('Not Implemented!', E_USER_WARNING);
}

function getCikkKepekHTML($Cid) {
    global $MySqliLink, $Aktoldal;
    $HTMLkod  = '';
    
    if ($Aktoldal['OImgDir']!='') {
      $KepUtvonal = "img/".$Aktoldal['OImgDir']."/";
    } else {
      $KepUtvonal = "img/";
    }
    $SelectStr = "SELECT KNev, KFile FROM CikkKepek WHERE Cid=$Cid ORDER BY KSorszam DESC";
    $result = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01");
    $HTMLkod .= "<div class = 'divCikkKepek'>\n";
    
    while ($row = mysqli_fetch_array($result)){
        $Src = $KepUtvonal.$row['KFile'];
        $KNev = $row['KNev'];
        $HTMLkod .= "<div class = 'divCikkKep'>";
            $HTMLkod .= "<img src='$Src'  class = 'imgCikkKep' alt='$KNev'>";
        $HTMLkod .= "</div>\n";
    }
    $HTMLkod .= "</div>\n";
    return $HTMLkod;
}

?>
