<?php

$KiegTartalom = array();
        
$KiegTartalom['id'] = 0;
$KiegTartalom['KiegTNev'] = "";
$KiegTartalom['KiegTTartalom'] = "";
$KiegTartalom['KiegTPrioritas'] = 0;

  
    function setKiegT($KTid) {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }


 


    function setTorolKiegT() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

 
    function getKiegTForm() {
		
	global $KiegTartalom;
	$HTMLkod        = '';
	  $ErrorStr       = '';  
          $KiegTNev       = "";
          $KiegTTartalom  = "";
          $KiegTPrioritas = 0;         

	if ($_SESSION['AktFelhasznalo'.'FSzint']>0)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
            
	$KiegTNev           = $KiegTartalom['KiegTNev'];
        $KiegTTartalom      = $KiegTartalom['KiegTTartalom'];
        $KiegTPrioritas     = $KiegTartalom['KiegTPrioritas'];

        
        
        

        $HTMLkod .= "<div id='divKiegTForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

            $HTMLkod .= "<form action='?f0=kiegeszito_tartalom' method='post' id='formKiegTForm'>\n";

        //Kiegészítő tartalom neve

        $HTMLkod .= "<p class='pKiegTNev'><label for='KiegTNev' class='label_1'>Kiegészítő tartalom neve:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='KiegTNev' id='KiegTNev' placeholder='Kiegészítő tartalom név' value='$KiegTNev' size='40'></p>\n"; 

        //Kiegészítő tartalom tartalma

        $HTMLkod .= "<p class='pTartalom'><label for='Tartalom' class='label_1'>Kiegészítő tartalom tartalma:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Tartalom' id='Tartalom' placeholder='Kiegészítő tartalom' value='$KiegTTartalom' size='40'></p>\n"; 

        //Kiegészítő tartalom prioritása

        $HTMLkod .= "<p class='pPrioritas'><label for='Prioritas' class='label_1'>Prioritas:</label>\n ";
        $HTMLkod .= "<input type='number' name='Prioritas' id='Prioritas' min='0' max='20' step='1' value='$KiegTPrioritas'></p>\n";  

        //Submit
        $HTMLkod .= "<input type='submit' name='submitKiegTartalom' value='Módosítás'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";   

        return $HTMLkod;
	}
    }

   function setUjKiegT() {
       global $MySqliLink;
       if (isset($_POST['submitUjKiegTartalom'])) { echo "<h1>hhhhhhhhhhhhhhh</h1>";
          $HTMLkod        = '';
	  $ErrorStr       = '';  
          $KiegTNev       = "";
          $KiegTTartalom  = "";
          $KiegTPrioritas = 0; 
          $Kid = 0;
          
        $SelectStr   = "SELECT * FROM KiegTartalom  "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba OM 41 ");
        $rowDB       = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            $row   = mysqli_fetch_array($result);   
            $KiegTNev       = $row['KiegTNev'];
            $KiegTTartalom  = $row['KiegTTartalom'];
            $KiegTPrioritas = $row['KiegTPrioritas'];
            $Kid = $row['id'];
        }
        
        
            if (isset($_POST['KiegTNev']))         {$KiegTNev=test_post($_POST['KiegTNev']);}  
            if (isset($_POST['KiegTTartalom']))    {$KiegTTartalom=test_post($_POST['KiegTTartalom']);}  
            if (isset($_POST['KiegTPrioritas']))   {$KiegTPrioritas=test_post($_POST['KiegTPrioritas']);}
        
        if ($KiegTNev!='') {
            
            
            
        } else {
          $ErrorStr .= 'Err001';  // Nincs név
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
              $ErrClassONev = ' Error '; 
              $ErrorStr .= 'Hiányzik a kieg tartalom neve! ';
            } 
        
          $HTMLkod .= "<div id='divUjKiegTForm' >\n";
          if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

          $HTMLkod .= "<form action='?f0=kiegeszito_tartalom' method='post' id='formUjKiegTForm'>\n";

        //Új kiegészítő tartalom neve

        $HTMLkod .= "<p class='pUjKiegTNev'><label for='UjKiegTNev' class='label_1'>Új kiegészítő tartalom neve:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='UjKiegTNev' id='UjKiegTNev' placeholder='Új kiegészítő tartalom név' value='$KiegTNev' size='40'></p>\n"; 

        //Új kiegészítő tartalom tartalma

        $HTMLkod .= "<p class='pTartalom'><label for='Tartalom' class='label_1'>Új kiegészítő tartalom tartalma:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Tartalom' id='Tartalom' placeholder='Új kiegészítő tartalom' value='$KiegTTartalom' size='40'></p>\n"; 

        //Új kiegészítő tartalom prioritása

        $HTMLkod .= "<p class='pPrioritas'><label for='Prioritas' class='label_1'>Prioritas:</label>\n ";
        $HTMLkod .= "<input type='number' name='Prioritas' id='Prioritas' min='0' max='20' step='1' value='$KiegTPrioritas'></p>\n";  

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



?>
