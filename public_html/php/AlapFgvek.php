<?php


function getURL(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}




function setKepFeltolt($AktKonytart,$KFileName) {    
    if (isset($_FILES["file"]) && $_FILES["file"]["name"]!='') { 
      $UploadErr   = '';
      $allowedExts = array("gif", "jpeg", "jpg", "png", "JPG", "JPEG");
      $temp        = explode(".", $_FILES["file"]["name"]); 
      $extension   = end($temp);
      if ((($_FILES["file"]["type"]   == "image/gif")
        || ($_FILES["file"]["type"]   == "image/jpeg")
        || ($_FILES["file"]["type"]   == "image/jpg")
        || ($_FILES["file"]["type"]   == "image/pjpeg")
        || ($_FILES["file"]["type"]   == "image/x-png")
        || ($_FILES["file"]["type"]   == "image/png"))
        && ($_FILES["file"]["size"]   < 2000000)
        && in_array($extension, $allowedExts))
      {
        if ($_FILES["file"]["error"] > 0) {
          $UploadErr = "ErrK02 " . $_FILES["file"]["name"]; 
        } else {
          $CelFilename =   $AktKonytart.$KFileName.'.'.$extension; echo "GGGGFFFFF";
          if (file_exists($CelFilename)) {
            if (!@unlink($CelFilename)) {
                $UploadErr = 'ErrK02'.$_FILES["file"]["name"]."<br>"; // Nem sikerült a törlés
            } else {  
                //Meglévő kép felülírása
                if (move_uploaded_file($_FILES["file"]["tmp_name"],$CelFilename)) {
                    $UploadErr =  $KFileName.'.'.$extension; 
                } else {
                    $UploadErr .= "ErrK05".$_FILES["file"]["name"]."<br>";                     
                }
            }
          } else { 
            //Új kép feltöltése
            if (move_uploaded_file($_FILES["file"]["tmp_name"],$CelFilename)) {
              $UploadErr =  $KFileName.'.'.$extension; 
            } else {
              $UploadErr .= "ErrK05".$_FILES["file"]["name"]."<br>";                     
            }
          }
        }
      } else {
        if ($_FILES["file"]["name"] >'') {$UploadErr = "ErrK01 ".$KFileName.'.'.$extension;}
      }
    } else {
        $UploadErr = "ErrK00 ";       
    }  
    return $UploadErr;;
}


    function setKepekFeltolt() {       
       
    }

    function getTXTtoURL($txt) {
        $arr = array(' ' => '_', ',' => '_',  ';' => '',  '"' => '',  "'" => '',  ':' => '',  '  ' => '__', '   ' => '___', 'á' => 'a', 'Á' => 'A', 
               'é' => 'e', 'É' => 'e', 'ó' => 'o', 'Ó' => 'O', 'í' => 'i', 'Í' => 'I', 'Ú' => 'U', 'ú' => 'u', 'Ö' => 'O', 
               'ö' => 'o', 'Ő' => 'O', 'ő' => 'o', 'Ü' => 'U', 'ü' => 'u', 'Ű' => 'U', 'ű' => 'u', '.' => '_', "\x5C" => "", 
               "/" => "", '|' => '', '?' => '', '*' => '', '\\' => '', ':' => '', '<' => '', '<' => '>');  
        $txt = strtr($txt ,$arr);
        return $txt;
    }

    function getTXTtoHTML($txt) {
        trigger_error('Not Implemented!', E_USER_WARNING);
    }

    function FileNevTisztit($str){ 
      $arr = array(' ' => '',  'á' => 'a', 'Á' => 'A', 'é' => 'e', 'É' => 'e', 'ó' => 'o', 'Ó' => 'O', 'í' => 'i', 'Í' => 'I', 
               'Ú' => 'U', 'ú' => 'u', 'Ö' => 'O', 'ö' => 'o', 'Ő' => 'O', 'ő' => 'o', 'Ü' => 'U', 'ü' => 'u', 'Ű' => 'U', 
               'ű' => 'u');  
      $str1 = strtr($str ,$arr);
      return $str1;
    }
    
   function test_post($data) {
     global $MySqliLink;
     $data = trim($data);
     $arr  = array( "'" => "&apos;", '"' => "&quot;", '”' => "&quot;", ":" => "&#58;", "  " => " ", "   " => " ",
                    "<" => "&lt;", ">" => "&gt;",   "=" => "&#61;", "\x5C" => "" );
     $data = strtr($data, $arr);
     $data = mysqli_real_escape_string($MySqliLink, $data);
   return $data;
   }
   
   function test_post1($data) {
     global $MySqliLink;
     $data = trim($data);
     $arr  = array( "'" => "&apos;", '"' => "&quot;", '”' => "&quot;", ":" => "&#58;", "  " => " ", "   " => " ",
                    "<" => "&lt;", ">" => "&gt;",   "=" => "&#61;", "\x5C" => "" );
     $data  = strtr($data, $arr);
   return;
   }
   
   function SQL_post($data) {
     global $MySqliLink;
     $data = trim($data);
     $arr  = array( "'" => "&apos;",  "\x5C" => "" );
     $data = strtr($data, $arr);
     $data = mysqli_real_escape_string($MySqliLink, $data);
   return $data;
   }
   
   function STR_post($data) {
     global $MySqliLink;
     $data = trim($data);
     $arr  = array( "'" => "&apos;",  "\x5C" => "" );
     $data = strtr($data, $arr);
     //$data = mysql_escape_string($data);
   return $data;
   }
   
   function INT_post($data) {
     $data = trim($data);
     settype($data,'integer');  if (!(is_int($data))) {$data=0;}
     return $data;
    }

   function SoremelesVissza($data) {
     $arr  = array( "\r" => "&#13;","\0" => "&#10;");
     $data = strtr($data, $arr);     
   return $data;
   }
