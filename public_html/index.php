<?php
  $oURL=''; $MySqliLink='';
    $Aktoldal       = array();
    $SzuloOldal     = array();
    $NagyszuloOldal = array();
    $DedSzuloId = 0;

  //MUNKAMENET INDÍTÁSA
  session_start();
  $mm_azon   = session_id(); 
  //Ha első bejelentkezés, akkor AktFelhasznalo tömb inicializálása
  //FSzint=1 > Látogató
  //FSzint=2 > Bejelentkezett felhasználó
  //FSzint=3 > Oldal moderátora
  //FSzint=4 > Rendszergazda
  //FSzint=5 > Kiemelt rendszergazda
  if (!isset($_SESSION['AktFelhasznalo'.'FSzint'])) {
      $_SESSION['AktFelhasznalo'.'FNev']   = '';
      $_SESSION['AktFelhasznalo'.'FFNev']  = '';
      $_SESSION['AktFelhasznalo'.'FEmail'] = '';
      $_SESSION['AktFelhasznalo'.'FSzint'] =  1;
      $_SESSION['AktFelhasznalo'.'FSzerep']= '';
      $_SESSION['AktFelhasznalo'.'FKep']   = '';  
      
      $_SESSION['ElozoOldalId']   = 1; 
  }  
  
  $_SESSION['ErrorStr']   = '';
  //ADATBÁZIS MEGNYITÁSA
  require_once("php/DB/Adatbazis.php");
  require_once("php/Init.php");  
  //Alapadatok lekérdezése
  require_once("php/Alapbeallitasok.php");
  $_SESSION['ErrorStr']   .= setAlapbeallitasok();  
  $AlapAdatok = getAlapbeallitasok();
  
  //BE- vagy KIJELENTKEZÉS; FELHASZNÁLÓI ADATOK MÓDOSÍTÁSA
  require_once("php/Felhasznalo.php");
  $_SESSION['ErrorStr']   .= setBelepes();
  $_SESSION['ErrorStr']   .= setKilepes();
  $_SESSION['ErrorStr']   .= setFelhasznalo();
  $_SESSION['ErrorStr']   .= setUjFelhasznalo();
  $_SESSION['ErrorStr']   .= setFelhasznaloTorol();    
  
  require_once("php/Oldal.php");
  require_once("php/FelhasznaloCsoport.php");  
  require_once("php/FCsoportTagok.php");
  require_once 'php/KiegeszitoTartalom.php';
  require_once 'php/OldalModerator.php';
  require_once 'php/Menu.php';
  require_once 'php/OldalCikkei.php';
  require_once 'php/AlapFgvek.php';
  require_once 'php/OldalKeptar.php';
  require_once 'php/morzsa.php';
  require_once 'php/Lablec.php';
  
  //AZ AKTUÁLIS OLDAL ADATAINAK BEOLVASÁSA  
  if (isset($_GET['f0'])) { $oURL = $_GET['f0'];} else { $oURL = '';}  
  getOldalData($oURL);  
  
  //A MODERÁTOR STÁTUSZ ELLENŐRZÉSE
  if (getOModeratorTeszt($Aktoldal['id']) > 0) {$_SESSION['AktFelhasznalo'.'FSzint'] =  3;}
  
  
  
  //FELHASZNÁLÓI CSOPORTADATOK MÓDOSÍTÁSA
  $_SESSION['ErrorStr']   .= setUjFCsoport();  
  $_SESSION['ErrorStr']   .= setFCsoport(); 
  $_SESSION['ErrorStr']   .= setFCsoportTorol(); 
  $_SESSION['ErrorStr']   .= setCsoportTagok(); 
  $_SESSION['ErrorStr']   .= setOModerator();
  
  //AZ OLDAL ADATAINAK MÓDOSÍTÁSA
  $_SESSION['ErrorStr']   .= setUjOldal();
  $_SESSION['ErrorStr']   .= setOldal();
  $_SESSION['ErrorStr']   .= setOldalTorol();  
  $_SESSION['ErrorStr']   .= setOldalKepek();
  $_SESSION['ErrorStr']   .= setOldalKepFeltolt();
  $_SESSION['ErrorStr']   .= setOldalKepTorol();
  
  
  //KIEGÉSZÍTŐ TARTALOM MÓDOSÍTÁSA
 $_SESSION['ErrorStr']   .= setUjKiegT();
 $_SESSION['ErrorStr']   .= setKiegT();
 $_SESSION['ErrorStr']   .= setTorolKiegT();
