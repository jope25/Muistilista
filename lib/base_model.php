<?php

class BaseModel {

//    protected $validators;

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

// Kint antaa jostain syystä virheilmoituksen "Invalid argument supplied for foreach()" 
// rivistä 23. Lisäksi, en keksinyt kuinka välittää parametrit nätisti.
//
//    public function errors() {
//        $errors = array();
//        foreach ($this->validators as $validator) {
//            $validator_errors = $this->{$validator};
//            $errors = array_merge($errors, $validator_errors);
//        }
//
//        return $errors;
//    }
    
    public function validoi_pituus($merkkijono, $ylaraja) {
        $virheet = array();
        if ($merkkijono == '' || $merkkijono == null) {
            $virheet[] = 'Nimi/salasana ei saa olla tyhjä!';
        }
        if (strlen($merkkijono) < 5 || strlen($merkkijono) > $ylaraja) {
            $virheet[] = 'Nimen/salasanan pituuden tulee olla vähintään 5 ja enintään ' .
                    $ylaraja . ' merkkiä!';
        }

        return $virheet;
    }

    public function validoi_lisatieto($lisatieto) {
        $virhe = array();
        if (strlen($lisatieto) > 500) {
            $virhe[] = 'Lisatiedon maksimi merkkimäärä on 500';
        }
        return $virhe;
    }

    public function validoi_tarkeysaste() {
        $virhe = array();
        
        return $virhe;
    }

    public function validoi_luokat() {
        $virheet = array();
        
        return $virheet;
    }

    public function validoi_tarkeys($tarkeys) {
        $virhe = array();

        if (!is_numeric($tarkeys) || $tarkeys % 1 != 0 || $tarkeys < 1 || $tarkeys > 5) {
            $virhe[] = 'Tärkeyden tulee olla kokonaisluku, jonka arvo on 1-5';
        }

        return $virhe;
    }

}
