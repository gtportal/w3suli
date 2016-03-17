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
    $Cid      = $_SESSION['SzerkCikk'.'id']; //echo "<h1>vvvvvvvvvvvv ".$_SESSION['AktFelhasznalo'.'FSzint']."</h1>";
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
      if (($KFileDb==1) && ($_FILES["COKepFile"]["name"][0]=='')) {$KFileDb=0; $UploadErr = 'Nincs fájl kijelölve.';}
     
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
              $UploadErr = "Hibakód: " . $_FILES["COKepFile"]["error"][$i] . "<br>"; 
            } else {
              if (file_exists($KepUtvonal.$AktFileNev)) { 
                //Meglévő kép felülírása
                move_uploaded_file($_FILES["COKepFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev);
                $UploadErr .=  "Felülírva: " .$KepUtvonal.$AktFileNev."<br>"; $KepOK=true;
                $InsertIntoStr = "INSERT INTO CikkKepek VALUES ('', $Cid,'$AktFileNev','','',0,0,0,0)";
                if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba CKF 02");}
              } else {
                //Új kép feltöltése
                move_uploaded_file($_FILES["COKepFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev); 
                $UploadErr .=  "Feltöltve: ". $KepUtvonal.$AktFileNev."<br>"; $KepOK=true;        
                $InsertIntoStr = "INSERT INTO CikkKepek VALUES ('', $Cid,'$AktFileNev','','',0,0,0,0)";
                if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba CKF 03");}
              }
            }
          } else {
            if ($AktFileNev >'') {$UploadErr .= "Err101 Érvénytelen file.".$_FILES["COKepFile"]["name"][$i];}
          }            
          $i++; $OkKFileCt--;
        }
        if ($i<$KFileDb) { $UploadErr .= "<h1> Csak 10 kép tölthető fel!</h1>";}
      }         
      return $UploadErr; 
      
    }
}


function getCikkKepFeltoltForm() {
    global $Aktoldal;
    $Oid        = $Aktoldal['id'];
    $HTMLkod    ='';    
    $HTMLkod   .="  <div id='CikkKepekFeltoltForm'>
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
    echo $Oid;
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) && (isset($_POST['submitCikkKepForm'])))   {
        if (isset($_POST["rowDB"])) {$rowDB=test_post($_POST["rowDB"]);}
        for($i=0; $i<$rowDB; $i++) {
            if  ((isset($_POST["CKFile_$i"]))&&($_POST["CKFile_$i"]!='')) {
                $KFile      = FileNevTisztit($_POST["CKFile_$i"]);
                $KNev       = '';
                $KLeiras    = '';
                $KSzelesseg = 0;
                $KMagassag  = 0;
                $KStilus    = 0;
                $KSorszam   = 0;
                if (isset($_POST["CKNev_$i"]))       {$KNev=test_post($_POST["CKNev_$i"]);}
                if (isset($_POST["CKLeiras_$i"]))    {$KLeiras=test_post($_POST["CKLeiras_$i"]);}
                if (isset($_POST["CKSzelesseg_$i"])) {$KSzelesseg=test_post($_POST["CKSzelesseg_$i"]);}
                if (isset($_POST["CKMagassag_$i"]))  {$KMagassag=test_post($_POST["CKMagassag_$i"]);}
                if (isset($_POST["CKStilus_$i"]))    {$KStilus=test_post($_POST["CKStilus_$i"]);}
                if (isset($_POST["CKSorszam_$i"]))   {$KSorszam=test_post($_POST["CKSorszam_$i"]);}
                if ($Oid == $Aktoldal['id']) {
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
                }
            }
        }
    }
    return $ErrorStr;
}