//=========================================================================================================================
// 
// SAJÁT SZINTAKTIKA KEZELÉSE
//
//=========================================================================================================================

function SzintaxisCsere($str){ 

 // echo $str1;
  $str1 = ListaCsere($str)."\n\n";
  $str1 = TablaCserePlusz($str1)."\n\n";

  $str1 = ItalicCsere($str1)."\n\n";
  $str1 = BoldCsere($str1)."\n\n";


  $str1 = SuperCsere($str1,'^','<sup>','</sup>')."\n\n";
  $str1 = SuperCsere($str1,',,','<sub>','</sub>')."\n\n";

  $str1 = SuperCsere($str1,'~~','<del>','</del>')."\n\n";

  $str1 = HCsere($str1,'&#61;&#61;&#61;','<h3>','</h3>')."\n\n";
  $str1 = HCsere($str1,'&#61;&#61;','<h2>','</h2>')."\n\n";
  
  $str1 = HCsere($str1,'===','<h3>','</h3>')."\n\n";
  $str1 = HCsere($str1,'==','<h2>','</h2>')."\n\n";

  $str1 = SortoresCsere($str1)."\n\n";
  $str1 = LinkCsere($str1)."\n\n";


  return $str1;
}

function SzintaxisCsereElozetes($str){ 

  $str1 = ListaCsere($str)."\n\n";
  $str1 = TablaCserePlusz($str1)."\n\n";

  $str1 = ItalicCsere($str1)."\n\n";
  $str1 = BoldCsere($str1)."\n\n";


  $str1 = SuperCsere($str1,'^','<sup>','</sup>')."\n\n";
  $str1 = SuperCsere($str1,',,','<sub>','</sub>')."\n\n";

  $str1 = SuperCsere($str1,'~~','<del>','</del>')."\n\n";

  $str1 = HCsere($str1,'&#61;&#61;&#61;','<h4>','</h4>')."\n\n";
  $str1 = HCsere($str1,'&#61;&#61;','<h3>','</h3>')."\n\n";
  
  $str1 = HCsere($str1,'===','<h4>','</h4>')."\n\n";
  $str1 = HCsere($str1,'==','<h3>','</h3>')."\n\n";

  $str1 = SortoresCsere($str1)."\n\n";
  $str1 = LinkCsere($str1)."\n\n";


  return $str1;
}

//=========================================================================================================================
// <a href='URL' > LINKFELIRAT </a> BEILLESZTÉSE 
// HATÁROLÓ:  [URL|Linkfelirat]       Példa:  [http://webfejlesztes.gtportal.eu/|Webfejelsztes]
//
// Ha nincs Linkfelirat, akkor az URL jelenik meg
// Az URL "http"-vel kell, hogy kezdődjön
//=========================================================================================================================

function LinkCsere($str){
  $strtomb=explode('[',$str);  
  $HSzint = 0;
  $str1 = '';
  $str2 = '';
  foreach ($strtomb as $SOR) {
    if ((strpos($SOR,"]")>0) && (strpos($SOR,"http")===0)) {
      $stra = substr($SOR,0, strpos($SOR,"]"));
      $strb = substr($SOR,strpos($SOR,"]")+1);
 
      if (strpos($stra,"|")>0) {
        $LinkTomb=explode('|',$stra);  
        if (strpos($LinkTomb[0],"matrac.gtportal.eu")>0) {$Blank = '';} else {$Blank = 'target="_blank"';}
        $str1 .= '<a href="'.$LinkTomb[0].'" rel="nofollow"  '.$Blank.' >'.$LinkTomb[1].'</a>'.$strb; 
      } else {
		if (strpos($stra,"matrac.gtportal.eu")>0) {$Blank = '';} else {$Blank = 'target="_blank"';}  
		$str1 .= '<a href="'.$stra.'" rel="nofollow"  '.$Blank.' >'.$stra.'</a>'.$strb;}
    } else {$str1 .= $SOR;}
  }
  return $str1;
}

