<?php

$AktFelhasznalo            = array();
$AktFelhasznalo['FNev']    = '';
$AktFelhasznalo['FFNev']   = '';
$AktFelhasznalo['FJelszo'] = '';
$AktFelhasznalo['FEmail']  = '';
$AktFelhasznalo['FSzint']  = 1;
$AktFelhasznalo['FSzerep'] = '';
$AktFelhasznalo['FKep']    = '';

   
// ============= Be és kijelentkezés ============
function setBelepes() {
    global $MySqliLink, $oURL; 
    $ErrorStr = '';	
    if (($_SESSION['AktFelhasznalo'.'FSzint']>0) &&  (isset($_POST['submitBelepesForm']))){
        if (isset($_POST['FFNev']))   {$FFNev    = test_post($_POST['FFNev']);}
        if (isset($_POST['FJelszo'])) {$FJelszo  = test_post($_POST['FJelszo']);}
			
        //----------------- HIBAKEZELÉS -------------------------			
        if($FFNev  ==''){$ErrorStr .= ' Err001 '; } //nincs felhasználónév megadva
        if($FJelszo==''){$ErrorStr .= ' Err002 '; } //nincs jelszó megadva
        else {$FJelszo = md5($FJelszo);}            //Megj. md5()-öt akkor használjuk, ha érkezett jelszó
                        
        if ($ErrorStr=='') { //Megj. Az adatbázisból csak akkor olvasunk, ha jók a feltételben szereplő adatok
            $SelectStr   = "SELECT * FROM Felhasznalok WHERE FFNev='$FFNev' AND FJelszo='$FJelszo' ";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sBe 01 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) {
                $row = mysqli_fetch_array($result);
                mysqli_free_result($result);
				
                $_SESSION['AktFelhasznalo'.'id']      = $row['id'];
                $_SESSION['AktFelhasznalo'.'FNev']    = $row['FNev'];
                $_SESSION['AktFelhasznalo'.'FFNev']   = $row['FFNev'];
                $_SESSION['AktFelhasznalo'.'FJelszo'] = $row['FJelszo'];
                $_SESSION['AktFelhasznalo'.'FEmail']  = $row['FEmail'];
                $_SESSION['AktFelhasznalo'.'FSzint']  = $row['FSzint'];
                $_SESSION['AktFelhasznalo'.'FSzerep'] = $row['FSzerep'];
                //$_SESSION['AktFelhasznalo'.'FKep']  = $row['FKep'];  
                
                // =================== VISSZATÉRÉS A BEJELENTKEZÉS ELŐTT LÁTOGATOTT OLDALRA =========================
                // Megj. Sikeres bejelentkezés után nem írjuk ki ismét a bejelentkező oldalt
                //       Az előző oldal ID-jét az $_SESSION['ElozoOldalId'] munkamenet változó tartalmazza
                $ElozoID     = $_SESSION['ElozoOldalId'];
                // Megj. Az előző ID alapján lekérjük a hozzátartozó $oURL-t, amely alapján az aktuális olda adatainak kezelése folyik 
                $SelectStr   = "SELECT OUrl FROM Oldalak WHERE id=$ElozoID ";  
                $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sBe 01aa ");
                $rowDB       = mysqli_num_rows($result); 
                if ($rowDB > 0) {
                    $row  = mysqli_fetch_array($result);
                    mysqli_free_result($result);
                    $oURL = $row['OUrl'];
                }
            } 
            else 
            {
                $ErrorStr .= ' Err003 '; //hibás felhasználónév vagy jelszó
            } 
        }
    }
    return $ErrorStr;
}	

function getBelepesForm() {
    $HTMLkod  = '';
    $ErrorStr = ''; 
		
    if ($_SESSION['AktFelhasznalo'.'FSzint']==1){ // FSzint-et növelni, ha működik a felhasználókezelés!!!					
        $FFNev           = '';
        $FJelszo         = '';
        $ErrClassFFNev   = '';
        $ErrClassFJelszo = '';         

        if (isset($_POST['submitBelepesForm'])) {
            if (isset($_POST['FFNev']))   {$FFNev   = test_post($_POST['FFNev']);}   // Megj. test_post()
            if (isset($_POST['FJelszo'])) {$FJelszo = test_post($_POST['FJelszo']);} 
		   		   
            $ErrClassFFNev   = '';
            $ErrClassFJelszo = ''; 
            
            //Felhasználó felhasználónevének ellenőrzése	
            if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
            {
                $ErrClassFFNev = ' Error '; 
                $ErrorStr     .= U_FNEV_NINCS."!<br>";
            }
		   
            //Jelszó ellenőrzése		   		   
            if (strpos($_SESSION['ErrorStr'],'Err002')!==false) 
            {
                $ErrClassFJelszo = ' Error '; 
                $ErrorStr       .= U_JELSZO_NINCS."!<br>";
            } 
 
            //Felhasználónév és jelszó együttes vizsgálata		   
            if (strpos($_SESSION['ErrorStr'],'Err003')!==false) 
            {
                $ErrClassFFNev   = ' Error '; 
                $ErrClassFJelszo = ' Error '; 
                $ErrorStr .= U_JELSZO_HIBAS."!<br>";
            }
        }	

        // ============== FORM ÖSSZEÁLLÍTÁSA ===================== 
        $HTMLkod .= "<div id='divBelepesForm' >\n";
        if ($ErrorStr!='') {
        $HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
        $HTMLkod .= "<form action='?f0=bejelentkezes' method='post' id='formBelepesForm'>\n";
		
        //Felhasználó felhasználói neve		
        $HTMLkod .= "<p class='pFFNev'><label for='FFNev' class='label_1'>".U_FFNEV.": </label><br>\n ";
        $HTMLkod .= "<input type='text' name='FFNev' class='$ErrClassFFNev' id='FFNev' placeholder='".U_FFNEV."' value='$FFNev' size='20'></p>\n"; 
		   
        //Jelszó		
        $HTMLkod .= "<p class='pJelszo1'><label for='FJelszo' class='label_1'>".U_JELSZO.": </label><br>\n ";
        $HTMLkod .= "<input type='password' name='FJelszo' class='$ErrClassFJelszo' id='FJelszo' placeholder='".U_JELSZO."' value='' size='20'></p>\n"; 
		
        //Submit
        $HTMLkod .= "<input type='submit' name='submitBelepesForm' value='".U_BELEPES."'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";        
    }
    return $HTMLkod;  //Megj. Mindig legyen visszatérési érték!
}

function setKilepes() {	
    global $MySqliLink, $oURL; 
    $ErrorStr = ''; 
    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) &&  ($oURL == 'kijelentkezes')){
        $_SESSION['AktFelhasznalo'.'id']      = 0;
        $_SESSION['AktFelhasznalo'.'FNev']    = "";
        $_SESSION['AktFelhasznalo'.'FFNev']   = "";
        $_SESSION['AktFelhasznalo'.'FJelszo'] = "";
        $_SESSION['AktFelhasznalo'.'FEmail']  = "";
        $_SESSION['AktFelhasznalo'.'FSzint']  = 1;
        $_SESSION['AktFelhasznalo'.'FSzerep'] = "";
        //$_SESSION['AktFelhasznalo'.'FKep']  = "";	
        
        // =================== VISSZATÉRÉS A KIJELENTKEZÉS ELŐTT LÁTOGATOTT OLDALRA =========================
        // Megj. Sikeres KIjelentkezés után nem írjuk ki a KIjelentkező oldalt
        //       Az előző oldal ID-jét az $_SESSION['ElozoOldalId'] munkamenet változó tartalmazza
        $ElozoID     = $_SESSION['ElozoOldalId'];
        // Megj. Az előző ID alapján lekérjük a hozzátartozó $oURL-t, amely alapján az aktuális olda adatainak kezelése folyik 
        $SelectStr   = "SELECT OUrl FROM Oldalak WHERE id=$ElozoID "; 
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sKILs 01aa ");
        $rowDB       = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            $row  = mysqli_fetch_array($result);
            $oURL = $row['OUrl'];
            mysqli_free_result($result);
        }
    }		
    return $ErrorStr;
}   
    
