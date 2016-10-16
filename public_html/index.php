<?php
  global $oURL;
  $oURL           = ''; 
  $MySqliLink     = '';
  $Aktoldal       = array();
  $SzuloOldal     = array();
  $NagyszuloOldal = array();
  $DedSzuloId     = 0;
  $UkSzuloId      = 0;
  

  require_once 'lang/w3suli_hu.php';
  require_once 'php/AlapFgvek.php';
  
  //MUNKAMENET INDÍTÁSA
  session_start();
  $mm_azon   = session_id(); 
  //Ha első bejelentkezés, akkor AktFelhasznalo tömb inicializálása
  //FSzint=1 > Látogató
  //FSzint=2 > Bejelentkezett felhasználó
  //FSzint=3 > Oldal moderátora
  //FSzint=4 > Rendszergazda
  //FSzint=5 > Kiemelt rendszergazda
  
  if (!isset($_SESSION['AktFelhasznalo'.'id']))     {$_SESSION['AktFelhasznalo'.'id']      = 0;} 
  if (!isset($_SESSION['AktFelhasznalo'.'FNev']))   {$_SESSION['AktFelhasznalo'.'FNev']    = '';}
  if (!isset($_SESSION['AktFelhasznalo'.'FFNev']))  {$_SESSION['AktFelhasznalo'.'FFNev']   = '';}
  if (!isset($_SESSION['AktFelhasznalo'.'FEmail'])) {$_SESSION['AktFelhasznalo'.'FEmail']  = '';}
  if (!isset($_SESSION['AktFelhasznalo'.'FSzint'])) {$_SESSION['AktFelhasznalo'.'FSzint']  = 1;}
  if (!isset($_SESSION['AktFelhasznalo'.'FSzerep'])){$_SESSION['AktFelhasznalo'.'FSzerep'] = '';}
  if (!isset($_SESSION['AktFelhasznalo'.'FKep']))   {$_SESSION['AktFelhasznalo'.'FKep']    = '';}    
   
  if (!isset($_SESSION['ElozoOldalId']))     {$_SESSION['ElozoOldalId']     = 1;} 
  if (!isset($_SESSION['SzerkFelhasznalo'])) {$_SESSION['SzerkFelhasznalo'] = 0;} 
  if (!isset($_SESSION['SzerkFCsoport']))    {$_SESSION['SzerkFCsoport']    = 0;} 
  if (!isset($_SESSION['SzerkModerator']))   {$_SESSION['SzerkModerator']   = 0;} 
  if (!isset($_SESSION['SzerkMCsoport']))    {$_SESSION['SzerkMCsoport']    = 0;} 
  if (!isset($_SESSION['SzerkCikk'.'id']))   {$_SESSION['SzerkCikk'.'id']   = 0;} 
  if (!isset($_SESSION['SzerkCikk'.'Oid']))  {$_SESSION['SzerkCikk'.'Oid']  = 0;} 
  
  if (!isset($_SESSION['LapozCikk'.'CT']))   {$_SESSION['LapozCikk'.'CT']   = 0;} 
  if (!isset($_SESSION['LapozCikk'.'OUrl'])) {$_SESSION['LapozCikk'.'OUrl'] = '';}  
  if (!isset($_SESSION['LapozKat'.'CT']))    {$_SESSION['LapozKat'.'CT']    = 0;} 
  if (!isset($_SESSION['LapozKat'.'OUrl']))  {$_SESSION['LapozKat'.'OUrl']  = '';}
  
  
  
  $_SESSION['ErrorStr']   = '';
  if ($_SESSION['AktFelhasznalo'.'FSzint']==4) {$_SESSION['AktFelhasznalo'.'FSzint']=3;} // A moderátor oldalanként változik  
  //if (isset($_GET['f0']))  { $oURL = $_GET['f0'];}  else { $oURL = '';}  
  if (isset($_GET['f0']))  { $oURL = getTXTtoURL($_GET['f0']);}  else { $oURL = '';}  
  if (isset($_GET['lap'])) { $oLap = INT_post($_GET['lap']);}    else { $oLap = 0;} 
  if (isset($_GET['cim'])) { $CCim = getTXTtoURL($_GET['cim']);} else { $CCim = '';} 

  //ADATBÁZIS MEGNYITÁSA
  require_once("init/db/start.php");
  $RootURL        = getRootURL();
  $TisztaOURL     = getTisztaURL();
  //require_once("php/DB/Adatbazis.php");
  //require_once("php/Init.php");  
  //Alapadatok lekérdezése
  require_once("php/Alapbeallitasok.php");
  
  $_SESSION['ErrorStr']   .= setAlapbeallitasok();  
  $AlapAdatok = getAlapbeallitasok();
  
  //BE- vagy KIJELENTKEZÉS; FELHASZNÁLÓI ADATOK MÓDOSÍTÁSA
  require_once("php/Felhasznalo.php");
  if ($_SESSION['AktFelhasznalo'.'FSzint'] > 0) {
    $_SESSION['ErrorStr']   .= setBelepes(); 
    $_SESSION['ErrorStr']   .= setKilepes(); 
    $_SESSION['ErrorStr']   .= SetUjJelszo();
  }
  if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {
    $_SESSION['ErrorStr']   .= setFelhasznalo();
    $_SESSION['ErrorStr']   .= setUjFelhasznalo();  
    $_SESSION['ErrorStr']   .= setFelhasznaloTorol();    
  }
  
  require_once("php/Oldal.php");
  require_once("php/Lapozas.php");
  require_once("php/FelhasznaloCsoport.php");  
  require_once("php/FCsoportTagok.php");
  require_once 'php/KiegeszitoTartalom.php';
  require_once 'php/FoMenu.php';
  require_once 'php/OldalModerator.php';
  require_once 'php/OldalLathatosag.php';
  require_once 'php/Menu.php';
  require_once 'php/OldalCikkei.php';
  require_once 'php/Cikk.php';
  require_once 'php/CikkKep.php';
  require_once 'php/CikkDokumentum.php';
  
  require_once 'php/OldalKeptar.php';
  require_once 'php/morzsa.php';
  require_once 'php/Lablec.php';
  
  require_once 'php/OldalElozetesek.php';
  require_once 'php/Oldalterkep.php';
  require_once 'php/MenuPlusz.php';
  
  //AZ AKTUÁLIS OLDAL ADATAINAK BEOLVASÁSA
  getOldalData($oURL);  
  

  
  //A MODERÁTOR STÁTUSZ ELLENŐRZÉSE
  if (($_SESSION['AktFelhasznalo'.'FSzint'] == 3) || ($_SESSION['AktFelhasznalo'.'FSzint'] == 4))
  {
    if (getOModeratorTeszt() > 0)    // Csak akkor érdekes, ha bejelentkezett, de nem rendszergazda     
    {
        $_SESSION['AktFelhasznalo'.'FSzint'] =  4;
    } else {
        $_SESSION['AktFelhasznalo'.'FSzint'] =  3;        
    }
  } 
  
