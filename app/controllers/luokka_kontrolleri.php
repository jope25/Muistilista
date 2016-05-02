<?php

class LuokkaKontrolleri extends BaseController {

    public static function index() {
        self::check_logged_in();

        $kayttaja_id = self::get_user_logged_in()->id;

        $luokat = Luokka::kaikki($kayttaja_id);
        View::make('luokka/index.html', array('luokat' => $luokat));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $luokka = Luokka::etsi($id);

        if ($luokka->kayttaja == $kayttaja_id) {
            View::make('luokka/nayta.html', array('luokka' => $luokka));
        } else {
            Redirect::to('/luokka', array('virhe' => 'Luokka ei kuulu sinulle!'));
        }
    }

    public static function luo() {
        self::check_logged_in();
        View::make('luokka/uusi.html');
    }

    public static function lisaa() {
        self::check_logged_in();
        $params = $_POST;
        $attribuutit = array(
            'kayttaja' => self::get_user_logged_in()->id,
            'nimi' => $params['nimi'],
            'lisatieto' => $params['lisatieto'],
        );
        $luokka = new Luokka($attribuutit);
        $virheet = $luokka->virheet();

        if (count($virheet) == 0) {
            $luokka->tallenna();
            Redirect::to('/luokka/' . $luokka->id, array('viesti' => 'Luokka on lisätty '
                . 'muistilistaan!'));
        } else {
            View::make('luokka/uusi.html', array('virheet' => $virheet,
                'attribuutit' => $attribuutit));
        }
    }

    public static function muokkaa($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $luokka = Luokka::etsi($id);

        if ($luokka->kayttaja == $kayttaja_id) {
            View::make('luokka/muokkaa.html', array('attribuutit' => $luokka));
        } else {
            Redirect::to('/luokka', array('virhe' => 'Luokka ei liity muistilistaasi!'));
        }
    }

    public static function paivita($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $params = $_POST;
        $attribuutit = array(
            'id' => $id,
            'nimi' => $params['nimi'],
            'lisatieto' => $params['lisatieto']
        );
        $luokka = new Luokka($attribuutit);
        $virheet = $luokka->virheet();
        if (!$luokka->on_kirjautuneen_kayttajan($kayttaja_id)) {
            Redirect::to('/luokka', array('virhe' => 'Luokka ei liity muistilistaasi!'));
        }
        if (count($virheet) > 0) {
            View::make('luokka/muokkaa.html', array('virheet' => $virheet,
                'attribuutit' => $attribuutit));
        } else {
            $luokka->paivita();
            Redirect::to('/luokka/' . $luokka->id, array('viesti' =>
                'Luokka on muokattu onnistuneesti!'));
        }
    }

    public static function poista($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $luokka = new Luokka(array('id' => $id));

        if ($luokka->on_kirjautuneen_kayttajan($kayttaja_id)) {
            $luokka->poista();
            Redirect::to('/luokka', array('viesti' => 'Luokka on poistettu onnistuneesti!'));
        } else {
            Redirect::to('/luokka', array('virhe' => 'Luokka ei liity muistilistaasi!'));
        }
    }

}
