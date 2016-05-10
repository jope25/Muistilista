<?php

class TaKontrolleri extends BaseController {

    public static function index() {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $asteet = Tarkeysaste::kaikki($kayttaja_id);
        View::make('ta/index.html', array('asteet' => $asteet));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $ta = Tarkeysaste::etsi($id);

        if ($ta->kayttaja == $kayttaja_id) {
            View::make('ta/nayta.html', array('ta' => $ta));
        } else {
            Redirect::to('/ta', array('virhe' => 'Tarkeysaste ei kuulu sinulle!'));
        }
    }

    public static function luo() {
        self::check_logged_in();
        View::make('ta/uusi.html');
    }

    public static function lisaa() {
        self::check_logged_in();
        $params = $_POST;
        $attribuutit = array(
            'kayttaja' => self::get_user_logged_in()->id,
            'nimi' => $params['nimi'],
            'tarkeys' => $params['tarkeys'],
            'lisatieto' => $params['lisatieto'],
        );
        $ta = new Tarkeysaste($attribuutit);
        $virheet = $ta->virheet();

        if (count($virheet) == 0) {
            $ta->tallenna();
            Redirect::to('/ta/' . $ta->id, array('viesti' => 'Tärkeysaste on lisätty '
                . 'muistilistaan!'));
        } else {
            View::make('ta/uusi.html', array('virheet' => $virheet,
                'attribuutit' => $attribuutit));
        }
    }

    public static function muokkaa($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $ta = Tarkeysaste::etsi($id);

        if ($ta->kayttaja == $kayttaja_id) {
            View::make('ta/muokkaa.html', array('attribuutit' => $ta));
        } else {
            Redirect::to('/ta', array('virhe' => 'Tärkeysaste ei liity muistilistaasi!'));
        }
    }

    public static function paivita($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $params = $_POST; 
        $attribuutit = array(
            'id' => $id,
            'nimi' => $params['nimi'],
            'tarkeys' => $params['tarkeys'],
            'lisatieto' => $params['lisatieto']
        );
        $ta = new Tarkeysaste($attribuutit);
        $virheet = $ta->virheet();
        if (!$ta->on_kirjautuneen_kayttajan($kayttaja_id)) {
            Redirect::to('/ta', array('virhe' => 'Tärkeysaste ei liity muistilistaasi!'));
        }
        if (count($virheet) > 0) {
            View::make('ta/muokkaa.html', array('virheet' => $virheet,
                'attribuutit' => $attribuutit));
        } else {
            $ta->paivita();
            Redirect::to('/ta/' . $ta->id, array('viesti' =>
                'Tärkeysastetta on muokattu onnistuneesti!'));
        }
    }

    public static function poista($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $ta = new Tarkeysaste(array('id' => $id));

        if ($ta->on_kirjautuneen_kayttajan($kayttaja_id) && $ta->voiko_poistaa()) {
            $ta->poista();
            Redirect::to('/ta', array('viesti' => 'Tärkeysaste on poistettu onnistuneesti!'));
        } else if (!$ta->voiko_poistaa ()) {
            Redirect::to('/askare', array('virhe' => 'Poista tärkeysaste ensin askareista, joissa se on!'));
        } else {
            Redirect::to('/ta', array('virhe' => 'Tärkeysaste ei liity muistilistaasi!'));
        }
    }

}
