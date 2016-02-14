<?php


    function setKepFeltolt($AktKonytart,$KFileName) {
      $UploadErr   = '';
      $allowedExts = array("gif", "jpeg", "jpg", "png", "JPG", "JPEG");
      $temp        = explode(".", $_FILES["file"]["name"]); 
      $extension   = end($temp);
      if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"]   == "image/jpeg")
        || ($_FILES["file"]["type"]   == "image/jpg")
        || ($_FILES["file"]["type"]   == "image/pjpeg")
        || ($_FILES["file"]["type"]   == "image/x-png")
        || ($_FILES["file"]["type"]   == "image/png"))
        && ($_FILES["file"]["size"]   < 2000000)
        && in_array($extension, $allowedExts))
      {
        if ($_FILES["file"]["error"] > 0) {
          $UploadErr = "Err01: " . $_FILES["file"]["error"] . "<br>"; 
        } else {
          $CelFilename =   $AktKonytart.$KFileName.'.'.$extension;
          if (file_exists($CelFilename)) {
            //Meglévő kép felülírása
            move_uploaded_file($_FILES["file"]["tmp_name"],$CelFilename);
            $UploadErr =  $KFileName.'.'.$extension; 
          } else {
            //Új kép feltöltése
            move_uploaded_file($_FILES["file"]["tmp_name"],$CelFilename);
            $UploadErr =  $KFileName.'.'.$extension; 
          }
        }
      } else {
        if ($_FILES["file"]["name"] >'') {$UploadErr = "Err02";}
      }
      return $UploadErr;;
    }


    function setKepekFeltolt() {       
      global $Aktoldal, $SzuloOldal, $NagyszuloOldal, $MySqliLink;  
      $Oid          = $Aktoldal['id'];
      $UploadErr    = '';
      if ($Aktoldal['OImgDir']!='') {
        $KepUtvonal = "img/".$Aktoldal['OImgDir']."/";            
      } else {
        $KepUtvonal = "img/";    
      }
      //=============== Lehetséges Fájlnevek ==================
      $lehetKFile     = array();
      for($i=9; $i>=0; $i--) {
        $lehetKFile[] = $Aktoldal['OUrl'].'_'.$i; 
      }
      //print_r($lehetKFile);
      //=============== Létező Fájlnevek ==================
      $vanKFile     = array();
      $SelectStr    = "SELECT KFile FROM OldalKepek WHERE Oid=$Oid" ; //echo "<h1>$SelectStr1</h1>";
      $result       = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba sKF 01");  
      $rowDB        = mysqli_num_rows($result); 
      if ($rowDB > 0) {
        while($row = mysqli_fetch_array($result))
        {
          $temp       = explode(".", $row['KFile']);
          $vanKFile[] = $temp[0];
        }
      }     
      //=============== Használható Fájlnevek ==================
      $OkKFile     = array();
      $OkKFile0    = array_diff($lehetKFile,$vanKFile);
      foreach ($OkKFile0 as $key => $value) {$OkKFile[] = $value;}
      $OkKFileCt   = count($OkKFile);
      $KFileDb     = count($_FILES['OKepFile']['name']);
      if (($KFileDb==1) && ($_FILES["OKepFile"]["name"][0]=='')) {$KFileDb=0; $UploadErr = 'Nincs fájl kijelölve.';}
     
      if (isset($_FILES["OKepFile"])) {
        $i=0;  
        while (($i<$KFileDb) && ($OkKFileCt>0)) {  
          if ($i==0) {$UploadErr = '';}          
          $allowedExts = array("gif", "jpeg", "jpg", "png");
          $temp        = explode(".", $_FILES["OKepFile"]["name"][$i]);          
          $extension   = end($temp);  
            
          print_r($OkKFile);
          $AktFileNev = $OkKFile[$OkKFileCt-1].'.'.$extension; echo "<h1>AktFileNev:$KepUtvonal $AktFileNev</h1>";    
          
          if (( ($_FILES["OKepFile"]["type"][$i] == "image/gif")
             || ($_FILES["OKepFile"]["type"][$i] == "image/jpeg")
             || ($_FILES["OKepFile"]["type"][$i] == "image/jpg")
             || ($_FILES["OKepFile"]["type"][$i] == "image/pjpeg")
             || ($_FILES["OKepFile"]["type"][$i] == "image/x-png")
             || ($_FILES["OKepFile"]["type"][$i] == "image/png"))
             && ($_FILES["OKepFile"]["size"][$i] < 2000000)
             && in_array($extension, $allowedExts))
          {
            if ($_FILES["OKepFile"]["error"][$i] > 0) {
              $UploadErr = "Hibakód: " . $_FILES["OKepFile"]["error"][$i] . "<br>"; 
            } else {
              if (file_exists($KepUtvonal.$AktFileNev)) { 
                //Meglévő kép felülírása
                move_uploaded_file($_FILES["OKepFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev);
                $UploadErr .=  "Felülírva: " .$KepUtvonal.$AktFileNev."<br>"; $KepOK=true;
                $InsertIntoStr = "INSERT INTO OldalKepek VALUES ('', $Oid,'$AktFileNev','','',0,0,0,0)";
                if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba sKF 02");}
              } else {
                //Új kép feltöltése
                move_uploaded_file($_FILES["OKepFile"]["tmp_name"][$i],$KepUtvonal.$AktFileNev); 
                $UploadErr .=  "Feltöltve: ". $KepUtvonal.$AktFileNev."<br>"; $KepOK=true;        
                $InsertIntoStr = "INSERT INTO OldalKepek VALUES ('', $Oid,'$AktFileNev','','',0,0,0,0)";
                if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba sKF 03");}
              }
            }
          } else {
            if ($AktFileNev >'') {$UploadErr .= "Err101 Érvénytelen file.".$_FILES["OKepFile"]["name"][$i]; echo "<h1>GGGGGGGGGGG</h1>";}
          }            
          $i++; $OkKFileCt--;
        }
        if ($i<$KFileDb) { $UploadErr .= "<h1> Csak 10 kép tölthető fel!</h1>";}
      }
         
      return $UploadErr; 
        
    }

    function getTXTtoURL($txt) {
        $arr = array(' ' => '_', ',' => '_',  ';' => '',  '"' => '',  "'" => '',  ':' => '',  '  ' => ' ', 'á' => 'a', 'Á' => 'A', 
               'é' => 'e', 'É' => 'e', 'ó' => 'o', 'Ó' => 'O', 'í' => 'i', 'Í' => 'I', 'Ú' => 'U', 'ú' => 'u', 'Ö' => 'O', 
               'ö' => 'o', 'Ő' => 'O', 'ő' => 'o', 'Ü' => 'U', 'ü' => 'u', 'Ű' => 'U', 'ű' => 'u', '.' => '_', "\x5C" => "");  
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
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
   return $data;
   }
   
   function SQL_post($data) {
     $data = trim($data);
     $data = stripslashes($data);
   return $data;
   }
?>
