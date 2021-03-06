<?php
/*
 * @author    Tóth-Kovács Gellért
 */

//-------------------------------------------------------------------------------------
//OLDALAK CSOPORT SZERINTI LÁTHATÓSÁGA
//-------------------------------------------------------------------------------------

function getOLathatosagForm(){
    global $MySqliLink, $Aktoldal, $SzuloOldal, $NagyszuloOldal;
    $HTMLkod          = '';    
    $OUrl             = $Aktoldal['OUrl'];
    $Oid              = $Aktoldal['id'];  
    
    $Szulo_Oid        = $SzuloOldal['id']; 
    $Nagyszulo_Oid    = $NagyszuloOldal['id'];
    $Dedszulo_Oid     = $NagyszuloOldal['OSzuloId'];

    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  {
        $HTMLkod     .= "<div id='divOLathatosagCsoportValaszt' >\n";        
        $SelectStr    = "SELECT * FROM FelhasznaloCsoport";
        $result       = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOL 01 ");
        $rowDB        = mysqli_num_rows($result);
        if ($rowDB > 0) {
            $i = 0;
            while ($row   = mysqli_fetch_array($result)) {
                $id       = $row['id'];
                $CsNev    = $row['CsNev'];

                //Lekérdezzük, hogy mely csoportok láthatják az oldalt és aloldalait            
                $SelectStr = "SELECT * FROM OLathatosag WHERE CSid=$id AND Oid=$Oid";
                $result2   = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOL 02 ");                
                $rowDB_2   = mysqli_num_rows($result2);
                if($rowDB_2>0){
                    $checked="checked";
                    mysqli_free_result($result2);                    
                }else{
                    $checked="";                    
                }
                $HTMLkod .= "<input type='checkbox' name='OLathatosag_$i' id='OLathatosag_$i' $checked>\n";
                $HTMLkod .= "<label for='OLathatosag_$i' class='label_1'>$CsNev</label>\n ";
                //id
                $HTMLkod .= "<input type='hidden' name='OLathatosagId_$i' id='OLathatosagId_$i' value='$id'><br>\n";
                $i++;
            }
            mysqli_free_result($result);
        }
        $HTMLkod     .= "<input type='hidden' name='OLathatosagDB' id='OLathatosagDB' value='$rowDB'>\n";    
        $HTMLkod     .= "</div>\n";
    } 
    return $HTMLkod;
}
function setOLathatosag(){ 
    global $MySqliLink, $Aktoldal;
    $ErrorStr    = '';    
    $Oid         = $Aktoldal['id'];
    $OUrl        = $Aktoldal['OUrl'];
    $OLathatosag = $Aktoldal['OLathatosag']; 
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>4)  {
        if (isset($_POST['submitOldalForm'])) {                       
            if (isset($_POST['OLathatosagDB'])) {$OLathatosagDB = INT_post($_POST['OLathatosagDB']);} else {$OLathatosagDB = 0;}
            for ($i = 0; $i < $OLathatosagDB; $i++){
                if (isset($_POST["OLathatosagId_$i"])) {$id = INT_post($_POST["OLathatosagId_$i"]);} else {$id = 0;}
                
                if($OLathatosag==2 || $OUrl = "Kezdolap") //Akkor érdekes, ha a megjelenítés csoportra korlátozott
                {
                    if (isset($_POST["OLathatosag_$i"])){
                        $SelectStr  = "SELECT * FROM OLathatosag WHERE CSid=$id AND OId=$Oid"; 
                        $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sOL 01 ");
                        $rowDB      = mysqli_num_rows($result);  mysqli_free_result($result);
                        if ($rowDB == 0 ) {
                            $InsertIntoStr = "INSERT INTO OLathatosag (Oid, CSid)  
                                                               VALUES ($Oid,$id)";
                            $result        = mysqli_query($MySqliLink,$InsertIntoStr) OR die("Hiba sOL 02 ");
                        }     
                    }
                    else
                    {
                        $DeleteStr = "DELETE FROM OLathatosag WHERE CSid = $id AND Oid = $Oid";
                        $result    = mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sOL 04 ");
                    }
                }
                else
                {
                    $DeleteStr = "DELETE FROM OLathatosag WHERE CSid = $id AND Oid = $Oid";
                    $result    = mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sOL 06 ");
                }
                
                //Ha egyik oldal sem látható, és a kezdőlapon állították azt be                
                if (isset($_POST['OLathatosag']) && ($_POST['OLathatosag']==0 && $OUrl = "Kezdolap"))             //MEGNÉZNI !!!!!!!!!!!!!!!
                {
                    $SelectStr  = "SELECT * FROM OLathatosag"; 
                    $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sOL 07 ");
                    $rowDB      = mysqli_num_rows($result);
                    mysqli_free_result($result);

                    if($rowDB>0){
                        $DeleteStr = "DELETE FROM OLathatosag";
                        $result    = mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sOL 08 ");
                    }
                }
            }
        }
    }
    return $ErrorStr;  
}

//-------------------------------------------------------------------------------------
//OLDAL LÁTHATÓSÁGÁNAK VIZSGÁLATA
//-------------------------------------------------------------------------------------