//  echo "HHHHHHHHHH: ".$_SESSION['AktFelhasznalo'.'FSzint'];
  
  //FELHASZNÁLÓI CSOPORTADATOK MÓDOSÍTÁSA
  if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {
    $_SESSION['ErrorStr']   .= setUjFCsoport();  
    $_SESSION['ErrorStr']   .= setFCsoport(); 
    $_SESSION['ErrorStr']   .= setFCsoportTorol(); 
    $_SESSION['ErrorStr']   .= setCsoportTagok(); 
  }
    
  //AZ OLDAL ADATAINAK MÓDOSÍTÁSA
  if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {  
    $_SESSION['ErrorStr']   .= setUjOldal();
    $_SESSION['ErrorStr']   .= setOldal();
    $_SESSION['ErrorStr']   .= setOldalTorol();  
    $_SESSION['ErrorStr']   .= setOldalKepek();
    $_SESSION['ErrorStr']   .= setOldalKepFeltolt();
    $_SESSION['ErrorStr']   .= setOldalKepTorol();
    $_SESSION['ErrorStr']   .= setOModerator();
    $_SESSION['ErrorStr']   .= setOLathatosag();
  }

  //A CIKKEK ADATAINAK MÓDOSÍTÁSA
  if ($_SESSION['AktFelhasznalo'.'FSzint'] > 1) {
    $_SESSION['ErrorStr']   .= setUjCikk();
    $_SESSION['ErrorStr']   .= setCikk();  
    $_SESSION['ErrorStr']   .= setCikkTorol();  
    $_SESSION['ErrorStr']   .= setCikkKepek();
    $_SESSION['ErrorStr']   .= setCikkKepFeltolt();
    
    $_SESSION['ErrorStr']   .= setCikkDokumentumok();
    $_SESSION['ErrorStr']   .= setCikkDokFeltolt();
  }
  
  //KIEGÉSZÍTŐ TARTALOM MÓDOSÍTÁSA
  if ($_SESSION['AktFelhasznalo'.'FSzint'] > 4) {   
    $_SESSION['ErrorStr']   .= setKiegT(); 
    $_SESSION['ErrorStr']   .= setFoMenu();
    $_SESSION['ErrorStr']   .= setMenuPlusz();
  }
  
    //Modulcsatoló fájl beolvasása
  if ($Aktoldal['OTipus']>100) {
      $ModulURL = "modulok/modul_".$Aktoldal['OTipus'].".php";
      require_once $ModulURL;
  }
  //AZ AKTUÁLIS MODUL ADATAINAK MÓDOSÍTÁSA
  if (($_SESSION['AktFelhasznalo'.'FSzint'] > 0) && ($Aktoldal['OTipus']>100)) {   
    $_SESSION['ErrorStr']   .= setModul(); 
  }
 