// ============= Új felhasználó ============     
function setUjFelhasznalo() {
    global $MySqliLink;
    $ErrorStr = ''; 		
    $FNev     = '';
    $FFNev    = '';
    $FJelszo  = '';
    $FJelszo2 = '';
    $FEmail   = '';
    $FEmail2  = '';
    $FSzint   = 0;
    $FSzerep  = '';
    $FKep     = '';
    $FCsoport = '';
		
    if (($_SESSION['AktFelhasznalo'.'FSzint']>4) &&  (isset($_POST['submitUjFelhasznaloForm']))){ 			
        if (isset($_POST['FNev']))   	    {$FNev     = test_post($_POST['FNev']);}
        if (isset($_POST['FFNev']))         {$FFNev    = test_post($_POST['FFNev']);}
        if (isset($_POST['FJelszo']))       {$FJelszo  = test_post($_POST['FJelszo']);}
        if (isset($_POST['FJelszo2']))      {$FJelszo2 = test_post($_POST['FJelszo2']);}
        if (isset($_POST['FEmail']))        {$FEmail   = test_post($_POST['FEmail']);}
        if (isset($_POST['FEmail2']))       {$FEmail2  = test_post($_POST['FEmail2']);}
        if (isset($_POST['FSzint']))        {$FSzint   = test_post($_POST['FSzint']);}
        if (isset($_POST['FSzerep']))       {$FSzerep  = test_post($_POST['FSzerep']);}
        if (isset($_POST['selectCsoportValaszt2'])) {$FCsoport = $_POST['selectCsoportValaszt2'];}
			
        //----------------- HIBAKEZELÉS -------------------------			
        if($FFNev==''){$ErrorStr .= ' Err004 '; }              //nincs felhasználónév
        else { // Megj. az $FFNev hosszát csak akkor ellenőrizük, ha 0-nál nagyobb
            if (strlen($FFNev)>40) { $ErrorStr .= ' Err005 ';} //túl hosszú felhasználónév
            if (strlen($FFNev)<5)  { $ErrorStr .= ' Err006 ';} //túl rövid felhasználónév
        }
			
        if ($ErrorStr=='') { // Megj. az $FFNev-et csak akkor használjuk lekérdezésben, ha nincs vele gond
            $SelectStr   = "SELECT * FROM Felhasznalok WHERE FFNev='$FFNev'"; 
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUF 01 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) { $ErrorStr .= ' Err007,'; mysqli_free_result($result);} //van ilyen néven már felhasználó   
        }
                        
        if ($FNev=='') {$ErrorStr  .= ' Err001 '; }            //nincs név
        else { // Megj. az $FNev hosszát csak akkor ellenőrizzük, ha 0-nál nagyobb 
            if (strlen($FNev)>40) { $ErrorStr .= ' Err002 ';} //túl hosszú a név
            if (strlen($FNev)<5)  { $ErrorStr .= ' Err003 ';} //túl rövid a név
        }
           
        if (strlen($FJelszo)>20)  { $ErrorStr .= ' Err008 ';} //túl hosszú jelszó
        if (strlen($FJelszo)<5)   { $ErrorStr .= ' Err009 ';} //túl rövid jelszó			
        if($FJelszo!=$FJelszo2)   { $ErrorStr .= ' Err010 ';} //nem egyeznek a jelszavak			
/* Email nem kötelező			
        if($FEmail=='')           { $ErrorStr .= ' Err011 ';} //nincs email cím megadva	
*/	
        if($FEmail!=$FEmail2)     { $ErrorStr .= ' Err012 ';} //nem egyeznek az email címek
        if (strlen($FEmail)>50)   { $ErrorStr .= ' Err014 ';} //túl hosszú email cím
         
        // ---------------- ADATOK TÁROLÁSA ---------------------
        if($ErrorStr ==''){
            $FJelszo           = md5($FJelszo); //Megj. Ha a bejelentkezésnél md5()-öt használunk, akkor itt is
            $InsertIntoStr     = "INSERT INTO Felhasznalok (FNev, FFNev, FJelszo, FEmail, FSzint, FSzerep, FKep)
                                                    VALUES ('$FNev','$FFNev','$FJelszo','$FEmail',$FSzint,'$FSzerep','$FKep')";
            if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba UF 01 "); }    
            
            $SelectStr         = "SELECT id FROM FelhasznaloCsoport WHERE CSNev='$FCsoport'"; 
            $result            = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba UF 02 ");  
            $rowDB             = mysqli_num_rows($result); 
            if ($rowDB > 0) {
                $row           = mysqli_fetch_array($result);   mysqli_free_result($result);         
                $CsId          = $row['id'];   
                $InsertIntoStr = "INSERT INTO FCsoportTagok (Fid, CSid, KapcsTip)
                                                     VALUES (LAST_INSERT_ID(),$CsId,0)";
                if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba UF 03 "); }  
            }    
        } 		
    } 
    return $ErrorStr;
}   
    
    
function getUjFelhasznaloForm() {
    global $MySqliLink;	
    $HTMLkod             = '';
    $ErrorStr            = ''; 	
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $FNev            = '';
        $FFNev           = '';
        $FJelszo         = '';
        $FJelszo2        = '';
        $FEmail          = '';
        $FEmail2         = '';
        $FSzint          = 0;
        $FSzerep         = '';
        $FKep            = '';
        $InfoClass       = '';
        $ErrClassFNev    = '';
        $ErrClassFFNev   = '';
        $ErrClassFEmail  = '';
        $ErrClassFJelszo = '';
          
	// ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitUjFelhasznaloForm'])) {
            if (isset($_POST['FNev']))    {$FNev    = test_post($_POST['FNev']);}
            if (isset($_POST['FFNev']))   {$FFNev   = test_post($_POST['FFNev']);}
            if (isset($_POST['FEmail']))  {$FEmail  = test_post($_POST['FEmail']);} 
            if (isset($_POST['FEmail2'])) {$FEmail2 = test_post($_POST['FEmail2']);}
            if (isset($_POST['FSzint']))  {$FSzint  = test_post($_POST['FSzint']);} 
            if (isset($_POST['FSzerep'])) {$FSzerep = test_post($_POST['FSzerep']);}
	   
            if (strpos($_SESSION['ErrorStr'],'Err')!==false)
                        {$InfoClass  = ' ErrorInfo ';} else {$InfoClass  = ' OKInfo ';} 
            //Felhasználó nevének ellenőrzése	  
            $ErrClassFNev = '';	   
            if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
            {
                $ErrClassFNev = ' Error '; 
                $ErrorStr    .= U_FNEV_NINCS."!<br>";
            } 
            else 
            {
                $ErrClassFNev = ''; 
                if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
                    $ErrClassFNev = ' Error '; 
                    $ErrorStr    .= U_FNEV_HOSSZU."!<br>";
                }
                if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
                    $ErrClassFNev = ' Error '; 
                    $ErrorStr    .= U_FNEV_ROVID."!<br>";
                }
            } 
	   
            //Felhasználó felhasználónevének ellenőrzése   
            $ErrClassFFNev = '';	
            if (strpos($_SESSION['ErrorStr'],'Err004')!==false) 
            {
                $ErrClassFFNev = ' Error '; 
                $ErrorStr .= U_FFNEV_NINCS;
            }
            else 
            {
                $ErrClassFFNev = '';
                if (strpos($_SESSION['ErrorStr'],'Err005')!==false) {
                    $ErrClassFFNev  = ' Error '; 
                    $ErrorStr      .= U_FFNEV_HOSSZU."!<br>";
                }
                if (strpos($_SESSION['ErrorStr'],'Err006')!==false) {
                    $ErrClassFFNev  = ' Error '; 
                    $ErrorStr      .= U_FFNEV_ROVID."!<br>";
                }
                if (strpos($_SESSION['ErrorStr'],'Err007')!==false) {
                    $ErrClassFFNev  = ' Error '; 
                    $ErrorStr      .= U_FFNEV_MARVAN."!<br>";
                }
            }
       
            //Jelszó ellenőrzése       
            $ErrClassFJelszo = '';       
            if (strpos($_SESSION['ErrorStr'],'Err010')!==false) 
            {
                $ErrClassFJelszo     = ' Error '; 
                $ErrorStr           .= U_JELSZO_KULONBOZO."! <br>";
            }            
            if (strpos($_SESSION['ErrorStr'],'Err008')!==false) {
                $ErrClassFJelszo = ' Error '; 
                $ErrorStr       .= U_JELSZO_HOSSZU."!<br>";
            }
            if (strpos($_SESSION['ErrorStr'],'Err009')!==false) {
                $ErrClassFJelszo = ' Error '; 
                $ErrorStr     .= U_JELSZO_ROVID."!<br>";
            }
       
            //Email cím ellenőrzése       
            $ErrClassFEmail = '';       
            if (strpos($_SESSION['ErrorStr'],'Err011')!==false) 
            {
                $ErrClassFEmail = ' Error '; 
                $ErrorStr .= U_EMAIL_NINCS."!<br>";
            } 
            else 
            {
                $ErrClassFEmail = '';
                if (strpos($_SESSION['ErrorStr'],'Err012')!==false) {
                    $ErrClassFEmail = ' Error '; 
                    $ErrorStr .= U_EMAIL_KULONBOZO."!<br>";
                }
                if (strpos($_SESSION['ErrorStr'],'Err014')!==false) 
                {
                    $ErrClassFEmail = ' Error '; 
                    $ErrorStr      .= U_EMAIL_HOSSZU."!<br>";
                }
            }       
            //if($_SESSION['ErrorStr']==''){$ErrorStr=U_REG_OK."!<br>";} 
            if ($_SESSION['ErrorStr'] == '' ){
                $ErrorStr        = "<p class='time'>".U_REG_OK.date("H.i.s.")."<p>".$ErrorStr; 
            } else {
                $ErrorStr        = "<p class='time'>".U_ELKULDVE.date("H.i.s.")."<p>".$ErrorStr;
            }
	}	
	if ($ErrClassFNev!='' || $ErrClassFFNev!='' || $ErrClassFJelszo!='' || $ErrClassFEmail!='')	
        {$InfoClass = "ErrorStr";} else {$InfoClass = "OKStr";} 
        // ============== FORM ÖSSZEÁLLÍTÁSA ===================== 	
        $HTMLkod .= "<div id='divUjFelhasznaloForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<div class='$InfoClass'>$ErrorStr</div>";}    
        $HTMLkod .= "<form action='?f0=regisztracio' method='post' id='formUjFelhasznaloForm'>\n";
        $HTMLkod .= "<h2> ".U_UFSET.":</h2>";
        $HTMLkod .= "<fieldset> <legend>".U_FADATOK."</legend>";
        //Felhasználó neve  
        $HTMLkod .= "<p class='pFNev'><label for='FNev' class='label_1'><b>".U_FNEV.":</b></label><br>\n ";
        $HTMLkod .= "<input type='text' name='FNev' class='$ErrClassFNev' id='FNev' placeholder='".U_FNEV."' value='$FNev' size='20' required ></p>\n"; 

        //Felhasználó felhasználói neve    
        $HTMLkod .= "<p class='pFFNev'><label for='FFNev' class='label_1'><b>".U_FFNEV.": </b></label><br>\n ";
        $HTMLkod .= "<input type='text' name='FFNev' class='$ErrClassFFNev' id='FFNev' placeholder='".U_FFNEV."' value='$FFNev' size='20' required ></p>\n"; 

        //Jelszó    
        $HTMLkod .= "<p class='pJelszo1'><label for='FJelszo' class='label_1'><b>".U_JELSZO.": </b></label><br>\n ";
        $HTMLkod .= "<input type='password' name='FJelszo' class='$ErrClassFJelszo' id='FJelszo' placeholder='".U_JELSZO."' value='$FJelszo' size='20' required ></p>\n"; 

        //Jelszó újra    
        $HTMLkod .= "<p class='pJelszo2'><label for='FJelszo2' class='label_1'><b>".U_JELSZO_ISMET.": </b></label><br>\n ";
        $HTMLkod .= "<input type='password' name='FJelszo2' class='$ErrClassFJelszo' id='FJelszo2' placeholder='".U_JELSZO_ISMET."' value='$FJelszo2' size='20' required ></p>\n"; 

        //Email cím
        $HTMLkod .= "<p class='pFEmail'><label for='FEmail' class='label_1'><b>".U_EMAIL.": </b></label><br>\n ";
        $HTMLkod .= "<input type='text' name='FEmail' class='$ErrClassFEmail' id='FEmail' placeholder='".U_EMAIL."' value='$FEmail' size='30'></p>\n"; 

        //Email cím újra
        $HTMLkod .= "<p class='pFEmail2'><label for='FEmail2' class='label_1'><b>".U_EMAIL_ISMET.": </b></label><br>\n ";
        $HTMLkod .= "<input type='text' name='FEmail2' class='$ErrClassFEmail' id='FEmail2' placeholder='".U_EMAIL_ISMET."' value='$FEmail2' size='30'></p>\n";   

        //Felhasználói szint
        $HTMLkod .="<b>".U_FSZINT.":</b><br>";                
        $HTMLkod .="<input type='radio' id='FSzint_2' name='FSzint' value='2' checked >";
        $HTMLkod .="<label for='FSzint_2' class='label_1'>".U_FSZ_FELH."</label><br>";
        $HTMLkod .="<input type='radio' id='FSzint_3' name='FSzint' value='3' >";
        $HTMLkod .="<label for='FSzint_3' class='label_1'>".U_FSZ_SZERK."</label><br>";                   
        $HTMLkod .="<input type='radio' id='FSzint_5' name='FSzint' value='5' >";
        $HTMLkod .="<label for='FSzint_5' class='label_1'>".U_FSZ_RG."</label><br>";    
        $HTMLkod .="<input type='radio' id='FSzint_6' name='FSzint' value='6' >";
        $HTMLkod .="<label for='FSzint_6' class='label_1'>".U_FSZ_RGHTML."</label><br>";        
        $HTMLkod .="<input type='radio' id='FSzint_7' name='FSzint' value='7' >";
        $HTMLkod .="<label for='FSzint_7' class='label_1'>".U_FSZ_WMESTER."</label><br>";       

        //Felhasználó szerepe    
        $HTMLkod .= "<p class='pFSzerep'><label for='FSzerep' class='label_1'><b>".U_FSZEREP.": </b></label><br>\n ";
        $HTMLkod .= "<input type='text' name='FSzerep' id='FSzerep' placeholder='".U_FSZEREP."' value='$FSzerep' size='20'></p>\n"; 

        //Felhasználó csoportba rendelése

        $HTMLkod .= "<p class='pFCsoport'><label for='selectCsoportValaszt2' class='label_1'><b>".U_FCSOPORTHOZ.": </b></label><br>\n ";
        $HTMLkod .= "<select name='selectCsoportValaszt2' id='selectCsoportValaszt2' size='1' required>";

        $SelectStr     = "SELECT id, CsNev FROM FelhasznaloCsoport";  
        $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 10 ");
        $rowDB         = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            while($row = mysqli_fetch_array($result))
            {
                $CsNev = $row['CsNev'];
                if (isset($_POST['selectCsoportValaszt2']) && ($_POST['selectCsoportValaszt2'] == $row['CsNev']))
                    {$Select = " selected ";}else{$Select = "";}

                $HTMLkod    .= "<option value='$CsNev' $Select >$CsNev</option>";            

            }
            mysqli_free_result($result);
        }
        //Submit
        $HTMLkod .= "</select>";
        $HTMLkod .= "</fieldset>";
        $HTMLkod .= "<input type='submit' name='submitUjFelhasznaloForm' value='".U_BTN_ELKULD."'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";   
    }           
    return $HTMLkod;
}     
	
    
// ============= Felhasználó adatainak módosítása ============    
// A felhasználók adatait csak a rendszergazdák módosíthatják    
function setFelhasznalo() {        
    global $MySqliLink;
    $ErrorStr  = '';
    $ErrorStr .= setFelhasznaloCsoportValaszt();
    $ErrorStr .= setFelhasznaloValaszt();

    if (($_SESSION['AktFelhasznalo'.'FSzint']>4) &&  (isset($_POST['submitFelhasznaloForm']))){ 			
        if (isset($_POST['FNev']))   {$FNev    = test_post($_POST['FNev']);}  
        if (isset($_POST['FFNev']))  {$FFNev   = test_post($_POST['FFNev']);} 
        if (isset($_POST['FEmail'])) {$FEmail  = test_post($_POST['FEmail']);} 
        if (isset($_POST['FSzint'])) {$FSzint  = test_post($_POST['FSzint']);} 
        if (isset($_POST['FSzerep'])){$FSzerep = test_post($_POST['FSzerep']);} 
        //if (isset($_POST['FKep'])) {$FKep = test_post($_POST['FKep']);} 

        $FId = $_SESSION['SzerkFelhasznalo'];

        //----------------- HIBAKEZELÉS -------------------------		

        if ($FNev=='') {$ErrorStr  .= ' Err001 '; }            //nincs név
        else { // Megj. az $FNev hosszát csak akkor ellenőrizük, ha 0-nál nagyobb 
            if (strlen($FNev)>40)  { $ErrorStr .= ' Err002 ';} //túl hosszú a név
            if (strlen($FNev)<5)   { $ErrorStr .= ' Err003 ';} //túl rövid a név
        }
        
        if($FFNev==''){$ErrorStr .= ' Err004 '; }              //nincs felhasználónév
        else { // Megj. az $FFNev hosszát csak akkor ellenőrizük, ha 0-nál nagyobb
            if (strlen($FFNev)>40) { $ErrorStr .= ' Err005 ';} //túl hosszú felhasználónév
            if (strlen($FFNev)<5)  { $ErrorStr .= ' Err006 ';} //túl rövid felhasználónév
        }

        if ($ErrorStr=='') { // Megj. az $FFNev-et csak akkor használjuk lekérdezésben, ha nincs vele gond
            $SelectStr   = "SELECT id FROM Felhasznalok WHERE FFNev='$FFNev'"; 
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUF 01 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) { 
                $row = mysqli_fetch_array($result);
                if($_SESSION['SzerkFelhasznalo']!=$row['id'])
                {
                    $ErrorStr .= ' Err007,';  //van ilyen néven már felhasználó
                }
                mysqli_free_result($result);
            }             
        }  
        //if($FEmail==''){ $ErrorStr .= ' Err008 ';}   //nincs email cím megadva
        
        if (strlen($FEmail)>50)   { $ErrorStr .= ' Err014 ';} //túl hosszú email cím
        
        // A kiemelt rendszergazda adatait csak kiemelt rendszergazda módosíthatja
        if (($_SESSION['SzerkFelhasznalo']==1) && ($_SESSION['AktFelhasznalo'.'FSzint']<7)) { $ErrorStr .= ' Err009 ';}

        // ---------------- ADATOK TÁROLÁSA ---------------------
        if($ErrorStr ==''){
            
            //Felhasználói adatok tárolása
            $UpdateStr = "UPDATE Felhasznalok SET FNev='$FNev', FFNev='$FFNev', FEmail='$FEmail', FSzint=$FSzint, FSzerep='$FSzerep' WHERE id='$FId'";    
            if (!mysqli_query($MySqliLink,$UpdateStr)) {die("Hiba sFV 04 "); }   

            //Csoportok tárolása
            $FCsoportDB = 0;
            if (isset($_POST['FCsoportDB'])) {$FCsoportDB = test_post($_POST['FCsoportDB']);}
            for ($i = 0; $i < $FCsoportDB; $i++){
                $id = test_post($_POST["FCsoportId_$i"]);
                if (isset($_POST["FCsoport_$i"])){ //Az adott jelölőnégyzet be volt jelölve
                    $SelectStr = "SELECT id FROM FCsoportTagok WHERE CSid=$id AND Fid=$FId";
                    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFV 05 ");
                    $rowDB     = mysqli_num_rows($result);
                    if ($rowDB >0) {mysqli_free_result($result);}

                    if($rowDB<1){ //Az adott csoportnak még nem tagja
                        if((isset($_POST['FCsoportTip']))&&($_POST['FCsoportTip']=="FCsoportTip_".$i)){$FTip=0;}else{$FTip=1;}

                        $InsertIntoStr = "INSERT INTO FCsoportTagok (Fid, CSid, KapcsTip)
                                                             VALUES ($FId,$id,$FTip)";
                        $result        = mysqli_query($MySqliLink,$InsertIntoStr) OR die("Hiba sFV 06 ");
                    } 
                    else //Az alapcsoport és a másodlagos csoportok vizsgálata és cseréje
                    {
                        if((isset($_POST['FCsoportTip']))&&($_POST['FCsoportTip']=="FCsoportTip_".$i))
                        {
                            $UpdateStr = "UPDATE FCsoportTagok SET KapcsTip=0 WHERE CSid=$id AND Fid=$FId";
                            $result    = mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba sFV 07 ");
                        }
                        else
                        {
                            $UpdateStr = "UPDATE FCsoportTagok SET KapcsTip=1 WHERE CSid=$id AND Fid=$FId";
                            $result    = mysqli_query($MySqliLink,$UpdateStr) OR die("Hiba sFV 08 ");
                        }
                    }    
                }
                else //A jelölőnégyzet NEM volt bejelölve
                {
                    $SelectStr = "SELECT KapcsTip FROM FCsoportTagok WHERE CSid=$id AND Fid=$FId"; 
                    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFV 09 ");
                    $rowDB     = mysqli_num_rows($result);
                    $row       = mysqli_fetch_array($result);
                    
                    if ($rowDB >0) {mysql_free_result($result);}

                    if($rowDB>0){ //Már meglévő csoporttagságot töröltek
                        
                        if($row['KapcsTip']!=0)
                        {
                            $DeleteStr = "DELETE FROM FCsoportTagok WHERE CSid=$id AND Fid=$FId";
                            $result    = mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sFV 10 ");
                        }
                        else //A törölt csoport a felhasználó alapcsoportja, mely nem törölhető közvetlenül
                        {
                            $ErrorStr.=" Err009 ";
                        }
                    }
                    else //A felhasználó alapcsoportjának egy olyan csoportot állítunk, aminek nem volt még tagja
                    {
                        if((isset($_POST['FCsoportTip']))&&($_POST['FCsoportTip']=="FCsoportTip_".$i))
                        {
                            $InsertIntoStr = "INSERT INTO FCsoportTagok (Fid, CSid, KapcsTip)
                                                                 VALUES ($FId,$id,0)";
                            $result    = mysqli_query($MySqliLink, $InsertIntoStr) OR die("Hiba sFV 11 ");
                        }
                    }
                }
            }
        } 
    }
    return $ErrorStr;
}

