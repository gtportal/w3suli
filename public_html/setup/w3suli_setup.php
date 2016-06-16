<?php


$DBfNev =''; $DBNev =''; $DBJelszo =''; $DBJelszo1 = ''; $FNev =''; $FFNev =''; $FJelszo =''; $FJelszo1='';
$ErrStr='';  $Err=0;

//------------------------------------------------------------------------------------------------------------------
//  A TARTALOM MEGJELENÍTÉSE
//------------------------------------------------------------------------------------------------------------------

function getTartalomHTML()
{
  echo  "\n\n<div id='tartalom'>\n ";
  if (isset($_POST['submit1'])) { setSetupData(); } 
  getSetupForm();
  echo  "\n\n</div>";
}

//------------------------------------------------------------------------------------------------------------------
//  A SETUP ŰRLAP ADATAINAK KEZELÉSE
//------------------------------------------------------------------------------------------------------------------

function setSetupData()
{
global $DBNev, $DBfNev, $DBJelszo, $DBJelszo1, $FNev, $FFNev, $FJelszo, $FJelszo1, $ErrStr, $Err, $MySqliLink;
  $HTMLkod =''; $ErrStr=''; 
  
  if (isset($_POST['DBNev'])     && ($_POST['DBNev'] > ''))    {$DBNev     = STR_post($_POST['DBNev']);}     else {$ErrStr.='ERR01 ';}
  if (isset($_POST['DBfNev'])    && ($_POST['DBfNev'] > ''))   {$DBfNev    = STR_post($_POST['DBfNev']);}    else {$ErrStr.='ERR02 ';}
  if (isset($_POST['DBJelszo'])  && ($_POST['DBJelszo'] > '')) {$DBJelszo  = STR_post($_POST['DBJelszo']);}  else {$ErrStr.='ERR03 ';}
  if (isset($_POST['DBJelszo1']) && ($_POST['DBJelszo1'] > '')){$DBJelszo1 = STR_post($_POST['DBJelszo1']);} else {$ErrStr.='ERR03 ';}
  if ($_POST['DBJelszo'] != $_POST['DBJelszo1']) {$ErrStr.='ERR03 ';}
 
  if (isset($_POST['FNev'])      && ($_POST['FNev'] > ''))    {$FNev     = STR_post($_POST['FNev']);}     else {$ErrStr.='ERR05 ';}
  if (isset($_POST['FFNev'])     && ($_POST['FFNev'] > ''))   {$FFNev    = STR_post($_POST['FFNev']);}    else {$ErrStr.='ERR06 ';}
  if (isset($_POST['FJelszo'])   && ($_POST['FJelszo'] > '')) {$FJelszo  = STR_post($_POST['FJelszo']);}  else {$ErrStr.='ERR07 ';}
  if (isset($_POST['FJelszo1'])  && ($_POST['FJelszo1'] > '')){$FJelszo1 = STR_post($_POST['FJelszo1']);} else {$ErrStr.='ERR07 ';}
  if ($_POST['FJelszo'] != $_POST['FJelszo1']) {$ErrStr.='ERR07 ';}  

  $FJelszoMD5 = md5($FJelszo);

  if ($ErrStr>'')  {$Err=1;} else {
    $TartalomStr   = '
                      <?php 
                        if ($_SESSION["AktFelhasznalo"."FSzint"]>0) {
                          $MySqliLink'." = mysqli_connect('localhost', '$DBfNev', '$DBJelszo', '$DBNev'); 
                          if (!".'$MySqliLink'.") { die('AB hiba 123'); }
                        }    
                      ?>\n";
  } 
  if ($Err==0) {
    // Fájl mentése
    $FileNev ="init/db/start.php";
    $all = fopen($FileNev, "w") or die("A $FileNev állományt nem lehet megnyitni!");
    fwrite($all, $TartalomStr);
    fclose($all);
  }

  // A fájl meghívásával megnyitjuk az adatbázist
  require_once("init/db/start.php"); 

  $HTMLkod .= "<div id='Visszajelzes' style='float:left; width:300px; background-color:#fff;margin-right:6px;'>";
  
  if ($Err==0) {require_once("setup/w3suli_DB_init.php"); }
    
  if ($Err==0) {$HTMLkod .= Letrehoz_AlapAdatok();}
  if ($Err==0) {$HTMLkod .= Letrehoz_Cikkek();}
  if ($Err==0) {$HTMLkod .= Letrehoz_CikkDokumentumok();}  
  if ($Err==0) {$HTMLkod .= Letrehoz_CikkKepek();}
  if ($Err==0) {$HTMLkod .= Letrehoz_FCsoportTagok();}
  if ($Err==0) {$HTMLkod .= Letrehoz_FelhasznaloCsoport();}
  if ($Err==0) {$HTMLkod .= Letrehoz_Felhasznalok();}  
  if ($Err==0) {$HTMLkod .= Letrehoz_FoMenuLink();}
  if ($Err==0) {$HTMLkod .= Letrehoz_KiegTartalom();}
  if ($Err==0) {$HTMLkod .= Letrehoz_MenuPlusz();}
  if ($Err==0) {$HTMLkod .= Letrehoz_OLathatosag();}
  if ($Err==0) {$HTMLkod .= Letrehoz_Oldalak();}
  if ($Err==0) {$HTMLkod .= Letrehoz_OldalCikkei();}
  if ($Err==0) {$HTMLkod .= Letrehoz_OldalKepek();}
  if ($Err==0) {$HTMLkod .= Letrehoz_OModeratorok();}


  // Az adminisztrátor adatainak felvétele
  $InsertIntoStr = "INSERT INTO Felhasznalok VALUES ('', '$FNev','$FFNev','$FJelszoMD5',' ',7,'Webmester','')";
  if (!mysqli_query($MySqliLink,$InsertIntoStr))  { 
    $Err=1;  $ErrStr .= "MySqli hiba ";    
  }
  $HTMLkod .= "</div>";
  echo $HTMLkod;
}


