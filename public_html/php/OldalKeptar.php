<?php


$OldalKepek = array();
$OldalKepek['id']         = 0;
$OldalKepek['Oid']        = 0;
$OldalKepek['KFile']      = '';
$OldalKepek['KNev']       = '';
$OldalKepek['KLeiras']    = '';
$OldalKepek['KSzelesseg'] = 0;
$OldalKepek['KMagassag']  = 0;
$OldalKepek['KStilus']    = 0;
$OldalKepek['KSorszam']   = 0;

    function setOldalKepek() {
        //'KFile_$i'
        
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
        //Csak rendszergazdáknak és moderátoroknak!
        $ErrorStr = '';
        $Oid      = $Aktoldal['id'];
        if (($_SESSION['AktFelhasznalo'.'FSzint']>0) && (isset($_POST['submitOldalKepForm'])))   { 
          for($i=9; $i>=0; $i--) {              
            if  ((isset($_POST["KFile_$i"]))&&($_POST["KFile_$i"]!='')) { 
                $KFile      = $_POST["KFile_$i"];               
                $KNev       = '';
                $KLeiras    = '';
                $KSzelesseg = 0;
                $KMagassag  = 0;
                $KStilus    = 0;
                $KSorszam   = 0;   
                if (isset($_POST["KNev_$i"]))       {$KNev=test_post($_POST["KNev_$i"]);}  
                if (isset($_POST["KLeiras_$i"]))    {$KLeiras=test_post($_POST["KLeiras_$i"]);}
                if (isset($_POST["KSzelesseg_$i"])) {$KSzelesseg=test_post($_POST["KSzelesseg_$i"]);}  
                if (isset($_POST["KMagassag_$i"]))  {$KMagassag=test_post($_POST["KMagassag_$i"]);} 
                if (isset($_POST["KStilus_$i"]))    {$KStilus=test_post($_POST["KStilus_$i"]);}  
                if (isset($_POST["KSorszam_$i"]))   {$KSorszam=test_post($_POST["KSorszam_$i"]);} 
                $UpdateStr = "UPDATE OldalKepek SET
                   KNev='$KNev', 
                   KLeiras='$KLeiras', 
                   KSzelesseg='$KSzelesseg', 
                   KMagassag='$KMagassag', 
                   KStilus='$KStilus', 
                   KSorszam='$KSorszam'             
                   WHERE KFile='$KFile' AND Oid=$Oid";
               if (!mysqli_query($MySqliLink,$UpdateStr)) {die("Hiba OK 01 ");}// echo "<h1>".$UpdateStr."</h1>";
            }
          }            
        } 
        return $ErrorStr;    
        
        
    }


    function setOldalKepFeltolt() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
        //Csak rendszergazdáknak és moderátoroknak!
        $ErrorStr = '';
        if (($_SESSION['AktFelhasznalo'.'FSzint']>0) && (isset($_POST['submit_KepekFeltoltForm'])))   { 
            return setKepekFeltolt();
        }
    }


    function setOldalKepTorol() {
        global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;
        //Csak rendszergazdáknak és moderátoroknak!
        $ErrorStr = '';
        $Oid      = $Aktoldal['id'];
        if (($_SESSION['AktFelhasznalo'.'FSzint']>0) && (isset($_POST['submitOldalKepForm'])))   { 
          for($i=9; $i>=0; $i--) {
            if  (isset($_POST["KTorol_$i"])) {
                $KFile = $_POST["KTorol_$i"];
                $DeletetStr = "Delete FROM OldalKepek  WHERE KFile='$KFile' AND Oid=$Oid"; 
                if (!mysqli_query($MySqliLink,$DeletetStr)) {die("Hiba Okt 01");}
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
        $Oid         = $Aktoldal['id'];
        if ($Aktoldal['OImgDir']!='') {
          $KepUtvonal = "img/".$Aktoldal['OImgDir']."/";            
        } else {
          $KepUtvonal = "img/";    
        }
        $KepCT       = 0;
        $HTMLkod     = '';
        $OldalKepek  = array();
        $OldalKepInit = array();
        $OldalKepInit['Oid']        = $Oid;
        $OldalKepInit['KFile']      = '';
        $OldalKepInit['KNev']       = '';
        $OldalKepInit['KLeiras']    = '';
        $OldalKepInit['KSzelesseg'] = 0;
        $OldalKepInit['KMagassag']  = 0;
        $OldalKepInit['KStilus']    = 0;
        $OldalKepInit['KSorszam']   = 0; 
        //Ha még nem lett elküldve vagy az oldal adatait sikerült módosítani >> nem volt hiba
        if (!isset($_POST['submitOldalKepForm']) || ($_SESSION['ErrorStr']==''))  { 
          //Az aktuális oldalhoz kapcsolódó képek beolvasása adatbázisból
          $SelectStr   = "SELECT * FROM OldalKepek WHERE Oid=$Oid order by KSorszam "; 
          $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OKepF 01 ");
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
          //Ha az adatbázisban tárol képek száma kisebb mint 10, akkor a hiányzó képek adatait inicializáljuk                       
          for($i=$KepCT;$i<10;$i++) { $OldalKepek[] = $OldalKepInit; }          
        } else {
          //Ha a formot már elküldték, de hibás adatokkal 
          for($i=0;$i<10;$i++) {
              
          }  
            
        }
        // ============== A KepekFeltoltForm ÖSSZEÁLLÍTÁSA =====================
        $HTMLkod0    ='';
        $HTMLkod0   .="  <div id='KepekFeltoltForm'>
                               <h3>Képek feltöltése:</h3>
                               <form action='?f0=$OUrl'  method='post' enctype='multipart/form-data'>
                                 <input type='file' name='OKepFile[]' id='file_KepekFeltoltForm' multiple='multiple'>
                                 <input type='submit' name='submit_KepekFeltoltForm' id='submit_KepekFeltoltForm' value='Feltöltés'>                                      
                               </form>
                             </div>";
        
        
        // ============== Az OldalKepForm ÖSSZEÁLLÍTÁSA =====================
        $HTMLkod1    ='';
        $ErrClassONev='';
        for($i=0;$i<10;$i++) {
          $HTMLkod1 .= "<div class='Kepszerk'>"; 
          
          $HTMLkod1 .= "<h3>$i. kép</h3>";
          $Src       = $KepUtvonal.$OldalKepek[$i]['KFile'];
          $HTMLkod1 .= "<img src='$Src' alt='$i. kép' >"; 
          $HTMLkod1 .= "<input type='hidden' name='KFile_$i' value='".$OldalKepek[$i]['KFile']."'>";
          
          $HTMLkod1 .= "<div style='float:left;'>";
          $HTMLkod1 .= "<p class='pKSorszam'><label for='KSorszam_$i' class='label_1'>Sorszám:</label>\n ";
          $HTMLkod1 .= "<input type='number' name='KSorszam_$i' id='KSorszam_$i' min='0' max='20' step='1'
                         class='$ErrClassONev' value='".$OldalKepek[$i]['KSorszam']."' ></p>\n"; 
          
          $HTMLkod1 .= "<p class='pKNev'><label for='KNev_$i' class='label_1'>A kép neve:</label>\n ";
          $HTMLkod1 .= "<input type='text' name='KNev_$i' id='KNev_$i' placeholder='Képnév' 
                         class='$ErrClassONev' value='".$OldalKepek[$i]['KNev']."' size='20'></p>\n"; 
          
          $HTMLkod1 .= "<p class='pKLeiras'><label for='KLeiras_$i' class='label_1'>Leírás:</label>\n ";
          $HTMLkod1 .= "<input type='text' name='KLeiras_$i' id='KLeiras_$i' placeholder='Leírás' 
                         class='$ErrClassONev' value='".$OldalKepek[$i]['KLeiras']."' size='60'></p>\n"; 
          
          $HTMLkod1 .= "<p class='pKSzelesseg'><label for='KSzelesseg_$i' class='label_1'>Szélesség:</label>\n ";
          $HTMLkod1 .= "<input type='number' name='KSzelesseg_$i' id='KSzelesseg_$i' min='0' max='20' step='1'
                         class='$ErrClassONev' value='".$OldalKepek[$i]['KSzelesseg']."' ></p>\n"; 
          
          $HTMLkod1 .= "<p class='pKMagassag'><label for='KMagassag_$i' class='label_1'>Magasság:</label>\n ";
          $HTMLkod1 .= "<input type='number' name='KMagassag_$i' id='KMagassag_$i' min='0' max='20' step='1'
                         class='$ErrClassONev' value='".$OldalKepek[$i]['KMagassag']."' ></p>\n"; 
          
          $HTMLkod1 .= "<p class='pKStilus'><label for='KStilus_$i' class='label_1'>Stílus:</label>\n ";
          $HTMLkod1 .= "<input type='number' name='KStilus_$i' id='KStilus_$i' min='0' max='20' step='1'
                         class='$ErrClassONev' value='".$OldalKepek[$i]['KStilus']."' ></p>\n";           
          
          $HTMLkod1 .= "<p class='pKTorol'><label for='KTorol_$i' class='label_1'>TÖRLÉS:</label>\n ";
          $HTMLkod1 .= "<input type='checkbox' name='KTorol_$i' id='KTorol_$i' 
                         class='$ErrClassONev' value='".$OldalKepek[$i]['KFile']."' ></p>\n";           
          
          $HTMLkod1 .= "</div>";
          $HTMLkod1 .= "</div>";  
        } 
        
        
        // ============== A HTML KÓD ÖSSZEÁLLÍTÁSA =====================        
        $HTMLkod .= "<div id='divOldalKepForm' >\n";
        $HTMLkod .= $_SESSION['ErrorStr'];
        $HTMLkod .= $HTMLkod0;
        $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOldalKepForm'>\n";
        $HTMLkod .= $HTMLkod1;
        
        $HTMLkod .=  "<br><input type='submit' name='submitOldalKepForm' value='Elküld'><br>\n";        
        $HTMLkod .= "</form>\n";
        $HTMLkod .= "</div>\n";
        return $HTMLkod;
    }


    function getOldalKepTorolForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
?>