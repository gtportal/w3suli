<?php

/* Példatömb
$CikkDokumentumok = array();
$CikkDokumentumok['id']         = 0;
$CikkDokumentumok['Cid']        = 0;
$CikkDokumentumok['DFile']      = '';
$CikkDokumentumok['DNev']       = '';
$CikkDokumentumok['DLeiras']    = '';
$CikkDokumentumok['CDMeretKB']   = 0;
$CikkDokumentumok['DSorszam']   = 0;
*/

function setCikkDokFeltolt() {
    global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
    $ErrorStr = ''; 
    $Cid      = $_SESSION['SzerkCikk'.'id'];  
    //Csak rendszergazdáknak, moderátoroknak és regisztrált felhasználóknak!
    if (($_SESSION['AktFelhasznalo'.'FSzint']>2) && (isset($_POST['submit_CikkDokokFeltoltForm'])) && ($Cid>0))   {   
        
        $Oid          = $Aktoldal['id'];
        $UploadErr    = '';
        if ($Aktoldal['OImgDir']!='') {
          $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/doc/";
        } else {
          $KepUtvonal = "img/oldalak/doc/";
        }
        $ErrorStr= DokKonyvtarLetrehoz($KepUtvonal); //echo "<h1>$KepUtvonal BRRRRRRRRRRRRRR $ErrorStr </h1>";
        
        if ($ErrorStr=='') {
            //=============== Lehetséges Fájlnevek ==================
            // Fájlnév felépítése cikk_$Cid_$DSorszam.kiterjesztés
            $lehetDFile  = array();
            $SelectStr   = "SELECT CNev FROM Cikkek WHERE id=$Cid ";  //echo "<h1>$SelectStr</h1>";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba CKx 01aa ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) {
                        $row   = mysqli_fetch_array($result);                    
                        $CDNev = $row['CNev'];
                        mysqli_free_result($result);
            }
            if (strlen($CDNev)>50) {$CDNev=substr($CDNev,0,50);} //echo "<h1>CNev: $CDNev</h1>";
            $CDNev = getTXTtoURL($CDNev);

            for($i=9; $i>=0; $i--) {
              $lehetDFile[] = $CDNev.'_'.$i; 
            }
            //print_r($lehetDFile);
            //=============== Létező Fájlnevek ==================
            $vanDFile     = array();
            $SelectStr    = "SELECT DFile FROM CikkDokumentumok WHERE Cid=$Cid" ;
            $result       = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba CKF 01");
            $rowDB        = mysqli_num_rows($result);
            if ($rowDB > 0) {
              while($row  = mysqli_fetch_array($result))
              {
                $temp       = explode(".", $row['DFile']);
                $vanDFile[] = $temp[0];
              }
              mysqli_free_result($result);
            }
          //=============== Használható Fájlnevek ==================
            $UploadErr   = '';
            $OkDFile     = array();
            $OkDFile0    = array_diff($lehetDFile,$vanDFile);
            foreach ($OkDFile0 as $key => $value) {$OkDFile[] = $value;}
            $OkDFileCt   = count($OkDFile);
            $DFileDb     = count($_FILES['CODokFile']['name']);
            if (($DFileDb==1) && ($_FILES["CODokFile"]["name"][0]=='')) {
                $DFileDb=0; $UploadErr = 'ErrK00'; 

            } else {
                if (isset($_FILES["CODokFile"])) {
                    $i=0;
                    while (($i<$DFileDb) && ($OkDFileCt>0)) {
                      $FFile       = $_FILES["CODokFile"]["name"][$i];  
                      $allowedExts = array("txt", "doc", "docx", "pdf", "xls", "xlsx", "ppt", "pptx", "zip");
                      $temp        = explode(".", $_FILES["CODokFile"]["name"][$i]);
                      $extension   = end($temp);
                      $AktFileNev  = $OkDFile[$OkDFileCt-1].'.'.$extension;
                      $FNev        = $_FILES["CODokFile"]["name"][$i];
                      if (( ($_FILES["CODokFile"]["type"][$i] == "application/msword")
                         || ($_FILES["CODokFile"]["type"][$i] == "application/excel")    
                         || ($_FILES["CODokFile"]["type"][$i] == "application/vnd.ms-excel")
                         || ($_FILES["CODokFile"]["type"][$i] == "application/x-msexcel")
                         || ($_FILES["CODokFile"]["type"][$i] == "application/x-excel")    
                         || ($_FILES["CODokFile"]["type"][$i] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
                         || ($_FILES["CODokFile"]["type"][$i] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                         || ($_FILES["CODokFile"]["type"][$i] == "application/pdf")
                         || ($_FILES["CODokFile"]["type"][$i] == "application/vnd.ms-powerpoint")
                         || ($_FILES["CODokFile"]["type"][$i] == "application/vnd.openxmlformats-officedocument.presentationml.presentation"))
                         || ($_FILES["CODokFile"]["type"][$i] == "application/zip")

                         && ($_FILES["CODokFile"]["size"][$i] < 2000000)
                         && in_array($extension, $allowedExts))
                        {
                            if ($_FILES["CODokFile"]["error"][$i] > 0) {                           
                              $UploadErr .= "ErrK02".$_FILES["CODokFile"]["name"][$i]."<br>"; 
                            } else {
                              if (file_exists($KepUtvonal.$AktFileNev)) {
                                //Meglévő kép felülírása
                                if (!@unlink($KepUtvonal.$AktFileNev)) {                                
                                    $UploadErr = 'ErrK02'.$FNev."<br>"; // Nem sikerült a törlés
                                } else {     
                                    if (move_uploaded_file($_FILES["CODokFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev)) {
                                        $UploadErr    .=   "OK001".$FNev." <br>"; $KepOK=true;
                                        $InsertIntoStr = "INSERT INTO CikkDokumentumok VALUES ('', $Cid,'$AktFileNev','','',0,0,'$FFile')";
                                        if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba CKF 02");}
                                    } else {
                                        $UploadErr .= "ErrK05".$FNev."<br>";                     
                                    }
                                }
                              } else {
                                //Új kép feltöltése
                                if (move_uploaded_file($_FILES["CODokFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev)) {
                                    $UploadErr    .=   "OK001".$FNev." <br>"; $KepOK=true;      
                                    $InsertIntoStr = "INSERT INTO CikkDokumentumok VALUES ('', $Cid,'$AktFileNev','','',0,0,'$FFile')";
                                    if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba CKF 03");}
                                } else {
                                        $UploadErr .= "ErrK05".$FNev."<br>";                     
                                }
                              }
                            }
                        } else {
                            if ($AktFileNev >'') {$UploadErr .= "ErrK01".$FNev."<br>"; }
                        }
                        $i++; $OkDFileCt--;
                    }
                if ($i<$DFileDb) { $UploadErr .= "<p class='Error'> ".U_CSAK_10_DOK."!</p>\n";}
              }
            }
        }    
        return $UploadErr;
    }
}


function getCikkDokFeltoltForm($ErrorStr) {
    global $Aktoldal;
    $OUrl       = $Aktoldal['OUrl'];
    $HTMLkod    = " <div id='CikkDokumentumokFeltoltForm'>                           
                    <form action='?f0=$OUrl'  method='post' enctype='multipart/form-data' id='CikkKepTolForm'>
                    <h2>".U_CDOK_FEL."</h2>
                    <p class='ErrClassKep'>$ErrorStr </p>
                    <fieldset> <legend>".U_CDOK_VAL.":</legend>
                    <input type='file' name='CODokFile[]' id='file_CikkDokumentumokFeltoltForm' multiple='multiple'>
                    </fieldset>     
                    <input type='submit' name='submit_CikkDokokFeltoltForm' id='submit_CikkDokokFeltoltForm' value='".U_BTN_FELTOLT."'>   <br>   <br>                                 
                    </form>
                    </div>";
    return $HTMLkod;
}

function setCikkDokumentumok() {
    global $Aktoldal, $MySqliLink;
    $ErrorStr   = '';
    $Oid        = 0;    
    $Cid        = $_SESSION['SzerkCikk'.'id'];
    $SelectStr  = "SELECT Oid From OldalCikkei WHERE Cid='$Cid'";
    $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCK 02");
    $rowDB      = mysqli_num_rows($result); 
    if ($rowDB > 0) {
        $row    = mysqli_fetch_array($result);    mysqli_free_result($result);
        $Oid    = $row['Oid'];
        if (($_SESSION['AktFelhasznalo'.'FSzint']>2) && (isset($_POST['submitCikkDokForm'])))   {
            if (isset($_POST["rowDB"])) {$rowDB=test_post($_POST["rowDB"]);}
            $DNev       = '';
            
            //=============================HIBAKEZELÉS==============================
       /*     for($i=0; $i<$rowDB; $i++) {
                if (isset($_POST["CDNev_$i"])) {
                    $DNev=test_post($_POST["CDNev_$i"]); echo "<h1>$i DNev: $DNev</h1>";
                    if(strlen($DNev)>40) {                         
                        $ErrorStr .= "ErrH01 $DNev <br>\n";
                        $DNev=substr($DNev,0,40);                        
                    }
                }
            }*/
            //====================POST beillesztése adatbázisba=====================
            if ($Oid == $Aktoldal['id']) {
                for($i=0; $i<$rowDB; $i++) {
                    if  ((isset($_POST["CDFile_$i"]))&&($_POST["CDFile_$i"]!='')) {
                        $DFile      = FileNevTisztit($_POST["CDFile_$i"]);                        
                        $DLeiras    = '';
                        $DMeretKB   = 0;
                        $DSorszam   = 0;                        
                        if (isset($_POST["CDLeiras_$i"]))    {$DLeiras  = test_post($_POST["CDLeiras_$i"]);}
                        if (isset($_POST["CDMeretKB_$i"]))   {$DMeretKB = test_post($_POST["CDMeretKB_$i"]);}
                        if (isset($_POST["CDSorszam_$i"]))   {$DSorszam = test_post($_POST["CDSorszam_$i"]);}
                        if (isset($_POST["CDNev_$i"]))       {$DNev     = test_post($_POST["CDNev_$i"]);} // echo "<h1>$i DNev: $DNev</h1>";
                        if(strlen($DNev)>40) {
                            $ErrorStr .= "ErrH01 $DNev <br>\n";
                            $DNev=substr($DNev,0,40);                        
                        }
                    }
                    $UpdateStr = "UPDATE CikkDokumentumok SET 
                                DNev='$DNev', 
                                DLeiras='$DLeiras', 
                                DMeretKB='$DMeretKB', 
                                DSorszam='$DSorszam' 
                                WHERE DFile='$DFile' 
                                AND Cid=$Cid";
                    mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uCK 01");
                    setCikkDokTorol($i);
                }
            }
        }
    }
    return $ErrorStr;
}

function getCikkDokForm() {
    global $Aktoldal, $MySqliLink;
    $Oid        = $Aktoldal['id'];
    $Cid        = $_SESSION['SzerkCikk'.'id']; 
    $OUrl       = $Aktoldal['OUrl'];
    $ErrorStr   = '';
    $ErrorStr1  = '';
    $HTMLkod    = '';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>2){     
      if ($_SESSION['SzerkCikk'.'id']>0) {             
        $ErrClassKep    = '';
        if (isset($_POST['submit_CikkDokokFeltoltForm'])) {
            $ErrorStr        = $_SESSION['ErrorStr'];
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
               $ErrorStr      = "<p class='time'>".U_MODOSITVA.date("H.i.s.")."<p>".$ErrorStr; 
            } else {
               $ErrorStr      = "<p class='time'>".U_ELKULDVE.date("H.i.s.")."<p>".$ErrorStr;
            }
        } 
        
        if (isset($_POST['submitCikkDokForm'])) {
            $ErrorStr1        = $_SESSION['ErrorStr'];
            $CikkKepOK        = 1;
            if (strpos($ErrorStr1,'ErrH01')!==false) {
               $ErrArr        = array('ErrH01' => U_HOSSZ_ER001);  
               $ErrorStr1     = strtr($ErrorStr1 ,$ErrArr); 
               $CikkKepOK     = 0;
            }
            if ($CikkKepOK    = 0 ){
               $ErrorStr1     = "<p class='time'>".U_MODOSITVA.date("H.i.s.")."<p>".$ErrorStr1; 
            } else {
               $ErrorStr1     = "<p class='time'>".U_ELKULDVE.date("H.i.s.")."<p>".$ErrorStr1;
            }
        }            
        $HTMLkod .= "<div id='divCikkDokForm' >\n";
        $HTMLkod .= getCikkDokFeltoltForm($ErrorStr);   
        
        
        // a $_SESSION['SzerkCik'][id] és a $_SESSION['SzerkCik'][Oid] által meghatározott cikk képeinek kezelése
        // Minta OldalKeptar.php getOldalKepForm()- fgv-e. 
        // Változás: a képek feltöltéséhez szüksége form saját fgv-t kap. >> getCikkKepFeltoltForm()

        if ($Aktoldal['OImgDir']!='') {
          $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/doc/";            
        } else {
          $KepUtvonal = "img/oldalak/doc/";    
        }
        //Az aktuális cikkhez kapcsolódó képek beolvasása adatbázisból 
        $SelectStr   = "SELECT * FROM CikkDokumentumok WHERE Cid=$Cid order by DSorszam ";
        $SelectStr   = "SELECT *
                        FROM CikkDokumentumok AS CK
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
                $CikkKep['DFile']      = $row['DFile'];
                $CikkKep['DNev']       = $row['DNev'];
                $CikkKep['DLeiras']    = $row['DLeiras'];
                $CikkKep['DMeretKB']   = $row['DMeretKB'];
                $CikkKep['DSorszam']   = $row['DSorszam'];
                $CikkKep['DTorol']     = $row['DFile'];
                $CikkDokumentumok[]           = $CikkKep;
            }
        } else {
            if (isset($_POST['rowDB'])) {
                for($i = 0; $i < $_POST['rowDB']; $i++) {
                    $CikkKep                      = array();
                    if (isset($_POST["CDFile_$i"]))      {$CikkKep['DFile']      = test_post1($_POST["CDFile_$i"]);} 
                      else {$CikkKep['DFile']     ='';}
                    if (isset($_POST["CDNev_$i"]))       {$CikkKep['DNev']       = test_post1($_POST["CDNev_$i"]);}
                      else {$CikkKep['DNev']      ='';}
                    if (isset($_POST["CDLeiras_$i"]))    {$CikkKep['DLeiras']    = test_post1($_POST["CDLeiras_$i"]);}
                      else {$CikkKep['DLeiras']   ='';}
                    if (isset($_POST["CDMeretKB_$i"]))   {$CikkKep['DMeretKB']   = INT_post($_POST["CDMeretKB_$i"]);}
                      else {$CikkKep['DMeretKB'] =0;}
                    if (isset($_POST["CDSorszam_$i"]))   {$CikkKep['DSorszam']   = INT_post($_POST["CDSorszam_$i"]);}
                      else {$CikkKep['DSorszam']   =0;}
                    if (isset($_POST["CDTorol_$i"]))     {$CikkKep['DTorol']     = INT_post($_POST["CDTorol_$i"]);}
                      else {$CikkKep['DTorol']     =0;}
                    $CikkDokumentumok[]                   = $CikkKep;
                }
            }
        }
        if ($rowDB != 0){
            $HTMLkod .= "<h2>".U_CDOK_MOD."</h2>\n";
            $HTMLkod .= "<p class='$ErrClassKep'>$ErrorStr1 </p>";
            
            mysqli_free_result($result); 
            $HTMLkod1    ='';
            $ErrClassCNev='';
            for($i = 0; $i < $rowDB; $i++) {
                $checked = "";
                if (strpos($_SESSION['ErrorStr'],'Err_')!=false) {
                    if (isset($_POST["CDTorol_$i"])) {$checked = " checked ";}
                }
                
                if (strpos($_SESSION['ErrorStr'],"Err_$i")!=false) {
                    $ErrClassCNev = "Error";
                } else {$ErrClassCNev='';}
                $HTMLkod1 .= "<div class='Kepszerk'>"; 

                $j        = $i+1;
                $HTMLkod1.= "<fieldset> <legend>".$j.". ".U_CDOK_ADAT."</legend>";
            
                $Src       = $KepUtvonal.$CikkDokumentumok[$i]['DFile']; //echo "<h1>$Src</h1>";
                $HTMLkod1 .= "<img src='$Src' alt='$i. kép' >";
                $HTMLkod1 .= "<input type='hidden' name='CDFile_$i' value='".$CikkDokumentumok[$i]['DFile']."'>";

                $HTMLkod1 .= "<div style='float:left;'>";
                $HTMLkod1 .= "<p class='pDSorszam'><label for='CDSorszam_$i' class='label_1'>".U_SORSZAM.":</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CDSorszam_$i' id='CDSorszam_$i' min='0' max='1000' step='1' value='".$CikkDokumentumok[$i]['DSorszam']."' ></p>\n"; 

                $HTMLkod1 .= "<p class='pDNev'><label for='CDNev_$i' class='label_1'>".U_CDOK_CIM.":</label>\n ";
                $HTMLkod1 .= "<input type='text' name='CDNev_$i' id='CDNev_$i' placeholder='".U_CDOK_CIM."' 
                               class='$ErrClassCNev' value='".$CikkDokumentumok[$i]['DNev']."' size='1000'></p>\n"; 

                $HTMLkod1 .= "<p class='pDLeiras'><label for='CDLeiras_$i' class='label_1'>".U_LEIRAS.":</label>\n ";
                $HTMLkod1 .= "<input type='text' name='CDLeiras_$i' id='CDLeiras_$i' placeholder='".U_LEIRAS."'  value='".$CikkDokumentumok[$i]['DLeiras']."' size='60'></p>\n"; 

                $HTMLkod1 .= "<p class='pDMeretKB'><label for='CDMeretKB_$i' class='label_1'>".U_CDOK_MERET.":</label>\n ";
                $HTMLkod1 .= "<input type='number' name='CDMeretKB_$i' id='CDMeretKB_$i' min='0' max='10000' step='1' value='".$CikkDokumentumok[$i]['DMeretKB']."' ></p>\n"; 


                $HTMLkod1 .= "<p class='pDTorol'><label for='CDTorol_$i' class='label_1'>".U_TORTLES.":</label>\n ";
                $HTMLkod1 .= "<input type='checkbox' name='CDTorol_$i' id='CDTorol_$i'  value='".$CikkDokumentumok[$i]['DFile']."' $checked ></p>\n";           
                $HTMLkod1 .= "</div>";
                $HTMLkod1 .= "</fieldset> ";
                $HTMLkod1 .= "</div>";  
            }
            // ============== A HTML KÓD ÖSSZEÁLLÍTÁSA =====================   
            $HTMLkod1 .= "<input type='hidden' name='rowDB' value='$rowDB'>";
          //  $HTMLkod .= $_SESSION['ErrorStr'];
            $HTMLkod  .= "<div id='divCikkDokForm1'><form action='?f0=$OUrl' method='post' id='formCikkDokForm'>\n";
            $HTMLkod  .= $HTMLkod1;
            $HTMLkod  .=  "<input type='submit' name='submitCikkDokForm' value='".U_BTN_MODOSITAS."' style='clear:left;'><br><br>\n";        
            $HTMLkod  .= "</form></div>\n";
        } else {
            $HTMLkod  .= "<div id='divCikkDokForm1'><form action='?f0=$OUrl' method='post' id='formCikkDokForm'>\n";
            $HTMLkod  .= "<h2>".U_CDOK_NINCS."!</h2>\n";
            
            $HTMLkod  .= "</form></div>\n";
        }
        $HTMLkod .= "</div> <br style='clear:left;'>\n";
      } else {
        $HTMLkod .= "<div id='divCikkDokForm' >\n";       
        $HTMLkod .= "<h2>".U_CIKK_NINCS."!</h2>\n";;
        $HTMLkod .= "</div>\n";
      }
    }
    return $HTMLkod;
}


function setCikkDokTorol($i) {
    global $Aktoldal, $MySqliLink;
    $Cid            = $_SESSION['SzerkCikk'.'id']; //  echo "<h1>HHHHHHHH</h1>";
    if (isset($_POST["rowDB"])) {$rowDB=test_post($_POST["rowDB"]);}
    if ($Aktoldal['OImgDir']!='') {
      $KepUtvonal   = "img/oldalak/".$Aktoldal['OImgDir']."/doc/";
    } else {
      $KepUtvonal   = "img/oldalak/doc/";
    }
    if  (isset($_POST["CDTorol_$i"]) && $_POST["CDTorol_$i"]) {
        $DFile      = test_post($_POST["CDTorol_$i"]); //echo "<h1>DFile:$DFile</h1>";
        unlink($KepUtvonal.$DFile);
        $DeletetStr = "Delete FROM CikkDokumentumok  WHERE DFile='$DFile' AND Cid=$Cid";
        mysqli_query($MySqliLink,$DeletetStr) OR die("Hiba dCK 01");
    }
}

function CikkOsszesDokTorol($Cid,$OImgDir) {
    global $MySqliLink;
    $ErrorStr     = '';   
    if ($OImgDir !='') {
      $KepUtvonal = "img/oldalak/".$OImgDir."/doc/";
    } else {
      $KepUtvonal = "img/oldalak/doc/";
    }
    $SelectStr    = "SELECT DFile FROM CikkDokumentumok WHERE Cid=$Cid";  
    $result       = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba COT 01");
    $rowDB        = mysqli_num_rows($result); 
    if ($rowDB > 0) {
        while ($row = mysqli_fetch_array($result)){        
            $Src    = $KepUtvonal.$row['DFile'];
            if (!unlink($Src)) {$ErrorStr .= 'Err200'.$row['DFile'].' '; }
            $DeletetStr = "Delete FROM CikkDokumentumok  WHERE Cid=$Cid";
            mysqli_query($MySqliLink,$DeletetStr) OR die("Hiba ddCK 01");
        }
        mysqli_free_result($result); 
    }
    return $ErrorStr; 
}



function getCikkDokumentumokHTML($Cid) {
    global $MySqliLink, $Aktoldal;
    $HTMLkod      = '';    
    if ($Aktoldal['OImgDir']!='') {
      $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/doc/";
    } else {
      $KepUtvonal = "img/oldalak/doc/";
    }
    
    $HTMLkod  .= "<div class = 'divCikkDokumentumok'>\n";
    $SelectStr = "SELECT DNev, DFile FROM CikkDokumentumok WHERE Cid=$Cid ORDER BY DSorszam DESC"; //echo "SelectStr:" .$SelectStr ."<br>";
    $result    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01pp");
    $rowDB     = mysqli_num_rows($result); 
    if ($rowDB > 0) {    
        while ($row   = mysqli_fetch_array($result)){
            $Src      = $KepUtvonal.$row['DFile'];
            $DNev     = $row['DNev'];
            $HTMLkod .= "<div class = 'divCikkDok'>";
            $HTMLkod .= "<img src='$Src'  class = 'imgCikkKep' alt='$DNev'>";
                //$HTMLkod .= $row['DFile'];
            $HTMLkod .= "</div>\n";
        }
        mysqli_free_result($result); 
    }
    $HTMLkod .= "</div>\n";
    return $HTMLkod;
}


function DokumentumAtnevez($RFNev,$UFNev,$Cid) { 
global $Aktoldal, $MySqliLink;       
    $ErrorStr   = '';
   // $Konytar    = 'img/oldalak/'.$Aktoldal['OImgDir']."/doc/";  
    if ($Konytar['OImgDir']!='') {
      $KepUtvonal = "img/oldalak/".$Aktoldal['OImgDir']."/doc/";
    } else {
      $Konytar = "img/oldalak/doc/";
    }
    $SelectStr  = "SELECT * FROM CikkDokumentumok WHERE Cid=$Cid";
    $result     = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sGC 01abpp");
    $rowDB      = mysqli_num_rows($result); 
    if ($rowDB > 0) {  
        while ($row  = mysqli_fetch_array($result)){
            $DFile   = $row['DFile'];
            $UFNev   = getTXTtoURL($UFNev);
            if (strlen($UFNev)>50) {$UFNev=substr($UFNev,0,50);}
            $RFNev   = getTXTtoURL($RFNev);
            if (strlen($RFNev)>50) {$RFNev=substr($RFNev,0,50);}

            $arr     = array($RFNev => $UFNev);
            $UDFile  = strtr($DFile ,$arr);

            $DFileM  = $Konytar.$DFile;
            $UDFileM = $Konytar.$UDFile;

            if (file_exists($DFileM)) {
                if (!rename($DFileM, $UDFileM)) {
                        $ErrorStr = 'Err100'; // Nem sikerült átnevezni
                }       
            } else {$ErrorStr = 'Err101';} // A fájl nem létezik

            if ($ErrorStr=='') {
               $UpdateStr = "UPDATE CikkDokumentumok SET 
                               DFile='$UDFile'
                               WHERE Cid=$Cid AND DFile='$DFile' LIMIT 1";          
               if (!mysqli_query($MySqliLink,$UpdateStr))  {echo "Hiba setOK 01 ";}  
            }
        }
        mysqli_free_result($result); 
    }
    return $ErrorStr;    
}

 function DokKonyvtarLetrehoz($KTarNev) {
        $ErrorStr       = '';
        $AktAlKonytart  = $KTarNev;       
        if (!is_dir($AktAlKonytart)) {     
            if (!mkdir($AktAlKonytart, 0777)) {
              $ErrorStr = 'Err100'; // Nem sikerült létrehozni
            }
        }
        return $ErrorStr;        
    }


?>