function getOLathatosagTeszt() {    
    global $MySqliLink, $Aktoldal, $SzuloOldal, $NagyszuloOldal;
    $LathatosagOK  = 0;    
    $Fid           = $_SESSION['AktFelhasznalo'.'id'];
    $OLathatosag   = $Aktoldal['OLathatosag'];      
    $Oid           = $Aktoldal['id'];
    $Szulo_Oid     = $SzuloOldal['id']; 
    $Nagyszulo_Oid = $NagyszuloOldal['id'];
    $Dedszulo_Oid  = $NagyszuloOldal['OSzuloId'];

    if($_SESSION['AktFelhasznalo'.'FSzint']>3) {
        //A rendszergazdák és moderátorok láthatják 
        $LathatosagOK=1;        
    } else {
        if($_SESSION['AktFelhasznalo'.'FSzint']>0)
        {
            if($OLathatosag==1) //Az oldalt mindenki láthatja
            {
                $SelectStr = "SELECT id FROM Oldalak 
                              WHERE OLathatosag=0 
                              AND (id=$Szulo_Oid OR id=$Nagyszulo_Oid OR id=$Dedszulo_Oid)";
                $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOLT 02 ");
                $rowDB       = mysqli_num_rows($result); 
                if ($rowDB  == 0){$LathatosagOK=1;} else {mysqli_free_result($result);}
            } 
            if($OLathatosag==2) //Az oldalt meghatározott csoportok láthatják
            { 
                $SelectStr  ="SELECT * FROM OLathatosag AS OL
                              LEFT JOIN FCsoportTagok AS FCsT
                              ON FCsT.CSid=OL.CSid WHERE FCsT.Fid=$Fid 
                              AND (OL.Oid=$Oid OR OL.Oid=$Szulo_Oid OR OL.Oid=$Nagyszulo_Oid OR OL.Oid=$Dedszulo_Oid)";
                $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gLT 01 ");
                $rowDB      = mysqli_num_rows($result);  mysqli_free_result($result);  
                if($rowDB>0){$LathatosagOK=2; mysqli_free_result($result);}
            }
        }
        //Moderátorstátusz vizsgálata
        //if(getOModeratorMenuTeszt($Oid)>0){$LathatosagOK=1;} 
    }    
    return $LathatosagOK;
}


function getOMenuLathatosagTeszt($Oid) {
    global $MySqliLink;
    $LathatosagOK = 0;       
    $Fid          = $_SESSION['AktFelhasznalo'.'id'];
       
    //Csak a látogató és bejelentkezett felhasználó esetén vizsgáljuk a láthatóságot    
    $SelectStr   = "SELECT OSzuloId, OLathatosag FROM Oldalak WHERE id=$Oid";
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMLT 01 ");
    $rowDB       = mysqli_num_rows($result); 
    if ($rowDB > 0) {
        $row     = mysqli_fetch_array($result); mysqli_free_result($result); 

        $OLathatosag = $row['OLathatosag']; // echo "<h1>$Oid -- OLathatosag: $OLathatosag  LathatosagOK: $LathatosagOK</h1>";
        $Szulo_Oid   = $row['OSzuloId'];
        if($OLathatosag==0){$LathatosagOK=0;} //Az oldalt senki sem láthatja (kivéve, ha moderátor)
        if($OLathatosag==1){$LathatosagOK=1;} //Az oldalt mindenki láthatja
        if($OLathatosag==2) //Az oldalt valamennyi felhasználó láthatja (csoporttagság alapján)
        {
            $Nagyszulo_Oid = 1;
            $Dedszulo_Oid  = 1;
            if($Szulo_Oid>1){ //Kezdőlap esetén $Szulo_Oid = 0;
                $SelectStr ="SELECT * FROM Oldalak WHERE id=$Szulo_Oid";
                $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMLT 02 ");
                $rowDB     = mysqli_num_rows($result); 
                if ($rowDB > 0) {
                    $row           = mysqli_fetch_array($result); mysqli_free_result($result);
                    $Nagyszulo_Oid = $row['OSzuloId'];

                    if($Nagyszulo_Oid!=1){
                        $SelectStr ="SELECT * FROM Oldalak WHERE id=$Nagyszulo_Oid";
                        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMLT 03 ");
                        $rowDB     = mysqli_num_rows($result); 
                        if ($rowDB > 0) {
                            $row          = mysqli_fetch_array($result); mysqli_free_result($result); 
                            $Dedszulo_Oid = $row['OSzuloId'];
                        }
                    }
                }
            }
            $SelectStr ="SELECT * FROM OLathatosag AS OL
                        LEFT JOIN FCsoportTagok AS FCsT
                        ON FCsT.CSid=OL.CSid WHERE FCsT.Fid=$Fid 
                        AND (OL.Oid=$Oid OR OL.Oid=$Szulo_Oid OR OL.Oid=$Nagyszulo_Oid OR OL.Oid=$Dedszulo_Oid)";
            $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMLT 04 ");
            $rowDB     = mysqli_num_rows($result);     
            if($rowDB>0){$LathatosagOK=1; mysqli_free_result($result); }else{$LathatosagOK=0;}
        }
    }
    
//   echo "<h1>$Oid -- OLathatosag: $OLathatosag  LathatosagOK: $LathatosagOK</h1>";
    //Moderátorstátusz vizsgálata
    if(getOModeratorMenuTeszt($Oid)>0){$LathatosagOK=1;}
//  echo "<h1>$Oid -- OLathatosag: $OLathatosag  LathatosagOK: $LathatosagOK</h1>";   
    //A rendszergazdák láthatják az összes oldalt
    if($_SESSION['AktFelhasznalo'.'FSzint']>4){$LathatosagOK=1;}
    
    return $LathatosagOK;
}
?>
