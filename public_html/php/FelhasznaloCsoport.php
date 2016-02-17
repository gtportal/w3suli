<?php

$FelhasznaloCsoport = array();
$FelhasznaloCsoport['id']       = 0;
$FelhasznaloCsoport['CsNev']    = '';
$FelhasznaloCsoport['CsLeiras'] = '';

function getCsoportValasztForm() {
	// A felhasználóknál készített getFelhasznaloValasztForm() fgv-hez hasonló módon lehetővé teszi 
	// a választást a létező közül
	// A $_SESSION['SzerkFCsoport'] munkamenet változóban tároljuk az 
	// aktuális csoport id-jét	

    global $MySqliLink;
    $HTMLkod  = '';
    $ErrorStr = ''; 
    $CsNev    = '';

    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  

        //Jelszó ellenőrzése
        $HTMLkod .= "<div id='divCsoportValaszt' >\n";
        if ($ErrorStr!='') {
        $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

        $HTMLkod .= "<form action='?f0=Felhasznaloi_csoportok' method='post' id='formCsoportValaszt'>\n";

        //Felhasználó kiválasztása a lenyíló listából
        $HTMLkod .= "<select name='selectCsoportValaszt' size='1'>";

        $SelectStr   = "SELECT id, CsNev FROM FelhasznaloCsoport";  //echo "<h1>$SelectStr</h1>";
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 01 ");
        while($row = mysqli_fetch_array($result))
        {
            $CsNev = $row['CsNev'];
            if($_SESSION['SzerkFCsoport'] == $row['id']){$Select = " selected ";}else{$Select = "";}

            $HTMLkod.="<option value='$CsNev' $Select >$CsNev</option>";
        }	
        //Submit
        $HTMLkod .= "<input type='submit' name='submitCsoportValaszt' value='Kiválaszt'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";    

    }
           
    return $HTMLkod;
}    

function setCsoportValaszt() {
	// I.) A $_SESSION['SzerkFCsoport'] munkamenet változó beállítása, ha
	// a kiválasztó űrlapot elküdték

	global $MySqliLink;
	$ErrorStr = '';

	if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
	$CsNev     = '';

		// ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
		if (isset($_POST['submitCsoportValaszt'])) {

			if (isset($_POST['selectCsoportValaszt'])) {$CsNev = test_post($_POST['selectCsoportValaszt']);}      

			if($CsNev!='')
			{
				$SelectStr   = "SELECT id FROM FelhasznaloCsoport WHERE CsNev='$CsNev' LIMIT 1";  //echo "<h1>$SelectStr</h1>";
				$result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 02 ");
				$row         = mysqli_fetch_array($result);  mysqli_free_result($result);

				$_SESSION['SzerkFCsoport'] = $row['id'];
			}
		}
	}
	return $ErrorStr;
}


function initFCsoport() {
    global $MySqliLink;
    //Az oldalak táblában létrehzzuk a "Felhasználói csoportok" nevű oldalt
    //Késöbb átkerül az Init.php-ba !!!!

    $SelectStr   = "SELECT id FROM Oldalak WHERE OTipus=20 ";  //echo "<h1>$SelectStr</h1>";
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba FCSi 01aa ");
    $rowDB       = mysqli_num_rows($result); 
    if ($rowDB == 0) {
        //Kezdőlap létrehozása
        $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Felhasználói csoportok','Felhasznaloi_csoportok',1,1,'Oldal leírása',
                          'Oldal kulcsszavai',1,20,'Oldal tartalma','','')";        
        if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 01 ");} 
    }
}