//=========================================================================================================================
// Esetleges linkek törlése a meta leírásból
// HATÁROLÓ:  [URL|Linkfelirat]       Példa:  ....[http://webfejlesztes.gtportal.eu/|Webfejelsztes]... >> ..........
//
//=========================================================================================================================

function LinkTorolMetabol($str){
  $strtomb=explode('[',$str);  
  $str1 = '';
  foreach ($strtomb as $SOR) {
    if ((strpos($SOR,"]")>0) && (strpos($SOR,"http")===0)) {
      $str1 .= substr($SOR,strpos($SOR,"]")+1);
    } else {$str1 .= $SOR;}
  }
  return $str1;
}


//=========================================================================================================================
// <br> BEILLESZTÉSE 
//
// Minden üres sorba <br>-t ír.
// 
//=========================================================================================================================

function SortoresCsere($str){
  $strtomb=explode("\x0D",$str);  
  $HSzint = 0;
  $str1 = '';
  foreach ($strtomb as $SOR) {
    if (strlen($SOR)>0) {$str1 .= "\x0D".$SOR;} else {$str1 .= "\x0D <br>";}
  }
  return $str1;
}

//=========================================================================================================================
// <h2> </h2> - <h3> </h3>  BEILLESZTÉSE 
// HATÁROLÓ:  == vagy ===      Példa: == H2 == vagy === H3 === 
// A nyitó után kötelező, és a záró előtt kötelező a szóköz
// 
//=========================================================================================================================

function HCsere($str,$compStr,$CsereTagEleje,$CsereTagVege){ 
  $strtomb  = explode($compStr,$str); 
  $TombHossz= Count($strtomb);
  $HSzint   = 0;
  $str1     = $strtomb[0]; 
  for ($i=1;$i<$TombHossz-1;$i++) { 
    $AktStr = $strtomb[$i];   $AktHossz = strlen($AktStr); if ($AktHossz>0) {$AktUtolso = $AktStr[$AktHossz-1];} else {$AktUtolso = '';}  
    $KovStr = $strtomb[$i+1]; $KovHossz = strlen($KovStr); if ($KovHossz>0) {$KovUtolso = $KovStr[$KovHossz-1];} else {$KovUtolso = '';} 
    $EloStr = $strtomb[$i-1]; $EloHossz = strlen($EloStr); if ($EloHossz>0) {$EloUtolso = $EloStr[$EloHossz-1];} else {$EloUtolso = '';}
    if ($i == 0 ) {$str1 = $AktStr; } else {
      if ($HSzint == 0 ) {
        if ((($AktStr>'') && ($i > 0)) &&
           (($AktStr[0] ==' ') || ($AktStr[0] =="\x0D") || ($AktStr[0] =="\x0A")) &&
           (($AktUtolso ==' ') || ($AktUtolso =="\x0D") || ($AktUtolso =="\x0A")) &&
           (($EloUtolso ==' ') || ($EloUtolso =="\x0D") || ($EloUtolso =="\x0A") || ($EloHossz == 0)))
        {
         $HSzint = 1; 
         $str1 .= $CsereTagEleje.$AktStr;
        } else {$str1.=$compStr.$AktStr;  }
      } else {
        if ((($AktStr>'') && ($i > 0)) &&
           (($AktStr[0] ==' ') || ($AktStr[0] =="\x0D") || ($AktStr[0] =="\x0A")) &&
           (($EloUtolso ==' ') || ($EloUtolso =="\x0D") || ($EloUtolso =="\x0A") || ($EloHossz == 0)))
        {
         $HSzint = 0;
         $str1 .= $CsereTagVege.$AktStr;
        } else {$str1.=$compStr.$AktStr;  } 
      }
    }
  }
 // if ($HSzint == 1) {$str1.=$CsereTagVege;}
  if ($HSzint == 1) {$str1.=$CsereTagVege.$strtomb[$TombHossz-1];} else {if ($TombHossz>1) {$str1.=$compStr.$strtomb[$TombHossz-1];}}
  return $str1;
}


//=========================================================================================================================
// <sup> </sup> - <sub> </sub> - <del> </del> BEILLESZTÉSE 
// HATÁROLÓ:  ^ vagy ,, vagy ~~      Példa: 2^8^ vagy H,,2,,O vagy ~~törölve~~
// A nyitó után nem lehet, a záró után kötelező a szóköz
// A nyitó előtt kötelező, a záró után nem lehet szóköz 
//=========================================================================================================================

