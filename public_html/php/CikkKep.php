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
    // setOldalKepek() fgv alapján
    
}

function getCikkKepForm() {
    global $Aktoldal;
    $Oid        = $Aktoldal['id'];
    $HTMLkod    ='';    
    $HTMLkod   .=getCikkKepFeltoltForm();
    
    // a $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk képeinek kezelése
    // Minta OldalKeptar.php getOldalKepForm()- fgv-e. 
    // Változás: a képek feltöltéséhez szüksége form saját fgv-t kap. >> getCikkKepFeltoltForm()
    
    return $HTMLkod;
}


function setCikkKepTorol() {
    // setOldalKepTorol() fgv alapján
}


function getCikkKepTorolForm() {
    // Integrálható a getCikkKepForm() fgv-be.
    trigger_error('Not Implemented!', E_USER_WARNING);
}

function getCikkKepekHTML() {
    trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