function getFelhasznaloForm() {
    global $MySqliLink;
    $HTMLkod  = '';
    $ErrorStr = ''; 
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $FNev           = '';
        $FFNev          = '';
        $FEmail         = '';
        $FSzint         = 0;
        $FSzerep        = '';
        $FKep           = '';
        $ErrClassFNev   = '';
        $ErrClassFFNev  = '';
        $ErrClassFEmail = '';
        $InfoClass      = '';
        $HTMLkod       .= getFelhasznaloCsoportValasztForm();
        
        if($_SESSION['SzerkFCsoport']>0)
        {
        $HTMLkod .= getFelhasznaloValasztForm();
        
            if($_SESSION['SzerkFelhasznalo']>0)
            {
                // ============== FORM KIVÁLASZTÁSA ===================== 
                if(isset($_POST['submitFelhasznaloForm']) || !isset($_POST['submitFelhasznaloTorol'])){$checked = " checked ";} else {$checked = "";}			
                $HTMLkod  .= "  <input name='chFelhasznaloForm'   id='chFelhasznaloForm'   value='chFelhasznaloForm'   type='radio' $checked >\n";
                $HTMLkod  .= "  <label for='chFelhasznaloForm'    class='chLabel'    id='labelFelhasznaloForm'>".U_FELH_SZERK."</label>\n";

                if(isset($_POST['submitFelhasznaloTorol'])){$checked = " checked ";} else {$checked = "";}			
                $HTMLkod  .= "  <input name='chFelhasznaloForm'   id='chFelhasznaloTorolForm'  value='chFelhasznaloTorolForm'  type='radio' $checked >\n";
                $HTMLkod  .= "  <label for='chFelhasznaloTorolForm'   class='chLabel'    id='labelFelhasznaloTorolForm'>".U_FELH_TOROL."</label>\n \n";
            } 
            else 
            {            
                $HTMLkod  .= "  <input name='chFelhasznaloForm'   id='chFelhasznaloTorolForm'  value='chFelhasznaloTorolForm'  type='radio' >\n";
                $HTMLkod  .= "  <label for='chFelhasznaloTorolForm'   class='chLabel'    id='labelFelhasznaloTorolForm'>".U_FELH_TOROL."</label>\n \n";
            }	
        
            if($_SESSION['SzerkFelhasznalo']>0)
            {
                $FId          = $_SESSION['SzerkFelhasznalo'];
                $SelectStr    = "SELECT * FROM Felhasznalok WHERE id='$FId' LIMIT 1"; 
                $result       = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFV 03 ");
                $rowDB        = mysqli_num_rows($result); 
                if ($rowDB > 0) {
                    $row      = mysqli_fetch_array($result);  mysqli_free_result($result);
                    $FNev     = $row['FNev'];
                    $FFNev    = $row['FFNev'];
                    $FEmail   = $row['FEmail'];
                    $FSzint   = $row['FSzint'];
                    $FSzerep  = $row['FSzerep'];
                    $FKep     = $row['FKep'];
                }        
                // ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
                if (isset($_POST['submitFelhasznaloForm'])) {
                    if (isset($_POST['FNev']))    {$FNev    = test_post($_POST['FNev']);}  
                    if (isset($_POST['FFNev']))   {$FFNev   = test_post($_POST['FFNev']);} 
                    if (isset($_POST['FEmail']))  {$FEmail  = test_post($_POST['FEmail']);} 
                    if (isset($_POST['FSzint']))  {$FSzint  = test_post($_POST['FSzint']);} 
                    if (isset($_POST['FSzerep'])) {$FSzerep = test_post($_POST['FSzerep']);} 
                    //if (isset($_POST['FKep'])) {$FKep = test_post($_POST['FKep']);} 
					
                    
                    if (strpos($_SESSION['ErrorStr'],'Err')!==false)
                       {$InfoClass  = ' ErrorInfo ';} else {$InfoClass  = ' OKInfo ';}        
                    
                    //Felhasználó nevének ellenőrzése                	   
                    if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
                    {
                            $ErrClassFNev  = ' Error '; 
                            $ErrorStr     .= U_FNEV_NINCS."!<br>";
                    } 
                    else 
                    {                    
                        if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
                            $ErrClassFNev  = ' Error '; 
                            $ErrorStr     .= U_FNEV_HOSSZU."!<br>";
                        }
                        if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
                            $ErrClassFNev  = ' Error '; 
                            $ErrorStr     .= U_FNEV_ROVID."!<br>";
                        }
                    } 

                    //Felhasználó felhasználónevének ellenőrzése                  	
                    if (strpos($_SESSION['ErrorStr'],'Err004')!==false) 
                    {
                        $ErrClassFFNev     = ' Error '; 
                        $ErrorStr         .= U_FFNEV_NINCS."!<br>";
                    }
                    else 
                    {                 
                        if (strpos($_SESSION['ErrorStr'],'Err005')!==false) {
                            $ErrClassFFNev = ' Error '; 
                            $ErrorStr     .= U_FFNEV_HOSSZU."!<br>";
                        }
                        if (strpos($_SESSION['ErrorStr'],'Err006')!==false) {
                            $ErrClassFFNev = ' Error '; 
                            $ErrorStr     .= U_FFNEV_ROVID."!<br>";
                        }
                        if (strpos($_SESSION['ErrorStr'],'Err007')!==false) {
                            $ErrClassFFNev = ' Error '; 
                            $ErrorStr     .= U_FFNEV_MARVAN."!<br>";
                        }
                    }
/* Email nem kötelező
                    //Email cím ellenőrzése                           
                    if (strpos($_SESSION['ErrorStr'],'Err008')!==false) 
                    {
                        $ErrClassFEmail = ' Error '; 
                        $ErrorStr      .= 'Nem adott meg e-mail címet! ';
                    }
  */                    
                     if (strpos($_SESSION['ErrorStr'],'Err014')!==false) 
                    {
                        $ErrClassFEmail = ' Error '; 
                        $ErrorStr      .= U_EMAIL_HOSSZU."! <br>";
                    }
                    
                    //Csoporttagság vizsgálata                    
                    if (strpos($_SESSION['ErrorStr'],'Err009')!==false) 
                    {
                        $ErrorStr .= U_ALAPCSOK_NEMT."!<br>";;
                    }

                    if ($_SESSION['ErrorStr'] == '' ){
                        $ErrorStr        = "<p class='time'>".U_MODOSITVA.": ".date("H.i.s.")."<p>".$ErrorStr; 
                    } else {
                        $ErrorStr        = "<p class='time'>".U_ELKULDVE.": ".date("H.i.s.")."<p>".$ErrorStr;
                    }
                }	

		// ============== FORM ÖSSZEÁLLÍTÁSA =====================                 
                $HTMLkod .= "<div id='divFelhasznaloForm' >\n";
                //echo $_SESSION['SzerkFelhasznalo']." - ".$_SESSION['AktFelhasznalo'.'FSzint'];
                if (!(($_SESSION['SzerkFelhasznalo']==1) && ($_SESSION['AktFelhasznalo'.'FSzint']<7))){
                if ($ErrorStr!='') {$HTMLkod .= "<div class='$InfoClass'>$ErrorStr</div>";}    

                    $HTMLkod .= "<form action='?f0=adatmodositas' method='post' id='formFelhasznaloForm'>\n";
                    $HTMLkod .= "<h2>".U_FSET."</h2>\n";
                    $HTMLkod .= "<fieldset> <legend>".U_FADATOK.":</legend>";
                    //Felhasználó neve    
                    $HTMLkod .= "<p class='pFNev'><label for='FNev' class='label_1'><b>".U_FNEV.":</b></label><br>\n ";
                    $HTMLkod .= "<input type='text' name='FNev' class='$ErrClassFNev' id='FNev' placeholder='".U_FNEV."' value='$FNev' size='20' required></p>\n"; 

                    //Felhasználó felhasználói neve    
                    $HTMLkod .= "<p class='pFFNev'><label for='FFNev' class='label_1'><b>".U_FFNEV.": </b></label><br>\n ";
                    $HTMLkod .= "<input type='text' name='FFNev' class='$ErrClassFFNev' id='FFNev' placeholder='".U_FFNEV."' value='$FFNev' size='20' required></p>\n"; 

                    //Email cím
                    $HTMLkod .= "<p class='pFEmail'><label for='FEmail' class='label_1'><b>".U_EMAIL.": </b></label><br>\n ";
                    $HTMLkod .= "<input type='text' name='FEmail' class='$ErrClassFEmail' id='FEmail' placeholder='".U_EMAIL."' value='$FEmail' size='30'></p>\n"; 

                    //Felhasználói szint
                    $HTMLkod .="<b>".U_FSZINT.":</b><br>";                
                    if($FSzint==2){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='FSzint_2' name='FSzint' value='2' $checked>";
                    $HTMLkod .="<label for='FSzint_2' class='label_1'>".U_FSZ_FELH."</label><br>";
                    if($FSzint==3){$checked=" checked ";}else{$checked="";}
                    $HTMLkod .="<input type='radio' id='FSzint_3' name='FSzint' value='3' $checked>";
                    $HTMLkod .="<label for='FSzint_3' class='label_1'>".U_FSZ_SZERK."</label><br>"; 
                    if ($_SESSION['AktFelhasznalo'.'FSzint']>4) {
                        if($FSzint==5){$checked=" checked ";}else{$checked="";}
                        $HTMLkod .="<input type='radio' id='FSzint_5' name='FSzint' value='5' $checked>";
                        $HTMLkod .="<label for='FSzint_5' class='label_1'>".U_FSZ_RG."</label><br>";  
                    }
                    if ($_SESSION['AktFelhasznalo'.'FSzint']>5) {
                        if($FSzint==6){$checked=" checked ";}else{$checked="";}
                        $HTMLkod .="<input type='radio' id='FSzint_6' name='FSzint' value='6' $checked>";
                        $HTMLkod .="<label for='FSzint_6' class='label_1'>".U_FSZ_RGHTML."</label><br>";        
                    }
                    if ($_SESSION['AktFelhasznalo'.'FSzint']>6) {
                        if($FSzint==7){$checked=" checked ";}else{$checked="";}
                        $HTMLkod .="<input type='radio' id='FSzint_7' name='FSzint' value='7' $checked>";
                        $HTMLkod .="<label for='FSzint_7' class='label_1'>".U_FSZ_WMESTER."</label><br>";                  
                    }
                    //Felhasználó szerepe    
                    $HTMLkod .= "<p class='pFSzerep'><label for='FSzerep' class='label_1'><b>".U_FSZEREP.": </b></label><br>\n ";
                    $HTMLkod .= "<input type='text' name='FSzerep' id='FSzerep' placeholder='".U_FSZEREP."' value='$FSzerep' size='20'></p>\n"; 

                    //Felhasználó csoportba rendelése
                    $HTMLkod .= "<p class='pFCsoport'><b>".U_FCSOP_VALASZT.":</b><br>\n ";

                    $SelectStr ="SELECT * FROM FelhasznaloCsoport";
                    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gFCs 01 ");
                    $rowDB     = mysqli_num_rows($result);
                    if ($rowDB > 0) {
                        $i          = 0;
                        while ($row = mysqli_fetch_array($result)) {
                            $id     = $row['id'];
                            $CsNev  = $row['CsNev'];

                            //Lekérdezzük, hogy mely csoportokhoz tartozik már a felhasználó
                            $SelectStr = "SELECT * FROM FCsoportTagok WHERE Fid=$FId AND CSid=$id";
                            $result2   = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gFCs 02 ");                    
                            $row_2     = mysqli_fetch_array($result2);
                            $rowDB_2   = mysqli_num_rows($result2);

                            //Csoporttagság vizsgálata                    
                            if($rowDB_2>0){
                                $checked="checked";
                                mysqli_free_result($result2); 
                            }else{
                                $checked="";                            
                            }
                            $HTMLkod .= "<input type='checkbox' name='FCsoport_$i' id='FCsoport_$i' $checked>\n";
                            $HTMLkod .= "<label for='FCsoport_$i' class='label_1' style='width:120px;display:inline-block;'>$CsNev</label>\n ";
                            $HTMLkod .= "<input type='hidden' name='FCsoportId_$i' id='FCsoportId_$i' value='$id'>\n";

                            //Alapcsoport vizsgálata (csoporttagság esetén)      
                            if($rowDB_2>0 && $row_2['KapcsTip']==0)
                            {
                                $checked     = " checked "; 
                                $alapcsoport = "(".U_ALAPCSOP.")";
                            }
                            else
                            {
                                $checked     = "";
                                $alapcsoport = "";
                            }
                            $HTMLkod .= "<input type='radio' name='FCsoportTip' id='FCsoportTip_$i' value='FCsoportTip_$i' $checked>$alapcsoport\n<br>";
                            $i++;  
                        }
                        mysqli_free_result($result); 
                    }    
                    $HTMLkod .= "<input type='hidden' name='FCsoportDB' id='FCsoportDB' value='$rowDB'>\n";	
                    $HTMLkod .= "</fieldset>";
                    //Submit
                    $HTMLkod .= "<input type='submit' name='submitFelhasznaloForm' value='".U_BTN_MODOSITAS."'><br>\n";        
                    $HTMLkod .= "</form>\n"; 
                } else {
                    $HTMLkod .= U_ADKEZ_NINCSJOG."\n"; 
                }
                $HTMLkod .= "</div>\n";   
            }
            $HTMLkod .=getFelhasznaloTorolForm();
        }	  
    }
    return $HTMLkod;	
}