// ============= Új felhasználó csoport létrehozása ============  
function setUjFCsoport() {

    global $MySqliLink;
    $ErrorStr = ''; 
    //initFCsoport();
	
    $CsNev     = '';
    $CsLeiras  = '';

		
    if (($_SESSION['AktFelhasznalo'.'FSzint']>3) &&  (isset($_POST['submitUjFCsoportForm']))){ 			
        if (isset($_POST['CsNev']))   	    {$CsNev     = test_post($_POST['CsNev']);}
        if (isset($_POST['CsLeiras']))      {$CsLeiras  = test_post($_POST['CsLeiras']);}

        //----------------- HIBAKEZELÉS -------------------------			
        if($CsNev==''){$ErrorStr .= ' Err001 '; }              //nincs csoportnév
        else {                                                 // Megj. az $CsNev hosszát csak akkor ellenőrizük, ha 0-nál nagyobb
            if (strlen($CsNev)>40) { $ErrorStr .= ' Err002 ';}   //túl hosszú csoportnév
            if (strlen($CsNev)<2)  { $ErrorStr .= ' Err003 ';}   //túl rövid csoportnév
        }
			
        if ($ErrorStr=='') {                                   // Megj. az $CsNev-et csak akkor használjuk legérdezésben, ha nincs vele gond
            $SelectStr   = "SELECT * FROM FelhasznaloCsoport WHERE CsNev='$CsNev'"; 
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUCs 01 ");
            $rowDB       = mysqli_num_rows($result); mysqli_free_result($result);
            if ($rowDB > 0) { $ErrorStr .= ' Err004,';}          //van ilyen néven már csoport   
        }
                        
        if ($CsLeiras=='') {$ErrorStr  .= ' Err005 '; }            //nincs leírás
       
			
        // ---------------- ADATOK TÁROLÁSA ---------------------
        if($ErrorStr ==''){
            $InsertIntoStr = "INSERT INTO FelhasznaloCsoport VALUES ('', '$CsNev','$CsLeiras')";
            if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba UCs 01 "); }               
        } 		
    }	

    return $ErrorStr;

}




