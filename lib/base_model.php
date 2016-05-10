<?php

class BaseModel {

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }
    
    // Merkkijonon pituuden validointi.
    public function validoi_pituus($merkkijono, $ylaraja) {
        $virheet = array();
        if ($merkkijono == '' || $merkkijono == null) {
            $virheet[] = 'Nimi/salasana ei saa olla tyhjä!';
        }
        if (strlen($merkkijono) < 3 || strlen($merkkijono) > $ylaraja) {
            $virheet[] = 'Nimen/salasanan pituuden tulee olla vähintään 5 ja enintään ' .
                    $ylaraja . ' merkkiä!';
        }

        return $virheet;
    }

    // Tietokohteiden lisatiedon validointi.
    public function validoi_lisatieto($lisatieto) {
        $virhe = array();
        if (strlen($lisatieto) > 500) {
            $virhe[] = 'Lisatiedon maksimi merkkimäärä on 500';
        }
        return $virhe;
    }
}