?>



<!DOCTYPE html>
<html lang="hu">
  <head> 
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes">
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_alap.css" />  
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_szerkeszt.css" />  
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_responsive.css" />
     <?php echo getHead(); ?>
  </head>
  <body>	  
     <div id='Keret'> 
       <header id='FoHeder'>
		   <a href="./" id="logoImgLink"><img src="img/shrek_k.png" alt="logó" title="Oldal neve" style="float:left;"></a>
		   <a href="./" id="logoImgLink">Az oldal neve</a>
	   </header>
	   <input name="chmenu" id="chmenu" value="chmenu" type="checkbox" style='display:none;'>
       <nav id='FoNav'> 
		 <div id='FoNavBal'>  
		   <label for="chmenu" class="MenusorElem" id="MenuLabel">
             <img src="img/ikonok/menu_k.png" alt="Menü" title="Menü" style="float:left;" id="MenuIkon1">
             <img src="img/ikonok/menu_z.png" alt="Menü" title="Menü" style="float:left;" id="MenuIkon2">     
             <span id="MENUGombDiv">Menü </span>
           </label>
         </div>
         <div id='FoNavJobb'>  
		   <a href='./' class='MPontLink'>Menüpont1</a>   
		   <a href='./' class='MPontLink'>Menüpont2</a>   
		   <a href='./' class='MPontLink'>Menüpont3</a> 
		 </div>  		    		 
       </nav>
       <div id='BelsoKeret'>
		  <nav id='HelyiNav'>
                     <?php echo getMenuHTML(); ?>
	        Helyi menü
            <ul class='Ul1'>
              <li><a href='./' class='AktLink'>Első szint</a> 
                <ul class='Ul2'>
                  <li><a href='./'>Második szint</a></li>
                  <li><a href='./' class='AktLink'>Második szint</a> 
                    <ul class='Ul3'>
                      <li><a href='./'>Harmadik szint</a></li>
                      <li><a href='./' class='AktLink'>Harmadik szint</a></li>                       
                    </ul>               
                  </li>
                  <li><a href='./'>Második szint</a></li>
                </ul>
              </li> 
              <li><a href='./'>Első szint</a>
                <ul class='Ul2'>
                  <li><a href='./'>Második szint</a></li>
                  <li><a href='./'>Második szint</a></li>
                  <li><a href='./'>Második szint</a></li> 
                </ul>   
              </li>  
              <li><a href='./'>Első szint</a></li>           
            </ul>		  
		  </nav>		   
		  <div id='Tartalom'>
                        <?php echo getMorzsaHTML(); ?>
                        <?php echo getOldalHTML(); ?>
			<section>  		
			 <article>   
				 <img src="img/Shrek_fierce.jpg" alt="leírás" title="1 cikk címe" style="float:left;">
				 <h2>1 cikk címe</h2>
		  "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
		   Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. <br><br>
		   Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
		   Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."	
		     </article>  
		     
		     
		     			 <article>   
				<img src="img/Shrek_1.jpg" alt="leírás" title="2 cikk címe" style="float:left;">			 
				 <h2>2. cikk címe</h2>
		  "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
		   Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
		   Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br><br>
		   Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."	
		     </article> 
		     </section>
		  </div> 		     
		  <aside id='KiegeszitoInfo'>Kiegészítő tartalmak</aside>       
       </div>     
       <footer id='FoFooter'><?php echo getLablecHTML(); ?></footer>
     
     </div>
  </body>
</html>