function setFelhasznaloValaszt() {
    // A kiválasztott felhasználó ID-je a $_SESSION['SzerkFelhasznalo'] változóba!!!
    global $MySqliLink;
    $ErrorStr = '';

    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $FFNev     = '';

        // ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitFelhasznaloValaszt'])) {
            if (isset($_POST['selectFelhasznaloValaszt'])) {
                $FFNev = test_post($_POST['selectFelhasznaloValaszt']);                
            }      

            if($FFNev!='') {
                $SelectStr   = "SELECT id FROM Felhasznalok WHERE FFNev='$FFNev' LIMIT 1";  
                $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFV 02 ");
                $rowDB       = mysqli_num_rows($result); 
                if ($rowDB > 0) {
                    $row     = mysqli_fetch_array($result);  mysqli_free_result($result);
                    $_SESSION['SzerkFelhasznalo'] = $row['id'];
                } 
            }
        }
    }
    return $ErrorStr;
}    
    
function getFelhasznaloValasztForm() {
    // A form megjeleníti a felhasználók nevét és lehetővé teszi egy felhasználó megjelölését
    // Később biztosítani kell a felhasználónevek szűkítését csoportok szerint
    global $MySqliLink;
    $HTMLkod  = '';
    $ErrorStr = ''; 

    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
        $FFNev     = '';
	$CsId      = $_SESSION['SzerkFCsoport'];
        
        $HTMLkod  .= "<div id='divFelhasznaloValaszt' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}

        $HTMLkod  .= "<form action='?f0=adatmodositas' method='post' id='formFelhasznaloValaszt'>\n";
        $HTMLkod  .= "<h2>".U_FELH_VALASZT."</h2>\n";
        $HTMLkod  .= "<fieldset> <legend>".U_FELH_LISTA.":</legend>";
        //Felhasználó kiválasztása a lenyíló listából
        $HTMLkod  .= "<select name='selectFelhasznaloValaszt' size='1'>";

	$SelectStr ="SELECT F.id, F.FFNev, F.FNev
                    FROM Felhasznalok AS F
                    LEFT JOIN FCsoportTagok AS FCsT
                    ON FCsT.Fid= F.id 
                    WHERE FCsT.Csid=$CsId";
        $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFV 01 ");
        $rowDB         = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            while($row = mysqli_fetch_array($result)){
                $FNev  = $row['FNev'];
                $FFNev = $row['FFNev'];
                if($_SESSION['SzerkFelhasznalo'] == $row['id']){
                    $select = " selected ";                    
                }else{
                    $select = "";                    
                }
                $HTMLkod.="<option value='$FFNev' $select >$FNev</option>";
            }
            mysqli_free_result($result);
        }
        $HTMLkod .= "</fieldset>";
        //Submit
        $HTMLkod .= "<input type='submit' name='submitFelhasznaloValaszt' value='Kiválaszt'><br><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";    
    }       
    return $HTMLkod;
}   

