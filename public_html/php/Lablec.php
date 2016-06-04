<?php
    function getLablecHTML() {
        $HTMLkod    = "";
        $AlapAdatok = getAlapbeallitasok();
        $WebhelyNev = $AlapAdatok['WebhelyNev'];
        $Iskola     = $AlapAdatok['Iskola'];
        $Cim        = $AlapAdatok['Cim'];
        $Telefon    = $AlapAdatok['Telefon'];
        
        $HTMLkod .= "<ul id = 'footer_ul'>\n
                    <li class = 'footer_li'>$WebhelyNev</li>\n
                    <li class = 'footer_li'>$Iskola</li>\n
                    <li class = 'footer_li'>$Cim</li>\n
                    <li class = 'footer_li'>$Telefon</li>\n
                    </ul>";
        return $HTMLkod;
}