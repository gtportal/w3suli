<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $SelectStr   = "SELECT * FROM Oldalak LIMIT 1"; 
    $result      = mysqli_query($MySqliLink,$SelectStr) OR die("Hiba I 01 ");
    $rowDB       = mysqli_num_rows($result); mysqli_free_result($result);
    if ($rowDB == 0) {
       InitOldalak();
       InitFelhasznalok(); 
       InitAlapAdatok();
    }    
    
    function InitOldalak() {
      global $MySqliLink;
      //Kezdőlap létrehozása
      $InsertIntoStr = "INSERT INTO Oldalak VALUES (1, 'Kezdőlap','kezdolap',1,1,'Kezdőlap leírása','Kezdőlap kulcsszavai',
                       0,0,'Kezdőlap tartalma','','')";
      echo "<h1>$InsertIntoStr </h1>";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 01 ");} 
      //Bejelentkező oldal
      $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Bejelentkezés','bejelentkezes',1,1,'Bejelentkezés leírása','Bejelentkezés kulcsszavai',
                       1,10,'Kezdőlap tartalma','','')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 02 ");} 
      //Kijelentkezés oldal
      $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Kijelentkezés','kijelentkezes',1,1,'Kijelentkezés leírása','Kijelentkezés kulcsszavai',
                       1,11,'Kijelentkezés tartalma','','')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 03 ");} 
      //Regisztráció oldal
      $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Regisztráció','regisztracio',1,1,'Regisztráció leírása','Regisztráció kulcsszavai',
                       1,12,'Regisztráció tartalma','','')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 04 ");}
      //Felhasználó törlése oldal
      $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Felhasználó törlése','felhasznalo_torlese',1,1,'Felhasználó törlése leírása','Felhasználó törlése kulcsszavai',
                       1,13,'Felhasználó törlése tartalma','','')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 02 ");} 
      //Felhasználó lista oldal
      $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Felhasználó lista','felhasznalo_lista',1,1,'Felhasználó lista leírása','Felhasználó lista kulcsszavai',
                       1,14,'Felhasználó lista tartalma','','')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 02 ");} 
      //Adatmódosítás oldal
      $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Adatmódosítás','adatmodositas',1,1,'Adatmódosítás leírása','Adatmódosítás kulcsszavai',
                       1,15,'Adatmódosítás tartalma','','')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 02 ");} 
      //Jelszómodosítás oldal
      $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Jelszómodosítás','jelszomodositas',1,1,'Jelszómodosítás leírása','Jelszómodosítás kulcsszavai',
                       1,16,'Jelszómodosítás tartalma','','')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 02 ");}  
      
      
      //Alapbeállítások oldal
      $InsertIntoStr = "INSERT INTO Oldalak VALUES ('', 'Alapbeállítások','alapbeallitasok',1,1,'Alapbeállítások leírása','Alapbeállítások kulcsszavai',
                       1,16,'Alapbeállítások tartalma','','')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IO 02 ");}       
      
    }
    
    
    function InitFelhasznalok() {
      global $MySqliLink;
      //Kiemelt rendszergazda létrehozása      
      $InsertIntoStr = "INSERT INTO Felhasznalok VALUES ('', 'root','Root','root','root@root.hu',5,'Root','root.jpg')";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IF 01 ");} 
      
    } 
    
    function InitAlapAdatok() {
      global $MySqliLink;
      //Kiemelt rendszergazda létrehozása      
      $InsertIntoStr = "INSERT INTO AlapAdatok VALUES ('', 'WebhelyNev','Iskola','Cim','Telefon',0)";
      if (!mysqli_query($MySqliLink,$InsertIntoStr)) {die("Hiba IA 01 ");} 
      
    } 