function getFelhasznaloCsoportValasztForm()
{
    global $MySqliLink;
    $HTMLkod  = '';
    $ErrorStr = ''; 

    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
        $CsNev         = '';

        $HTMLkod      .= "<div id='divCsoportValaszt2' >\n";
        if ($ErrorStr != '') {
            $HTMLkod  .= "<p class='ErrorStr'>$ErrorStr</p>";        
        }

        //Felhasználó kiválasztása a lenyíló listából
        $HTMLkod      .= "<form action='?f0=adatmodositas' method='post' id='formCsoportValaszt2'>\n";
        $HTMLkod      .= "<h2>".U_FCSOP_VALASZT."</h2>\n";
        $HTMLkod      .= "<fieldset> <legend>".U_FCSOP_LISTA.":</legend>";
        $HTMLkod      .= "<select name='selectCsoportValaszt' size='1'>";

        $SelectStr     = "SELECT id, CsNev FROM FelhasznaloCsoport";  
        $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 01 ");
        $rowDB         = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            while($row = mysqli_fetch_array($result))
            {
                $CsNev = $row['CsNev'];
                if($_SESSION['SzerkFCsoport'] == $row['id'])
                    {$Select = " selected ";}else{$Select = "";}
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
function setFelhasznaloCsoportValaszt()
{
    global $MySqliLink;
    $ErrorStr      = '';

    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        $CsNev     = '';
        // ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitCsoportValaszt'])) {
            if (isset($_POST['selectCsoportValaszt'])) {
                $CsNev = test_post($_POST['selectCsoportValaszt']);                
            }      

            if($CsNev!='') {
                $SelectStr     = "SELECT id FROM FelhasznaloCsoport WHERE CsNev='$CsNev' LIMIT 1"; 
                $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sCsV 02 ");
                $rowDB         = mysqli_num_rows($result); 
                if ($rowDB > 0) {
                    $row       = mysqli_fetch_array($result);  mysqli_free_result($result);
                    if($_SESSION['SzerkFCsoport'] != $row['id'])
                        {$_SESSION['SzerkFelhasznalo']=0;}
                    $_SESSION['SzerkFCsoport'] = $row['id'];
                }
            }
        }
    }
    return $ErrorStr;
}


