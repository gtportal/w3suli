<?php 
$AlapAdatok['WebhelyNev'] = 'Webhely neve'; 
$AlapAdatok['Iskola']     = '';
$AlapAdatok['Cim']        = '';
$AlapAdatok['Telefon']    = '';
$AlapAdatok['Stilus']     =  0;


function setAlapbeallitasok() {  
    global $MySqliLink, $AlapAdatok;
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>3) 
    { // FSzint-et növelni, ha működik a felhasználókezelés!!!  

        $WNev        = $AlapAdatok['WebhelyNev'];
        $Iskola      = $AlapAdatok['Iskola'];
        $Cim         = $AlapAdatok['Cim'];
        $Telefon     = $AlapAdatok['Telefon'];
        $Stilus      = $AlapAdatok['Stilus'];	

        if (isset($_POST['submitAlapbeallitasok'])) {  
            $WNev   =$_POST['WNev'];  
            $Iskola =$_POST['Iskola']; 
            $Cim    =$_POST['Cim'];  
            $Telefon=$_POST['Telefon']; 
            $Stilus =$_POST['Stilus'];

            $UpdateStr = "UPDATE AlapAdatok SET 
                                WebhelyNev='$WNev',
                                Iskola='$Iskola',
                                Cim='$Cim',
                                Telefon='$Telefon',
                                Stilus=$Stilus ";

            $result      = mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba sAb 01");
        }
    }  
}


function getAlapbeallitasForm() {
    global $AlapAdatok;
    $HTMLkod='';
    $ErrorStr = '';         

    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  {
        $WNev        = $AlapAdatok['WebhelyNev'];
        $Iskola      = $AlapAdatok['Iskola'];
        $Cim         = $AlapAdatok['Cim'];
        $Telefon     = $AlapAdatok['Telefon'];
        $Stilus      = $AlapAdatok['Stilus'];

        $HTMLkod .= "<div id='divAlapbeallitasForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

        $HTMLkod .= "<form action='?f0=alapbeallitasok' method='post' id='formAlapbeallitasForm'>\n";

        //Webhely neve
        $HTMLkod .= "<p class='pWNev'><label for='WNev' class='label_1'>A webhely neve:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='WNev' id='WNev' placeholder='Webhelynév' value='$WNev' size='40'></p>\n"; 

        //Iskola neve
        $HTMLkod .= "<p class='pIskola'><label for='Iskola' class='label_1'>Az iskola neve:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Iskola' id='Iskola' placeholder='Iskola neve' value='$Iskola' size='40'></p>\n"; 

        //Iskola címe
        $HTMLkod .= "<p class='pCim'><label for='Cim' class='label_1'>Az iskola címe:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Cim' id='Cim' placeholder='Iskola címe' value='$Cim' size='40'></p>\n"; 

        //Iskola telefonszám
        $HTMLkod .= "<p class='pTelefon'><label for='Telefon' class='label_1'>Az iskola telefonszáma:</label><br>\n ";
        $HTMLkod .= "<input type='text' name='Telefon' id='Telefon' placeholder='Iskola telefonszáma' value='$Telefon' size='40'></p>\n"; 

        //Stíluskiválasztó
        $HTMLkod .= "<p class='pStilus'><label for='Stilus' class='label_1'>Stilus:</label>\n ";
        $HTMLkod .= "<input type='number' name='Stilus' id='Stilus' min='0' max='13' step='1' value='$Stilus'></p>\n";  

        //Submit
        $HTMLkod .= "<input type='submit' name='submitAlapbeallitasok' value='Módosítás'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";   

        return $HTMLkod;
    }
}


function getAlapbeallitasok() {
    global $MySqliLink;

    $SelectStr   = "SELECT * FROM AlapAdatok LIMIT 1";     
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gAb 1"); 
    $row    = mysqli_fetch_array($result, MYSQLI_ASSOC); mysqli_free_result($result);

    return $row;
}

function getStyleClass() {
    trigger_error('Not Implemented!', E_USER_WARNING);
}
?>