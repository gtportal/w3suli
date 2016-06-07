<?php



//-------------------------------------------------------------------------------------
//FELHASZNÁLÓK MODERÁTORNAK VÁLASZTÁSA
//-------------------------------------------------------------------------------------

function setOModerator() {
    global $MySqliLink, $Aktoldal;
    $Oid = $Aktoldal['id'];
    $ErrorStr = '';
    
    //Csoport(ok) kiválasztása    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  {
        $ErrorStr .= setOModeratorCsoportValaszt();
        $ErrorStr .= setOModeratorCsoport();
    }
    
    //Felhasználók kiválasztása    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  {
        if (isset($_POST['submitOModeratorValaszt'])) {
            if (isset($_POST['MValasztDB'])) {$MValasztDB = INT_post($_POST['MValasztDB']);} else {$MValasztDB = 0;}
            for ($i = 0; $i < $MValasztDB; $i++){
                if (isset($_POST["MValasztId_$i"])) { $id = INT_post($_POST["MValasztId_$i"]);} else {$id = 0;}
                if (isset($_POST["MValaszt_$i"])){
                    $SelectStr = "SELECT * FROM OModeratorok WHERE Fid=$id AND OId=$Oid "; //echo $SelectStr."<br>";
                    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sMod 01 ");
                    $rowDB     = mysqli_num_rows($result);
                    mysql_free_result($result);                    
                    if($rowDB<1){
                        $InsertIntoStr = "INSERT INTO OModeratorok VALUES ('','$Oid','$id','0')";
                        $result     = mysqli_query($MySqliLink,$InsertIntoStr) OR die("Hiba sMod 02 ");
                    }     
                }
                else
                {
                    $SelectStr = "SELECT * FROM OModeratorok WHERE Fid=$id AND OId=$Oid "; //echo $SelectStr."<br>";
                    $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sMod 03 ");
                    $rowDB  = mysqli_num_rows($result);
                    mysql_free_result($result);
                    
                    if($rowDB>0){
                        $DeleteStr = "DELETE FROM OModeratorok WHERE Fid = $id AND Oid = $Oid";
                        $result    = mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sMod 04 ");
                    }  
                }
            }
        }
    }
    return $ErrorStr;
}
function getOModeratorForm() {
    global $MySqliLink, $Aktoldal;
    $OUrl = $Aktoldal['OUrl'];
    $Oid  = $Aktoldal['id'];
    $HTMLkod  = '';
    $ErrorStr = ''; 
    
    $HTMLkod .= "<div id='divOldalModeratorForm' >\n";
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
        $HTMLkod .= "<div id='divOModeratorForm' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
        
        //Csoport kiválasztása
        
        $HTMLkod .= getOModeratorCsoportValasztForm();
        
        //Felhasználó kiválasztása
        
        if($_SESSION['SzerkMCsoport']>0)
        {
            
            $CsId = $_SESSION['SzerkMCsoport'];
            $SelectStr ="SELECT F.id, F.FNev, F.FFNev
                        FROM Felhasznalok AS F
                        LEFT JOIN FCsoportTagok AS FCsT
                        ON FCsT.Fid= F.id 
                        WHERE FCsT.Csid=$CsId";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gMod 01 ");
            $rowDB  = mysqli_num_rows($result);
            if ($rowDB>0) {
                $HTMLkod .= "<div id='divOModeratorValasztForm' >\n";
                $HTMLkod .= "<h3>Válasszon moderátort!</h3>\n";            
                $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOModeratorValaszt'>\n";
                $HTMLkod .= "<fieldset> <legend>Felhasználók listája</legend>";
                $i = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $FNev = $row['FNev'];
                    $id = $row['id'];
                    //Lekérdezzük, van-e már az oldalon moderátor a csoporton belül
                    $SelectStr ="SELECT * FROM OModeratorok AS OM
                                JOIN Oldalak AS O
                                ON OM.Oid=O.id 
                                WHERE OM.Fid=$id AND OM.Oid=$Oid";
                    $result2      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gMod 02 ");
                    mysqli_free_result(result2);
                    $rowDB_2  = mysqli_num_rows($result2);
                    if($rowDB_2>0){$checked="checked";}else{$checked="";}
                    $HTMLkod .= "<input type='checkbox' name='MValaszt_$i' id='MValaszt_$i' $checked>\n";
                    $HTMLkod .= "<label for='MValaszt_$i' class='label_1'>$FNev</label>\n ";
                    //id
                    $HTMLkod .= "<input type='hidden' name='MValasztId_$i' id='MValasztId_$i' value='$id'><br>\n";
                    $i++;
                }
                $HTMLkod .= "<input type='hidden' name='MValasztDB' id='MValasztDB' value='$rowDB'>\n";
                //Submit
                 $HTMLkod .= "</fieldset>";
                $HTMLkod .= "<input type='submit' name='submitOModeratorValaszt' value='Kiválaszt'><br>\n";        
                $HTMLkod .= "</form>\n";    
                $HTMLkod .= "</div>\n";
            }
            
        }    
        $HTMLkod .= "</div>\n";
        $HTMLkod .= getOModeratorCsoportForm();
    }
      
    $HTMLkod .= "</div>\n";
    
    return $HTMLkod;  
}
function getOModeratorCsoportValasztForm(){
    global $MySqliLink, $Aktoldal;
    $OUrl = $Aktoldal['OUrl'];
    $HTMLkod  = '';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!!  
        $CsNev    = '';
        
        $HTMLkod .= "<div id='divOModeratorCsoportValaszt' >\n";
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
        
        $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOModeratorCsoportValaszt'>\n";
        //Felhasználó(k) kiválasztása
        $HTMLkod .= "<h2>Moderátor kiválasztása</h2>";
        $HTMLkod .= "<h3>A moderátor csoportjának kiválasztása</h3>";
        $HTMLkod .= "<fieldset> <legend>Felhasználói csoportok listája</legend>";
        $HTMLkod .= "<select name='selectOModeratorCsoportValaszt' size='1'>";
        $SelectStr   = "SELECT id, CsNev FROM FelhasznaloCsoport";  //echo "<h1>$SelectStr</h1>";
        $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sMCsV 01 ");
        while($row = mysqli_fetch_array($result))
        {
            $CsNev = $row['CsNev'];
            if($_SESSION['SzerkMCsoport'] == $row['id']){$Select = " selected ";}else{$Select = "";}
            $HTMLkod.="<option value='$CsNev' $Select >$CsNev</option>";
        }	
        $HTMLkod .= "</select></fieldset>";
        //Submit
        $HTMLkod .= "<input type='submit' name='submitOModeratorCsoportValaszt' value='Kiválaszt'><br>\n";        
        $HTMLkod .= "</form>\n";            
        $HTMLkod .= "</div>\n";    
    }     
    return $HTMLkod;   
}
function setOModeratorCsoportValaszt(){
    global $MySqliLink;
    $ErrorStr = '';
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 
        
        $CsNev     = '';
        // ============== FORM ELKÜLDÖTT ADATAINAK VIZSGÁLATA ===================== 
        if (isset($_POST['submitOModeratorCsoportValaszt'])) {
            if (isset($_POST['selectOModeratorCsoportValaszt'])) 
            {$CsNev = test_post($_POST['selectOModeratorCsoportValaszt']);}      
            if($CsNev!='')
            {
                $SelectStr   = "SELECT id FROM FelhasznaloCsoport WHERE CsNev='$CsNev' LIMIT 1";  //echo "<h1>$SelectStr</h1>";
                $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sMCsV 02 ");
                $row         = mysqli_fetch_array($result);  mysqli_free_result($result);
                //Ha kiválasztottunk egy másik csoportot, akkor újratöltjük a felhasználókat
                
                if($_SESSION['SzerkMCsoport'] != $row['id']){$_SESSION['SzerkModerator']=0;}
                $_SESSION['SzerkMCsoport'] = $row['id'];
            }
        }
    }
    return $ErrorStr;  
}