function SuperCsere($str,$compStr,$CsereTagEleje,$CsereTagVege){   
  $strtomb=explode($compStr,$str);  
  $TombHossz=Count($strtomb);
  $ItalicSzint = 0;
  $str1 = $strtomb[0];
  for ($i=1;$i<$TombHossz-1;$i++) {
    $AktStr = $strtomb[$i];   $AktHossz = strlen($AktStr); if ($AktHossz>0) {$AktUtolso = $AktStr[$AktHossz-1];} else {$AktUtolso = '';}  
    $KovStr = $strtomb[$i+1]; $KovHossz = strlen($KovStr); if ($KovHossz>0) {$KovUtolso = $KovStr[$KovHossz-1];} else {$KovUtolso = '';} 
    $EloStr = $strtomb[$i-1]; $EloHossz = strlen($EloStr); if ($EloHossz>0) {$EloUtolso = $EloStr[$EloHossz-1];} else {$EloUtolso = '';}
//Első tag
  if ($ItalicSzint == 0 ) {
  if (($AktStr>'') && ($i > 0) && ($AktStr[0]!==' ') && ($AktStr[0]!=="\x0D") && ($AktStr[0]!=="\x0A") && ($AktUtolso!==' '))
       {$str1.=$CsereTagEleje.$AktStr; $ItalicSzint = 1;}  else { if ($AktStr>'') { if ($i > 0) {$str1.=$compStr.$AktStr;} else  {$str1.=$AktStr;} }}
   } else {
//Második tag
     if ((($AktStr>'') &&
          ($EloUtolso !== ' ')  && ($EloHossz !== 0)) 
       or (($AktHossz == 0) && (($EloUtolso !== ' ') && ($EloUtolso !== "\x0D") && ($EloUtolso !== "\x0A") && ($EloHossz !== 0)))) 
        {$str1.=$CsereTagVege.$AktStr; $ItalicSzint=0;} else {$str1.=$compStr.$AktStr;     }
   }
  }
 // if ($ItalicSzint == 1) {$str1.=$CsereTagVege;}
  if ($ItalicSzint == 1) {$str1.=$CsereTagVege.$strtomb[$TombHossz-1];} else {if ($TombHossz>1) {$str1.=$compStr.$strtomb[$TombHossz-1];}}
  return $str1;
}

//=========================================================================================================================
// <i> és </i> BEILLESZTÉSE 
// HATÁROLÓ:  _          Példa: _szöveg_
// A nyitó után nem lehet, a záró után kötelező a szóköz
// A nyitó előtt kötelező, a záró után nem lehet szóköz 
//=========================================================================================================================
function ItalicCsere($str){   
  $strtomb     = explode('_',$str);  
  $TombHossz   = Count($strtomb);
  $ItalicSzint = 0;
  $str1        = $strtomb[0];
  for ($i=1;$i<$TombHossz-1;$i++) {
    $AktStr = $strtomb[$i];   $AktHossz = strlen($AktStr); if ($AktHossz>0) {$AktUtolso = $AktStr[$AktHossz-1];} else {$AktUtolso = '';}  
    $KovStr = $strtomb[$i+1]; $KovHossz = strlen($KovStr); if ($KovHossz>0) {$KovUtolso = $KovStr[$KovHossz-1];} else {$KovUtolso = '';} 
    $EloStr = $strtomb[$i-1]; $EloHossz = strlen($EloStr); if ($EloHossz>0) {$EloUtolso = $EloStr[$EloHossz-1];} else {$EloUtolso = '';}
    $Dupla_ = 0;
    if ($i>1) {if ((strlen($EloStr)==0)&&(strlen($strtomb[$i-2]))) {$Dupla_ = 1;} }
//Első tag
  if ($ItalicSzint == 0 ) {
    if (($AktStr>'') && ($i > 0) && ($AktStr[0]!==' ') && ($Dupla_ == 0) &&
        (($KovStr[0]==' ') || ($KovStr[0]=='*') || ($KovStr[0]=='^') || ($KovStr[0]=='~')  || ($KovStr[0]==',') || ($KovStr[0]=='<') || ($KovStr[0]=="\x0D") ||  ($KovStr[0]=="\x0A") ||  ($KovHossz == 0)) && 
         (($EloUtolso == ' ') || ($EloUtolso == '*') || ($EloUtolso == '^') || ($EloUtolso == '~')  || ($EloUtolso == ',')  ||  ($EloUtolso == '>')  || 
          ($EloUtolso == "\x0D")  || ($EloUtolso == "\x0A") || ($EloHossz == 0))) 
          {$str1.='<i>'.$AktStr; $ItalicSzint = 1; }  
    else { 
             // if ($AktStr>'') { if ($i > 0) {$str1.='_'.$AktStr;} else  {$str1.=$AktStr;} }
               if ($i > 0) {$str1.='_'.$AktStr;} else  {$str1.=$AktStr;} 
         }
   } else {
//Második tag
     if ((($AktStr>'') && (($AktStr[0] ==' ') || ($AktStr[0]=='<')  || ($AktStr[0] =='*')  || ($AktStr[0] =='^') || ($AktStr[0] =='~')  || ($AktStr[0] ==',')  || ($AktStr[0] =="\x0D") || ($AktStr[0] =="\x0A") ) && 
          ($EloUtolso !== ' ')  && ($EloUtolso !== "\x0D")  && ($EloUtolso !== "\x0A")  && ($EloHossz !== 0)) 
       or (($AktHossz == 0) && (($EloUtolso !== ' ') && ($EloUtolso !== '*') && ($EloUtolso !== '^')  && ($EloHossz !== 0)))) 
        {$str1.='</i>'.$AktStr; $ItalicSzint=0;} else {$str1.='_'.$AktStr;}
   }
  }
  if ($ItalicSzint == 1) {$str1.='</i>'.$strtomb[$TombHossz-1];} else {if ($TombHossz>1) {$str1.='_'.$strtomb[$TombHossz-1];}}
  return $str1;
}

