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

    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  

        //Jelszó ellenőrzése
        $HTMLkod .= "<div id='divCsoportValaszt' >\n";
        if ($ErrorStr!='') {
        $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
        
        $HTMLkod .= "<form action='?f0=Felhasznaloi_csoportok' method='post' id='formCsoportValaszt'>\n";
        $HTMLkod .= "<h2>".U_FCSOP_VALASZT."</h2>\n";

        $HTMLkod .= "<fieldset> <legend>".U_FCSOP_LISTA.":</legend>";
        //Felhasználó kiválasztása a lenyíló listából
        $HTMLkod .= "<select name='selectCsoportValaszt' size='1'>";

        $SelectStr     = "SELECT id, CsNev FROM FelhasznaloCsoport";  
        $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 01 ");
        $rowDB         = mysqli_num_rows($result);
        if ($rowDB > 0) {
            while($row = mysqli_fetch_array($result))
            {
                $CsNev      = $row['CsNev'];
                if($_SESSION['SzerkFCsoport'] == $row['id']){
                    $Select = " selected ";                    
                }else{
                    $Select = "";                    
                }

                $HTMLkod.="<option value='$CsNev' $Select >$CsNev</option>";
            }
            mysqli_free_result($result);
        }
        $HTMLkod .= "</fieldset>";
        //Submit
        $HTMLkod .= "<input type='submit' name='submitCsoportValaszt' value='".U_BTN_KIVALASZT."'><br><br>\n";        
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

    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $CsNev     = '';

        // ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitCsoportValaszt'])) {
            if (isset($_POST['selectCsoportValaszt'])) {
                $CsNev       = test_post($_POST['selectCsoportValaszt']);                
            }      
            if($CsNev!='') {
                $SelectStr   = "SELECT id FROM FelhasznaloCsoport WHERE CsNev='$CsNev' LIMIT 1"; 
                $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 02 ");
                $rowDB       = mysqli_num_rows($result); 
                if ($rowDB > 0) {                    
                    $row     = mysqli_fetch_array($result);  
                    $_SESSION['SzerkFCsoport'] = $row['id'];
                    mysqli_free_result($result); 
                }
            }
        }
    }
    return $ErrorStr;
}


// ============= Új felhasználó csoport létrehozása ============  
function setUjFCsoport() {
    global $MySqliLink;
    $ErrorStr  = ''; 
    $CsNev     = '';
    $CsLeiras  = '';		
    if (($_SESSION['AktFelhasznalo'.'FSzint']>4) &&  (isset($_POST['submitUjFCsoportForm']))){ 			
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
            $rowDB       = mysqli_num_rows($result);
            if ($rowDB > 0) { 
                $ErrorStr .= ' Err004,'; mysqli_free_result($result); // ilyen néven már van csoport  
                
            }  
        }                        
        if ($CsLeiras=='') {$ErrorStr  .= ' Err005 '; }            //nincs leírás       
			
        // ---------------- ADATOK TÁROLÁSA ---------------------
        if($ErrorStr ==''){
            $InsertIntoStr = "INSERT INTO FelhasznaloCsoport VALUES ('', '$CsNev','$CsLeiras')";
            if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba UCs 01 "); }
            $_SESSION['SzerkFCsoport'] = mysqli_insert_id($MySqliLink);
        } 		
    }
    return $ErrorStr;
}