function getUjFCsoportForm() {

    global $MySqliLink;
    $HTMLkod   = '';
	
    if ($_SESSION['AktFelhasznalo'.'FSzint']>0)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $CsNev     = '';
        $CsLeiras  = '';


	// ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitUjFCsoportForm'])) {
            if (isset($_POST['CsNev']))    {$CsNev        = test_post($_POST['CsNev']);}      // Megj. test_post() használata !!!
            if (isset($_POST['CsLeiras'])) {$CsLeiras     = test_post($_POST['CsLeiras']);}
	   	  
            $ErrClassCsNev = '';	   
            if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
            {
                $ErrClassCsNev = ' Error '; 
                $ErrorStr    .= 'Hiányzik a csoport neve! ';
            }
            else 
            {
                if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
                    $ErrClassCsNev = ' Error '; 
                    $ErrorStr .= 'Túl hosszú a csoport neve! ';
                }
                if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
                    $ErrClassCsNev = ' Error '; 
                    $ErrorStr .= 'Túl rövid a csoport neve! ';
                }
            } 
            
	    $ErrClassCsLeiras = ''; 

	    if (strpos($_SESSION['ErrorStr'],'Err004')!==false) 
	    {
                $ErrClassCsNev = ' Error '; 
                $ErrorStr .= 'Létezik már ilyen csoport! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err005')!==false) 
	    {
                $ErrClassCsLeiras = ' Error '; 
                $ErrorStr .= 'Hiányzik a csoport leírása! ';
            }
            
            if($_SESSION['ErrorStr']==''){$ErrorStr='Sikeresen létrehozta a csoportot!';} 
	}	
		
        // ============== FORM ÖSSZEÁLLÍTÁSA ===================== 
	$HTMLkod .= "<div id='divUjFCsoportForm' >\n";
        if ($ErrorStr!='') {
            $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}    

	$HTMLkod .= "<form action='?f0=Felhasznaloi_csoportok' method='post' id='formUjFCsoportForm'>\n";
          	   
        //Felhasználó neve    
        $HTMLkod .= "<p class='pCsNev'><label for='CsNev' class='label_1'>A csoport neve:</label><br>\n ";
	$HTMLkod .= "<input type='text' name='CsNev' class='$ErrClassCsNev' id='CsNev' placeholder='Csoport neve' value='$CsNev' size='40'></p>\n"; 
	
	//Felhasználó felhasználói neve    
        $HTMLkod .= "<p class='pCsLeiras'><label for='CsLeiras' class='label_1'>A csoport leírása: </label><br>\n ";
	$HTMLkod .= "<textarea type='text' name='CsLeiras' id='CsLeiras' class='$ErrClassCsLeiras' placeholder='Csoport leírása'";
	$HTMLkod .= "rows='4' cols='60'>$CsLeiras</textarea></p>\n";

        //Submit
        $HTMLkod .= "<input type='submit' name='submitUjFCsoportForm' value='Csoport létrehozása'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";   
    }        

    return $HTMLkod;

}
// ============= Felhasználó csoport adatainak módosítása ============     
function setFCsoport() {
    global $MySqliLink;
    $ErrorStr = ''; 
    $ErrorStr .= setCsoportValaszt();

        global $MySqliLink;
    $ErrorStr = ''; 
    //initFCsoport();
	
    $CsNev     = '';
    $CsLeiras  = '';

		
    if (($_SESSION['AktFelhasznalo'.'FSzint']>3) &&  (isset($_POST['submitFCsoportForm']))){ 			
        if (isset($_POST['CsNev']))   	    {$CsNev     = test_post($_POST['CsNev']);}
        if (isset($_POST['CsLeiras']))      {$CsLeiras  = test_post($_POST['CsLeiras']);}

        //----------------- HIBAKEZELÉS -------------------------			
        if($CsNev==''){$ErrorStr .= ' Err001 '; }              //nincs csoportnév
        else {                                                 // Megj. az $CsNev hosszát csak akkor ellenőrizük, ha 0-nál nagyobb
            if (strlen($CsNev)>40) { $ErrorStr .= ' Err002 ';}   //túl hosszú csoportnév
            if (strlen($CsNev)<2)  { $ErrorStr .= ' Err003 ';}   //túl rövid csoportnév
        }
			
        if ($ErrorStr=='') {                                   // Megj. az $CsNev-et csak akkor használjuk legérdezésben, ha nincs vele gond
            $SelectStr   = "SELECT id FROM FelhasznaloCsoport WHERE CsNev='$CsNev'"; 
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 05 ");
            $rowDB       = mysqli_num_rows($result);
            if ($rowDB > 0) { 	
		$row = mysqli_fetch_array($result);	

		if($_SESSION['SzerkFCsoport']!=$row['id'])
		{
		    $ErrorStr .= ' Err004 ';  //van ilyen néven már csoport   
		}
		mysqli_free_result($result);
	    } 
        }
                        
        if ($CsLeiras=='') {$ErrorStr  .= ' Err005 '; }            //nincs leírás

        // ---------------- ADATOK TÁROLÁSA ---------------------
        if($ErrorStr ==''){
            $UpdateStr = "UPDATE FelhasznaloCsoport SET CsNev='$CsNev', CsLeiras='$CsLeiras' WHERE id='$FId'";    
            if (!mysqli_query($MySqliLink,$UpdateStr)) {die("Hiba sCsV 04 "); }               
        } 
		}
		//echo "<h1>ErrorStr: $ErrorStr</h1>";
    return $ErrorStr;
}

