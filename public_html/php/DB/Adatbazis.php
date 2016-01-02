<?php
if ($_SESSION['AktFelhasznalo'.'FSzint']>0) {
    // Attributes
    /**
     * Kapcsolódás adatbázishoz
     */
    $MySqliLink = mysqli_connect('localhost', 'w3suliroot', 'w3rootjelszo', 'w3suli');

    //Kapcsolat ellenőrzése
    if (!$MySqliLink) {
      die('Kapcsolódási hiba 0 (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }

}

?>