function getUjFCsoportForm() {
    global $MySqliLink;
    
    $HTMLkod              = '';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $CsNev            = '';
        $CsLeiras         = '';
        $ErrClassCsNev    = '';
        $ErrClassCsLeiras = '';  
        $ErrorStr         = '';
        $InfoClass        = '';
	// ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitUjFCsoportForm'])) {
            if (isset($_POST['CsNev']))    {$CsNev        = test_post($_POST['CsNev']);}      
            if (isset($_POST['CsLeiras'])) {$CsLeiras     = test_post($_POST['CsLeiras']);}	   	  
            $ErrClassCsNev             = '';	   
            if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
            {
                $ErrClassCsNev         = ' Error '; 
                $ErrorStr             .= U_FNEV_NINCS."!<br>";
            }
            else 
            {
                if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
                    $ErrClassCsNev     = ' Error '; 
                    $ErrorStr         .= U_FNEV_HOSSZU."!<br>";
                }
                if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
                    $ErrClassCsNev     = ' Error '; 
                    $ErrorStr         .= U_FNEV_ROVID."!<br>";
                }
            } 
            
	    $ErrClassCsLeiras          = ''; 
   
	    if (strpos($_SESSION['ErrorStr'],'Err004')!==false) 
	    {
                $ErrClassCsNev         = ' Error '; 
                $ErrorStr             .= U_FCSOP_MARVAN."!<br>";
            }
            if (strpos($_SESSION['ErrorStr'],'Err005')!==false) 
	    {
                $ErrClassCsLeiras      = ' Error '; 
                $ErrorStr             .= U_LEIRAS_NINCS."!<br>";
            }
            
            if ($_SESSION['ErrorStr'] == '' ){
                $ErrorStr              = "<p class='time'>".U_FCSOP_LETREHOZVA.":".date("H.i.s.")."<p>".$ErrorStr; 
            } else {
                $ErrorStr              = "<p class='time'>".U_ELKULDVE.":".date("H.i.s.")."<p>".$ErrorStr;
            }
            if (strpos($_SESSION['ErrorStr'],'Err')!==false)
                          {$InfoClass  = ' ErrorInfo ';} else {$InfoClass  = ' OKInfo ';} 
	}	
		
        // ============== FORM ÖSSZEÁLLÍTÁSA ===================== 
	$HTMLkod .= "<div id='divUjFCsoportForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<div class='$InfoClass'>$ErrorStr</div>";}    

	$HTMLkod .= "<form action='?f0=Felhasznaloi_csoportok' method='post' id='formUjFCsoportForm'>\n";
        $HTMLkod .= "<h2>".U_FCSOP_LETREHOZ."</h2>\n";
        $HTMLkod .= "<fieldset> <legend>".U_FCSOP_ADATAI.":</legend>";  	   
        //Felhasználó neve    
        $HTMLkod .= "<p class='pCsNev'><label for='CsNev' class='label_1'>".U_NEV.":</label><br>\n ";
	$HTMLkod .= "<input type='text' name='CsNev' class='$ErrClassCsNev' id='CsNev' placeholder='".U_NEV."' value='$CsNev' size='40'></p>\n"; 
	
	//Felhasználó felhasználói neve    
        $HTMLkod .= "<p class='pCsLeiras'><label for='CsLeiras' class='label_1'>".U_LEIRAS.": </label><br>\n ";
	$HTMLkod .= "<textarea type='text' name='CsLeiras' id='CsLeiras' class='$ErrClassCsLeiras' placeholder='".U_LEIRAS."'";
	$HTMLkod .= "rows='4' cols='60'>$CsLeiras</textarea></p>\n";

        $HTMLkod .= "</fieldset>";
        //Submit
        $HTMLkod .= "<input type='submit' name='submitUjFCsoportForm' value='".U_BTN_LETRHOZAS."'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";   
    }        

    return $HTMLkod;

}
// ============= Felhasználó csoport adatainak módosítása ============     
function setFCsoport() {
    global $MySqliLink;
    $ErrorStr  = ''; 
    $ErrorStr .= setCsoportValaszt();
    $CsNev     = '';
    $CsLeiras  = '';	
    $FId       = 0;
    if (($_SESSION['AktFelhasznalo'.'FSzint']>4) &&  (isset($_POST['submitFCsoportForm']))){ 			
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
            if ($rowDB   > 0) { 	
		$row     = mysqli_fetch_array($result);
		if($_SESSION['SzerkFCsoport']!=$row['id'])
		{
		    $ErrorStr .= ' Err004 ';  //van ilyen néven már csoport   
		} else {
                    $FId = $row['id'];
                    
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
    return $ErrorStr;
}

function getFCsoportForm() {
	global $MySqliLink;
	$HTMLkod          = '';
        $InfoClass        = '';
        $ErrorStr         = $_SESSION['ErrorStr'];

	if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
		     
		$HTMLkod .= getCsoportValasztForm();

		if($_SESSION['SzerkFCsoport']>0)
		{
			// ============== FORM KIVÁLASZTÁSA ===================== 
			if(isset($_POST['submitUjFCsoportForm']))
			{$checked = " checked ";} else {$checked = "";}	
			$HTMLkod  .= "  <input name='chCsoportForm'   id='chUjFCsoportForm'   value='chUjFCsoportForm'   type='radio' $checked >\n";
			$HTMLkod  .= "  <label for='chUjFCsoportForm'    class='chLabel'    id='labelUjFCsoportForm'>".U_FCSOP_LETREHOZ."</label>\n";
			if(isset($_POST['submitFCsoportForm']))
			{$checked = " checked ";} else {$checked = "";}		
			$HTMLkod  .= "  <input name='chCsoportForm'   id='chFCsoportForm'   value='chFCsoportForm'   type='radio' $checked >\n";
			$HTMLkod  .= "  <label for='chFCsoportForm'    class='chLabel'    id='labelFCsoportForm'>".U_FCSOP_MODOSIT."</label>\n";
                	if(isset($_POST['submitFCsoportTorol']))
			{$checked = " checked ";} else {$checked = "";}			
			$HTMLkod  .= "  <input name='chCsoportForm'   id='chFCsoportTorolForm'  value='chFCsoportTorolForm'  type='radio' $checked >\n";
			$HTMLkod  .= "  <label for='chFCsoportTorolForm'   class='chLabel'    id='labelFCsoportTorolForm'>".U_FCSOP_TORLES."</label>\n \n";
		} 
		else 
		{            
			$HTMLkod  .= "  <input name='chCsoportForm'   id='chUjFCsoportForm'   value='chUjFCsoportForm'   type='radio' >\n";
			$HTMLkod  .= "  <label for='chUjFCsoportForm'    class='chLabel'    id='labelUjFCsoportForm'>".U_FCSOP_LETREHOZ."</label>\n";
			$HTMLkod  .= "  <input name='chCsoportForm'   id='chFCsoportTorolForm'  value='chFCsoportTorolForm'  type='radio'>\n";
			$HTMLkod  .= "  <label for='chFCsoportTorolForm'   class='chLabel'    id='labelFCsoportTorolForm'>".U_FCSOP_TORLES."</label>\n \n";
		}	      			  
		if($_SESSION['SzerkFCsoport']>0)
		{
			$FId              = $_SESSION['SzerkFCsoport']; 
			$SelectStr        = "SELECT * FROM FelhasznaloCsoport WHERE id='$FId' LIMIT 1"; 
			$result           = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 03 ");
                        $rowDB            = mysqli_num_rows($result); 
                        if ($rowDB > 0) {
                            $row          = mysqli_fetch_array($result);  mysqli_free_result($result);
                            $CsNev        = $row['CsNev'];
                            $CsLeiras     = $row['CsLeiras'];                            
                        }    
			$ErrClassCsNev    = '';
			$ErrClassCsLeiras = '';

			// ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
			if (isset($_POST['submitFCsoportForm'])) {
				if (isset($_POST['CsNev']))    {$CsNev     = test_post($_POST['CsNev']);}  
				if (isset($_POST['CsLeiras'])) {$CsLeiras  = test_post($_POST['CsLeiras']);} 
				$ErrClassCsNev                = '';	   
				if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
				{
					$ErrClassCsNev        = ' Error '; 
					$ErrorStr            .= U_FNEV_NINCS."!<br>";
				}
				else 
				{
					if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
						$ErrClassCsNev = ' Error '; 
						$ErrorStr     .= U_FNEV_HOSSZU."!<br>";
					}
					if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
						$ErrClassCsNev = ' Error '; 
						$ErrorStr     .= U_FNEV_HOSSZU."!<br>";
					}
			    	} 
			    
				$ErrClassCsLeiras         = '';
				if (strpos($_SESSION['ErrorStr'],'Err004')!==false) 
				{
					$ErrClassCsNev    = ' Error '; 
					$ErrorStr        .= U_FCSOP_MARVAN."!<br>";
				}
				if (strpos($_SESSION['ErrorStr'],'Err005')!==false) 
				{
					$ErrClassCsLeiras  = ' Error '; 
					$ErrorStr         .= U_LEIRAS_NINCS."!<br>";
				}
			    
				if ($_SESSION['ErrorStr'] == '' ){
                                    $ErrorStr              = "<p class='time'>".U_MODOSITVA.": ".date("H.i.s.")."<p>".$ErrorStr; 
                                } else {
                                    $ErrorStr              = "<p class='time'>".U_ELKULDVE.": ".date("H.i.s.")."<p>".$ErrorStr;
                                }
                                
                                if (strpos($_SESSION['ErrorStr'],'Err')!==false)
                                     {$InfoClass  = ' ErrorInfo ';} else {$InfoClass  = ' OKInfo ';} 
			}
		}

			// ============== FORM ÖSSZEÁLLÍTÁSA ===================== 
		$HTMLkod .= "<div id='divFCsoportForm' >\n";
		if ($ErrorStr!='') {$HTMLkod .= "<div class='$InfoClass'>$ErrorStr</div>";}    

		$HTMLkod .= "<form action='?f0=Felhasznaloi_csoportok' method='post' id='formFCsoportForm'>\n";
		$HTMLkod .= "<h2>".U_FCSOP_ADATMODOSIT."</h2>\n";  
                $HTMLkod .= "<fieldset> <legend>".U_FCSOP_ADATAI.":</legend>";
		//Felhasználó neve    
		$HTMLkod .= "<p class='pCsNev'><label for='CsNev' class='label_1'>".U_NEV.":</label><br>\n ";
		$HTMLkod .= "<input type='text' name='CsNev' class='$ErrClassCsNev' id='CsNev' placeholder='".U_NEV."' value='$CsNev' size='40'></p>\n"; 
	
		//Felhasználó felhasználói neve    
		$HTMLkod .= "<p class='pCsLeiras'><label for='CsLeiras' class='label_1'>".U_LEIRAS.": </label><br>\n ";
		$HTMLkod .= "<textarea type='text' name='CsLeiras' id='CsLeiras' class='$ErrClassCsLeiras' placeholder='".U_LEIRAS."'";
		$HTMLkod .= "rows='4' cols='60'>$CsLeiras</textarea></p>\n";

                $HTMLkod .= "</fieldset>";
		//Submit
		$HTMLkod .= "<input type='submit' name='submitFCsoportForm' value='".U_BTN_MODOSITAS."'><br>\n";        
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
    $ErrorStr          = '';      
    if (($_SESSION['AktFelhasznalo'.'FSzint']>4) && isset($_POST['submitFCsoportTorol'])) { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
	$SelectStr     = "SELECT id FROM FelhasznaloCsoport";  
        $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFCsT 01 ");
        $rowDB         = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            while ($row    = mysqli_fetch_array($result)) {	
                $i         = $row['id'];            
                if ($i>1) { 
                    if (isset($_POST["CsoportTorolId_$i"])){
                        $id     = test_post($_POST["CsoportTorolId_$i"]);
                    } else {$id = 0;}
                    if (isset($_POST["CsoportTorol_$i"]) && ($_POST["CsoportTorol_$i"]==$id )) {
                        $DeleteStr = "DELETE FROM FelhasznaloCsoport WHERE id = $id"; 
                        mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sCsT 02 ");
                        $DeleteStr = "DELETE FROM FCsoportTagok WHERE CSid = $id"; 
                        mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sCsT 03 ");
                        $DeleteStr = "DELETE FROM OLathatosag WHERE CSid = $id"; 
                        mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sCsT 04 ");
                    }  
                }
           }
           mysqli_free_result($result);
        }
    }
    return $ErrorStr;  
}
	