function getSetupForm()
{
global $DBNev, $DBfNev, $DBJelszo, $DBJelszo1, $FFNev, $FNev, $FJelszo, $FJelszo1, $ErrStr, $Err;
$HTMLkod = '';

if (!isset($_POST['submit1'])) {
    $HTMLkod .= "<h1>".U_SETUP_TELEPIT."</h1>";    
} else {
  if ($Err == 0) {$HTMLkod .= "<h1 style='color:#080;'>".U_SETUP_KESZ."</h1> 
	  
	  <span style='color:#080;'>".U_DATUM.": ".date("Y.m.d.").U_IDO.":   ".date("H.i.s.")."</span><br><br>
    <strong>".U_SETUP_FONTOS."! </strong>";} 
  else {$HTMLkod .= "<h1 style='color:#800;'>".U_SETUP_HIBAVOLT."!</h1> ";} 
}

$HTMLkod .= "<div id='SetupFormDiv'><form method='post' action='#'>";

$HTMLkod .= "<fieldset class='DBAdatok'><legend> ".U_SETUP_ABADAT.": </legend>";
if (strpos($ErrStr,'ERR01')!== false) { $ErrClass="class='Error'"; } else { $ErrClass=""; }
$HTMLkod .= "<p><label for='DBNev' class='label_1'>".U_SETUP_ABNEV.": </label> 
       <input type='text' name='DBNev' id='DBNev' placeholder='".U_SETUP_ABNEV."' value='$DBNev' $ErrClass>
       <span>*</span></p>";
if (strpos($ErrStr,'ERR02')!== false) { $ErrClass="class='Error'"; } else { $ErrClass=""; }
$HTMLkod .= "<p><label for='DBfNev' class='label_1'>".U_SETUP_ABROOT.": </label> 
       <input type='text' name='DBfNev' id='DBfNev' placeholder='".U_SETUP_ABROOT."' value='$DBfNev' $ErrClass>
       <span>*</span></p>";
if (strpos($ErrStr,'ERR03')!== false) { $ErrClass="class='Error'"; } else { $ErrClass=""; }
$HTMLkod .= "<p><label for='DBJelszo' class='label_1'>".U_SETUP_ABJELSZO.": </label> 
       <input type='password' name='DBJelszo' id='DBJelszo' placeholder='".U_SETUP_ABJELSZO."' value='$DBJelszo' $ErrClass>
       <span>*</span></p>";
$HTMLkod .= "<p><label for='DBJelszo1' class='label_1'>".U_SETUP_ABJELSZO2.": </label> 
       <input type='password' name='DBJelszo1' id='DBJelszo1' placeholder='".U_SETUP_ABJELSZO2."' value='$DBJelszo1' $ErrClass>
       <span>*</span></p>";
$HTMLkod .= "<small>".U_SETUP_ABUZI1." 
             <br><i>".U_SETUP_ABUZI2."</i></small>";
$HTMLkod .= "</fieldset>";


$HTMLkod .= "<fieldset class='AdAdatok'><legend>".U_SETUP_ROOTADATOK.": </legend>";
if (strpos($ErrStr,'ERR05')!== false) { $ErrClass="class='Error'"; } else { $ErrClass=""; }
$HTMLkod .= "<p><label for='FNev' class='label_1'>".U_SETUP_ROOTNEV.": </label> 
       <input type='text' name='FNev' id='FNev' placeholder='".U_SETUP_ROOTNEV."' value='$FNev' $ErrClass>
       <span>*</span></p>";
if (strpos($ErrStr,'ERR06')!== false) { $ErrClass="class='Error'"; } else { $ErrClass=""; }
$HTMLkod .= "<p><label for='FFNev' class='label_1'>".U_SETUP_ROOTFNEV.": </label> 
       <input type='text' name='FFNev' id='FFNev' placeholder='".U_SETUP_ROOTFNEV."' value='$FFNev' $ErrClass>
       <span>*</span></p>";
if (strpos($ErrStr,'ERR07')!== false) { $ErrClass="class='Error'"; } else { $ErrClass=""; }
$HTMLkod .= "<p><label for='FJelszo' class='label_1'>".U_SETUP_ROOTJELSZO.": </label> 
       <input type='password' name='FJelszo' id='FJelszo' placeholder='".U_SETUP_ROOTJELSZO."' value='$FJelszo' $ErrClass>
       <span>*</span></p>";
$HTMLkod .= "<p><label for='FJelszo1' class='label_1'>".U_SETUP_ROOTJELSZO2.": </label> 
       <input type='password' name='FJelszo1' id='FJelszo1' placeholder='".U_SETUP_ROOTJELSZO2."' value='$FJelszo1' $ErrClass>
       <span>*</span></p>";

$HTMLkod .= "<small>".U_SETUP_ROOTUZI."</small>";
$HTMLkod .= "</fieldset>";

$HTMLkod .= "<input type='submit' name='submit1' value='".U_BTN_START."' >";
$HTMLkod .= U_SETUP_STARTUZI.".";
$HTMLkod .= "</form> </div>";

  $HTMLkod = "<div id='FormDiv'>".$HTMLkod."<div>";

echo $HTMLkod;

}


?>