function getCikkKepForm() {
    global $Aktoldal, $MySqliLink;
    $Oid        = $Aktoldal['id'];
    $Cid        = $_SESSION['SzerkCikk'.'id'];
    $HTMLkod    ='';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1){
        $HTMLkod   .=getCikkKepFeltoltForm();

        // a $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk képeinek kezelése
        // Minta OldalKeptar.php getOldalKepForm()- fgv-e. 
        // Változás: a képek feltöltéséhez szüksége form saját fgv-t kap. >> getCikkKepFeltoltForm()

        if ($Aktoldal['OImgDir']!='') {
          $KepUtvonal = "img/".$Aktoldal['OImgDir']."/";            
        } else {
          $KepUtvonal = "img/";    
        }
        if (!isset($_POST['submitCikkKepForm']) || ($_SESSION['ErrorStr']==''))  {
          //Az aktuális cikkhez kapcsolódó képek beolvasása adatbázisból 
            $SelectStr   = "SELECT * FROM CikkKepek WHERE Cid=$Cid order by KSorszam ";
            $SelectStr = "SELECT *
                            FROM CikkKepek AS CK
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= CK.Cid 
                            WHERE OC.Oid=$Oid
                            AND CK.Cid=$Cid";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCK 01 ");
            $rowDB       = mysqli_num_rows($result);

            while($row   = mysqli_fetch_array($result)) {
                $CikkKep               = array();
                $CikkKep['id']         = $row['id'];
                $CikkKep['Oid']        = $row['Oid'];
                $CikkKep['KFile']      = $row['KFile'];
                $CikkKep['KNev']       = $row['KNev'];
                $CikkKep['KLeiras']    = $row['KLeiras'];
                $CikkKep['KSzelesseg'] = $row['KSzelesseg'];
                $CikkKep['KMagassag']  = $row['KMagassag'];
                $CikkKep['KStilus']    = $row['KStilus'];
                $CikkKep['KSorszam']   = $row['KSorszam'];
                $CikkKepek[]           = $CikkKep;
            }
        }
        if ($rowDB != 0){
            $HTMLkod1    ='';
            $ErrClassCNev='';
            for($i = 0; $i < $rowDB; $i++) {
                $HTMLkod1 .= "<div class='Kepszerk'>"; 

                $HTMLkod1 .= "<h3>$i. kép</h3>";
                $Src       = $KepUtvonal.$CikkKepek[$i]['KFile'];
                $HTMLkod1 .= "<img src='$Src' alt='$i. kép' >";
                $HTMLkod1 .= "<input type='hidden' name='CKFile_$i' value='".$CikkKepek[$i]['KFile']."'>";

                $HTMLkod1 .= "<div style='float:left;'>";
                $HTMLkod1 .= "<p class='pKSorszam'><label for='CKSorszam_$i' class='label_1'>Sorszám:</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CKSorszam_$i' id='CKSorszam_$i' min='0' max='20' step='1'
                               class='$ErrClassCNev' value='".$CikkKepek[$i]['KSorszam']."' ></p>\n"; 

                $HTMLkod1 .= "<p class='pKNev'><label for='CKNev_$i' class='label_1'>A kép neve:</label>\n ";
                $HTMLkod1 .= "<input type='text' name='CKNev_$i' id='CKNev_$i' placeholder='Képnév' 
                               class='$ErrClassCNev' value='".$CikkKepek[$i]['KNev']."' size='20'></p>\n"; 

                $HTMLkod1 .= "<p class='pKLeiras'><label for='CKLeiras_$i' class='label_1'>Leírás:</label>\n ";
                $HTMLkod1 .= "<input type='text' name='CKLeiras_$i' id='CKLeiras_$i' placeholder='Leírás' 
                               class='$ErrClassCNev' value='".$CikkKepek[$i]['KLeiras']."' size='60'></p>\n"; 

                $HTMLkod1 .= "<p class='pKSzelesseg'><label for='CKSzelesseg_$i' class='label_1'>Szélesség:</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CKSzelesseg_$i' id='CKSzelesseg_$i' min='0' max='20' step='1'
                               class='$ErrClassCNev' value='".$CikkKepek[$i]['KSzelesseg']."' ></p>\n"; 

                $HTMLkod1 .= "<p class='pKMagassag'><label for='CKMagassag_$i' class='label_1'>Magasság:</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CKMagassag_$i' id='CKMagassag_$i' min='0' max='20' step='1'
                               class='$ErrClassCNev' value='".$CikkKepek[$i]['KMagassag']."' ></p>\n"; 

                $HTMLkod1 .= "<p class='pKStilus'><label for='CKStilus_$i' class='label_1'>Stílus:</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CKStilus_$i' id='CKStilus_$i' min='0' max='20' step='1'
                               class='$ErrClassCNev' value='".$CikkKepek[$i]['KStilus']."' ></p>\n";           

                $HTMLkod1 .= "<p class='pKTorol'><label for='CKTorol_$i' class='label_1'>TÖRLÉS:</label>\n ";
                $HTMLkod1 .= "<input type='checkbox' name='CKTorol_$i' id='CKTorol_$i' 
                               class='$ErrClassCNev' value='".$CikkKepek[$i]['KFile']."' ></p>\n";           

                $HTMLkod1 .= "</div>";
                $HTMLkod1 .= "</div>";  
            }
            // ============== A HTML KÓD ÖSSZEÁLLÍTÁSA =====================   
            $HTMLkod1 .= "<input type='hidden' name='rowDB' value='$rowDB'>";
            $HTMLkod .= "<div id='divCikkKepForm' >\n";
            $HTMLkod .= $_SESSION['ErrorStr'];
            $HTMLkod .= "<div><form action='?f0=$OUrl' method='post' id='formCikkKepForm'>\n";
            $HTMLkod .= $HTMLkod1;
            

            $HTMLkod .=  "<br><br><br><br><br><br><input type='submit' name='submitCikkKepForm' value='Elküld'><br><br>\n";        
            $HTMLkod .= "</form></div>\n";
            $HTMLkod .= "</div>\n";
        }
    }
    return $HTMLkod;
}


function setCikkKepTorol() {
    global $Aktoldal, $MySqliLink;
    $ErrorStr = '';
    $Oid      = 0;
    $Cid        = $_SESSION['SzerkCikk'.'id'];
    $SelectStr = "SELECT Oid From OldalCikkei WHERE Cid='$Cid'";
    $result = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCK 02");
    $row = mysqli_fetch_array($result);    mysql_free_result($result);
    $Oid = $row['Oid'];
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) && (isset($_POST['submitCikkKepForm']))) { 
        if (isset($_POST["rowDB"])) {$rowDB=test_post($_POST["rowDB"]);}
        if ($Oid == $Aktoldal['id']) {
            for($i=0; $i<$rowDB; $i++) {
                if  (isset($_POST["CKTorol_$i"]) && $_POST["CKTorol_$i"]) {
                    $KFile = $_POST["CKTorol_$i"];
                    $DeletetStr = "Delete FROM CikkKepek  WHERE KFile='$KFile' AND Cid=$Cid";
                    if (!mysqli_query($MySqliLink,$DeletetStr)) {die("Hiba dCK 01");}
                }
            }
        }
    }
    return $ErrorStr;
}


function getCikkKepTorolForm() {
    // Integrálható a getCikkKepForm() fgv-be.
    trigger_error('Not Implemented!', E_USER_WARNING);
}

function getCikkKepekHTML() {
    trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
