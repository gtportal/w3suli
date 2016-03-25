<?php

/**
 *
 * @author   Szabó Máté, Guti Patrik, Bárczi Dávid 
 */
 function InitMenuPl() {
  global $MySqliLink;
  /* Ha nincs, akkor létrehozzuk a FoMenuLink tábla 10 rekordját*/
  $SelectStr = "SELECT id FROM MenuPlusz"; 
  $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba Mpinit 01 ");
  $rowDB     = mysqli_num_rows($result); mysqli_free_result($result);
  if ($rowDB < 10) {
    for ($i=$rowDB;$i<10;$i++) {
      $InsertIntoStr = "INSERT INTO MenuPlusz VALUES ('', '', '', 0)"; 
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba FoMenuLinit 01 ");}         
    }        
  }           
}

function getMenuPluszForm() {
    global $MySqliLink, $MenuPlTartalom;	
    $HTMLkod  = '';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
        $SelectStr = "SELECT * FROM MenuPlusz";
        $result = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sKTT 01");
        for ($i = 0; $i < 10; $i++){
            $row = mysqli_fetch_array($result);
            $MenuPlTartalom['id']             = $row['id'];
            $MenuPlTartalom['MenuPlNev']      = $row['MenuPlNev'];
            $MenuPlTartalom['MenuPlTartalom'] = $row['MenuPlTartalom'];
            $MenuPlTartalom['MenuPlPrioritas']= $row['MenuPlPrioritas'];
            $MenuPlTomb[]                     = $MenuPlTartalom;
        }
        
        $HTMLkod .= "<div id='divModMenuPlForm' >\n";
	$HTMLkod .= "<form action='?f0=MenuPlusz' method='post' id='formModMenuPlForm'>\n";
        
        for ($i = 0; $i < 10; $i++){
            $id             = $MenuPlTomb[$i]['id'];
            $MenuPlNev       = $MenuPlTomb[$i]['MenuPlNev'];
            $MenuPlTartalom  = $MenuPlTomb[$i]['MenuPlTartalom'];
            $MenuPlPrioritas = $MenuPlTomb[$i]['MenuPlPrioritas'];
            
            $HTMLkod .= "<div class='divMenuPlElem'>\n ";
            $HTMLkod .= "<p class='pMenuPlid'>".$i.". rekord</p>\n ";            
            
            //Kiegészítő tartalom neve
            $HTMLkod .= "<p class='pModMenuPlNev'><label for='ModMenuPlNev_$i' class='label_1'>Módosított kiegészítő tartalom neve:</label><br>\n ";
            $HTMLkod .= "<input type='text' name='ModMenuPlNev_$i' id='ModMenuPlNev_$i' placeholder='MenuPlNev' value='$MenuPlNev' size='40'></p>\n"; 

            //Kiegészítő tartalom tartalma
            $HTMLkod .= "<p class='pModMenuPlTartalom'><label for='ModMenuPlTartalom_$i' class='label_1'>Módosított kiegészítő tartalom tartalma:</label><br>\n ";
            $HTMLkod .= "<textarea type='text' name='ModMenuPlTartalom_$i' id='ModMenuPlTartalom_$i' placeholder='$MenuPlTartalom' 
                         rows='4' cols='60'>$MenuPlTartalom</textarea></p>\n"; 

            //Kiegészítő tartalom prioritása
            $HTMLkod .= "<p class='pModMenuPlPrioritas'><label for='ModMenuPlPrioritas_$i' class='label_1'>Prioritás:</label>\n ";
            $HTMLkod .= "<input type='number' name='ModMenuPlPrioritas_$i' id='ModMenuPlPrioritas_$i' min='0' max='9' step='1' value='$MenuPlPrioritas'></p>\n";  
            
            //Törlésre jelölés
            $HTMLkod .= "<p class='pTorolMP'><label for='TorolMenuPl_$i' class='label_1'>TÖRLÉS:</label>\n ";
            $HTMLkod .= "<input type='checkbox' name='TorolMenuPlTartalom_$i' id='TorolMenuPl_$i'></p>\n";
            
            //id
            $HTMLkod .= "<input type='hidden' name='ModMPid_$i' id='ModMPid_$i' value='$id'>\n";
            $HTMLkod .= "</div>\n ";
        }        
        //Submit
        $HTMLkod .= "<br style='clear:both;float:none;'>\n";
        $HTMLkod .= "<input type='submit' name='submitMenuPlTartalom' id='submitMenuPlTartalom' value='Módosítás'>\n";
        $HTMLkod .= "</form>\n";
        $HTMLkod .= "</div>\n";      
    }
    return $HTMLkod; 
    
}

function setMenuPlusz() {    
    global $MySqliLink;
    $ErrorStr        = "";  
    $MenuPlNev       = "";
    $MenuPlTartalom  = "";
    $MenuPlPrioritas = 0;
    InitMenuPl();
    
    if (isset($_POST['submitMenuPlTartalom'])) { 
        for ($i = 0; $i < 10; $i++){
            $id = $_POST["ModMPid_$i"];
            if (!isset($_POST["TorolMenuPlTartalom_$i"])){
                if (isset($_POST["ModMenuPlNev_$i"])) {
                    $MenuPlNev = $_POST["ModMenuPlNev_$i"];
                }
                if (isset($_POST["ModMenuPlTartalom_$i"]))  {
                    $MenuPlTartalom  = $_POST["ModMenuPlTartalom_$i"];
                }
                if (isset($_POST["ModMenuPlPrioritas_$i"])) {
                    $MenuPlPrioritas = $_POST["ModMenuPlPrioritas_$i"];
                }

                $UpdateStr = "UPDATE MenuPlusz SET
                                MenuPlNev       = '$MenuPlNev',
                                MenuPlTartalom  = '$MenuPlTartalom',
                                MenuPlPrioritas =  '$MenuPlPrioritas'
                                WHERE id = '$id'";
                mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uUKT 2");
            } else {
                $UpdateStr = "UPDATE MenuPlusz SET
                                MenuPlNev       = '',
                                MenuPlTartalom  = '',
                                MenuPlPrioritas =  0
                                WHERE id = '$id'";
                mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uUKT 2");
            }
        }
    }    
    return $ErrorStr;
    
}

function getMenuPluszHTML() {
    global $MySqliLink;
    $HTMLkod   = '';
    $SelectStr = "SELECT * FROM MenuPlusz WHERE MenuPlPrioritas>0 ORDER BY MenuPlPrioritas DESC";
    $result    = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sKTT 01");
    $rowDB     = mysqli_num_rows($result); 
    if ($rowDB > 0) { 
        while ($row = mysqli_fetch_array($result)){
            if ($row['MenuPlTartalom']!=''){
                $HTMLkod .= "<div class ='divMenuPlKulso'>\n";
                if ($row['MenuPlNev']!='') {$HTMLkod .= "<h2>".$row['MenuPlNev']."</h2>\n";}
                $HTMLkod .= "<div class = 'divMenuPlT'>".$row['MenuPlTartalom']."\n";
                $HTMLkod .= "</div></div>\n";
            }
        }
    }
    return $HTMLkod;
}

?>