//=========================================================================================================================
// <b> és </br> BEILLESZTÉSE 
// HATÁROLÓ:  *          Példa: *szöveg*
// A nyitó után nem lehet, a záró után kötelező a szóköz
// A nyitó előtt kötelező, a záró után nem lehet szóköz 
//=========================================================================================================================


function BoldCsere($str){   
  $strtomb=explode('*',$str);  
  $TombHossz=Count($strtomb);
  $BoldSzint = 0;
  $str1 = $strtomb[0];
  for ($i=1;$i<$TombHossz-1;$i++) {
    $AktStr = $strtomb[$i];   $AktHossz = strlen($AktStr); if ($AktHossz>0) {$AktUtolso = $AktStr[$AktHossz-1];} else {$AktUtolso = '';}  
    $KovStr = $strtomb[$i+1]; $KovHossz = strlen($KovStr); if ($KovHossz>0) {$KovUtolso = $KovStr[$KovHossz-1];} else {$KovUtolso = '';} 
    $EloStr = $strtomb[$i-1]; $EloHossz = strlen($EloStr); if ($EloHossz>0) {$EloUtolso = $EloStr[$EloHossz-1];} else {$EloUtolso = '';}
//Első tag
  if ($BoldSzint == 0 ) {
  if (($AktStr>'') && ($i > 0) && ($AktStr[0]!==' ') && 
      (($KovStr[0]==' ') || ($KovStr[0]=='_') || ($KovStr[0]=='<') || ($KovStr[0]=='^') || ($KovStr[0]=='~') || ($KovStr[0]==',')  ||  ($KovStr[0]=="\x0D") ||  
       ($KovStr[0]=="\x0A") ||  ($KovHossz == 0)) && 
      (($EloUtolso == ' ') || ($EloUtolso == '_') ||  ($EloUtolso == '>') ||  ($EloUtolso == '^') ||  ($EloUtolso == '~')  ||  ($EloUtolso == ',') || 
       ($EloUtolso == "\x0D")  || ($EloUtolso == "\x0A") || ($EloHossz == 0))) 
       {$str1.='<b>'.$AktStr; $BoldSzint = 1;}  else { if ($AktStr>'') { if ($i > 0) {$str1.='*'.$AktStr;} else  {$str1.=$AktStr;} }}
   } else {
//Második tag
     if ((($AktStr>'') && (($AktStr[0] ==' ') || ($AktStr[0]=='<') || ($AktStr[0]=='^') || ($AktStr[0] =='_') || ($AktStr[0] =='~') || ($AktStr[0] ==',') || 
          ($AktStr[0] =="\x0D") || ($AktStr[0] =="\x0A") ) && 
          ($EloUtolso !== ' ') && ($EloUtolso !== "\x0D")  && ($EloUtolso !== "\x0A")  && ($EloHossz !== 0)) 
       or (($AktHossz == 0) && (($EloUtolso !== ' ') && ($EloUtolso !== "\x0D")  && ($EloUtolso !== "\x0A")  && ($EloUtolso !== '_')  && 
           ($EloUtolso !== '^') && ($EloUtolso !== '~') && ($EloUtolso !== ',') && ($EloHossz !== 0)))) 
        {$str1.='</b>'.$AktStr; $BoldSzint=0;} else {$str1.='*'.$AktStr;}
   }
  }
 // if ($BoldSzint == 1) {$str1.='</b>';}
  if ($BoldSzint == 1) {$str1.='</b>'.$strtomb[$TombHossz-1];} else {if ($TombHossz>1) {$str1.='*'.$strtomb[$TombHossz-1];}}
  return $str1;
}

//=========================================================================================================================
// TÁBLA KÓD BEILLESZTÉSE - Plusz változat
// HATÁROLÓK  || - balra igazított cella; ||j - jobbra igazított cella; ||k - középre igazított cella
//=========================================================================================================================