// ============= Felhasználó jelszavának módosítása ============ 
// A felhasználók jelszavait a rendszergazdák és a felhasználók is módosíthatják     
// Ha a felhasználó módosítja a jelszót, akkor meg kell adnia az érvényes jelszót   
function SetUjJelszo() {
    global $MySqliLink;
    $ErrorStr = ''; 

    if (($_SESSION['AktFelhasznalo'.'FSzint']>1) &&  (isset($_POST['submitUjJelszoForm'])))
    { 
        $FRJelszo  = '';
        $FUJelszo  = '';
        $FUJelszo2 = '';
        $FFNev     = $_SESSION['AktFelhasznalo'.'FFNev'];
        
        if (isset($_POST['FRJelszo']))  {$FRJelszo  = test_post($_POST['FRJelszo']);}			
        if (isset($_POST['FUJelszo']))  {$FUJelszo  = test_post($_POST['FUJelszo']);}
        if (isset($_POST['FUJelszo2'])) {$FUJelszo2 = test_post($_POST['FUJelszo2']);}

        //----------------- HIBAKEZELÉS -------------------------
        if($FRJelszo == '')				{$ErrorStr .= ' Err001 ';}
        if(($FUJelszo == '') || ($FUJelszo2 == ''))	{$ErrorStr .= ' Err002 ';}

        $SelectStr   = "SELECT FJelszo FROM Felhasznalok WHERE FFNev='$FFNev'";
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUJ 01 ");
        $rowDB       = mysqli_num_rows($result); 
        if ($rowDB > 0) {
            $row          = mysqli_fetch_array($result); mysqli_free_result($result);
            $FRJelszo     = md5($FRJelszo); 
            if($FRJelszo != $row['FJelszo']){$ErrorStr .= ' Err003 ';} //nem jól adta meg a régi jelszót
            if (strlen($FUJelszo)>20) { $ErrorStr .= ' Err004 ';} //túl hosszú jelszó
            if (strlen($FUJelszo)<5)  { $ErrorStr .= ' Err005 ';} //túl rövid jelszó			
            if ($FUJelszo!=$FUJelszo2){ $ErrorStr .= ' Err006 ';} // nem egyeznek a jelszavak

            // ---------------- JELSZÓ MÓDOSÍTÁSA AZ ADATBÁZISBAN ---------------------
            if($ErrorStr ==''){
                $FUJelszo = md5($FUJelszo); 
                $UpdateStr = "UPDATE Felhasznalok SET FJelszo = '$FUJelszo' WHERE FFNev='$FFNev'"; 
                if (!mysqli_query($MySqliLink,$UpdateStr)) {die("Hiba sUJ 02 ");}                               
            } 
        }
    }	
    return $ErrorStr;
}

