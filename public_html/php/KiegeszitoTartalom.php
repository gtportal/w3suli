<?php

$KiegTartalom = array();
        
$KiegTartalom['id'] = 0;
$KiegTartalom['KiegTNev'] = "";
$KiegTartalom['KiegTTartalom'] = "";
$KiegTartalom['KiegTPrioritas'] = 0;

  
function setKiegT($KTid) {
}





function setTorolKiegT() {
    trigger_error('Not Implemented!', E_USER_WARNING);
}


function getKiegTForm() {
    
}

function setUjKiegT() {
    global $MySqliLink;
    if (isset($_POST['submitUjKiegTartalom'])) {
        $ErrorStr       = "";  
        $KiegTNev       = "";
        $KiegTTartalom  = "";
        $KiegTPrioritas = 0;

        if (isset($_POST['UjKiegTNev']) && strlen($_POST['UjKiegTNev']))  {
            $KiegTNev = $_POST['UjKiegTNev'];
            
            $SelectStr   = "SELECT * FROM KiegTartalom WHERE KiegTNev ='$KiegTNev'";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUKT 1 ");
            $rowDB       = mysqli_num_rows($result);
              if ($rowDB > 0) { $ErrorStr .= ' Err002,';}  //KiegTNev már létezik
              if (strlen($KiegTNev)>40) { $ErrorStr .= ' Err003,';}  //KiegTNev túl hosszú
              if (strlen($KiegTNev)<3) { $ErrorStr .= ' Err004,';}  //KiegTNev túl rövid
        } else {$ErrorStr .= ' Err001,';} //Nincs név
        
        if (isset($_POST['UjTartalom']) && strlen($_POST['UjTartalom']))  {
            $KiegTTartalom  = test_post($_POST['UjTartalom']);
        } else {$ErrorStr .= ' Err005,';} //Nincs tartalom
        
        if (isset($_POST['UjPrioritas'])) {$KiegTPrioritas = $_POST['UjPrioritas'];}
        
        if($ErrorStr == ""){
            $InsertStr = "INSERT INTO KiegTartalom VALUES ('', '$KiegTNev', '$KiegTTartalom', '$KiegTPrioritas')";
            mysqli_query($MySqliLink,$InsertStr) OR die("Hiba iUKT 2");
        }

        return $ErrorStr;
    }
}


function getUjKiegTForm() {
    if ($_SESSION['AktFelhasznalo'.'FSzint']>0)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
        $HTMLkod        = '';
        $ErrorStr       = '';  
        $KiegTNev       = "";
        $KiegTTartalom  = "";
        $KiegTPrioritas = 0;  

        if (strpos($_SESSION['ErrorStr'],'Err001')!==false) {
            $ErrorStr .= "Hiányzik a kiegészítő tartalom neve!\n";
        }
        if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
            $ErrorStr .= "Ilyen nevű kiegészítő tartalom már létezik!\n";
        }
        if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
            $ErrorStr .= "A kiegészítő tartalom neve túl hosszú!\n";
        }
        if (strpos($_SESSION['ErrorStr'],'Err004')!==false) {
            $ErrorStr .= "A kiegészítő tartalom neve túl rövid! \n";
        }
        if (strpos($_SESSION['ErrorStr'],'Err005')!==false) {
            $ErrorStr .= "Hiányzik a tartalom! \n";
        }

        $HTMLkod .= "<div id='divUjKiegTForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

        $HTMLkod .= "<form action='?f0=kiegeszito_tartalom' method='post' id='formUjKiegTForm'>\n";

        //Új kiegészítő tartalom neve

        $HTMLkod .= "<p class='pUjKiegTNev'><label for='UjKiegTNev' class='label_1'>Új kiegészítő tartalom neve:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='UjKiegTNev' id='UjKiegTNev' placeholder='Új kiegészítő tartalom név' value='$KiegTNev' size='40'></p>\n"; 

        //Új kiegészítő tartalom tartalma

        $HTMLkod .= "<p class='pUjTartalom'><label for='UjTartalom' class='label_1'>Új kiegészítő tartalom tartalma:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='UjTartalom' id='UjTartalom' placeholder='Új kiegészítő tartalom' value='$KiegTTartalom' size='40'></p>\n"; 

        //Új kiegészítő tartalom prioritása

        $HTMLkod .= "<p class='pUjPrioritas'><label for='UjPrioritas' class='label_1'>Prioritás:</label>\n ";
        $HTMLkod .= "<input type='number' name='UjPrioritas' id='UjPrioritas' min='0' max='20' step='1' value='$KiegTPrioritas'></p>\n";  

        //Submit
        $HTMLkod .= "<input type='submit' name='submitUjKiegTartalom' value='Létrehozás'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";   

        return $HTMLkod;
    }
}

function getTorolKiegTform($KTid) {
    trigger_error('Not Implemented!', E_USER_WARNING);
}


function getKiegTHTML() {
    trigger_error('Not Implemented!', E_USER_WARNING);
}