//-------------------------------------------------------------------------------------
//TELJES CSOPORTOK MODERÁTORNAK VÁLASZTÁSA
//-------------------------------------------------------------------------------------

function getOModeratorCsoportForm(){
    global $MySqliLink, $Aktoldal;
    $OUrl = $Aktoldal['OUrl'];
    $Oid  = $Aktoldal['id'];
    $HTMLkod = '';
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  { // FSzint-et növelni, ha működik a felhasználókezelés!!! 

        $HTMLkod .= "<div id='divOModeratorCsoportForm' >\n";
        
        
        if ($ErrorStr!='') {$HTMLkod .= "<p class='ErrorStr'>$ErrorStr</p>";}
        $HTMLkod .= "<form action='?f0=$OUrl' method='post' id='formOModeratorCsoport'>\n";
        $HTMLkod .= "<h2>Moderátor-csoport kiválasztás</h2>\n";
        
        $HTMLkod .= "<fieldset> <legend>Felhasználói csoportok listája</legend>";
        $SelectStr ="SELECT * FROM FelhasznaloCsoport";
        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gModCs 01 ");
        $rowDB     = mysqli_num_rows($result);
        
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $CsNev = $row['CsNev'];
            
            //Lekérdezzük, hogy mely csoportok vannak már beállítva moderátornak
            
            $SelectStr = "SELECT * FROM OModeratorok WHERE CSid=$id AND Oid=$Oid";
            $result2   = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gModCs 02 ");
            mysqli_free_result(result2);
            $rowDB_2  = mysqli_num_rows($result2);
            
            if($rowDB_2>0){$checked="checked";}else{$checked="";}
            
            $HTMLkod .= "<input type='checkbox' name='MCsoport_$i' id='MCsoport_$i' $checked>\n";
            $HTMLkod .= "<label for='MCsoport_$i' class='label_1'>$CsNev</label>\n ";
            //id
            $HTMLkod .= "<input type='hidden' name='MCsoportId_$i' id='MCsoportId_$i' value='$id'><br>\n";
            $i++;
        }
        $HTMLkod .= "<input type='hidden' name='MCsoportDB' id='MCsoportDB' value='$rowDB'>\n";
        $HTMLkod .= "</fieldset>";
        //Submit
        $HTMLkod .= "<input type='submit' name='submitOModeratorCsoport' value='Kiválaszt'><br>\n";        
        $HTMLkod .= "</form>\n";    
        $HTMLkod .= "</div>\n";
    } 
    return $HTMLkod;
}
function setOModeratorCsoport(){
    global $MySqliLink, $Aktoldal;
    $ErrorStr = '';
    
    $Oid = $Aktoldal['id'];
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  {
        if (isset($_POST['submitOModeratorCsoport'])) {
            if (isset($_POST['MCsoportDB'])) {$MCsoportDB = test_post($_POST['MCsoportDB']);}    else {$MCsoportDB = 0;}
            for ($i = 0; $i < $MCsoportDB; $i++){
                if (isset($_POST["MCsoportId_$i"])) { $id = test_post($_POST["MCsoportId_$i"]);} else {$id = 0;}
                if (isset($_POST["MCsoport_$i"])){
                    $SelectStr = "SELECT * FROM OModeratorok WHERE CSid=$id AND OId=$Oid"; //echo $SelectStr."<br>";
                    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sModCs 01 ");
                    $rowDB     = mysqli_num_rows($result);
                    mysql_free_result($result);
                    
                    if($rowDB<1){
                        $InsertIntoStr = "INSERT INTO OModeratorok VALUES ('','$Oid','0','$id')";
                        $result        = mysqli_query($MySqliLink,$InsertIntoStr) OR die("Hiba sModCs 02 ");
                    }     
                }
                else
                {
                    $SelectStr = "SELECT * FROM OModeratorok WHERE CSid=$id AND OId=$Oid "; //echo $SelectStr."<br>";
                    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sModCs 03 ");
                    $rowDB     = mysqli_num_rows($result);
                    mysql_free_result($result);
                    
                    if($rowDB>0){
                        $DeleteStr = "DELETE FROM OModeratorok WHERE CSid = $id AND Oid = $Oid";
                        $result    = mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sModCs 04 ");
                    }  
                }
            }
        }
    }
    
    return $ErrorStr;  
}

