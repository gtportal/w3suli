<?php 
//    $FoMenuLink['Linknév']    = ''; 
//    $FoMenuLink['URL']        = '';
//    $FoMenuLink['Sorrend']    = '';


function InitFoMenu() {
  global $MySqliLink;
  // Ha nincs, akkor létrehozzuk a FoMenuLink tábla 10 rekordját
  $SelectStr = "SELECT id FROM FoMenuLink"; 
  $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba FOMinit 01 ");
  $rowDB     = mysqli_num_rows($result); mysqli_free_result($result);
  if ($rowDB < 10) {
    for ($i=$rowDB;$i<=10;$i++) {
      $InsertIntoStr = "INSERT INTO FoMenuLink VALUES ('', '', '', 0)"; //echo "<h1>$InsertIntoStr</h1>";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba FoMenuLinit 01 ");}         
    }        
  }           
}


function setFoMenu() {
  global $MySqliLink;
  $FoMenuLink = array();
  InitFoMenu();   // Ez a fgv később átkerül a Setup.php-ba 
  
  // Adatbázisból feltöltjük a $FoMenuLink tömböt pl.>> $FoMenuLink[$i]['Linknév']

  // A KiegTartalom.php-hoz hasonlóan bekérjük a $_POST tömb tartalmát   
  
  // Frissítjük az adatbázis tartalmaát
            
}


function getFoMenuForm() {
  global $MySqliLink;
  $FoMenuLink = array();
  $HTMLkod1   = '';
 
  // Adatbázisból feltöltjük a $FoMenuLink tömböt pl.>> $FoMenuLink[$i]['Linknév']
  
  
  // A KiegTartalom.php-hoz hasonlóan összeállítjuk a FORM tartalmát
           
    
  return $HTMLkod;	
}

function getFoMenuHTML() {
  global $MySqliLink;
  $FoMenuLink = array();
  $HTMLkod1   = '';
 
  // Adatbázisból feltöltjük a $FoMenuLink tömböt pl.>> $FoMenuLink[$i]['Linknév']
  
  
  // Összeállítjuk a Főmenű Linkjeinek listáját
  // <a href='./' class='MPontLink'>Menüpont1</a>  <a href='./' class='MPontLink'>Menüpont1</a>  ... formában
           
    
  return $HTMLkod;	
}



?>