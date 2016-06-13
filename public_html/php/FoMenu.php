<?php 
/* 
    Author     : Wigmond Ádám
*/


function setFoMenu() {
  global $MySqliLink;
  $FoMenuLink = array();
  $ErrorStr   = '';
  
  // Adatbázisból feltöltjük a $FoMenuLink tömböt pl.>> $FoMenuLink[$i]['Linknév']
  if ($_SESSION['AktFelhasznalo'.'FSzint']>5)  {
    if (isset($_POST['submitFoMenu'])) {
          for ($i = 0; $i < 10; $i++){
              $id = INT_post($_POST["ModFoMenuid_$i"]);
              if (!isset($_POST["TorolFoMenu_$i"])){
                  if (isset($_POST["ModFoMenuNev_$i"])) {
                      $LNev = test_post($_POST["ModFoMenuNev_$i"]);
                  }
                  if (isset($_POST["ModFoMenuTartalom_$i"]))  {
                      $LURL  = test_post($_POST["ModFoMenuTartalom_$i"]);
                  }
                  if (isset($_POST["ModFoMenuPrioritas_$i"])) {
                      $LPrioritas = INT_post($_POST["ModFoMenuPrioritas_$i"]);
                  }
                  $UpdateStr = "UPDATE FoMenuLink SET
                                  LNev       = '$LNev',
                                  LURL       = '$LURL',
                                  LPrioritas =  '$LPrioritas'
                                  WHERE id   = '$id'";                     
                  mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uUKT 2");
              } else {
                  $UpdateStr = "UPDATE FoMenuLink SET
                                  LNev       = '',
                                  LURL       = '',
                                  LPrioritas =  0
                                  WHERE id    = '$id'";
                  mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uUKT 2");
              }
          }
      }
    }
    return $ErrorStr;
}


function getFoMenuForm() {
  global $MySqliLink;
  $FoMenuLink    = array();
  $FoMenuLinkTmb = array();
  $HTMLkod       = '';
  // Adatbázisból feltöltjük a $FoMenuLink tömböt pl.>> $FoMenuLink[$i]['Linknév']
  if ($_SESSION['AktFelhasznalo'.'FSzint']>5)  {
        $SelectStr = "SELECT * FROM FoMenuLink";
        $result    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba fml 01");
        $rowDB     = mysqli_num_rows($result); 
        if ($rowDB > 0) {            
            for ($i = 0; $i < 10; $i++){
                $row = mysqli_fetch_array($result);
                $FoMenuLink['id']        = $row['id'];
                $FoMenuLink['LNev']      = $row['LNev'];
                $FoMenuLink['LURL']      = $row['LURL'];
                $FoMenuLink['LPrioritas']= $row['LPrioritas'];
                $FoMenuLinkTmb[]         = $FoMenuLink;
            }
            mysqli_free_result($result); 

            $HTMLkod .= "<div id='divModFoMenuForm' >\n";
            $HTMLkod .= "<form action='?f0=Fomenu_linkek_beallitasa' method='post' id='formModFoMenuForm'>\n";
            $HTMLkod .= "<h2>".U_FOMENU_TITLE."</h2>\n";

            for ($i = 0; $i < 10; $i++){
                $id         = $FoMenuLinkTmb[$i]['id'];
                $LNev       = $FoMenuLinkTmb[$i]['LNev'];
                $LURL       = $FoMenuLinkTmb[$i]['LURL'];
                $LPrioritas = $FoMenuLinkTmb[$i]['LPrioritas'];

                $HTMLkod .= "<div class='divFoMenuElem'>\n ";
                $j        = $i+1;
                $HTMLkod .= "<fieldset> <legend>".$j.". ".U_FOMENU_LINK_ADATOK."</legend>";

                //Kiegészítő tartalom neve
                $HTMLkod .= "<p class='pModFoMenuNev'><label for='ModFoMenuNev_$i' class='label_1'>".U_FOMENU_LINK_FELIRAT.":</label><br>\n ";
                $HTMLkod .= "<input type='text' name='ModFoMenuNev_$i' id='ModFoMenuNev_$i' placeholder='".U_FOMENU_LINK_FELIRAT."' value='$LNev' size='40'></p>\n"; 

                //Kiegészítő tartalom tartalma
                $HTMLkod .= "<p class='pModFoMenuTartalom'><label for='ModFoMenuTartalom_$i' class='label_1'>".U_FOMENU_LINK_URL.":</label><br>\n ";
                $HTMLkod .= "<input type='text' name='ModFoMenuTartalom_$i' id='ModFoMenuTartalom_$i' placeholder='".U_FOMENU_LINK_URL_pl."' value='$LURL' size='40'></p>\n";

                //Kiegészítő tartalom prioritása
                $HTMLkod .= "<p class='pModFoMenuPrioritas'><label for='ModFoMenuPrioritas_$i' class='label_1'>".U_PRIORITAS.":</label>\n ";
                $HTMLkod .= "<input type='number' name='ModFoMenuPrioritas_$i' id='ModFoMenuPrioritas_$i' min='0' max='10' step='1' value='$LPrioritas'></p>\n";  

                //Törlésre jelölés
                $HTMLkod .= "<p class='pTorolFoMenu'><label for='pTorolFoMenu_$i' class='label_1'>".U_TORTLES.":</label>\n ";
                $HTMLkod .= "<input type='checkbox' name='TorolFoMenu_$i' id='TorolFoMenu_$i'></p>\n";

                //id
                $HTMLkod .= "<input type='hidden' name='ModFoMenuid_$i' id='ModFoMenuid_$i' value='$id'>\n";
                $HTMLkod .= "</fieldset>";
                $HTMLkod .= "</div>\n ";
            }

            //Submit
            $HTMLkod .= "<br style='clear:both;float:none;'>\n";
            $HTMLkod .= "<input type='submit' name='submitFoMenu' id='submitFoMenu' value='".U_BTN_MODOSITAS."'>\n";
            $HTMLkod .= "</form>\n";
            $HTMLkod .= "</div>\n";
            return $HTMLkod;
      }
  }
  return $HTMLkod;	
}

