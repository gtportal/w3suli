
                      <?php 
                        if ($_SESSION["AktFelhasznalo"."FSzint"]>0) {
                          $MySqliLink = mysqli_connect('localhost', 'proba', 'proba', 'proba'); 
                          if (!$MySqliLink) { die('AB hiba 123'); }
                          if (!mysqli_set_charset($MySqliLink, "utf8")) {die('Kapcsolódási hiba 1 ') ;}
                        }    
                      ?>