function getFCsoportForm() {
	global $MySqliLink;
	$HTMLkod   = '';

	if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
		     
		$HTMLkod .= getCsoportValasztForm();

		if($_SESSION['SzerkFCsoport']>0)
		{
			// ============== FORM KIVÁLASZTÁSA ===================== 
			if(isset($_POST['submitUjFCsoportForm']))
			{$checked = " checked ";} else {$checked = "";}			

			$HTMLkod  .= "  <input name='chCsoportForm'   id='chUjFCsoportForm'   value='chUjFCsoportForm'   type='radio' $checked >\n";
			$HTMLkod  .= "  <label for='chUjFCsoportForm'    class='chLabel'    id='labelUjFCsoportForm'>Csoport létrehozása</label>\n";


			if(isset($_POST['submitFCsoportForm']))
			{$checked = " checked ";} else {$checked = "";}			

			$HTMLkod  .= "  <input name='chCsoportForm'   id='chFCsoportForm'   value='chFCsoportForm'   type='radio' $checked >\n";
			$HTMLkod  .= "  <label for='chFCsoportForm'    class='chLabel'    id='labelFCsoportForm'>Csoport adatainak módosítása</label>\n";
	


			if(isset($_POST['submitFCsoportTorol']))
			{$checked = " checked ";} else {$checked = "";}	
		
			$HTMLkod  .= "  <input name='chCsoportForm'   id='chFCsoportTorolForm'  value='chFCsoportTorolForm'  type='radio' $checked >\n";
			$HTMLkod  .= "  <label for='chFCsoportTorolForm'   class='chLabel'    id='labelFCsoportTorolForm'>Csoport törlése</label>\n \n";
		} 
		else 
		{            

			$HTMLkod  .= "  <input name='chCsoportForm'   id='chUjFCsoportForm'   value='chUjFCsoportForm'   type='radio' >\n";
			$HTMLkod  .= "  <label for='chUjFCsoportForm'    class='chLabel'    id='labelUjFCsoportForm'>Csoport létrehozása</label>\n";

			$HTMLkod  .= "  <input name='chCsoportForm'   id='chFCsoportTorolForm'  value='chFCsoportTorolForm'  type='radio'>\n";
			$HTMLkod  .= "  <label for='chFCsoportTorolForm'   class='chLabel'    id='labelFCsoportTorolForm'>Csoport törlése</label>\n \n";
		}		        
				  
		if($_SESSION['SzerkFCsoport']>0)
		{
			$FId = $_SESSION['SzerkFCsoport']; //echo "<h1>HÚÚÚÚÚÚÚÚÚÚÚ</h1>";

			$SelectStr = "SELECT * FROM FelhasznaloCsoport WHERE id='$FId' LIMIT 1"; //echo $SelectStr;
			$result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 03 ");
			$row       = mysqli_fetch_array($result);  mysqli_free_result($result);

			$CsNev     = $row['CsNev'];
			$CsLeiras  = $row['CsLeiras'];

			$ErrClassCsNev = '';
			$ErrClassCsLeiras = '';

			// ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
			if (isset($_POST['submitFCsoportForm'])) {
				if (isset($_POST['CsNev']))   {$CsNev     = test_post($_POST['CsNev']);}  
				if (isset($_POST['CsLeiras']))   {$CsLeiras   = test_post($_POST['CsLeiras']);} 
					
					
				$ErrClassCsNev = '';	   
				if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
				{
					$ErrClassCsNev = ' Error '; 
					$ErrorStr    .= 'Hiányzik a csoport neve! ';
				}
				else 
				{
					if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
						$ErrClassCsNev = ' Error '; 
						$ErrorStr .= 'Túl hosszú a csoport neve! ';
					}
					if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
						$ErrClassCsNev = ' Error '; 
						$ErrorStr .= 'Túl rövid a csoport neve! ';
					}
			    	} 
			    
				$ErrClassCsLeiras = ''; 

				if (strpos($_SESSION['ErrorStr'],'Err004')!==false) 
				{
					$ErrClassCsNev = ' Error '; 
					$ErrorStr .= 'Létezik már ilyen csoport! ';
				}
				if (strpos($_SESSION['ErrorStr'],'Err005')!==false) 
				{
					$ErrClassCsLeiras = ' Error '; 
					$ErrorStr .= 'Hiányzik a csoport leírása! ';
				}
			    
				if($_SESSION['ErrorStr']==''){$ErrorStr='Sikeresen módosította a csoportot!';} 
			}
		}

			// ============== FORM ÖSSZEÁLLÍTÁSA ===================== 
		$HTMLkod .= "<div id='divFCsoportForm' >\n";
		if ($ErrorStr!='') {
		    $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}    

		$HTMLkod .= "<form action='?f0=Felhasznaloi_csoportok' method='post' id='formFCsoportForm'>\n";
		  	   
		//Felhasználó neve    
		$HTMLkod .= "<p class='pCsNev'><label for='CsNev' class='label_1'>A csoport új neve:</label><br>\n ";
		$HTMLkod .= "<input type='text' name='CsNev' class='$ErrClassCsNev' id='CsNev' placeholder='Csoport új neve' value='$CsNev' size='40'></p>\n"; 
	
		//Felhasználó felhasználói neve    
		$HTMLkod .= "<p class='pCsLeiras'><label for='CsLeiras' class='label_1'>A csoport új leírása: </label><br>\n ";
		$HTMLkod .= "<textarea type='text' name='CsLeiras' id='CsLeiras' class='$ErrClassCsLeiras' placeholder='Csoport új leírása'";
		$HTMLkod .= "rows='4' cols='60'>$CsLeiras</textarea></p>\n";

		//Submit
		$HTMLkod .= "<input type='submit' name='submitFCsoportForm' value='Csoport módosítása'><br>\n";        
		$HTMLkod .= "</form>\n";            
		$HTMLkod .= "</div>\n";   

		$HTMLkod .= getUjFCsoportForm();
		$HTMLkod .= getFCsoportTorolForm();

	}
	return $HTMLkod;
}    
    