function getUjJelszoForm() {
    $HTMLkod   = '';
    $ErrorStr  = ''; 
    $InfoClass = '';

    if ($_SESSION['AktFelhasznalo'.'FSzint']>1)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
        $ErrClassFRJelszo = ''; 
        $ErrClassFUJelszo = '';
        
        if (strpos($_SESSION['ErrorStr'],'Err')!==false)
                        {$InfoClass  = ' ErrorInfo ';} else {$InfoClass  = ' OKInfo ';} 
        //Jelszó ellenőrzése        
        if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
        {
        $ErrClassFRJelszo = ' Error '; 
        $ErrorStr .= U_RJELSZO_NINCS."!<br>";
        } 
        else
        {
            if (strpos($_SESSION['ErrorStr'],'Err003')!==false) 
            {
            $ErrClassFRJelszo = ' Error '; 
            $ErrorStr .= U_JELSZO_HIBA."!<br>";
            } 
        }

        if (strpos($_SESSION['ErrorStr'],'Err002')!==false) 
        {
            $ErrClassFUJelszo = ' Error '; 
            $ErrorStr .= U_UJELSZO_NINCS."!<br>";
        } 
        else
        {
            if (strpos($_SESSION['ErrorStr'],'Err004')!==false) 
            {
                $ErrClassFUJelszo = ' Error '; 
                $ErrorStr .= U_JELSZO_ROVID."!<br>";
            } 
            if (strpos($_SESSION['ErrorStr'],'Err005')!==false) 
            {
                $ErrClassFUJelszo = ' Error '; 
                $ErrorStr .= U_JELSZO_HOSSZU."!<br>";
            } 
            if (strpos($_SESSION['ErrorStr'],'Err006')!==false) 
            {
                $ErrClassFUJelszo = ' Error '; 
                $ErrorStr .= U_JELSZO_KULONBOZO."!<br>";
            } 
        }

        if (isset($_POST["submitUjJelszoForm"])){
            if ($_SESSION['ErrorStr'] == '' ){
                $ErrorStr        = "<p class='time'>".U_MODOSITVA.": ".date("H.i.s.")."<p>".$ErrorStr; 
            } else {
                $ErrorStr        = "<p class='time'>".U_ELKULDVE.": ".date("H.i.s.")."<p>".$ErrorStr;
            }	
        }

        $HTMLkod .= "<div id='divUjJelszoForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<div class='$InfoClass'>$ErrorStr</div>";}

        $HTMLkod .= "<form action='?f0=jelszomodositas' method='post' id='formUjJelszoForm'>\n";
        
        $HTMLkod .= "<h2>".U_UJELSZO_BEALL.":</h2>";
        $HTMLkod .= "<fieldset> <legend>".U_JELSZAVAK_BEKER.":</legend>";

        //Régi jelszó
        $HTMLkod .= "<p class='pFRJelszo'><label for='FRJelszo' class='label_1'>".U_RJELSZO.": </label><br>\n ";
        $HTMLkod .= "<input type='password' name='FRJelszo' class='$ErrClassFRJelszo' id='FRJelszo' placeholder='".U_RJELSZO."' size='20'></p>\n"; 

        //Új jelszó
        $HTMLkod .= "<p class='pFUJelszo'><label for='FUJelszo' class='label_1'>".U_UJELSZO.": </label><br>\n ";
        $HTMLkod .= "<input type='password' name='FUJelszo' class='$ErrClassFUJelszo' id='FUJelszo' placeholder='".U_UJELSZO."' size='20'></p>\n"; 

        //Új jelszó újra
        $HTMLkod .= "<p class='pFUJelszo2'><label for='FUJelszo2' class='label_1'>".U_UJELSZO_ISMET.": </label><br>\n ";
        $HTMLkod .= "<input type='password' name='FUJelszo2' class='$ErrClassFUJelszo' id='FUJelszo2' placeholder='".U_UJELSZO_ISMET."' size='20'>";
        $HTMLkod .= "</p>\n"; 
        $HTMLkod .= "</fieldset>";

        //Submit
        $HTMLkod .= "<br><input type='submit' name='submitUjJelszoForm' value='".Módosítás."'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";   
    }    
    return $HTMLkod;
}
 
    
// ============= Felhasználó törlése ============  
function setFelhasznaloTorol() {
    global $MySqliLink;
    $ErrorStr = '';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  {
        if (isset($_POST['submitFelhasznaloTorol'])) {
            if (isset($_POST['FTorolDB'])){ $FTorolDB = INT_post($_POST['FTorolDB']);} else {$FTorolDB = 0; }
            for ($i = 0; $i < $FTorolDB; $i++){
                if (isset($_POST["FTorolId_$i"])){$id = INT_post($_POST["FTorolId_$i"]);} else {$id = 0;}
                if (isset($_POST["FTorol_$i"]) && $id>1){
                    $DeleteStr = "DELETE FROM Felhasznalok WHERE id = $id"; 
                    mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sFT 02 ");
                    $DeleteStr = "DELETE FROM FCsoportTagok WHERE Fid = $id"; 
                    mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sFT 03 "); 
                }
            }
        }
    }
    return $ErrorStr;
}

