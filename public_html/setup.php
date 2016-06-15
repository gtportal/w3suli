<?php
  session_start();
  $mm_azon   = session_id(); 
  if (!isset($_SESSION['AktFelhasznalo'.'FSzint'])) {$_SESSION['AktFelhasznalo'.'FSzint']=1;}
  require_once 'lang/w3suli_hu.php';
  require_once("php/AlapFgvek.php");
  require_once("setup/w3suli_setup.php");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
     <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes">
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_alap.css" />  
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_szerkeszt.css" />  
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_responsive.css" />
     <link type="text/css" rel="stylesheet" media="all"   href="css/w3suli_stilus_2.css" />
     <link href='https://fonts.googleapis.com/css?family=Istok+Web:700,400&amp;subset=latin-ext,latin' rel='stylesheet' type='text/css'>
</head>
<body>

  <div id='Keret'>
    <div id='FoHeder' style='background-color:#fff;'>
      <img src="img/ikonok/HeaderImg/w3logo.png" alt="W3Suli logo" id='webaruhazlogo' style="float:left;" height="95" >
      <h1> <?php echo U_SETUP_CÍM ?> <br> V1.03</h1>
    </div>
    <?php 
      getTartalomHTML();   
    ?>
  </div>   
</body>
</html>