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
        global $AktFelhasznalo, $MySqliLink;
		$ErrorStr = ''; 
		
		$FFNev    = '';
		$FJelszo  = '';
		
		if (($_SESSION['AktFelhasznalo'.'FSzint']>0) &&  (isset($_POST['submitBelepesForm']))){
			if (isset($_POST['FFNev']))         {$FFNev    = test_post($_POST['FFNev']);}
			if (isset($_POST['FJelszo']))       {$FJelszo  = test_post($_POST['FJelszo']);}}
			
			//----------------- HIBAKEZELÉS -------------------------
			
			if($FFNev=='')  {$ErrorStr .= ' Err001 '; } //nincs felhasználónév megadva
			if($FJelszo==''){$ErrorStr .= ' Err002 '; } //nincs jelszó megadva
			
			$SelectStr   = "SELECT * FROM Felhasznalok WHERE FFNev='$FFNev' AND FJelszo='$FJelszo' ";  //echo "<h1>$SelectStr</h1>";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sBelepes 01 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0) {
				$row = mysqli_fetch_array($result);
				mysqli_free_result($result);
				
				$_SESSION['AktFelhasznalo'.'FNev']    = $row['FNev'];
				$_SESSION['AktFelhasznalo'.'FFNev']   = $row['FFNev'];
				$_SESSION['AktFelhasznalo'.'FJelszo'] = $row['FJelszo'];
				$_SESSION['AktFelhasznalo'.'FEmail']  = $row['FEmail'];
				$_SESSION['AktFelhasznalo'.'FSzint']  = $row['FSzint'];
				$_SESSION['AktFelhasznalo'.'FSzerep'] = $row['FSzerep'];
				//$_SESSION['AktFelhasznalo'.'FKep']  = $row['FKep'];  
			} else {
				$ErrorStr .= ' Err003,'; //hibás felhasználónév vagy jelszó
			} 
			return $ErrorStr;
	}	


    function getBelepesForm() {

		global $AktFelhasznalo;
		$HTMLkod='';
		$ErrorStr = ''; 
		$FFNev    = '';
		$FJelszo  = '';
		
		if ($_SESSION['AktFelhasznalo'.'FSzint']>0)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
					
		$FFNev    = $AktFelhasznalo['FFNev'];
		$FJelszo  = $AktFelhasznalo['FJelszo'];

		if (isset($_POST['submitBelepesForm'])) {
		   $FFNev      = $_POST['FFNev'];
		   $FJelszo     = $_POST['FJelszo']; 
		   
		   //Felhasználó felhasználónevének ellenőrzése
		   
		   
			$ErrClassFFNev = '';
			$ErrClassFJelszo = ''; 
			
			if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
			{
				  $ErrClassFFNev = ' Error '; 
				  $ErrorStr .= 'Nincs felhasználónév megadva! ';
			}
		   
		   //Jelszó ellenőrzése
		   		   
		   if (strpos($_SESSION['ErrorStr'],'Err002')!==false) 
		   {
				  $ErrClassFJelszo = ' Error '; 
				  $ErrorStr .= 'Nem adott meg jelszót! ';
		   } 

		   
		   //Felhasználónév és jelszó együttes vizsgálata
		   
		   if (strpos($_SESSION['ErrorStr'],'Err003')!==false) 
		   {
				  $ErrClassFFNev = ' Error '; 
			      $ErrClassFJelszo = ' Error '; 
				  $ErrorStr .= 'Hibás felhasználónév vagy jelszó! ';
		   }
		   
		   if($_SESSION['ErrorStr']==''){$ErrorStr='Sikeres bejelentkezés!';} 
		}	

		$HTMLkod .= "<div id='divBelepesForm' >\n";
		if ($ErrorStr!='') {
		$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
		

		$HTMLkod .= "<form action='?f0=bejelentkezes' method='post' id='formBelepesForm'>\n";
		
		//Felhasználó felhasználói neve
		
		$HTMLkod .= "<p class='pFFNev'><label for='FFNev' class='label_1'>Felhasználónév: </label><br>\n ";
		$HTMLkod .= "<input type='text' name='FFNev' class='$ErrClassFFNev' id='FFNev' placeholder='Felhasználónév' value='$FFNev' size='20'></p>\n"; 
		   
		//Jelszó
		
		$HTMLkod .= "<p class='pJelszo1'><label for='FJelszo' class='label_1'>Jelszó: </label><br>\n ";
		$HTMLkod .= "<input type='password' name='FJelszo' class='$ErrClassFJelszo' id='FJelszo' placeholder='Jelszó' value='' size='20'></p>\n"; 
		
		//Submit
		$HTMLkod .= "<input type='submit' name='submitBelepesForm' value='Bejelentkezés'><br>\n";        
		$HTMLkod .= "</form>\n";            
		$HTMLkod .= "</div>\n";
		
		return $HTMLkod;
		}
    }

    function setKilepes() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getKilepesForm() {
        // Lehet, hogy felesleges
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
    
    
// ============= Új felhasználó ============     
    function setUjFelhasznalo() {
        global $AktFelhasznalo, $MySqliLink;
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
		
		if (($_SESSION['AktFelhasznalo'.'FSzint']>0) &&  (isset($_POST['submitUjFelhasznaloForm']))){ 			
			if (isset($_POST['FNev']))   		{$FNev     = test_post($_POST['FNev']);}
			if (isset($_POST['FFNev']))         {$FFNev    = test_post($_POST['FFNev']);}
			if (isset($_POST['FJelszo']))       {$FJelszo  = test_post($_POST['FJelszo']);}
			if (isset($_POST['FJelszo2']))      {$FJelszo2 = test_post($_POST['FJelszo2']);}
			if (isset($_POST['FEmail']))        {$FEmail   = test_post($_POST['FEmail']);}
			if (isset($_POST['FEmail2']))       {$FEmail2  = test_post($_POST['FEmail2']);}
			if (isset($_POST['FSzint']))        {$FSzint   = test_post($_POST['FSzint']);}
			if (isset($_POST['FSzerep']))       {$FSzerep  = test_post($_POST['FSzerep']);}
			
			//----------------- HIBAKEZELÉS -------------------------
			
			if($FNev==''){$ErrorStr .= ' Err001 '; } //nincs név
			if (strlen($FNev)>40) { $ErrorStr .= ' Err002 ';} //túl hosszú a név
			if (strlen($FNev)<6) { $ErrorStr .= ' Err003 ';} //túl rövid a név
			
			if($FFNev==''){$ErrorStr .= ' Err004 '; } //nincs felhasználónév
			if (strlen($FFNev)>40) { $ErrorStr .= ' Err005 ';} //túl hosszú felhasználónév
			if (strlen($FFNev)<6) { $ErrorStr .= ' Err006 ';} //túl rövid felhasználónév
			
			$SelectStr   = "SELECT * FROM Felhasznalok WHERE FFNev='$FFNev'"; // echo "<h1>$SelectStr</h1>";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sUF 01 ");
            $rowDB       = mysqli_num_rows($result); mysqli_free_result($result);
            if ($rowDB > 0) { $ErrorStr .= ' Err007,';} //van ilyen néven már felhasználó            
           
			if (strlen($FJelszo)>20) { $ErrorStr .= ' Err008 ';} //túl hosszú jelszó
			if (strlen($FJelszo)<6)  { $ErrorStr .= ' Err009 ';} //túl rövid jelszó			
			if($FJelszo!=$FJelszo2)  { $ErrorStr .= ' Err010 ';} // nem egyeznek a jelszavak
			
			
			if($FEmail==''){$ErrorStr .= ' Err011 '; } //nincs email cím megadva			
			if($FEmail!=$FEmail2)    { $ErrorStr .= ' Err012 ';} // nem egyeznek az email címek
			
			// ---------------- ADATOK TÁROLÁSA ---------------------
			if($ErrorStr ==''){
				 $InsertIntoStr = "INSERT INTO Felhasznalok VALUES ('', '$FNev','$FFNev','$FJelszo','$FEmail',$FSzint,'$FSzerep','$FKep')";
				 if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba UF 01 ");}               
		    } 
		
		}	
		return $ErrorStr;
    }
    
    
    
    
    function getUjFelhasznaloForm() {
	
	global $AktFelhasznalo;
	$HTMLkod='';
	$ErrorStr = ''; 
	
	if ($_SESSION['AktFelhasznalo'.'FSzint']>0)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
		        
	
	$FNev     = $AktFelhasznalo['FNev'];
	$FFNev    = $AktFelhasznalo['FFNev'];
	$FJelszo  = $AktFelhasznalo['FJelszo'];
	$FJelszo2 = '';
	$FEmail   = $AktFelhasznalo['FEmail'];
	$FEmail2   = '';
	$FSzint   = $AktFelhasznalo['FSzint'];
	$FSzerep  = $AktFelhasznalo['FSzerep'];
	$FKep     = $AktFelhasznalo['FKep'];
	
	if (isset($_POST['submitUjFelhasznaloForm'])) {
	   $FNev       = $_POST['FNev']; 
	   $FFNev      = $_POST['FFNev'];
	   $FEmail     = $_POST['FEmail']; 
	   $FEmail2    = $_POST['FEmail2'];
	   $FSzint     = $_POST['FSzint']; 
	   $FSzerep    = $_POST['FSzerep'];
	   
	   //Felhasználó nevének ellenőrzése
	  
	   $ErrClassFNev = '';
	   
	   if (strpos($_SESSION['ErrorStr'],'Err001')!==false) 
	   {
              $ErrClassFNev = ' Error '; 
              $ErrorStr .= 'Hiányzik a felhasználó neve! ';
       } 
       else 
       {
			  $ErrClassFNev = ''; 
			  if (strpos($_SESSION['ErrorStr'],'Err002')!==false) {
					$ErrClassFNev = ' Error '; 
					$ErrorStr .= 'Túl hosszú a felhasználó neve! ';
			  }
			  if (strpos($_SESSION['ErrorStr'],'Err003')!==false) {
					$ErrClassFNev = ' Error '; 
					$ErrorStr .= 'Túl rövid a felhasználó neve! ';
			  }
	   } 
	   
	   //Felhasználó felhasználónevének ellenőrzése
	   
	   
		$ErrClassFFNev = '';
		
        if (strpos($_SESSION['ErrorStr'],'Err004')!==false) 
        {
              $ErrClassFFNev = ' Error '; 
              $ErrorStr .= 'Nincs felhasználónév megadva! ';
        }
        else 
        {
			$ErrClassFFNev = '';
			if (strpos($_SESSION['ErrorStr'],'Err005')!==false) {
					$ErrClassFFNev = ' Error '; 
					$ErrorStr .= 'Túl hosszú a felhasználónév! ';
			}
			if (strpos($_SESSION['ErrorStr'],'Err006')!==false) {
					$ErrClassFFNev = ' Error '; 
					$ErrorStr .= 'Túl rövid a felhasználónév! ';
			}
			if (strpos($_SESSION['ErrorStr'],'Err007')!==false) {
					$ErrClassFFNev = ' Error '; 
					$ErrorStr .= 'Létezik már a megadott felhasználó! ';
			}
		}
       
       //Jelszó ellenőrzése
       
       $ErrClassFJelszo = ''; 
       
       if (strpos($_SESSION['ErrorStr'],'Err010')!==false) 
       {
              $ErrClassFJelszo = ' Error '; 
              $ErrorStr .= 'A jelszavak nem egyeznek! ';
       } 
       else 
       {
		    $ErrClassFJelszo = ''; 
			if (strpos($_SESSION['ErrorStr'],'Err008')!==false) {
              $ErrClassFJelszo = ' Error '; 
              $ErrorStr .= 'Túl hosszú a jelszó! ';
            }
            if (strpos($_SESSION['ErrorStr'],'Err009')!==false) {
              $ErrClassFJelszo = ' Error '; 
              $ErrorStr .= 'Túl rövid a jelszó! ';
            }
		}
       
       //Email cím ellenőrzése
       
       $ErrClassFEmail = '';
       
       if (strpos($_SESSION['ErrorStr'],'Err011')!==false) 
       {
              $ErrClassFEmail = ' Error '; 
              $ErrorStr .= 'Nem adott meg e-mail címet! ';
       } 
       else 
       {
		   $ErrClassFEmail = '';
		   if (strpos($_SESSION['ErrorStr'],'Err012')!==false) {
              $ErrClassFEmail = ' Error '; 
              $ErrorStr .= 'Az e-mail címek nem egyeznek! ';
		   }
	   }
       
       if($_SESSION['ErrorStr']==''){$ErrorStr='Sikeres regisztráció!';} 
	}	
		


	$HTMLkod .= "<div id='divUjFelhasznaloForm' >\n";
    if ($ErrorStr!='') {
	$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
    

	$HTMLkod .= "<form action='?f0=regisztracio' method='post' id='formUjFelhasznaloForm'>\n";
          	   
    //Felhasználó neve
    
    $HTMLkod .= "<p class='pFNev'><label for='FNev' class='label_1'>A felhasználó neve:</label><br>\n ";
	$HTMLkod .= "<input type='text' name='FNev' class='$ErrClassFNev' id='FNev' placeholder='Felhasználó neve' value='$FNev' size='20'></p>\n"; 
	
	//Felhasználó felhasználói neve
    
    $HTMLkod .= "<p class='pFFNev'><label for='FFNev' class='label_1'>Felhasználónév: </label><br>\n ";
	$HTMLkod .= "<input type='text' name='FFNev' class='$ErrClassFFNev' id='FFNev' placeholder='Felhasználónév' value='$FFNev' size='20'></p>\n"; 
	   
    //Jelszó
    
    $HTMLkod .= "<p class='pJelszo1'><label for='FJelszo' class='label_1'>Jelszó: </label><br>\n ";
	$HTMLkod .= "<input type='password' name='FJelszo' class='$ErrClassFJelszo' id='FJelszo' placeholder='Jelszó' value='$FJelszo' size='20'></p>\n"; 
	
	//Jelszó újra
    
    $HTMLkod .= "<p class='pJelszo2'><label for='FJelszo2' class='label_1'>Jelszó újra: </label><br>\n ";
	$HTMLkod .= "<input type='password' name='FJelszo2' class='$ErrClassFJelszo' id='FJelszo2' placeholder='Jelszó újra' value='$FJelszo2' size='20'></p>\n"; 
	   
    //Email cím

	$HTMLkod .= "<p class='pFEmail'><label for='FEmail' class='label_1'>E-mail cím: </label><br>\n ";
	$HTMLkod .= "<input type='text' name='FEmail' class='$ErrClassFEmail' id='FEmail' placeholder='E-mail cím' value='$FEmail' size='30'></p>\n"; 
	   
	//Email cím újra

	$HTMLkod .= "<p class='pFEmail2'><label for='FEmail2' class='label_1'>E-mail cím újra: </label><br>\n ";
	$HTMLkod .= "<input type='text' name='FEmail2' class='$ErrClassFEmail' id='FEmail2' placeholder='E-mail cím újra' value='$FEmail2' size='30'></p>\n";   
	   
    //Felhasználói szint

	$HTMLkod .= "<p class='pFSzint'><label for='FSzint' class='label_1'>Felhasználói szint: </label><br>\n ";
    $HTMLkod .= "<input type='number' name='FSzint' id='FSzint' min='0' max='5' step='1' value='$FSzint'></p>\n";  
    
    //Felhasználó szerepe
    
    $HTMLkod .= "<p class='pFSzerep'><label for='FSzerep' class='label_1'>Felhasználó szerepe: </label><br>\n ";
	$HTMLkod .= "<input type='text' name='FSzerep' id='FSzerep' placeholder='Felhasználó szerepe' value='$FSzerep' size='20'></p>\n"; 
        
    //Submit
    $HTMLkod .= "<input type='submit' name='submitUjFelhasznaloForm' value='Elküld'><br>\n";        
    $HTMLkod .= "</form>\n";            
    $HTMLkod .= "</div>\n";   
	}        
	   
    return $HTMLkod;
	}     
	
    
// ============= Felhasználó adatainak módosítása ============    
// A felhasználók adatait csak a rendszergazdák módosíthatják    
    function setFelhasznalo() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getFelhasznaloForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
  
    function setFelhasznaloValszt() {
        // A kiválasztott felhasználó ID-je a $_SESSION['SzerkFelhasznalo'] változóba!!!
        trigger_error('Not Implemented!', E_USER_WARNING);
    }    
    
    function getFelhasznaloValsztForm() {
        // A form megjeleníti a felhasználók nevét és lehetővé teszi egy felhasználó megjelölését
        // Később biztosítani kell a felhasználónevek szűkítését csoportok szerint
        trigger_error('Not Implemented!', E_USER_WARNING);
    }    
    
// ============= Felhasználó jelszavának módosítása ============ 
// A felhasználók jelszavait a rendszergazdák és a felhasználók is módosíthatják     
// Ha a felhasználó módosítja a jelszót, akkor meg kell adnia az érvényes jelszót   
    function SetUjJelszo() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
    
    function getUjJelszoForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }
 
    
// ============= Felhasználó törlése ============    
    function setFelhasznaloTorol() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }    

    function getFelhasznaloTorolForm() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }    
    
// ============= Egy felhasználó adatainak lekérdezése============      
    function getAktFelhasznalo() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function getFelhasznalo($Fid) {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }


    function getFelhasznaloLista() {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

?>