function TablaCserePlusz($str){ 
  $strtomb=explode("\x0A",$str);  
  $CsereTomb1  = array('||'  => ' </th> <th style="text-align:left;" > ');
  $CsereTomb1a = array('||j' => ' </th> <th style="text-align:right;" > ');
  $CsereTomb1b = array('||k' => ' </th> <th style="text-align:center;" > ');

  $CsereTomb2 = array('||'   => ' </td> <td style="text-align:left;" > ');
  $CsereTomb2a = array('||j' => ' </td> <td style="text-align:right;" > ');
  $CsereTomb2b = array('||k' => ' </td> <td style="text-align:center;" > ');
  $TablaSzint = 0;
  $str1 = '';
 /* echo  "<style scoped> 
         .altalanosTBL th, .altalanosTBL td {padding:6px;border:1px solid ; width:100px; }  
         .altalanosTBL {border-spacing: 0;}
        </style>
  "; */
  foreach ($strtomb as $SOR) {
   $Sor1 = ''; 
   if (strpos($SOR,"||")===0) {
     if ($TablaSzint==1) {
       if (strpos($SOR,"j")==2) { $Sor1 .= '<tr><td style="text-align:right;" >'.substr($SOR,3); } else {
          if (strpos($SOR,"k")==2) { $Sor1 .= '<tr><td style="text-align:center;" >'.substr($SOR,3); } else {
            $Sor1 .= '<tr><td style="text-align:left;">'.substr($SOR,2);
          }
       }
       $Sor1  = substr($Sor1,0,strripos($Sor1,"||"))."</td></tr>\n";
       $Sor1  = strtr($Sor1 ,$CsereTomb2a); $Sor1  = strtr($Sor1 ,$CsereTomb2b); $Sor1  = strtr($Sor1 ,$CsereTomb2);
     } 
     if ($TablaSzint==0) {
       if (strpos($SOR,"j")==2) { $Sor1 .= '<table class="altalanosTBL"><tr><th style="text-align:right;" >'.substr($SOR,3); } else {
          if (strpos($SOR,"k")==2) { $Sor1 .= '<table class="altalanosTBL"><tr><th style="text-align:center;" >'.substr($SOR,3); } else {
            $Sor1 .= '<table class="altalanosTBL"><tr><th style="text-align:left;">'.substr($SOR,2);
          }
       }
       $Sor1  = substr($Sor1,0,strripos($Sor1,"||"))."</th></tr> \n";
       $Sor1  = strtr($Sor1 ,$CsereTomb1a); $Sor1  = strtr($Sor1 ,$CsereTomb1b); $Sor1  = strtr($Sor1 ,$CsereTomb1);
       $TablaSzint = 1;
     }
   } else {
     if ($TablaSzint == 1) {
       $Sor1 .= '</table> '.$SOR;
       $TablaSzint = 0;
     } else {$Sor1 .= $SOR;}
   }
   $str1 .= $Sor1;   
  }
  return $str1;
}

//=========================================================================================================================
// TÁBLA KÓD BEILLESZTÉSE - alap változat
// HATÁROLÓK  || - balra igazított cella; 
//=========================================================================================================================

function TablaCsere($str){ 
  $strtomb=explode("\x0A",$str);  
  $CsereTomb1 = array('||' => '</th><th>');
  $CsereTomb2 = array('||' => '</td><td>');
  $TablaSzint = 0;
  $str1 = '';
 // echo "<h1>".count($strtomb)."</h1>";
/*  echo  "<style scoped> 
         .altalanosTBL th, .altalanosTBL td {padding:6px;border:1px solid ; }  
         .altalanosTBL {border-spacing: 0;}
        </style>
  "; */
  foreach ($strtomb as $SOR) {
   $Sor1 = ''; 
   if (strpos($SOR,"||")===0) {
 //    echo "<h1> strpos:".strpos($SOR,"||")."</h1> \n";
     if ($TablaSzint==1) {
       $Sor1 .= '<tr><td>'.substr($SOR,2);
       $Sor1  = substr($Sor1,0,strripos($Sor1,"||"))."</td></tr>\n";
       $Sor1  = strtr($Sor1 ,$CsereTomb2);
     } 
     if ($TablaSzint==0) {
       $Sor1 .= '<table class="altalanosTBL"><tr><th>'.substr($SOR,2);
       $Sor1  = substr($Sor1,0,strripos($Sor1,"||"))."</th></tr> \n";
       $Sor1  = strtr($Sor1 ,$CsereTomb1);
       $TablaSzint = 1;
     }
   } else {
     if ($TablaSzint == 1) {
       $Sor1 .= '</table> '.$SOR;
       $TablaSzint = 0;
     } else {$Sor1 .= $SOR;}
   }
   $str1 .= $Sor1;   
  }
  return $str1;
}