//-------------------------------------------------------------------------------------
//MODERÁTOR-JOGOSULTSÁG VIZSGÁLATA
//-------------------------------------------------------------------------------------
function getOModeratorTeszt() {
    $ModeratorOK = 0;
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1){  
        global $MySqliLink, $Aktoldal, $SzuloOldal, $NagyszuloOldal;

        $Fid           = $_SESSION['AktFelhasznalo'.'id'];
        $Oid           = $Aktoldal['id'];
        $Szulo_Oid     = $SzuloOldal['id']; 
        $Nagyszulo_Oid = $NagyszuloOldal['id'];
        $Dedszulo_Oid  = $NagyszuloOldal['OSzuloId'];

        if($Dedszulo_Oid!=1){$Ukszulo_Oid=1;}else{$Ukszulo_Oid=0;}

        //Csoport moderátorságának vizsgálata
        $SelectStr ="SELECT * FROM OModeratorok AS OM
                LEFT JOIN FCsoportTagok AS FCsT
                ON FCsT.CSid=OM.CSid WHERE FCsT.Fid=$Fid 
                AND (OM.Oid=$Oid OR OM.Oid=$Szulo_Oid OR OM.Oid=$Nagyszulo_Oid OR OM.Oid=$Dedszulo_Oid OR OM.Oid=$Ukszulo_Oid)";
        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMT 01 ");
        $rowDB     = mysqli_num_rows($result); mysql_free_result($result);
        if($rowDB>0){$ModeratorOK=1;}

        //Felhasználó moderátorságának vizsgálata
        $SelectStr  = "SELECT * FROM OModeratorok WHERE Fid=$Fid AND (Oid=$Oid OR Oid=$Szulo_Oid OR Oid=$Nagyszulo_Oid OR Oid=$Dedszulo_Oid OR Oid=$Ukszulo_Oid)";
        $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMT 02 ");
        $rowDB      = mysqli_num_rows($result);  mysql_free_result($result);                    
        if($rowDB>0){$ModeratorOK=1;}
    }
    return $ModeratorOK;
}