function getFoMenuHTML() {
    global $MySqliLink;
    $FoMenuLink = array();
    $HTMLkod    = ''; 
    // Adatbázisból feltöltjük a $FoMenuLink tömböt pl.>> $FoMenuLink[$i]['Linknév']
    // Összeállítjuk a Főmenű Linkjeinek listáját
    $SelectStr  = "SELECT * FROM FoMenuLink WHERE LPrioritas>0 ORDER BY LPrioritas DESC";
    $result     = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba fml 02");
    $rowDB      = mysqli_num_rows($result); 
    if ($rowDB > 0) { 
        while ($row = mysqli_fetch_array($result)){
            if ($row['LURL']){
                $HTMLkod .= "<a href='".$row['LURL']."' class ='MPontLink'>".$row['LNev']."</a>\n";            
            }
        }
        mysqli_free_result($result); 
    }
  return $HTMLkod;	
}

function getFoMenuPLHTML() {
    global $MySqliLink;
    $FoMenuLink = array();
    $HTMLkod    = ''; 
    // Adatbázisból feltöltjük a $FoMenuLink tömböt pl.>> $FoMenuLink[$i]['Linknév']
    // Összeállítjuk a Főmenű Linkjeinek listáját
    // <a href='LURL' class='MPontLink'>Menüpont1</a>  <a href='./' class='MPontLink'>Menüpont1</a>  ... formában
    $SelectStr  = "SELECT * FROM FoMenuLink WHERE LPrioritas>0 ORDER BY LPrioritas DESC";
    $result     = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba fml 02");
    $rowDB      = mysqli_num_rows($result); 
    if ($rowDB > 0) { 
        while ($row = mysqli_fetch_array($result)){
            if ($row['LURL']){
                $HTMLkod .= "<li class='M1'><a href='".$row['LURL']."' class ='MPontLink'>".$row['LNev']."</a></li>\n";            
            }
        } 
        mysqli_free_result($result);
    }
    if ($HTMLkod!='') { $HTMLkod="<ul class='Ul1' id='FoLinkHMenu'>$HTMLkod</ul>"; }
    return $HTMLkod;	
}

?>