function ListaCsere($str){ 
  $ListaSzint = 0;
  $strtomb=explode("\x0A",$str."\x0A");
 // echo "<h1>".count($strtomb)."</h1>";
 
  
/*
  echo  "\n<style scoped> 
         .Felsorol {list-style-position:inside; padding-left:12px;}  
         .Felsorol li {padding-left:6px;}
        </style>";
  
*/
//=========================================================================================================================
// LISTA KÓD BEILLESZTÉSE - Plusz változat
// HATÁROLÓK:  2 db/ 4 db/ 6 db szóköz után *  -számozatlan lista
//             2 db/ 4 db/ 6 db szóköz után #  -számozott ista
//=========================================================================================================================

  $ListaSzint = 0;
  $AktListaSzint = 0;
  $ListaTipus = array(0,0,0,0);
  $Sor1 = '';
  foreach ($strtomb as $SOR) {
    $ListaOK = 0; // Jelzi, hogy egy lista sort találtunk. A lista utosó eleme után értéke 0, de a listaszint még nem. 
    if (strpos($SOR,"  * ")===0) {
      if ($AktListaSzint == 1) {
         $AktListaSzint = 1; $ListaOK = 1; $ListaTipus[1]=1;
         $Sor1 .= '</li><li>';
         $Sor1 .= substr($SOR,3);
      }
      if ($AktListaSzint == 0) {
         $AktListaSzint = 1; $ListaOK = 1; $ListaTipus[1]=1;
         $Sor1 .= '<ul class="Felsorol"><li>';
         $Sor1 .= substr($SOR,3);
      }
      if ($AktListaSzint == 2) {
         $AktListaSzint = 1; $ListaOK = 1; $ListaTipus[1]=1;
         if ($ListaTipus[2]==1) {$Sor1 .= '</li></ul><li>';} else {$Sor1 .= '</li></ol><li>';}
         $Sor1 .= substr($SOR,3);
      }
      if ($AktListaSzint == 3) {
         $AktListaSzint = 1; $ListaOK = 1; $ListaTipus[1]=1;
         if ($ListaTipus[3]==1) {$Sor1 .= '</li></ul>';} else {$Sor1 .= '</li></ol>';}
         if ($ListaTipus[2]==1) {$Sor1 .= '</li></ul><li>';} else {$Sor1 .= '</li></ol><li>';}
         $Sor1 .= substr($SOR,3);
      }
    }
    if (strpos($SOR,"  # ")===0) {
      if ($AktListaSzint == 1) {
         $AktListaSzint = 1; $ListaOK = 1; $ListaTipus[1]=2;
         $Sor1 .= '</li><li>';
         $Sor1 .= substr($SOR,3);
      }
      if ($AktListaSzint == 0) {
         $AktListaSzint = 1; $ListaOK = 1; $ListaTipus[1]=2;
         $Sor1 .= '<ol class="Felsorol"><li>';
         $Sor1 .= substr($SOR,3);
      }
      if ($AktListaSzint == 2) {
         $AktListaSzint = 1; $ListaOK = 1; $ListaTipus[1]=2;
         if ($ListaTipus[2]==1) {$Sor1 .= '</li></ul><li>';} else {$Sor1 .= '</li></ol><li>';}
         $Sor1 .= substr($SOR,3);
      }
      if ($AktListaSzint == 3) {
         $AktListaSzint = 1; $ListaOK = 1; $ListaTipus[1]=2;
         if ($ListaTipus[3]==1) {$Sor1 .= '</li></ul>';} else {$Sor1 .= '</li></ol>';}
         if ($ListaTipus[2]==1) {$Sor1 .= '</li></ul><li>';} else {$Sor1 .= '</li></ol><li>';}
         $Sor1 .= substr($SOR,3);
      }
    }

    // 2. szint
    if (strpos($SOR,"    * ")===0) {
      if ($AktListaSzint == 2) {
         $AktListaSzint = 2; $ListaOK = 1; $ListaTipus[2]=1;
         $Sor1 .= '</li><li>';
         $Sor1 .= substr($SOR,5);
      }
      if ($AktListaSzint == 0) {
         $AktListaSzint = 2; $ListaOK = 1; $ListaTipus[2]=1;
         $Sor1 .= '<ul class="Felsorol"><li><ul class="Felsorol"><li>';
         $Sor1 .= substr($SOR,5);
      }
      if ($AktListaSzint == 1) {
         $AktListaSzint = 2; $ListaOK = 1; $ListaTipus[2]=1;
         $Sor1 .= '<ul class="Felsorol"><li>';
         $Sor1 .= substr($SOR,5);
      }
      if ($AktListaSzint == 3) {
         $AktListaSzint = 2; $ListaOK = 1; $ListaTipus[2]=1;
         if ($ListaTipus[3]==1) {$Sor1 .= '</li></ul><li>';} else {$Sor1 .= '</li></ol><li>';}
         $Sor1 .= substr($SOR,5);
      }
    }
    if (strpos($SOR,"    # ")===0) {
      if ($AktListaSzint == 2) {
         $AktListaSzint = 2; $ListaOK = 1; $ListaTipus[2]=2;
         $Sor1 .= '</li><li>';
         $Sor1 .= substr($SOR,5);
      }
      if ($AktListaSzint == 0) {
         $AktListaSzint = 2; $ListaOK = 1; $ListaTipus[2]=2;
         $Sor1 .= '<ol class="Felsorol"><li><ol class="Felsorol"><li>';
         $Sor1 .= substr($SOR,5);
      }
      if ($AktListaSzint == 1) {
         $AktListaSzint = 2; $ListaOK = 1; $ListaTipus[2]=2;
         $Sor1 .= '<ol class="Felsorol"><li>';
         $Sor1 .= substr($SOR,5);
      }
      if ($AktListaSzint == 3) {
         $AktListaSzint = 2; $ListaOK = 1; $ListaTipus[2]=2;
         if ($ListaTipus[3]==1) {$Sor1 .= '</li></ul><li>';} else {$Sor1 .= '</li></ol><li>';}
         $Sor1 .= substr($SOR,5);
      }
    }
    // 3. szint
    if (strpos($SOR,"      * ")===0) {
      if ($AktListaSzint == 3) {
         $AktListaSzint = 3; $ListaOK = 1; $ListaTipus[3]=1;
         $Sor1 .= '</li><li>';
         $Sor1 .= substr($SOR,7);
      }
      if ($AktListaSzint == 0) {
         $AktListaSzint = 3; $ListaOK = 1; $ListaTipus[3]=1;
         $Sor1 .= '<ul class="Felsorol"><li><ul class="Felsorol"><li><ul class="Felsorol"><li>';
         $Sor1 .= substr($SOR,7);
      }
      if ($AktListaSzint == 1) {
         $AktListaSzint = 3; $ListaOK = 1; $ListaTipus[3]=1;
         $Sor1 .= '<ul class="Felsorol"><li><ul class="Felsorol"><li>';
         $Sor1 .= substr($SOR,7);
      }
      if ($AktListaSzint == 2) {
         $AktListaSzint = 3; $ListaOK = 1; $ListaTipus[3]=1;
         $Sor1 .= '<ul class="Felsorol"><li>';
         $Sor1 .= substr($SOR,7);
      }
    }
   
    if (strpos($SOR,"      # ")===0) {
      if ($AktListaSzint == 3) {
         $AktListaSzint = 3; $ListaOK = 1; $ListaTipus[3]=2;
         $Sor1 .= '</li><li>';
         $Sor1 .= substr($SOR,7);
      }
      if ($AktListaSzint == 0) {
         $AktListaSzint = 3; $ListaOK = 1; $ListaTipus[3]=2;
         $Sor1 .= '<ol class="Felsorol"><li><ol class="Felsorol"><li><ol class="Felsorol"><li>';
         $Sor1 .= substr($SOR,7);
      }
      if ($AktListaSzint == 1) {
         $AktListaSzint = 3; $ListaOK = 1; $ListaTipus[3]=2;
         $Sor1 .= '<ol class="Felsorol"><li><ol class="Felsorol"><li>';
         $Sor1 .= substr($SOR,7);
      }
      if ($AktListaSzint == 2) {
         $AktListaSzint = 3; $ListaOK = 1; $ListaTipus[3]=2;
         $Sor1 .= '<ol class="Felsorol"><li>';
         $Sor1 .= substr($SOR,7);
      }
    }

 // Ha magasabb szinten fejelződik be a lista
    if (($AktListaSzint==3) && ($ListaOK==0)) {
       if ($ListaTipus[3]==1) {$Sor1 .= '</li></ul>';} else {$Sor1 .= '</li></ol>';}
       $AktListaSzint=2;
    } 
    if (($AktListaSzint==2) && ($ListaOK==0)) {
       if ($ListaTipus[2]==1) {$Sor1 .= '</li></ul>';} else {$Sor1 .= '</li></ol>';}
       $AktListaSzint=1;
    } 
    if (($AktListaSzint==1) && ($ListaOK==0)) {
       if ($ListaTipus[1]==1) {$Sor1 .= '</li></ul>';} else {$Sor1 .= '</li></ol>';}
       $AktListaSzint=0;
    } 

    if (($AktListaSzint==0) && ($ListaOK==0)) {
       $Sor1 .= $SOR;
    } 
       $Sor1 .= "\x0A";
  }


return $Sor1;
}

   
function getElsoKepHTML($Cid,$KepUtvonal) {   
    global $MySqliLink;
    $HTMLkod     = '';
    $SelectStr   = "SELECT KFile, KNev FROM CikkKepek WHERE Cid=$Cid ORDER BY KSorszam DESC LIMIT 1";
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba gEKH 1 ");
    $rowDB       = mysqli_num_rows($result); 
    if ($rowDB > 0) {
        $row     = mysqli_fetch_array($result);  mysqli_free_result($result); 
        $Src     = $KepUtvonal.$row['KFile'];
        $KNev    = $row['KNev'];
        $HTMLkod.= "<img src='$Src'  class = 'imgOE' alt='$KNev'>";
    }
    return $HTMLkod;    
}   
   
?>