function getOModeratorMenuTeszt($Oid) {
    global $MySqliLink;
    $ModeratorOK   = 0;
    if ($_SESSION['AktFelhasznalo'.'FSzint']>1){        
        $Nagyszulo_Oid = 1;
        $Dedszulo_Oid  = 1;
        $Ukszulo_Oid   = 1; 

        $SelectStr = "SELECT OSzuloId FROM Oldalak WHERE id=$Oid";
        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMMT 01 ");
        $row       = mysqli_fetch_array($result);    
        $Szulo_Oid = $row['OSzuloId'];

        if($Szulo_Oid>1){ //Kezdőlap esetén $Szulo_Oid = 0;
            $SelectStr     = "SELECT * FROM Oldalak WHERE id=$Szulo_Oid";
            $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMMT 02 ");
            $row           = mysqli_fetch_array($result); mysql_free_result($result); 
            $Nagyszulo_Oid = $row['OSzuloId'];

            if($Nagyszulo_Oid!=1){
                $SelectStr    ="SELECT * FROM Oldalak WHERE id=$Nagyszulo_Oid";
                $result       = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMMT 03 ");
                $row          = mysqli_fetch_array($result); mysql_free_result($result); 
                $Dedszulo_Oid = $row['OSzuloId'];
            }
        }

        //Csoport moderátorságának vizsgálata    
        $Fid       = $_SESSION['AktFelhasznalo'.'id'];
        $SelectStr = "SELECT * FROM OModeratorok AS OM
                LEFT JOIN FCsoportTagok AS FCsT
                ON FCsT.CSid=OM.CSid 
                WHERE FCsT.Fid=$Fid 
                AND (OM.Oid=$Oid OR OM.Oid=$Szulo_Oid OR OM.Oid=$Nagyszulo_Oid OR OM.Oid=$Dedszulo_Oid OR OM.Oid=$Ukszulo_Oid)";
        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMMT 04 ");
        $rowDB     = mysqli_num_rows($result);
        mysql_free_result($result);        

        if($rowDB>0){$ModeratorOK=1;}else{$ModeratorOK=0;}

        //Felhasználó moderátorságának vizsgálata    
        $SelectStr = "SELECT * FROM OModeratorok 
                      WHERE Fid=$Fid  
                      AND (Oid=$Oid OR Oid=$Szulo_Oid OR Oid=$Nagyszulo_Oid OR Oid=$Dedszulo_Oid OR Oid=$Ukszulo_Oid)";
        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMMT 05 ");
        $rowDB     = mysqli_num_rows($result);
        mysql_free_result($result);                    
        if($rowDB>0){$ModeratorOK=1;}else{$ModeratorOK=0;}
    }
    return $ModeratorOK;
}

?>