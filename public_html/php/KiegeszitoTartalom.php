<?php
global $KiegTartalom;

$KiegTartalom = array();
$KiegTartalom['id']          = 0;
$KiegTartalom['KiegTNev']      = '';
$KiegTartalom['KiegTTartalom'] = '';
$KiegTartalom['KiegTPrioritas'] = 0;

function InitKiegT() {
  global $MySqliLink;
  // Ha nincs, akkor létrehozzuk a FoMenuLink tábla 10 rekordját
  $SelectStr = "SELECT id FROM KiegTartalom"; 
  $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba KTinit 01 ");
  $rowDB     = mysqli_num_rows($result); mysqli_free_result($result);
  if ($rowDB < 10) {
    for ($i=$rowDB;$i<10;$i++) {
      $InsertIntoStr = "INSERT INTO KiegTartalom VALUES ('', '', '', 0)"; //echo "<h1>$InsertIntoStr</h1>";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba FoMenuLinit 01 ");}         
    }        
  }           
}

function setKiegT() {
    global $MySqliLink;
    $ErrorStr       = "";  
    $KiegTNev       = "";
    $KiegTTartalom  = "";
    $KiegTPrioritas = 0;
    InitKiegT();
    
    if (isset($_POST['submitKiegTartalom'])) {
        for ($i = 0; $i < 10; $i++){
            $id = $_POST["ModKTid_$i"];
            if (!$_POST["TorolKiegT_$i"]){
                if (isset($_POST["ModKTNev_$i"])) {
                    $KiegTNev = $_POST["ModKTNev_$i"];
                }
                if (isset($_POST["ModKTTartalom_$i"]))  {
                    $KiegTTartalom  = $_POST["ModKTTartalom_$i"];
                }
                if (isset($_POST["ModKTPrioritas_$i"])) {
                    $KiegTPrioritas = $_POST["ModKTPrioritas_$i"];
                }

                $UpdateStr = "UPDATE KiegTartalom SET
                                KiegTNev       = '$KiegTNev',
                                KiegTTartalom  = '$KiegTTartalom',
                                KiegTPrioritas =  '$KiegTPrioritas'
                                WHERE id = '$id'";
                mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uUKT 2");
            } else {
                $UpdateStr = "UPDATE KiegTartalom SET
                                KiegTNev       = '',
                                KiegTTartalom  = '',
                                KiegTPrioritas =  0
                                WHERE id = '$id'";
                mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba uUKT 2");
            }
        }
    }
    return $ErrorStr;
}



function getKiegTForm() {
    if ($_SESSION['AktFelhasznalo'.'FSzint']>0)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
        
       global $MySqliLink, $KiegTartalom;
       $HTMLkod        = '';
       //$ErrorStr       = '';
        
      
        $SelectStr = "SELECT * FROM KiegTartalom";
        $result = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sKTT 01");

        for ($i = 0; $i < 10; $i++){
            $row = mysqli_fetch_array($result);
            $KiegTartalom['id']            = $row['id'];
            $KiegTartalom['KiegTNev']      = $row['KiegTNev'];
            $KiegTartalom['KiegTTartalom'] = $row['KiegTTartalom'];
            $KiegTartalom['KiegTPrioritas']= $row['KiegTPrioritas'];
            $KiegTTomb[]   = $KiegTartalom;
        }
        
        $HTMLkod .= "<div id='divModKiegTForm' >\n";
	$HTMLkod .= "<form action='?f0=kiegeszito_tartalom' method='post' id='formModKiegTForm'>\n";
        
        for ($i = 0; $i < 10; $i++){
            $id             = $KiegTTomb[$i]['id'];
            $KiegTNev       = $KiegTTomb[$i]['KiegTNev'];
            $KiegTTartalom  = $KiegTTomb[$i]['KiegTTartalom'];
            $KiegTPrioritas = $KiegTTomb[$i]['KiegTPrioritas'];
            
            $HTMLkod .= "<div class='divKiegTElem'>\n ";
            $HTMLkod .= "<p class='pKiegTid'>".$i.". rekord</p>\n ";            
            
            //Kiegészítő tartalom neve
            $HTMLkod .= "<p class='pModKTNev'><label for='ModKTNev_$i' class='label_1'>Módosított kiegészítő tartalom neve:</label><br>\n ";
            $HTMLkod .= "<input type='text' name='ModKTNev_$i' id='ModKTNev_$i' placeholder='$KiegTNev' value='$KiegTNev' size='40'></p>\n"; 

            //Kiegészítő tartalom tartalma
            $HTMLkod .= "<p class='pModKTTartalom'><label for='ModKTTartalom_$i' class='label_1'>Módosított kiegészítő tartalom tartalma:</label><br>\n ";
            $HTMLkod .= "<textarea type='text' name='ModKTTartalom_$i' id='ModKTTartalom_$i' placeholder='$KiegTTartalom' 
                         rows='4' cols='60'>$KiegTTartalom</textarea></p>\n"; 

            //Kiegészítő tartalom prioritása
            $HTMLkod .= "<p class='pModKTPrioritas'><label for='ModKTPrioritas_$i' class='label_1'>Prioritás:</label>\n ";
            $HTMLkod .= "<input type='number' name='ModKTPrioritas_$i' id='ModKTPrioritas_$i' min='0' max='9' step='1' value='$KiegTPrioritas'></p>\n";  
            
            //Törlésre jelölés
            $HTMLkod .= "<p class='pTorolKiegT'><label for='pTorolKiegT_$i' class='label_1'>TÖRLÉS:</label>\n ";
            $HTMLkod .= "<input type='checkbox' name='TorolKiegT_$i' id='TorolKiegT_$i'></p>\n";
            
            //id
            $HTMLkod .= "<input type='hidden' name='ModKTid_$i' id='ModKTid_$i' value='$id'>\n";
            $HTMLkod .= "</div>\n ";
        }
        
        //Submit
        $HTMLkod .= "<br style='clear:both;float:none;'>\n";
        $HTMLkod .= "<input type='submit' name='submitKiegTartalom' id='submitKiegTartalom' value='Módosítás'>\n";
        $HTMLkod .= "</form>\n";
        $HTMLkod .= "</div>\n";
        return $HTMLkod;
    }
}



function getKiegTHTML() {
    global $MySqliLink;
    $HTMLkod        = '';

    $SelectStr = "SELECT * FROM KiegTartalom WHERE KiegTPrioritas>0  ORDER BY KiegTPrioritas DESC";
    $result = mysqli_query($MySqliLink, $SelectStr) OR die("Hiba sKTT 01");

    while ($row = mysqli_fetch_array($result)){
        if ($row['KiegTTartalom']){
            $HTMLkod .= "<div class ='divKiegTKulso'>\n";
            if ($row['KiegTNev']!='') {$HTMLkod .= "<h2>".$row['KiegTNev']."</h2>\n";}
            $HTMLkod .= "<div class = 'divKiegT'>".$row['KiegTTartalom']."\n";
            $HTMLkod .= "</div></div>\n";
        }
    }
    return $HTMLkod;
}
