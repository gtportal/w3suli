<?php


function getKatLapinfo($MaxDBperOldal) {
    global $MySqliLink, $Aktoldal, $oURL, $oLap;
    $AktLap = 1;
    if ($_SESSION['LapozKat'.'OUrl']  == $Aktoldal['OUrl']) {$AktLap = $_SESSION['LapozKat'.'CT'];}
    if ($AktLap>0) {$AktLap  = $oLap; }
    $arrGyermekek            = array(); 
    $arrLapinfo              = array();
    $arrLapinfo['SelectStr'] = '';
    $arrLapinfo['OsszDB']    = '';
    $arrLapinfo['LapozHTML'] = '';
    $LapozHTML               = '';
  
    $SelectStr     = "SELECT * FROM Oldalak WHERE OSzuloId=".$Aktoldal['id']."
                     AND OTipus<10 order by OPrioritas DESC, ONev"; 
    $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba LInf 01 ");
    $MaxOldal      = mysqli_num_rows($result);
    if ($MaxOldal  > 0) {        
        while($row = mysqli_fetch_array($result)) {
            if(getOMenuLathatosagTeszt($row['id'])>0){
                $arrGyermekek[] = $row['id'];
            }
        } 
        if ($MaxOldal > $MaxDBperOldal) {
            $MaxLap         = ($MaxOldal-1) / $MaxDBperOldal;      
            settype($MaxLap, "integer"); $MaxLap++;          
            if ($AktLap>$MaxLap) {$AktLap=$MaxLap;} if ($AktLap<1) {$AktLap=1;}

            $AktElsoOldal   = (($AktLap-1) * $MaxDBperOldal);
            $AktUtolsoOldal = ($AktLap * $MaxDBperOldal);    
            if ($AktUtolsoOldal>$MaxOldal) {$AktUtolsoOldal=$MaxOldal;}
            $OldalSzam      = $AktUtolsoOldal-$AktElsoOldal;
            $arrIdLista     = array_slice($arrGyermekek, $AktElsoOldal,$OldalSzam);
            $strIdLista     = implode(",", $arrIdLista);
            $arrLapinfo['SelectStr'] = "SELECT * FROM Oldalak WHERE id IN ($strIdLista)"; 
            $EllsoLap       = $AktLap - 5;  if ($EllsoLap<1) {$EllsoLap=1;}            
            $UtolsoLap      = $EllsoLap + 10;  if ($UtolsoLap>$MaxLap) {$UtolsoLap=$MaxLap;}
            $_SESSION['LapozKat'.'OUrl']  = $Aktoldal['OUrl'];
            $_SESSION['LapozKat'.'CT']    = $AktLap;

            // Gyors vissza
            $LapozHTML       = '';
            if ($AktLap > 5)   {$AktLap1 = $AktLap-5;
                 if ($AktLap1>1) {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt;&lt; </a></li>";} else
                                 {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt;&lt; </a></li>";}
            }     
            // Vissza
            if ($AktLap   > 2) {
                $AktLap1     = $AktLap-1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt; </a></li>";            
            } 
            if ($AktLap  == 2) {
                $AktLap1     = $AktLap-1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt; </a></li>";            
            } 
            // Számozott
            if ($AktLap     == 1) {$AktLink    = " class='AktLink' ";} else {$AktLink="";}
            if ($EllsoLap<2)  {$LapozHTML .= "<li><a href='?f0=".$Aktoldal['OUrl']."' $AktLink> 1 </a></li>";}
            for ($i=$EllsoLap+1;$i<=$UtolsoLap;$i++) {
                if ($AktLap == $i) {$AktLink = " class='AktLink' ";} else {$AktLink="";}
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$i' $AktLink> $i </a></li>";            
            }
            // Előre
            if ($AktLap   < $MaxLap) {
                $AktLap1     = $AktLap+1; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt; </a></li>";            
            }
            // Gyors előre
            if ($AktLap+5 <= $MaxLap) {
                $AktLap1     = $AktLap+5; 
                $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt;&gt; </a></li>";            
            }        
            $LapozHTML       = "<div class='divOLapozas'>$LapozHTML </div>";
        } else {
            $arrLapinfo['SelectStr'] = $SelectStr;            
        }
    }
    if ($MaxOldal > 0) {mysqli_free_result($result);}

    $arrLapinfo['LapozHTML']= $LapozHTML;
    return $arrLapinfo;
}

function getCikkLapinfo($MaxDBperOldal) {
   global $MySqliLink, $Aktoldal, $oURL, $oLap, $CCim;
    $AktLap    = 1;
    $MaxLap    = 1;
    $LapozHTML = '';
    if ($_SESSION['LapozCikk'.'OUrl']  == $Aktoldal['OUrl']) 
                   {$AktLap  = $_SESSION['LapozCikk'.'CT'];}
    if ($AktLap>0) {$AktLap  = $oLap; }
    $arrGyermekek            = array(); 
    $arrLapinfo              = array();
    $arrLapinfo['SelectStr'] = '';
    $arrLapinfo['OsszDB']    = '';
    $arrLapinfo['LapozHTML'] = '';
    $AktCCimURL              = trim(getTXTtoURL($CCim));
  
    if ($_SESSION['SzerkCikk'.'id'] == 0) {
        //A rendszergazdák és moderátorok minden cikket látnak
        if ($_SESSION['AktFelhasznalo'.'FSzint']>3) {
              $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id
                            WHERE OC.Oid=".$Aktoldal['id']."
                            ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
              $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id
                            WHERE OC.Oid=".$Aktoldal['id']."
                            ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
        }
        //A regisztrált felhasználók 
        if ($_SESSION['AktFelhasznalo'.'FSzint']==3) {
              $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id
                            WHERE 
                               OC.Oid=".$Aktoldal['id']." AND
                               (C.CSzerzo=".$_SESSION['AktFelhasznalo'.'id']."  OR  
                               C.CLathatosag>0 )    
                            ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
        }    
        if ($_SESSION['AktFelhasznalo'.'FSzint']==2) {
              $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id
                            WHERE 
                               OC.Oid=".$Aktoldal['id']." AND
                               C.CLathatosag>0     
                            ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
        }
        //Az egyszerű felhasználók csak azt látják, ami mindenki számára hozzáférhető
        if ($_SESSION['AktFelhasznalo'.'FSzint']==1) {
              $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                            FROM Cikkek AS C
                            LEFT JOIN OldalCikkei AS OC
                            ON OC.Cid= C.id
                            WHERE 
                               OC.Oid=".$Aktoldal['id']." AND
                               C.CLathatosag>2     
                            ORDER BY  OC.CPrioritas DESC, C.CModositasTime DESC";
        }    
    } else {
        $SelectStr = "SELECT C.id, C.CNev, C.CLeiras, C.CTartalom, C.CLathatosag, C.CSzerzo, C.CSzerzoNev, C.CModositasTime
                      FROM Cikkek AS C
                      LEFT JOIN OldalCikkei AS OC
                      ON OC.Cid= C.id
                      WHERE 
                         OC.Oid=".$Aktoldal['id']." AND
                         C.id=".$_SESSION['SzerkCikk'.'id'];  
    }    

    $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba CLInf 01 xa ");
    $MaxCikk       = mysqli_num_rows($result); 
    if ($MaxCikk > 0) {    
        $CikkCT    = 0;
        $AktCikkCT = -1;
        $LapozHTML = '';
        while($row = mysqli_fetch_array($result)) { 
            if (($_SESSION['AktFelhasznalo'.'FSzint']>3) || (getOMenuLathatosagTeszt($Aktoldal['id'])>0)) { 
                $arrGyermekek[] = $row['id'];            
                // Az aktuális cikk sorszámának meghatározása !!!!!!!!!!!!!!!!!!!!!!                
                $AktCikkURL     = trim(getTXTtoURL($row['CNev']));                
                if (strcmp($AktCikkURL,$AktCCimURL)==0) {$AktCikkCT = $CikkCT;}
                $CikkCT++;  
            }    
        }        
        $MaxCikk = count($arrGyermekek); 
        if ($MaxCikk > 0) {            
            if ($MaxCikk > $MaxDBperOldal) {
                if ($AktCikkCT > -1) {
                    if ($AktCikkCT == 0) {
                        $AktLap = 1;                    
                    } else {
                        $AktLap = $AktCikkCT/$MaxDBperOldal;
                        settype($AktLap, "integer"); $AktLap++;
                    }                   
                }                
                $MaxLap         = ($MaxCikk-1) / $MaxDBperOldal;      
                settype($MaxLap, "integer"); $MaxLap++;          
                if ($AktLap>$MaxLap) {$AktLap=$MaxLap;} if ($AktLap<1) {$AktLap=1;}
                $AktElsoCikk    = (($AktLap-1) * $MaxDBperOldal);
                $AktUtolsoCikk  = ($AktLap * $MaxDBperOldal);    
                if ($AktUtolsoCikk>$MaxCikk) {$AktUtolsoCikk=$MaxCikk;}
                $CikkAktDBSzam  = $AktUtolsoCikk-$AktElsoCikk;
                $arrIdLista     = array_slice($arrGyermekek, $AktElsoCikk,$CikkAktDBSzam);
                $strIdLista     = implode(",", $arrIdLista);
            } else {
                $strIdLista     = implode(",", $arrGyermekek);
            }                                   
            $arrLapinfo['SelectStr']       = "SELECT * FROM Cikkek WHERE id IN ($strIdLista) ORDER BY FIELD(id, $strIdLista) ";        

            $EllsoLap                      = $AktLap - 5;     if ($EllsoLap<1) {$EllsoLap=1;}
            $UtolsoLap                     = $EllsoLap + 10;  if ($UtolsoLap>$MaxLap) {$UtolsoLap=$MaxLap;}
            $_SESSION['LapozCikk'.'OUrl']  = $Aktoldal['OUrl'];
            $_SESSION['LapozCikk'.'CT']    = $AktLap;

            if ($MaxCikk > $MaxDBperOldal) {
                // Gyors vissza                
                if ($AktLap > 5)   {$AktLap1 = $AktLap-5;
                     if ($AktLap1>1) {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt;&lt; </a></li>";} else
                                     {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt;&lt; </a></li>";}
                }     
                // Vissza
                if ($AktLap   > 2) {
                    $AktLap1     = $AktLap-1; 
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt; </a></li>";            
                } 
                if ($AktLap     == 2) {
                    $AktLap1     = $AktLap-1; 
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt; </a></li>";            
                } 
                // Számozott
                if ($AktLap  ==1) {$AktLink=" class='AktLink' ";} else {$AktLink="";}
                if ($EllsoLap<2)  {$LapozHTML   .= "<li><a href='?f0=".$Aktoldal['OUrl']."' $AktLink> 1 </a></li>";}
                for ($i=$EllsoLap+1;$i<=$UtolsoLap;$i++) {
                    if ($AktLap==$i) {$AktLink=" class='AktLink' ";} else {$AktLink="";}
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$i' $AktLink> $i </a></li>";            
                }
                // Előre
                if ($AktLap   < $MaxLap) {
                    $AktLap1     = $AktLap+1; 
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt; </a></li>";            
                }
                // Gyors előre
                if ($AktLap+5 < $MaxLap) {
                    $AktLap1     = $AktLap+5; 
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt;&gt; </a></li>";            
                }        
                $LapozHTML       = "<div class='divOLapozas'>$LapozHTML </div>";
            }
        } else {
            $arrLapinfo['SelectStr'] = ''; 
        }       
    }
    if ($MaxCikk > 0) {mysqli_free_result($result);}
    $arrLapinfo['LapozHTML']= $LapozHTML;
    return $arrLapinfo;
}


function getCikkElozetesLapinfo($MaxDBperOldal,$Tipus) {
   global $MySqliLink, $Aktoldal, $oURL, $oLap, $CCim;
    $AktLap                  = 1;
    $LapozHTML               = '';
    if ($_SESSION['LapozCikk'.'OUrl']  == $Aktoldal['OUrl']) 
                   {$AktLap  = $_SESSION['LapozCikk'.'CT'];}
    if ($AktLap>0) {$AktLap  = $oLap; }
    $arrGyermekek            = array(); 
    $arrLapinfo              = array();
    $arrLapinfo['SelectStr'] = '';
    $arrLapinfo['OsszDB']    = 0;
    $arrLapinfo['LapozHTML'] = '';
    $arrLapinfo['OImgDir']   = '';
    $AktCCimURL              = trim(getTXTtoURL($CCim));
    $FSzint                  = $_SESSION['AktFelhasznalo'.'FSzint'];
  
    if ($FSzint>3) {
        if ($Tipus==0){
            $SelectStr="SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas, OC.Oid
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE C.KoElozetes>0  
                    ORDER BY C.KoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";
        }
        if ($Tipus==1){
            $SelectStr ="SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas, OC.Oid
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE O.OSzuloId=".$Aktoldal['id']." AND C.SZoElozetes>0 
                    ORDER BY C.SZoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";    
        }   
    }
 
    if ($FSzint==3) {
        if ($Tipus==0){
            $SelectStr="SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas, OC.Oid
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE C.KoElozetes>0  AND
                          (C.CSzerzo=".$_SESSION['AktFelhasznalo'.'id']."  OR  
                           C.CLathatosag>0 )
                    ORDER BY C.KoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";
        }
        if ($Tipus==1){
            $SelectStr ="SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas, OC.Oid
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE O.OSzuloId=".$Aktoldal['id']." AND C.SZoElozetes>0 AND
                          (C.CSzerzo=".$_SESSION['AktFelhasznalo'.'id']."  OR  
                           C.CLathatosag>0 )
                    ORDER BY C.SZoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";    
        }     
    }    
      if ($FSzint==2) {
        if ($Tipus==0){
            $SelectStr="SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas, OC.Oid
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE C.KoElozetes>0  AND
                          C.CLathatosag>0  
                    ORDER BY C.KoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";
        }
        if ($Tipus==1){
            $SelectStr ="SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas, OC.Oid
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE O.OSzuloId=".$Aktoldal['id']." AND C.SZoElozetes>0 AND
                          C.CLathatosag>0 
                    ORDER BY C.SZoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";    
        }  
    }   
   if ($FSzint==1) {
        if ($Tipus==0){
            $SelectStr="SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas, OC.Oid
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE C.KoElozetes>0  AND
                          C.CLathatosag>2  
                    ORDER BY C.KoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";
        }
        if ($Tipus==1){
            $SelectStr ="SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas, OC.Oid
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE O.OSzuloId=".$Aktoldal['id']." AND C.SZoElozetes>0 AND
                          C.CLathatosag>2  
                    ORDER BY C.SZoElozetes DESC, OC.CPrioritas DESC, C.CModositasTime DESC";    
        }  
    }    

    $result        = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba CLInf 01a ");
    $MaxCikk       = mysqli_num_rows($result);
    if ($MaxCikk > 0) {    
        $CikkCT    = 0;
        $AktCikkCT = 0;
        $AktCikkCT = -1;
        while($row = mysqli_fetch_array($result)) { 
            if (($FSzint>4) || (getOMenuLathatosagTeszt($row['Oid'])>0)) { 
                $arrGyermekek[] = $row['id'];            
                // Az aktuális cikk sorszámának meghatározása                
                $AktCikkURL     = trim(getTXTtoURL($row['CNev']));                
                if (strcmp($AktCikkURL,$AktCCimURL)==0) {$AktCikkCT = $CikkCT;}
                $CikkCT++;  
            }    
        }   
        
        $MaxCikk = count($arrGyermekek);
        if ($MaxCikk > 0) {
            $strIdLista         = '';
            if ($MaxCikk > $MaxDBperOldal) {
                $MaxLap         = ($MaxCikk-1) / $MaxDBperOldal;      
                settype($MaxLap, "integer"); $MaxLap++;          
                if ($AktLap>$MaxLap) {$AktLap=$MaxLap;} if ($AktLap<1) {$AktLap=1;}
                $AktElsoCikk    = (($AktLap-1) * $MaxDBperOldal);
                $AktUtolsoCikk  = ($AktLap * $MaxDBperOldal);    
                if ($AktUtolsoCikk>$MaxCikk) {$AktUtolsoCikk=$MaxCikk;}
                $CikkAktDBSzam  = $AktUtolsoCikk-$AktElsoCikk;
                $arrIdLista     = array_slice($arrGyermekek, $AktElsoCikk,$CikkAktDBSzam);
                $strIdLista     = implode(",", $arrIdLista);
            } else {
                $strIdLista     = implode(",", $arrGyermekek);
            }
            // A SelectStr összeállítása
            if ($strIdLista!='') {
            $arrLapinfo['SelectStr'] = "
                    SELECT C.id, C.CNev, O.OImgDir, C.CTartalom, C.CLeiras, OC.Cid, C.CSzerzoNev, C.CModositasTime, O.OUrl, OC.CPrioritas
                    FROM Cikkek AS C
                    LEFT JOIN OldalCikkei AS OC                    
                    ON OC.Cid = C.id
                    LEFT JOIN Oldalak AS O
                    ON OC.Oid = O.id
                    WHERE C.id IN ($strIdLista) ORDER BY FIELD(C.id, $strIdLista) ";
            } else {
                $arrLapinfo['SelectStr'] = "";
            }

            //Lapozó sávok összeállítása
            if (count($arrGyermekek) > $MaxDBperOldal) {
                $EllsoLap       = $AktLap - 5;     if ($EllsoLap<1) {$EllsoLap=1;}
                $UtolsoLap      = $EllsoLap + 10;  if ($UtolsoLap>$MaxLap) {$UtolsoLap=$MaxLap;}
                $_SESSION['LapozCikk'.'OUrl']  = $Aktoldal['OUrl'];
                $_SESSION['LapozCikk'.'CT']    = $AktLap;

                // Gyors vissza
                $LapozHTML = '';
                if ($AktLap > 5)   {$AktLap1       = $AktLap-5;
                     if ($AktLap1>1) {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt;&lt; </a></li>";} else
                                     {$LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt;&lt; </a></li>";}
                }     
                // Vissza
                if ($AktLap   > 2) {
                    $AktLap1     = $AktLap-1; 
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &lt; </a></li>";            
                } 
                if ($AktLap  == 2) {
                    $AktLap1     = $AktLap-1; 
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."'> &lt; </a></li>";            
                } 
                // Számozott
                if ($AktLap  ==1) {$AktLink=" class='AktLink' ";} else {$AktLink="";}
                if ($EllsoLap<2)  {$LapozHTML   .= "<li><a href='?f0=".$Aktoldal['OUrl']."' $AktLink> 1 </a></li>";}
                for ($i=$EllsoLap+1;$i<=$UtolsoLap;$i++) {
                    if ($AktLap==$i) {$AktLink=" class='AktLink' ";} else {$AktLink="";}
                    $LapozHTML .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$i' $AktLink> $i </a></li>";            
                }
                // Előre
                if ($AktLap   < $MaxLap) {
                    $AktLap1     = $AktLap+1; 
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt; </a></li>";            
                }
                // Gyors előre
                if ($AktLap+5 < $MaxLap) {
                    $AktLap1     = $AktLap+5; 
                    $LapozHTML  .= "<li><a href='?f0=".$Aktoldal['OUrl']."&amp;lap=$AktLap1'> &gt;&gt; </a></li>";            
                }        
                $LapozHTML = "<div class='divOLapozas'>$LapozHTML </div>";
            }
        } else {
            $arrLapinfo['SelectStr'] = ''; 
        }       
    }
    if ($MaxCikk > 0) {mysqli_free_result($result);}

    $arrLapinfo['LapozHTML']= $LapozHTML;
    return $arrLapinfo;
}



?>