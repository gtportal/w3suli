<?php
/*
 * @author    Tóth-Kovács Gellért
 */

//-------------------------------------------------------------------------------------
//OLDALAK CSOPORT SZERINTI LÁTHATÓSÁGA
//-------------------------------------------------------------------------------------

function getOLathatosagForm(){
    global $MySqliLink, $Aktoldal;
    $HTMLkod = '';
    
    $OUrl = $Aktoldal['OUrl'];
    $Oid  = $Aktoldal['id'];
    
    $Szulo_Oid     = $SzuloOldal['id']; 
    $Nagyszulo_Oid = $NagyszuloOldal['id'];
    $Dedszulo_Oid  = $NagyszuloOldal['OSzuloId'];

    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  {

        $HTMLkod .= "<div id='divOLathatosagCsoportValaszt' >\n";
        
        $SelectStr ="SELECT * FROM FelhasznaloCsoport";
        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOL 01 ");
        $rowDB     = mysqli_num_rows($result);
        
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $CsNev = $row['CsNev'];
            
            //Lekérdezzük, hogy mely csoportok láthatják az oldalt és aloldalait
            
            $SelectStr = "SELECT * FROM OLathatosag WHERE CSid=$id AND Oid=$Oid";
            $result2   = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOL 02 ");
            mysqli_free_result(result2);
            $rowDB_2  = mysqli_num_rows($result2);
            
            if($rowDB_2>0){$checked="checked";}else{$checked="";}
            
            $HTMLkod .= "<input type='checkbox' name='OLathatosag_$i' id='OLathatosag_$i' $checked>\n";
            $HTMLkod .= "<label for='OLathatosag_$i' class='label_1'>$CsNev</label>\n ";
            //id
            $HTMLkod .= "<input type='hidden' name='OLathatosagId_$i' id='OLathatosagId_$i' value='$id'><br>\n";
            $i++;
        }
        $HTMLkod .= "<input type='hidden' name='OLathatosagDB' id='OLathatosagDB' value='$rowDB'>\n";    
        $HTMLkod .= "</div>\n";
    } 
    return $HTMLkod;
}
function setOLathatosag(){ 
    global $MySqliLink, $Aktoldal;
    $ErrorStr = '';
    
    $Oid         = $Aktoldal['id'];
    $OUrl        = $Aktoldal['OUrl'];
    $OLathatosag = $Aktoldal['OLathatosag']; 
    
    if ($_SESSION['AktFelhasznalo'.'FSzint']>3)  {
        if (isset($_POST['submitOldalForm'])) {                       
            $OLathatosagDB = test_post($_POST['OLathatosagDB']);
            for ($i = 0; $i < $OLathatosagDB; $i++){
                $id = test_post($_POST["OLathatosagId_$i"]);
                
                if($OLathatosag==2 || $OUrl = "Kezdolap") //Akkor érdekes, ha a megjelenítés csoportra korlátozott
                {
                    if ($_POST["OLathatosag_$i"]){
                        $SelectStr = "SELECT * FROM OLathatosag WHERE CSid=$id AND OId=$Oid"; //echo $SelectStr."<br>";
                        $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sOL 01 ");
                        $rowDB  = mysqli_num_rows($result);
                        mysql_free_result($result);

                        if($rowDB<1){
                            $InsertIntoStr = "INSERT INTO OLathatosag VALUES ('',$Oid,$id)";
                            //echo $InsertIntoStr."<br>";
                            $result     = mysqli_query($MySqliLink,$InsertIntoStr) OR die("Hiba sOL 02 ");
                        }     
                    }
                    else
                    {
                        $SelectStr = "SELECT * FROM OLathatosag WHERE CSid=$id AND OId=$Oid "; //echo $SelectStr."<br>";
                        $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sOL 03 ");
                        $rowDB  = mysqli_num_rows($result);
                        mysql_free_result($result);

                        if($rowDB>0){
                            $DeleteStr = "DELETE FROM OLathatosag WHERE CSid = $id AND Oid = $Oid";
                            //echo $DeleteStr."<br>";
                            $result    = mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sOL 04 ");
                        }  
                    }
                }
                else
                {
                    $SelectStr = "SELECT * FROM OLathatosag WHERE CSid=$id AND OId=$Oid "; //echo $SelectStr."<br>";
                    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sOL 05 ");
                    $rowDB     = mysqli_num_rows($result);
                    mysql_free_result($result);

                    if($rowDB>0){
                        $DeleteStr = "DELETE FROM OLathatosag WHERE CSid = $id AND Oid = $Oid";
                        //echo $DeleteStr."<br>";
                        $result    = mysqli_query($MySqliLink, $DeleteStr) OR die("Hiba sOL 06 ");
                    }
                }
                
                //Ha egyik oldal sem látható, és a kezdőlapon állították azt be
                
                if($_POST['OLathatosag']==0 && $OUrl = "Kezdolap")
                {
                    $SelectStr = "SELECT * FROM OLathatosag"; //echo $SelectStr."<br>";
                    $result     = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sOL 07 ");
                    $rowDB  = mysqli_num_rows($result);
                    mysql_free_result($result);

                    if($rowDB>0){
                        $DeleteStr = "DELETE FROM OLathatosag";
                        //echo $DeleteStr."<br>";
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

function getOLathatosagTeszt($Oid) {
    $LathatosagOK = 0;
    global $MySqliLink, $Aktoldal, $SzuloOldal, $NagyszuloOldal;
    
    $Fid         = $_SESSION['AktFelhasznalo'.'id'];
    $OLathatosag = $Aktoldal['OLathatosag'];      
    $Oid           = $Aktoldal['id'];
    $Szulo_Oid     = $SzuloOldal['id']; 
    $Nagyszulo_Oid = $NagyszuloOldal['id'];
    $Dedszulo_Oid  = $NagyszuloOldal['OSzuloId'];

    if($_SESSION['AktFelhasznalo'.'FSzint']==1 || $_SESSION['AktFelhasznalo'.'FSzint']==2)
    {
        if($OLathatosag==0) //Az oldalt senki sem láthatja
        {
            $SelectStr = "SELECT * FROM Oldalak WHERE OLathatosag=$OLathatosag AND (id=$Oid OR id=$Szulo_Oid OR id=$Nagyszulo_Oid OR id=$Dedszulo_Oid)";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOLT 01 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0){$LathatosagOK=0;}
        }
        if($OLathatosag==1) //Az oldalt mindenki láthatja
        {
            $SelectStr = "SELECT * FROM Oldalak WHERE OLathatosag=$OLathatosag AND (id=$Oid OR id=$Szulo_Oid OR id=$Nagyszulo_Oid OR id=$Dedszulo_Oid)";
            $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOLT 02 ");
            $rowDB       = mysqli_num_rows($result); 
            if ($rowDB > 0){$LathatosagOK=1;} 
        } 
        if($OLathatosag==2) //Az oldalt meghatározott csoportok láthatják
        { 
            $SelectStr="SELECT * FROM OLathatosag AS OL
                        LEFT JOIN FCsoportTagok AS FCsT
                        ON FCsT.CSid=OL.CSid WHERE FCsT.Fid=$Fid 
                        AND (OL.Oid=$Oid OR OL.Oid=$Szulo_Oid OR OL.Oid=$Nagyszulo_Oid OR OL.Oid=$Dedszulo_Oid)";

            $result   = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gLT 01 ");
            $rowDB    = mysqli_num_rows($result);
            mysql_free_result($result);  

            if($rowDB>0){$LathatosagOK=1;}
        }
    }

    //Moderátorstátusz vizsgálata
    if(getOModeratorMenuTeszt($Oid)>0){$LathatosagOK=1;} 
    
    //A rendszergazdák láthatják az összes oldalt
    if($_SESSION['AktFelhasznalo'.'FSzint']>3){$LathatosagOK=1;} 
    
    return $LathatosagOK;
}
function getOMenuLathatosagTeszt($Oid) {
    global $MySqliLink;
    $LathatosagOK = 0;       
    $Fid = $_SESSION['AktFelhasznalo'.'id'];
       
    //Csak a látogató és bejelentkezett felhasználó esetén vizsgáljuk a láthatóságot
    
    $SelectStr = "SELECT OSzuloId, OLathatosag FROM Oldalak WHERE id=$Oid";
    $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMLT 01 ");
    $row       = mysqli_fetch_array($result);

    $OLathatosag = $row['OLathatosag']; 
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
            $row       = mysqli_fetch_array($result);
            mysql_free_result($result); 

            $Nagyszulo_Oid = $row['OSzuloId'];

            if($Nagyszulo_Oid!=1){
                $SelectStr ="SELECT * FROM Oldalak WHERE id=$Nagyszulo_Oid";
                $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMLT 03 ");
                $row       = mysqli_fetch_array($result);
                mysql_free_result($result); 

                $Dedszulo_Oid = $row['OSzuloId'];
            }
        }
        $SelectStr ="SELECT * FROM OLathatosag AS OL
                    LEFT JOIN FCsoportTagok AS FCsT
                    ON FCsT.CSid=OL.CSid WHERE FCsT.Fid=$Fid 
                    AND (OL.Oid=$Oid OR OL.Oid=$Szulo_Oid OR OL.Oid=$Nagyszulo_Oid OR OL.Oid=$Dedszulo_Oid)";
        $result    = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gOMLT 04 ");
        $rowDB     = mysqli_num_rows($result);
        mysql_free_result($result);  

        if($rowDB>0){$LathatosagOK=1;}else{$LathatosagOK=0;}
    }
    
    //Moderátorstátusz vizsgálata
    if(getOModeratorMenuTeszt($Oid)>0){$LathatosagOK=1;}
    
    //A rendszergazdák láthatják az összes oldalt
    if($_SESSION['AktFelhasznalo'.'FSzint']>3){$LathatosagOK=1;}
    
    return $LathatosagOK;
}
?>