function getFelhasznaloTorolForm() {	
    global $MySqliLink;
    $HTMLkod  = '';
    $ErrorStr = ''; 

    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  

        $HTMLkod      .= "<div id='divFelhasznaloTorol' >\n";
        if ($ErrorStr != '') { $HTMLkod  .= "<p class='ErrorStr'>$ErrorStr</p>";}

        $HTMLkod  .= "<form action='?f0=adatmodositas' method='post' id='formFelhasznaloTorol'>\n";
        $HTMLkod  .= "<h2>".U_FELH_TOROL."</h2>\n";
        $HTMLkod  .= "<fieldset> <legend>".U_FELH_LISTA.":</legend>";
        $CsId      = $_SESSION['SzerkFCsoport'];
        
        $SelectStr = "SELECT F.id, F.FNev, F.FFNev
                      FROM Felhasznalok AS F
                      LEFT JOIN FCsoportTagok AS FCsT
                      ON FCsT.Fid= F.id 
                      WHERE FCsT.Csid=$CsId";
        $result           = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sFT 01 ");
        $rowDB            = mysqli_num_rows($result);
        $i = 0;
        if ($rowDB > 0) {
            while ($row   = mysqli_fetch_array($result)) {
                $FNev     = $row['FNev'];
                $id       = $row['id'];
                //A kiemelt rendszergazda nem törőlhető
                if ($id>1) {
                    //Törlésre jelölés
                    $HTMLkod .= "<input type='checkbox' name='FTorol_$i' id='FTorol_$i'>\n";
                    $HTMLkod .= "<label for='FTorol_$i' class='label_1'>$FNev</label>\n ";
                    //id
                    $HTMLkod .= "<input type='hidden' name='FTorolId_$i' id='FTorolId_$i' value='$id'><br>\n";
                    $i++;
                }
            }
            mysqli_free_result($result); 
        }
        $HTMLkod .= "<input type='hidden' name='FTorolDB' id='FTorolDB' value='$rowDB'>\n";
        $HTMLkod .= "</fieldset>";
        //Submit
        $HTMLkod .= "<input type='submit' name='submitFelhasznaloTorol' value='".U_BTN_TOROL."'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";
	}
    return $HTMLkod;    
}    
    
// ============= Egy felhasználó adatainak lekérdezése============      

function getFelhasznalo($Fid) {
    trigger_error('Not Implemented!', E_USER_WARNING);
}


function getFelhasznaloLista() {
    trigger_error('Not Implemented!', E_USER_WARNING);
}

?>
