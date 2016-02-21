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