?>



<!DOCTYPE html>
<html lang="hu">
  <head> 
     <meta charset="UTF-8">
     <meta http-equiv="content-type" content="text/html; charset=UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes">
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_alap.css" />  
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_szerkeszt.css" />  
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_responsive.css" />
     <link href='https://fonts.googleapis.com/css?family=Istok+Web:700,400&amp;subset=latin-ext,latin' rel='stylesheet' type='text/css'>
     <link  rel="icon" type="image/png" href="img/ikonok/FavIcon/<?php echo $AlapAdatok['FavIcon']; ?>">
     <?php echo getHead(); ?>
     
<script>     
// A menü gombot nagy felbontásnál, az oldal letöltése után eldugjuk. A menü látszik.
function MenuNagyFelbontasnal() { 
    if (parseInt(window.innerWidth) > 1500) {
     document.getElementById('MenuLabel').style.display='none';
     document.getElementById('chmenu').checked=0;
    }
}
// A menüt kis felbontásnál, az oldal letöltése után eldugjuk
function MenuKisFelbontasnal() { 
    if (parseInt(window.innerWidth) < 800) {
     document.getElementById('chmenu').checked=1;
    }
}
// Az oldal letöltését követően hívandó JS fgv-ek
function JSonLoad()
{
   MenuNagyFelbontasnal();
   MenuKisFelbontasnal();
}
</script>
     
  </head>
  <body onLoad='JSonLoad()'>	  
     <div id='Keret'> 
       <header id='FoHeder'>
		   <a href="./" id="logoImgLink"><img src="img/ikonok/HeaderImg/<?php echo $AlapAdatok['HeaderImg']; ?>" alt="logó" title="Oldal neve" style="float:left;"></a>
		   <a href="./" id="logoLink"> <?php echo $AlapAdatok['HeaderStr']; ?></a>
	   </header>
	   <input name="chmenu" id="chmenu" value="chmenu" type="checkbox" style='display:none;'>
       <nav id='FoNav'> 
	 <div id='FoNavBal'>  
	   <label for="chmenu" class="MenusorElem" id="MenuLabel">
             <img src="img/ikonok/menu128.png" alt="Menü" title="Menü" style="float:left;" id="MenuIkon1">
             <img src="img/ikonok/menu228.png" alt="Menü" title="Menü" style="float:left;" id="MenuIkon2">     
             <span id="MENUGombDiv"><?php echo U_INDEX_MENU; ?> </span>
           </label>
         </div>
         <div id='FoNavJobb'>  
	     <?php echo getFoMenuHTML(); ?>
	 </div>  		    		 
       </nav>
           
       <?php if ($Aktoldal['OTipus']>100) { echo getModulSlider();} ?>
           
       <div id='BelsoKeret'>
		  <nav id='HelyiNav'>
                     <?php echo getMenuHTML()?>		  
		  </nav>		   
		  <div id='Tartalom'>
                        <?php echo getMorzsaHTML(); ?>
                        <?php echo getOldalHTML(); ?>
			
		  </div> 		     
		  <aside id='KiegeszitoInfo'><?php echo getKiegTHTML(); ?></aside>       
       </div>     
       <footer id='FoFooter'><?php echo getLablecHTML(); ?></footer>
     
     </div>
      
    <?php if (($AlapAdatok['GoogleKod']!='') && (strlen($AlapAdatok['GoogleKod'])>10)){
    echo "    
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', '".$AlapAdatok['GoogleKod']."', 'auto');
            ga('send', 'pageview');

        </script>";
    //UA-76662941-1
    
    }
   
    if (($AlapAdatok['FacebookOK']==2) || (($AlapAdatok['FacebookOK']==1)&& ($Aktoldal['OTipus'])==0)){
        echo "
            <div id='fb-root'></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = '//connect.facebook.net/hu_HU/sdk.js#xfbml=1&version=v2.6';
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>            
        ";    
    } 
    ?>
    
    
    <!-- Helyezd el ezt a címkét a head szakaszban vagy közvetlenül a záró body címke elé. -->
    <?php 
    if (($AlapAdatok['GooglePlus']==2) || (($AlapAdatok['GooglePlus']==1)&& ($Aktoldal['OTipus'])==0)){    
        echo "       
            <script src='https://apis.google.com/js/platform.js' async defer>
              {lang: 'hu'}
            </script>";
        } 
    ?>
    
  </body>

</html>