// ============= Felhasználó csoport törlése ============  
function setFCsoportTorol() {
    
    global $MySqliLink;
    $ErrorStr = '';

    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 

        // ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitFCsoportTorol'])) {
		
		$CsNev       = '';
				
                $SelectStr   = "SELECT CsNev FROM FelhasznaloCsoport"; 
                $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsT 01 ");
                
                while($row = mysqli_fetch_array($result)){
					
			$CsNev = $row['CsNev'];
			$temp = getTXTtoURL($CsNev);

			while(strpos($temp,' ')) { $temp = getTXTtoURL($temp);}

			if(isset($_POST[$temp])){
			$DeleteStr = "DELETE FROM FelhasznaloCsoport WHERE CsNev='$CsNev'";  //echo "<h1>$DeleteStr</h1>";
			$result      = mysqli_query($MySqliLink,$DeleteStr) OR die("Hiba sCsT 02 ");
			}
		}               
        }
    }
    return $ErrorStr;  
}

function getFCsoportTorolForm() {
    global $MySqliLink;
    $HTMLkod   = '';

    $ErrorStr = ''; 
    $TorolCsoport = array();

    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  

        $HTMLkod .= "<div id='divFCsoportTorol' >\n";
        if ($ErrorStr!='') {
        $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

        $HTMLkod .= "<form action='?f0=Felhasznaloi_csoportok' method='post' id='formFCsoportTorol'>\n";

        $SelectStr   = "SELECT CsNev FROM FelhasznaloCsoport";  //echo "<h1>$SelectStr</h1>";
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFT 01 ");
        while($row = mysqli_fetch_array($result))
        {
            $CsNev = $row['CsNev'];
	    $temp = getTXTtoURL($CsNev);

	    while(strpos($temp,' ')) { $temp = getTXTtoURL($temp);}

	    $HTMLkod.="<input type='checkbox' name='$temp' id='$temp' value='$CsNev'>";
	    $HTMLkod.="<label for='$temp'>$CsNev</label><br>";

        }	
        
        //Submit
        $HTMLkod .= "<input type='submit' name='submitFCsoportTorol' value='Töröl'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";    
	}

    return $HTMLkod;
}      
    
// ============= Felhasználó csoport tagjainak listázása ============     
function getCsoportListaHTML() {
    global $MySqliLink;
    $HTMLkod   = '';
    
    return $HTMLkod;
}



?>