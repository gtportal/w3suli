<?php
if ($_SESSION['AktFelhasznalo'.'FSzint']>0) {
    // Attributes
    /**
     * Kapcsolódás adatbázishoz
     */
    $MySqliLink = mysqli_connect('localhost', 'w3suliroot', 'w3rootjelszo', 'w3suli');

    //Kapcsolat ellenőrzése
    if (!$MySqliLink) { die('AB hiba 123'); }

}

?>