function getFCsoportTorolForm() {
    global $MySqliLink;
    $ErrorStr     = $_SESSION['ErrorStr'];
    $HTMLkod      = '';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $HTMLkod .= "<div id='divFCsoportTorol' >\n";
        if ($ErrorStr!='') {
        $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
        $HTMLkod .= "<form action='?f0=Felhasznaloi_csoportok' method='post' id='formFCsoportTorol'>\n";
        $HTMLkod .= "<h2>".U_FCSOP_TORLESE."</h2>\n";
        $HTMLkod .= "<fieldset> <legend>".U_FCSOPOK_VALASZT.":</legend>";
        
        $SelectStr    = "SELECT id, CsNev FROM FelhasznaloCsoport";  //echo "<h1>$SelectStr</h1>";
        $result       = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFT 01 ");
        $rowDB        = mysqli_num_rows($result);
        if ($rowDB > 0) {
            while ($row   = mysqli_fetch_array($result)) {
                $CsNev    = $row['CsNev'];
                $id       = $row['id'];            
                //Törlésre jelölés
                $HTMLkod .= "<input type='checkbox' name='CsoportTorol_$id' id='CsoportTorol_$id' value='$id'>\n";
                $HTMLkod .= "<label for='CsoportTorol_$id' class='label_1'>$CsNev</label><br>\n";
                //id
                $HTMLkod .= "<input type='hidden' name='CsoportTorolId_$id' id='CsoportTorolId_$id' value='$id'>\n";
             }
            mysqli_free_result($result);
        }
        $HTMLkod .= "<input type='hidden' name='CsTorolDB' id='CsTorolDB' value='$rowDB'>\n";        
        $HTMLkod .= "</fieldset>";
        //Submit
        $HTMLkod .= "<input type='submit' name='submitFCsoportTorol' value='".U_BTN_TOROL."'><br>\n";        
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